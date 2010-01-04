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
    '[parse]':
    {
        'close': '[/parse]',
        'function': [nodes.parse],
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