"""Pylons environment configuration"""
import os
import pickle

from pylons import config
from sqlalchemy import engine_from_config

from pysuitlons.lib import suit
from pysuitlons.suit import nodes, nodes_pylons

import pysuitlons.lib.app_globals as app_globals
import pysuitlons.lib.helpers
from pysuitlons.config.routing import make_map
from pysuitlons.model import init_model

def load_environment(global_conf, app_conf):
    """Configure the Pylons environment via the ``pylons.config``
    object
    """
    # Pylons paths
    root = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    paths = dict(root=root,
                 controllers=os.path.join(root, 'controllers'),
                 static_files=os.path.join(root, 'public'),
                 templates=os.path.join(root, 'templates'))

    # Initialize config with the basic options
    config.init_app(global_conf, app_conf, package='pysuitlons', paths=paths)

    config['routes.map'] = make_map()
    config['pylons.app_globals'] = app_globals.Globals()
    config['pylons.h'] = pysuitlons.lib.helpers

    # Setup the SQLAlchemy database engine
    engine = engine_from_config(config, 'sqlalchemy.')
    init_model(engine)

    # Set-up SUIT configuration
    # Setup SUIT template syntax (nodes)
    suit.vars['nodes'] = {
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
                nodes.attribute,
                nodes.conditionskip
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
            'function': [nodes.loop],
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
            'function': [nodes.attribute],
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
            'function': [nodes.returning],
            'skip': True
        },
        '[template]':
        {
            'close': '[/template]',
            'function': [nodes.templates],
            'var':
            {
                'files': paths['templates'],
                'filetypes': {'code':'py', 'templates': 'tpl'},
                'delimiter': '=>'
            }
        },
        '[template':
        {
            'close': ']',
            'function': [nodes.attribute],
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
            'skipescape': True,
            'var':
            {
                'equal': '=',
                'quote': '"'
            }
        },
        '[c]':
        {
            'close': '[/c]',
            'function': [nodes_pylons.tmpl_context],
            'var':
            {
                'delimiter': '=>',
                'serialize': False
            }
        },
        '[c':
        {
            'close': ']',
            'function': [nodes.attribute],
            'attribute': '[c]',
            'skip': True,
            'skipescape': True,
            'var':
            {
                'equal': '=',
                'quote': '"'
            }
        }
    }
    suit.vars['condition'] = []
    suit.vars['loop'] = []