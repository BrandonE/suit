import logging

from hashlib import md5

try:
    import simplejson as json
except ImportError:
    import json

import os

from pylons import config, response, request, tmpl_context as c, url
from paste.request import construct_url
from paste.httpexceptions import HTTPMovedPermanently

import suit
from suitframework.lib.base import BaseController, render

class RootController(BaseController):
    def template(self, templatefile, parameter1 = None, parameter2 = None):
        c.parameter1 = parameter1
        c.parameter2 = parameter2
        c.condition['home'] = False
        c.condition['docs'] = False
        c.condition['download'] = False
        c.condition['tryit'] = False
        c.condition['slacks'] = False
        cachefile = open(os.path.join(config['app_conf']['cache_dir'], 'suit.cache'), 'r')
        try:
            suit.cache = json.loads(cachefile.read())
        except ValueError:
            # First load doesn't have a cache.
            pass
        cachefile.close()
        cachecheck = md5(json.dumps(suit.cache, separators = (',', ':'))).hexdigest()
        try:
            templatefile = render(templatefile + '.tpl')
        except IOError:
            response.status = '404 Not Found'
        cachejson = json.dumps(suit.cache, separators = (',', ':'))
        if cachecheck != md5(cachejson):
            cachefile = open(os.path.join(config['app_conf']['cache_dir'], 'suit.cache'), 'w')
            cachefile.write(cachejson)
            cachefile.close()
        if (
            (
                'slacks' in request.POST and
                request.POST['slacks']
            ) or
            (
                'slacks' in request.GET and
                request.GET['slacks']
            )
        ):
            tree = json.dumps(suit.log['tree'], separators = (',', ':'))
            response.headerlist = [
                ('Pragma', 'public'),
                ('Expires', '0'),
                ('Cache-Control', 'must-revalidate, post-check=0, pre-check=0'),
                ('Content-type', 'text/json'),
                ('Content-Disposition', 'attachment; filename=tree.json'),
                ('Content-Length', len(tree))
            ]
            return tree
        return templatefile