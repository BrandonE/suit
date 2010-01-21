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

__version__ = '0.0.0'

def attribute(params):
    """Create node out of attribute"""
    if 'create' in params:
        params['var']['equal'] = params['create']
    return params

def bracket(params):
    """Handle brackets unrelated to the nodes"""
    params['case'] = ''.join((
        params['node'],
        params['case'],
        params['nodes'][params['node']]['close']
    ))
    return params

def size(params):
    """Define the correct size"""
    params['var']['equal'] = int(params['var']['equal']) + 7
    if params['var']['equal'] > 30:
        params['var']['equal'] = 30
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
    suit.case = params['case']
    suit.equal = params['var']['equal']
    params['case'] = suit.execute(
        suit.nodes,
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

NODES = {
    '[':
    {
        'close': ']',
        'stringfunctions': [bracket]
    },
    '[align]':
    {
        'close': '[/align]',
        'stringfunctions':
        [
            attribute,
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
        'create': '[align]'
    },
    '[b]':
    {
        'close': '[/b]',
        'stringfunctions': [template],
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
        'stringfunctions': [template],
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
        'stringfunctions':
        [
            attribute,
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
        'create': '[color]'
    },
    '[email]':
    {
        'close': '[/email]',
        'stringfunctions': [
            attribute,
            template
        ],
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
        'create': '[email]'
    },
    '[font]':
    {
        'close': '[/font]',
        'stringfunctions':
        [
            attribute,
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
        'create': '[font]'
    },
    '[i]':
    {
        'close': '[/i]',
        'stringfunctions': [template],
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
        'stringfunctions': [template],
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
        'stringfunctions':
        [
            attribute,
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
        'create': '[list]'
    },
    '[s]':
    {
        'close': '[/s]',
        'stringfunctions': [template],
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
        'stringfunctions':
        [
            attribute,
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
        'create': '[size]'
    },
    '[quote]':
    {
        'close': '[/quote]',
        'stringfunctions': [
            attribute,
            template
        ],
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
        'create': '[quote]'
    },
    '[u]':
    {
        'close': '[/u]',
        'stringfunctions': [
            attribute,
            template
        ],
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
        'stringfunctions': [
            attribute,
            template
        ],
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
        'create': '[url]'
    }
}