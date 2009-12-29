from pylons import config
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