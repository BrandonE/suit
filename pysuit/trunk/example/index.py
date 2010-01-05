#!/usr/local/bin/python2.6
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
import os
import sys
import cgitb
cgitb.enable()
from webob import Request, Response
sys.path.append('../')
from suit import SUIT
suitclass = SUIT()
sys.path.append('')
from config import files
from config import filetypes
from config import nodes
suitclass.vars['files'] = files
suitclass.vars['filetypes'] = filetypes
suitclass.vars['nodes'] = nodes
suitclass.vars['condition'] = {}
suitclass.vars['loop'] = {}
environ = dict(os.environ.items())
environ['wsgi.input'] = sys.stdin
environ['wsgi.errors'] = sys.stderr
environ['wsgi.version'] = (1,0)
environ['wsgi.multithread'] = False
environ['wsgi.multiprocess'] = True
environ['wsgi.run_once'] = True
environ['wsgi.url_scheme'] = 'http'
suitclass.vars['request'] = Request(environ)
suitclass.vars['response'] = Response()
execfile(
    ''.join((
        suitclass.vars['files']['code'],
        '/print.py'
    ))
)
print 'Content-Type: text/html\n'
print suitclass.gettemplate(
    open
    (
        ''.join((
            suitclass.vars['files']['templates'],
            '/index.tpl'
        ))
    ).read(),
    [
        ''.join((
            suitclass.vars['files']['code'],
            '/index.py'
        )),
        ''.join((
            suitclass.vars['files']['code'],
            '/variables.py'
        )),
        ''.join((
            suitclass.vars['files']['code'],
            '/parse.py'
        ))
    ]
)