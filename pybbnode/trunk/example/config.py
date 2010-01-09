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
import pickle
from suit import nodes
files = {
    'code': 'code',
    'templates': 'templates'
}
filetypes = {
    'code': 'py',
    'templates': 'tpl'
}
nodes = {
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
            'delimiter': '=>',
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
        'function': [
            nodes.jsondecode,
            nodes.condition
        ],
        'skip': True,
        'transform': False,
        'var':
        {
            'condition': 'false',
            'decode': ('condition', 'else'),
            'else': 'false'
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
            'blacklist': True,
            'equal': '=',
            'list': ('decode',),
            'quote': '"'
        }
    },
    '[loop]':
    {
        'close': '[/loop]',
        'function': [
            nodes.jsondecode,
            nodes.loop
        ],
        'skip': True,
        'var':
        {
            'decode': ('skip', 'vars'),
            'delimiter': '',
            'node': '[loopvar]',
            'skip': 'true',
            'vars': '[]'
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
            'list': ('decode', 'node'),
            'quote': '"'
        }
    },
    '[loopvar]':
    {
        'close': '[/loopvar]',
        'function': [
            nodes.jsondecode,
            nodes.loopvariables
        ],
        'var':
        {
            'decode': ('json', 'serialize'),
            'delimiter': '=>',
            'ignore': {},
            'json': 'false',
            'serialize': 'false',
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
            'list': ('json', 'serialize'),
            'quote': '"'
        }
    },
    '[parse]':
    {
        'close': '[/parse]',
        'function': [nodes.parse],
        'var': {}
    },
    '[parse':
    {
        'close': ']',
        'function': [nodes.attribute],
        'attribute': '[parse]',
        'skip': True,
        'var':
        {
            'equal': '=',
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
            nodes.jsondecode,
            nodes.returning
        ],
        'skip': True,
        'var':
        {
            'equal': '=',
            'list': ('stack',),
            'onesided': True,
            'quote': '"',
            'var':
            {
                'decode': ('stack',),
                'stack': 'false'
            }
        }
    },
    '[template]':
    {
        'close': '[/template]',
        'function': [nodes.templates],
        'var':
        {
            'files': files,
            'filetypes': filetypes,
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
            'delimiter': '=>',
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
        'function': [
            nodes.jsondecode,
            nodes.variables
        ],
        'var':
        {
            'decode': ('json', 'serialize'),
            'delimiter': '=>',
            'json': 'false',
            'serialize': 'false'
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
            'list': ('json', 'serialize'),
            'quote': '"'
        }
    }
}