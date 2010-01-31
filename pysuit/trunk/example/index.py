#!/usr/local/bin/python2.6
"""
**@This file is part of PySUIT.
**@PySUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@PySUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with PySUIT.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
"""
from cgi import escape
import cgitb
import glob
import json
import os
import sys
from webob import Request, Response
print 'Content-Type: text/html\n'
cgitb.enable()
environ = dict(os.environ.items())
environ['wsgi.input'] = sys.stdin
environ['wsgi.errors'] = sys.stderr
environ['wsgi.version'] = (1,0)
environ['wsgi.multithread'] = False
environ['wsgi.multiprocess'] = True
environ['wsgi.run_once'] = True
environ['wsgi.url_scheme'] = 'http'
sys.path.append('../')
import suit
from suit.nodes import NODES as nodes
suit.nodes = nodes
suit.nodes['[template]']['var']['list'] = []
for value in glob.glob('templates/*.tpl'):
    suit.nodes['[template]']['var']['list'].append(os.path.relpath(value))
    suit.nodes['[template]']['var']['list'].append(os.path.abspath(value))
suit.nodes['[code]']['var']['list'] = []
for value in glob.glob('code/*.py'):
    suit.nodes['[code]']['var']['list'].append(os.path.relpath(value))
    suit.nodes['[code]']['var']['list'].append(os.path.abspath(value))
suit.condition = {}
suit.loop = {}
suit.request = Request(environ)
suit.response = Response()
template = suit.execute(suit.nodes, open('templates/index.tpl').read())
def slack(params):
    params['tree']['case'] = params['var']
    return params
slacksnodes = {
	'<slacks':
	{
		'close': '/>',
        'postwalk': [slack],
		'skip': True,
		'var': escape(
            json.dumps(suit.LOG['contents'], separators = (',',':')),
            True
        )
	}
}
print suit.execute(slacksnodes, template)