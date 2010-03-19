"""
**@This file is part of BBNode.
**@BBNode is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@BBNode is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with BBNode.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
"""
from pylons import request, tmpl_context as c
from pylons.controllers.util import redirect
from pylons.i18n import ugettext as _gettext
from suitframework.lib import helpers as h
try:
    import simplejson as json
except ImportError:
    import json
import urllib
if 'submit' in request.POST and request.POST['submit'] == _gettext('Submit'):
    redirect(
        ''.join
        ((
            url(controller='root', action='template', templatefile = 'slacks'),
            '?url=',
            urllib.quote_plus(request.POST['url'])
        )),
        code = 303
    )
c.referrer = request.headers["referer"]
c.condition['tree'] = False
c.condition['referrer'] = ('referrer' in request.GET and
request.GET['referrer'])
c.loop['tree'] = []
try:
    tree = ''
    if 'url' in request.GET:
        tree = urllib.urlopen(
            request.GET['url'],
            urllib.urlencode({
                'slacks': 'true'
            })
        ).read()
    elif ('submit' in request.POST and
    request.POST['submit'] == _gettext('Upload')):
        tree = request.POST['file'].file.read()
    tree = json.loads(tree)
    tree.sort(key = lambda item: item['id'])
    tree = h.slacks(
        tree,
        _gettext('No Wrapper'),
        _gettext('Wrapper')
    )
    c.condition['tree'] = True
    c.loop['tree'] = tree
except (AttributeError, EOFError, IOError, NameError, TypeError, ValueError):
    pass