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
import suit
suit.language = {
    'contents': 'Contents of code/variables.py',
    'copyright': 'Copyright &copy; 2008-2010 <a href="http://www.suitframework.com/docs/credits" target="_blank">The SUIT Group</a>. All Rights Reserved.',
    'default': 'Default',
    'example': 'Example',
    'executed': 'Executed',
    'item': 'Item',
    'poweredby': 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>',
    'slacks': 'See this page built using SLACKS',
    'slogan': 'Scripting Using Integrated Templates',
    'submit': 'Submit',
    'suit': 'SUIT',
    'template': 'Template',
    'title': 'PySUIT',
    'update': 'Update'
}
languages = {
    'english': 'english'
}
if ('language' in suit.request.GET and
suit.request.GET['language'].lower() in languages):
    suit.languagename = languages[
        suit.request.GET['language'].lower()
    ]
else:
    suit.languagename = 'default'
if 'submit' in suit.request.POST and suit.request.POST['submit']:
    suit.template = suit.request.POST['template']
else:
    suit.template = open('templates/example.tpl').read()
from cgi import escape
suit.templateentities = escape(
    suit.template,
    True
)
suit.code = open('code/variables.py').read()
try:
    import pygments
    from pygments import highlight
    from pygments.lexers import PythonLexer
    from pygments.formatters import HtmlFormatter
    suit.code = highlight(suit.code, PythonLexer(), HtmlFormatter())
    suit.condition['pygments'] = True
except ImportError:
    suit.code = escape(suit.code)
    suit.condition['pygments'] = False