"""Pylons environment configuration"""
import os

from pylons import config
from sqlalchemy import engine_from_config

import pysuitlons.lib.app_globals as app_globals
import pysuitlons.lib.helpers

from pysuitlons.lib import suit, templating
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
    # Setup PySUIT template nodes.
    suit.vars['nodes'] = templating.nodes()
    suit.vars['condition'] = []
    suit.vars['loop'] = []