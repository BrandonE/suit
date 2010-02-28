import os

from pylons import config, tmpl_context as c
import suit

def render(template):
    """Provide our own rendering function for PySUIT."""
    filepath = os.path.join(config['suit.templates'], template)
    try:
        content = open(filepath).read()
    except IOError:
        raise IOError('Template does not exist: %s' % template)

    return suit.execute(config['suit.rules'], content)

def render_page(template):
    c._body = render(template)
    return render('base')

__all__ = ['render']