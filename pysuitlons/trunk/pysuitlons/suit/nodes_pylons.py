import pickle

from pylons import c, url as _url
from pylons.i18n import gettext as _gettext

def tmpl_context(params):
    """Rip-off of SUIT's default [var] node. Parses variables in the Pylons
    template context.
    """
    # Split up the file, paying attention to escape strings
    split = params['suit'].explodeunescape(
        params['var']['delimiter'],
        params['case'],
        params['config']['escape']
    )
    for key, value in enumerate(split): 
        if key == 0:
            params['case'] = getattr(c, value) 
        else:
            try:
                params['case'] = params['case'][value] 
            except (AttributeError, TypeError):
                try:
                    params['case'] = params['case'][int(value)] 
                except (AttributeError, TypeError, ValueError):
                    params['case'] = getattr(params['case'], value)
    if params['var']['serialize']:
        params['case'] = pickle.dumps(params['case'])
    return params

def url(params):
    """Pass attributes specified in the [url ... /] node to URL and generate
    a routes URL.
    """
    try:
        params['case'] = u'%s' % (_url(**params['var']))
    except TypeError:
        params['case'] = ''
    return params

def gettext(params):
    """Grabs a gettext string."""
    params['case'] = _gettext(params['var']['text'])
    return params