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
    'case': 'Case',
    'contents': 'Contents',
    'copyright': 'Copyright &copy; 2008-2010 <a href="http://www.suitframework.com/docs/credits" target="_blank">The SUIT Group</a>. All Rights Reserved.',
    'default': 'Default',
    'enablejavascript': 'Enable Javascript',
    'nowrapper': 'No Wrapper',
    'parallel': 'Parallel',
    'poweredby': 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>',
    'slacks': 'See this page built using SLACKS',
    'slogan': 'SLACKS Lets Application Coders Know SUIT',
    'suit': 'SUIT',
    'title': 'PySLACKS',
    'update': 'Update',
    'wrapper': 'Wrapper'
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
def recurse(slacks, nowrapper, wrapper):
    for key, value in enumerate(slacks):
        slacks[key] = {
            'array': False,
            'contents': value,
            'recursed': True
        }
        if isinstance(value, dict):
            slacks[key] = value
            slacks[key]['array'] = True
            slacks[key]['recursed'] = (not 'original' in slacks[key])
            slacks[key]['created'] = ('create' in slacks[key])
            slacks[key]['contents'] = recurse(
                value['contents'],
                nowrapper,
                wrapper
            )
            slacks[key]['recursed'] = (not 'original' in value);
            if not 'node' in value:
                slacks[key]['node'] = nowrapper
            elif not value['node']:
                slacks[key]['node'] = wrapper
            for key2, value2 in enumerate(value['parallel']):
                slacks[key]['parallel'][key2] = {
                    'parallel': value2
                }
    return slacks
suit.loop['slacks'] = []
if 'submit' in suit.request.POST and suit.request.POST['submit']:
    try:
        suit.loop['slacks'] = json.loads(suit.request.POST['slacks'])
        suit.loop['slacks'].sort(key = lambda item: item['id'])
        suit.loop['slacks'] = recurse(
            suit.loop['slacks'],
            suit.language['nowrapper'],
            suit.language['wrapper']
        )
    except (AttributeError, TypeError, ValueError):
        suit.loop['slacks'] = []
    