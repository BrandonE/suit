import logging

from hashlib import md5

try:
    import simplejson as json
except ImportError:
    import json

import os

from pylons import config, response, request, tmpl_context as c
from paste.request import construct_url
from paste.httpexceptions import HTTPMovedPermanently

import suit
from suitframework.lib.base import BaseController, render

class RootController(BaseController):
    def template(self, templatefile, parameter1 = None, parameter2 = None):
        c.parameter1 = parameter1
        c.parameter2 = parameter2
        c.condition.home = False
        c.condition.docs = False
        c.condition.download = False
        c.condition.tryit = False
        c.condition.slacks = False
        try:
            templatefile = render(templatefile + '.tpl')
        except IOError:
            response.status = '404 Not Found'
        defaultlog = {
            'hash': {},
            'contents': []
        }
        if 'slacks' in request.params:
            if c.condition.slacks:
                suit.log = defaultlog
            slacks = json.dumps(suit.log, separators = (',', ':'))
            suit.log = defaultlog
            response.headerlist = [
                ('Pragma', 'public'),
                ('Expires', '0'),
                (
                    'Cache-Control',
                    'must-revalidate, post-check=0, pre-check=0'
                ),
                ('Content-type', 'text/json'),
                ('Content-Disposition', 'attachment; filename=slacks.json'),
                ('Content-Length', len(slacks))
            ]
            return slacks
        suit.log = defaultlog
        return templatefile