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
suit.vars['language'] = {
    'copyright': 'Copyright &copy; 2008-2010 <a href="http://www.suitframework.com/docs/credits" target="_blank">The SUIT Group</a>. All Rights Reserved.',
    'default': 'Default',
    'example': 'Example',
    'message': 'Message',
    'poweredby': 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>',
    'slogan': 'BBCode Using SUIT Nodes',
    'suit': 'SUIT',
    'title': 'PyBBNode',
    'update': 'Update'
}
languages = {
    'english': 'english'
}
if ('language' in suit.vars['request'].GET and
suit.vars['request'].GET['language'].lower() in languages):
    suit.vars['languagename'] = languages[
        suit.vars['request'].GET['language'].lower()
    ]
else:
    suit.vars['languagename'] = 'default'
if ('submit' in suit.vars['request'].POST and
suit.vars['request'].POST['submit']):
    from bbnode import bbnode
    import cgi
    for value in bbnode.items():
        if 'var' in value[1] and 'label' in value[1]['var']:
            bbnode[value[0]]['var']['template'] = open(
                    ''.join((
                        '../templates/',
                        value[1]['var']['label'],
                        '.tpl'
                    ))
                ).read()
    config = {
        'escape': ''
    }
    suit.vars['message'] = cgi.escape(
        suit.vars['request'].POST['message'],
        True
    )
    suit.vars['parsed'] = suit.parse(
        bbnode,
        suit.vars['message'].replace('\n','<br />\n')
    )
else:
    suit.vars['message'] = ''