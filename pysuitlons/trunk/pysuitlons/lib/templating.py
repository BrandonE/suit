import os
import pickle

from pylons import config

from pysuitlons.suit import nodes as _nodes, nodes_pylons
from pysuitlons.lib import suit

def render(template, files=[]):
    """Provide our own rendering function for PySUIT."""
    code = []
    templates_path = config['pylons.paths']['templates']
    code_path = os.path.join(templates_path, 'code')
    for i in files:
        code.append('%s/%s.py' % (code_path, i))
    try:
        filepath = '%s/%s.tpl' % (templates_path, template)
        content = open(filepath)
    except:
        raise IOError('Template does not exist: %s' % template)
    return u'%s' % suit.gettemplate(content.read(), code)

def nodes():
    # Set-up SUIT configuration
    # Setup SUIT template syntax (nodes)
    return {
        '[':
        {
            'close': ']'
        },
        '[assign]':
        {
            'close': '[/assign]',
            'function': [_nodes.assign],
            'var':
            {
                'var': ''
            }
        },
        '[assign':
        {
            'close': ']',
            'function': [_nodes.attribute],
            'attribute': '[assign]',
            'skip': True,
            'skipescape': True,
            'var':
            {
                'equal': '=',
                'quote': '"'
            }
        },
        '[comment]':
        {
            'close': '[/comment]',
            'function': [_nodes.comments],
            'skip': True
        },
        '[escape]':
        {
            'close': '[/escape]',
            'function': [_nodes.escape],
            'skip': True,
            'skipescape': True,
            'var': '\r\n\t '
        },
        #'[eval]':
        #{
            #'close': '[/eval]',
            #'function': [_nodes.evaluation]
        #},
        '[if]':
        {
            'close': '[/if]',
            'function': [_nodes.condition],
            'skip': True,
            'strip': True,
            'var':
            {
                'condition': False,
                'else': False,
                'trim': '\r\n\t '
            }
        },
        '[if':
        {
            'close': ']',
            'function':
            [
                _nodes.attribute,
                _nodes.conditionskip
            ],
            'attribute': '[if]',
            'skip': True,
            'skipescape': True,
            'var':
            {
                'equal': '=',
                'quote': '"'
            }
        },
        '[loop]':
        {
            'close': '[/loop]',
            'function': [_nodes.loop],
            'skip': True,
            'var':
            {
                'vars': pickle.dumps([]),
                'delimiter': '',
                'trim': '\r\n\t ',
                'node': '[loopvar]'
            }
        },
        '[loop':
        {
            'close': ']',
            'function': [_nodes.attribute],
            'attribute': '[loop]',
            'skip': True,
            'skipescape': True,
            'var':
            {
                'blacklist': True,
                'equal': '=',
                'list': ('node',),
                'quote': '"'
            }
        },
        '[loopvar]':
        {
            'close': '[/loopvar]',
            'function': [_nodes.loopvariables],
            'var':
            {
                'delimiter': '.',
                'ignore': {},
                'serialize': False,
                'var': {}
            }
        },
        '[loopvar':
        {
            'close': ']',
            'function': [_nodes.attribute],
            'attribute': '[loopvar]',
            'skip': True,
            'skipescape': True,
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
            'function': [_nodes.replace],
            'var':
            {
                'replace': '',
                'search': ''
            }
        },
        '[replace':
        {
            'close': ']',
            'function': [_nodes.attribute],
            'attribute': '[replace]',
            'skip': True,
            'skipescape': True,
            'var':
            {
                'equal': '=',
                'quote': '"'
            }
        },
        '[return': 
        {
            'close': '/]',
            'function': [_nodes.attribute, _nodes.returning], 
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
            'function': [_nodes.templates],
            'var':
            {
                'files': config['pylons.paths']['templates'],
                'filetypes': {'code':'py', 'templates': 'tpl'},
                'delimiter': '.'
            }
        },
        '[template':
        {
            'close': ']',
            'function': [_nodes.attribute],
            'attribute': '[template]',
            'skip': True,
            'skipescape': True,
            'var':
            {
                'equal': '=',
                'list': ('label',),
                'quote': '"'
            }
        },
        '[try]':
        {
            'close': '[/try]',
            'function': [_nodes.trying],
            'skip': True,
            'var':
            {
                'var': ''
            }
        },
        '[try':
        {
            'close': ']',
            'function': [_nodes.attribute],
            'attribute': '[try]',
            'skip': True,
            'skipescape': True,
            'var':
            {
                'equal': '=',
                'quote': '"'
            }
        },
        # pysuitlons custom nodes are defined here.
        '[c]':
        {
            'close': '[/c]',
            'function': [nodes_pylons.tmpl_context],
            'var':
            {
                'delimiter': '.',
                'serialize': False
            }
        },
        '[c':
        {
            'close': ']',
            'function': [_nodes.attribute],
            'attribute': '[c]',
            'skip': True,
            'skipescape': True,
            'var':
            {
                'equal': '=',
                'quote': '"'
            }
        },
        '[url': 
        { 
            'close': '/]',
            'function': [_nodes.attribute, nodes_pylons.url], 
            'skip': True,
            'var':
            {
                'equal': '=',
                'onesided': True,
                'quote': '"',
                'var': {}
            } 
        },
        '[gettext': 
        { 
            'close': '/]',
            'function': [_nodes.attribute, nodes_pylons.gettext], 
            'skip': True,
            'var':
            {
                'equal': '=',
                'onesided': True,
                'quote': '"',
                'var': 
                {
                    'text': ''
                }
            }
        }
    }

__all__ = ['render', 'nodes']