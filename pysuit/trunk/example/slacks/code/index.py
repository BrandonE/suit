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
import suit
suit.language = {
    'copyright': 'Copyright &copy; 2008-2010 <a href="http://www.suitframework.com/docs/credits" target="_blank">The SUIT Group</a>. All Rights Reserved.',
    'default': 'Default',
    'htmlmode': 'HTML Mode',
    'na': 'N/A',
    'next': 'Next',
    'poweredby': 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>',
    'previous': 'Previous',
    'slacks': 'See this page built using SLACKS',
    'slogan': 'SLACKS Lets Application Coders Know SUIT',
    'suit': 'SUIT',
    'textmode': 'Text Mode',
    'title': 'PySLACKS',
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
from cgi import escape
try:
    import simplejson as json
except ImportError:
    import json
def recurse(slacks, na):
    for key, value in enumerate(slacks):
        if isinstance(value, str):
            slacks[key] = {
                'array': False,
                'contents': value
            }
        else:
            slacks[key]['contents'] = recurse(value['contents'], na)
            if 'node' in value:
                slacks[key]['node'] = na
            slacks[key]['array'] = True
    return slacks
if 'submit' in suit.request.POST and suit.request.POST['submit']:
    suit.loop['slacks'] = json.loads(suit.request.POST['slacks'])
    if not isinstance(suit.loop['slacks'], list):
        suit.loop['slacks'] = []
    suit.loop['slacks'] = recurse(suit.loop['slacks'], suit.language['na'])
else:
    suit.loop['slacks'] = []