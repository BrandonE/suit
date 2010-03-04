"""Pylons environment configuration"""
import os

from pylons import config
from sqlalchemy import engine_from_config

from rulebox import suitlons

import suitframework.lib.app_globals as app_globals
import suitframework.lib.helpers
from suitframework.config.routing import make_map
from suitframework.model import init_model

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
    config.init_app(global_conf, app_conf, package='suitframework', paths=paths)

    config['routes.map'] = make_map()
    config['pylons.app_globals'] = app_globals.Globals()
    config['pylons.h'] = suitframework.lib.helpers
    suitlons.rules['[call']['var']['var']['helpers'] = config['pylons.h']
    suitlons.rules['[transform]']['var']['var']['helpers'] = config['pylons.h']

    # Setup PySUIT templating rules.
    config['suit.templates'] = paths['templates']
    config['suit.rules'] = suitlons.rules