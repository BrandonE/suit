from pylons import c, url
from pylons.i18n import gettext

def tmpl_context(params):
    """Parse variables"""
    #Split up the file, paying attention to escape strings
    split = params['suit'].explodeunescape(
        params['var']['separator'],
        params['case'],
        params['escape']
    )
    #params['case'] = params['suit'].vars
    for value in split:
        params['case'] = getattr(c, value)
    return params

def url_for(params):
    """"""
    args = {}
    case = str(params['case']).split('; ')
    for argument in case:
        k, v = argument.split('=')
        args[k] = v
    if not 'id' in args:
        args['id'] = ''
    params['case'] = u'%s' % url(**args).rstrip('/')
    return params

__all__ = ['tmpl_context', 'url_for']