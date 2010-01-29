import os

from pylons import config
from pysuitlons.lib.suit import suit

def render(template):
    """Provide our own rendering function for PySUIT."""
    try:
        filepath = os.path.join(config['pylons.paths']['templates'],
            '%s.tpl' % (template))
        content = open(filepath).read()
    except IOError:
        raise IOError('Template does not exist: %s' % template)

    return suit.execute(config['suit.nodes'], content)

__all__ = ['render']