"""Helper functions

Consists of functions to typically be used within templates, but also
available to Controllers. This module is available to templates as 'h'.
"""
# Import helpers as desired, or define your own, ie:
#from webhelpers.html.tags import checkbox, password
try:
    import simplejson as json
except ImportError:
    import json
import os
from pylons import config, request, response, tmpl_context as c
from pylons.i18n import get_lang, set_lang, ugettext as _gettext
import suit
import urllib
from webhelpers.html import escape

def language():
    c.loop['languages'] = [
        {
            'id': 'en',
            'title': 'English',
            'selected': (get_lang() == 'en')
        }
    ]

def slacks():
    if ('submit' in request.POST and
    request.POST['submit'] == _gettext('Submit')):
        redirect(
            ''.join
            ((
                url(
                    controller = 'root',
                    action = 'template',
                    templatefile = 'slacks'
                ),
                '?url=',
                urllib.quote_plus(request.POST['url'])
            )),
            code = 303
        )
    c.referrer = request.headers["referer"]
    c.condition['tree'] = False
    c.condition['referrer'] = ('referrer' in request.GET and
    request.GET['referrer'])
    c.loop['tree'] = []
    try:
        tree = ''
        if 'url' in request.GET:
            tree = urllib.urlopen(
                request.GET['url'],
                urllib.urlencode({
                    'slacks': 'true'
                })
            ).read()
        elif ('submit' in request.POST and
        request.POST['submit'] == _gettext('Upload')):
            tree = request.POST['file'].file.read()
        tree = json.loads(tree)
        tree.sort(key = lambda item: item['id'])
        tree = slacksrecurse(
            tree,
            _gettext('No Wrapper'),
            _gettext('Wrapper')
        )
        c.condition['tree'] = True
        c.loop['tree'] = tree
    except (
        AttributeError,
        EOFError,
        IOError,
        NameError,
        TypeError,
        ValueError
    ):
        pass

def slacksrecurse(tree, nowrapper, wrapper):
    for key, value in enumerate(tree):
        tree[key] = {
            'array': False,
            'contents': value,
            'recursed': True
        }
        if isinstance(value, dict):
            tree[key] = value
            tree[key]['array'] = True
            tree[key]['recursed'] = (not 'original' in tree[key])
            tree[key]['created'] = ('create' in tree[key])
            tree[key]['contents'] = slacksrecurse(
                value['contents'],
                nowrapper,
                wrapper
            )
            tree[key]['recursed'] = (not 'original' in value);
            if not 'rule' in value:
                tree[key]['rule'] = nowrapper
            elif not value['rule']:
                tree[key]['rule'] = wrapper
            for key2, value2 in enumerate(value['parallel']):
                tree[key]['parallel'][key2] = {
                    'parallel': value2
                }
    return tree

def tryit():
    if 'submit' in request.POST and request.POST['submit']:
        c.template = request.POST['template']
    else:
        try:
            c.template = open(
                os.path.join(
                    config['suit.templates'],
                    'tryit',
                    c.parameter2 + '.tpl'
                )
            ).read()
        except TypeError:
            c.template = ''
    c.rule = ''
    c.condition['rule'] = True
    rules = {}
    if c.parameter1 == 'templating':
        from rulebox import templating
        rules = templating.rules.copy()
        rules['[code]']['var']['list'] = []
        rules['[template]']['var']['list'] = []
        c.rule = 'Templating'
    elif c.parameter1 == 'suitlons':
        from rulebox import suitlons
        rules = suitlons.rules
        c.rule = 'SUITlons'
    elif c.parameter1 == 'bbcode':
        from rulebox import bbcode
        rules = bbcode.rules
        for value in rules.items():
            if 'var' in value[1] and 'label' in value[1]['var']:
                rules[value[0]]['var']['template'] = open(
                        os.path.join(
                            config['suit.templates'],
                            'bbcode',
                            value[1]['var']['label'] + '.tpl'
                        )
                    ).read()
        c.template = escape(
            c.template,
            True
        ).replace('\n','<br />\n')
        c.rule = 'BBCode'
    elif c.parameter1 == None:
        c.condition['rule'] = False
    c.php = ''
    c.python = ''
    c.condition['php'] = False
    c.condition['python'] = False
    c.condition['pygments'] = False
    try:
        python = os.path.join(
            config['suit.templates'],
            'tryit',
            'code',
            c.parameter2 + '.py'
        )
        execfile(python)
        c.python = open(python).read()
        c.condition['python'] = True
        try:
            import pygments
            c.condition['pygments'] = True
        except ImportError:
            pass
        c.php = open(
            os.path.join(
                config['suit.templates'],
                'tryit',
                'code',
                c.parameter2 + '.php'
            )
        ).read()
        c.condition['php'] = True
    except (IOError, TypeError):
        pass
    if c.condition['pygments']:
        from pygments import highlight
        from pygments.lexers import PhpLexer, PythonLexer
        from pygments.formatters import HtmlFormatter
        c.php = highlight(c.php, PhpLexer(), HtmlFormatter())
        c.python = highlight(c.python, PythonLexer(), HtmlFormatter())
    c.executed = suit.execute(rules, c.template)