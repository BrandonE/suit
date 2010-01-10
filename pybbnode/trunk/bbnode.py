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
__version__ = '0.0.0'

def attribute(params):
    """Create node out of attribute"""
    params['var']['node'] = params['nodes'][
        params['open']['node']['attribute']
    ].copy()
    params['var']['node']['var'] = params['var']['node']['var'].copy()
    params['var']['node']['var']['equal'] = params['case']
    return params

def size(params):
    """Define the correct size"""
    params['var']['equal'] = int(params['var']['equal']) + 7
    if params['var']['equal'] > 30:
        params['var']['equal'] = 30
    return params

def stack(params):
    """Add the BBCode attribute to the stack"""
    params['case'] = ''.join((
        params['open']['open'],
        params['case'],
        params['open']['node']['close']
    ))
    params['taken'] = False
    #Add the new node to the stack
    newstack = params['suit'].stack(
        params['var']['node'],
        params['case'],
        params['open']['position']
    )
    params['stack'].extend(newstack['stack'])
    params['skipnode'].extend(newstack['skipnode'])
    params['preparse']['nodes'][params['case']] = params['var']['node']
    return params

def style(params):
    """Prevent style hacking"""
    explode = params['var']['equal'].split(';', 2)
    params['var']['equal'] = explode[0]
    params['var']['equal'] = params['var']['equal'].replace(
        '"',
        ''
    ).replace(
        '\'',
        ''
    )
    return params

def template(params):
    """Substitute variables into the template"""
    params['suit'].vars['case'] = params['case']
    params['suit'].vars['equal'] = params['var']['equal']
    params['case'] = params['suit'].parse(
        params['suit'].vars['nodes'],
        params['var']['template']
    )
    return params

def listitems(params):
    """Create the list items"""
    if not params['var']['equal'] or params['var']['equal'] in (
        '1',
        'a',
        'A',
        'i',
        'I'
    ):
        params['case'] = params['case'].replace('<br />', '')
        params['case'] = params['case'].split(params['var']['delimiter'])
        for key, value in enumerate(params['case']):
            if key != 0:
                params['case'][key] = ''.join((
                    params['var']['open'],
                    value,
                    params['var']['close']
                ))
        params['case'] = ''.join(params['case'])
    else:
        params['var']['template'] = ''.join((
            params['open']['open'],
            params['case'],
            params['open']['node']['close']
        ))
    return params

nodes = {
    '[':
    {
        'close': ']'
    },
    '[align]':
    {
        'close': '[/align]',
        'function':
        [
            style,
            template
        ],
        'var':
        {
            'equal': '',
            'label': 'align',
            'template': ''
        }
    },
    '[align=':
    {
        'close': ']',
        'function':
        [
            attribute,
            stack
        ],
        'attribute': '[align]',
        'var': {
            'node': {}
        }
    },
    '[b]':
    {
        'close': '[/b]',
        'function': [template],
        'var':
        {
            'equal': '',
            'label': 'b',
            'template': ''
        }
    },
    '[code]':
    {
        'close': '[/code]',
        'function': [template],
        'skip': True,
        'var':
        {
            'equal': '',
            'label': 'code',
            'template': ''
        }
    },
    '[color]':
    {
        'close': '[/color]',
        'function':
        [
            style,
            template
        ],
        'var':
        {
            'equal': '',
            'label': 'color',
            'template': ''
        }
    },
    '[color=':
    {
        'close': ']',
        'function':
        [
            attribute,
            stack
        ],
        'attribute': '[color]',
        'var': {
            'node': {}
        }
    },
    '[email]':
    {
        'close': '[/email]',
        'function': [template],
        'var':
        {
            'equal': '',
            'label': 'email',
            'template': ''
        }
    },
    '[email=':
    {
        'close': ']',
        'function':
        [
            attribute,
            stack
        ],
        'attribute': '[email]',
        'var': {
            'node': {}
        }
    },
    '[font]':
    {
        'close': '[/font]',
        'function':
        [
            style,
            template
        ],
        'var':
        {
            'equal': 'serif',
            'label': 'font',
            'template': ''
        }
    },
    '[font=':
    {
        'close': ']',
        'function':
        [
            attribute,
            stack
        ],
        'attribute': '[font]',
        'var': {
            'node': {}
        }
    },
    '[i]':
    {
        'close': '[/i]',
        'function': [template],
        'var':
        {
            'equal': '',
            'label': 'i',
            'template': ''
        }
    },
    '[img]':
    {
        'close': '[/img]',
        'function': [template],
        'var':
        {
            'equal': '',
            'label': 'img',
            'template': ''
        }
    },
    '[list]':
    {
        'close': '[/list]',
        'function':
        [
            listitems,
            template
        ],
        'var':
        {
            'close': '</li>',
            'delimiter': '[*]',
            'equal': '',
            'label': 'list',
            'open': '<li>',
            'template': ''
        }
    },
    '[list=':
    {
        'close': ']',
        'function':
        [
            attribute,
            stack
        ],
        'attribute': '[list]',
        'var': {
            'node': {}
        }
    },
    '[s]':
    {
        'close': '[/s]',
        'function': [template],
        'var':
        {
            'equal': '',
            'label': 's',
            'template': ''
        }
    },
    '[size]':
    {
        'close': '[/size]',
        'function':
        [
            style,
            size,
            template
        ],
        'var':
        {
            'equal': '3',
            'label': 'size',
            'template': ''
        }
    },
    '[size=':
    {
        'close': ']',
        'function':
        [
            attribute,
            stack
        ],
        'attribute': '[size]',
        'var': {
            'node': {}
        }
    },
    '[quote]':
    {
        'close': '[/quote]',
        'function': [template],
        'var':
        {
            'equal': '',
            'label': 'quote',
            'template': ''
        }
    },
    '[quote=':
    {
        'close': ']',
        'function':
        [
            attribute,
            stack
        ],
        'attribute': '[quote]',
        'var': {
            'node': {}
        }
    },
    '[u]':
    {
        'close': '[/u]',
        'function': [template],
        'var':
        {
            'equal': '',
            'label': 'u',
            'template': ''
        }
    },
    '[url]':
    {
        'close': '[/url]',
        'function': [template],
        'var':
        {
            'equal': '',
            'label': 'url',
            'template': ''
        }
    },
    '[url=':
    {
        'close': ']',
        'function':
        [
            attribute,
            stack
        ],
        'attribute': '[url]',
        'var': {
            'node': {}
        }
    },
}