"""
**@This file is part of Rulebox.
**@Rulebox is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@Rulebox is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with Rulebox.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
"""
import suit
from rulebox import templating

__version__ = '0.0.0'

def attribute(params):
    """Create rule out of attribute"""
    if 'create' in params:
        params['var']['equal'] = params['create']
    return params

def bracket(params):
    """Handle brackets unrelated to the rules"""
    params['tree']['case'] = ''.join((
        params['tree']['rule'],
        params['tree']['case'],
        params['rules'][params['tree']['rule']]['close']
    ))
    return params

def linebreaks(params):
    """Remove the HTML line breaks"""
    params['tree']['case'] = params['tree']['case'].replace('<br />', '')
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
        params['tree']['case'] = params['tree']['case'].replace('<br />', '')
        params['tree']['case'] = params['tree']['case'].split(params['var']['delimiter'])
        for key, value in enumerate(params['tree']['case']):
            if key != 0:
                params['tree']['case'][key] = ''.join((
                    params['var']['open'],
                    value,
                    params['var']['close']
                ))
        params['tree']['case'] = ''.join(params['tree']['case'])
    else:
        params['var']['template'] = ''.join((
            params['open']['open'],
            params['tree']['case'],
            params['open']['rule']['close']
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
    suit.case = params['tree']['case']
    suit.equal = params['var']['equal']
    params['tree']['case'] = suit.execute(
        templating.RULES,
        params['var']['template']
    )
    return params

RULES = {
    '[':
    {
        'close': ']',
        'postwalk': [bracket]
    },
    '[align]':
    {
        'close': '[/align]',
        'postwalk':
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
        'postwalk': [template],
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
        'postwalk': [
            linebreaks,
            template
        ],
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
        'postwalk':
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
        'postwalk': [
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
        'postwalk':
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
        'postwalk': [template],
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
        'postwalk': [template],
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
        'postwalk':
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
        'postwalk': [template],
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
        'postwalk':
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
        'postwalk': [
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
        'postwalk': [
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
        'postwalk': [
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