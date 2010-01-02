"""
**@This program is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@This program is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
"""
import pickle
import sys
sys.path.append('')
from suit import SUIT
from suit import helper
from suit import nodes

suitclass = SUIT()
suitclass.vars['files'] = {
    'code': 'code',
    'templates': 'templates'
}
suitclass.vars['filetypes'] = {
    'code': 'py',
    'templates': 'tpl'
}
suitclass.vars['nodes'] = {
    '[':
    {
        'close': ']'
    },
    '[assign]':
    {
        'close': '[/assign]',
        'function': [nodes.assign],
        'var':
        {
            'var': ''
        }
    },
    '[assign':
    {
        'close': ']',
        'function': [nodes.attribute],
        'attribute': '[assign]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'quote': '"'
        }
    },
    '[comment]':
    {
        'close': '[/comment]',
        'function': [nodes.comments],
        'skip': True
    },
    '[escape]':
    {
        'close': '[/escape]',
        'function': [nodes.escape],
        'skip': True,
        'skipescape': True,
        'var': '\r\n\t '
    },
    #'[eval]':
    #{
        #'close': '[/eval]',
        #'function': [nodes.evaluation]
    #},
    '[if]':
    {
        'close': '[/if]',
        'function': [nodes.condition],
        'skip': True,
        'transform': False,
        'var':
        {
            'condition': False,
            'else': False
        }
    },
    '[if':
    {
        'close': ']',
        'function':
        [
            nodes.attribute,
            nodes.conditionstack
        ],
        'attribute': '[if]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'quote': '"'
        }
    },
    '[loop]':
    {
        'close': '[/loop]',
        'function': [
            nodes.unserialize,
            nodes.loop
        ],
        'skip': True,
        'var':
        {
            'delimiter': '',
            'node': '[loopvar]',
            'skip': True,
            'unserialize': 'vars',
            'vars': pickle.dumps([])
        }
    },
    '[loop':
    {
        'close': ']',
        'function': [
            nodes.attribute,
            nodes.loopstack
        ],
        'attribute': '[loop]',
        'skip': True,
        'var':
        {
            'blacklist': True,
            'equal': '=',
            'list': ('node', 'unserialize'),
            'quote': '"'
        }
    },
    '[loopvar]':
    {
        'close': '[/loopvar]',
        'function': [nodes.loopvariables],
        'var':
        {
            'delimiter': '=>',
            'ignore': {},
            'serialize': False,
            'var': {}
        }
    },
    '[loopvar':
    {
        'close': ']',
        'function': [nodes.attribute],
        'attribute': '[loopvar]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'list': ('serialize',),
            'quote': '"'
        }
    },
    '[replace]':
    {
        'close': '[/replace]',
        'function': [nodes.replace],
        'var':
        {
            'replace': '',
            'search': ''
        }
    },
    '[replace':
    {
        'close': ']',
        'function': [nodes.attribute],
        'attribute': '[replace]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'quote': '"'
        }
    },
    '[return':
    {
        'close': '/]',
        'function':
        [
            nodes.attribute,
            nodes.returning
        ],
        'skip': True,
        'var':
        {
            'equal': '=',
            'onesided': True,
            'quote': '"',
            'var':
            {
                'stack': False
            }
        }
    },
    '[template]':
    {
        'close': '[/template]',
        'function': [nodes.templates],
        'var':
        {
            'files': suitclass.vars['files'],
            'filetypes': suitclass.vars['filetypes'],
            'delimiter': '=>'
        }
    },
    '[template':
    {
        'close': ']',
        'function': [nodes.attribute],
        'attribute': '[template]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'list': ('label',),
            'quote': '"'
        }
    },
    '[trim]':
    {
        'close': '[/trim]',
        'function': [nodes.trim],
    },
    '[try]':
    {
        'close': '[/try]',
        'function': [nodes.trying],
        'skip': True,
        'var':
        {
            'var': ''
        }
    },
    '[try':
    {
        'close': ']',
        'function': [nodes.attribute],
        'attribute': '[try]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'quote': '"'
        }
    },
    '[var]':
    {
        'close': '[/var]',
        'function': [nodes.variables],
        'var':
        {
            'delimiter': '=>',
            'serialize': False
        }
    },
    '[var':
    {
        'close': ']',
        'function': [nodes.attribute],
        'attribute': '[var]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'quote': '"'
        }
    }
}
suitclass.vars['condition'] = {}
suitclass.vars['loop'] = {}

def attribute(params):
    """Create node out of attribute"""
    params['var']['node'] = params['nodes'][
        params['open']['node']['attribute']
    ].copy()
    params['var']['node']['var'] = params['var']['node']['var'].copy()
    params['var']['node']['var']['equal'] = params['case']
    return params

def parse(bbcode):
    """Parse the BBcode"""
    config = {
        'escape': ''
    }
    return suitclass.parse(nodes, bbcode, config)

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
    newstack = {
        'node': params['case'],
        'nodes': {},
        'position': params['open']['position'],
        'skipnode': [],
        'stack': []
    }
    newstack['nodes'][newstack['node']] = params['var']['node']
    newstack = helper.stack(newstack)
    params['stack'].extend(newstack['stack'])
    params['skipnode'].extend(newstack['skipnode'])
    params['preparse']['nodes'][newstack['node']] = params['var']['node']
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
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/align.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
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
        'attribute': '[align]'
    },
    '[b]':
    {
        'close': '[/b]',
        'function': [template],
        'var':
        {
            'equal': '',
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/b.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
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
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/code.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
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
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/color.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
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
        'attribute': '[color]'
    },
    '[email]':
    {
        'close': '[/email]',
        'function': [template],
        'var':
        {
            'equal': '',
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/email.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read(),
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
        'attribute': '[email]'
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
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/font.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
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
        'attribute': '[font]'
    },
    '[i]':
    {
        'close': '[/i]',
        'function': [template],
        'var':
        {
            'equal': '',
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/i.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
        }
    },
    '[img]':
    {
        'close': '[/img]',
        'function': [template],
        'var':
        {
            'equal': '',
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/img.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
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
            'open': '<li>',
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/list.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
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
        'attribute': '[list]'
    },
    '[s]':
    {
        'close': '[/s]',
        'function': [template],
        'var':
        {
            'equal': '',
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/s.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
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
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/size.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
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
        'attribute': '[size]'
    },
    '[quote]':
    {
        'close': '[/quote]',
        'function': [template],
        'var':
        {
            'equal': '',
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/quote.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read(),
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
        'attribute': '[quote]'
    },
    '[u]':
    {
        'close': '[/u]',
        'function': [template],
        'var':
        {
            'equal': '',
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/u.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
        }
    },
    '[url]':
    {
        'close': '[/url]',
        'function': [template],
        'var':
        {
            'equal': '',
            'template': open(
                ''.join((
                    suitclass.vars['files']['templates'],
                    '/url.',
                    suitclass.vars['filetypes']['templates']
                ))
            ).read()
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
        'attribute': '[url]'
    },
}