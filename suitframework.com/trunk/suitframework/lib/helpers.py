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
from glob import glob
from pylons import config, request, response, tmpl_context as c, url
from pylons.controllers.util import redirect
from pylons.i18n import get_lang, set_lang, ugettext as _gettext
import suit
from rulebox import templating
import urllib
from webhelpers.html import escape
from webhelpers.html.builder import literal
from pygments import highlight
from pygments.lexers import get_lexer_by_name
from pygments.formatters import HtmlFormatter

def code(string):
    return string.replace(
        '\t', '    '
    ).replace(
        '\r\n', '\n'
    ).replace(
        '\r', '\'n'
    ).replace(
        '\n ', '\n&nbsp;'
    ).replace(
        '  ', ' &nbsp;'
    ).replace(
        '\n', '<br />'
    )

def docs():
    c.loop.articles = [
        {
            'articles': 
            [
                {
                    'title': 'Getting Started',
                    'url': 'gettingstarted'
                },
                {
                    'title': 'Comparison of SUIT to its Alternatives',
                    'url': 'comparison'
                },
                {
                    'title': 'Features',
                    'url': 'features'
                },
                {
                    'title': 'Rules',
                    'url': 'rules'
                },
                {
                    'title': 'Rules Vs. Functions',
                    'url': 'rulesvsfunctions'
                },
                {
                    'title': 'Escaping',
                    'url': 'gettingstarted'
                },
                {
                    'title': 'FAQ',
                    'url': 'faq'
                },
                {
                    'title': 'History',
                    'url': 'history'
                },
                {
                    'title': 'Changelog',
                    'url': 'changelog'
                },
                {
                    'title': 'Credits',
                    'url': 'credits'
                }
            ],
            'title': 'General',
            'url': 'general'
        },
        {
            'articles': 
            [
                {
                    'title': 'execute',
                    'url': 'execute'
                },
                {
                    'title': 'tokens',
                    'url': 'tokens'
                },
                {
                    'title': 'parse',
                    'url': 'parse'
                }
            ],
            'title': 'SUIT Functions',
            'url': 'suitfunctions'
        },
        {
            'articles': 
            [
                {
                    'title': 'defaultconfig',
                    'url': 'defaultconfig'
                }
            ],
            'title': 'Helper Functions',
            'url': 'helperfunctions'
        },
        {
            'articles': [],
            'title': 'Templating Rules',
            'url': 'templatingrules'
        },
        {
            'articles': [],
            'title': 'BBCode Rules',
            'url': 'bbcoderules'
        },
        {
            'articles': [],
            'title': 'SUITlons Rules',
            'url': 'suitlonsrules'
        },
        {
            'articles': [],
            'title': 'Helper Functions',
            'url': 'helperfunctions'
        },
        {
            'articles': [],
            'title': 'Subprojects',
            'url': 'subprojects'
        }
    ]
    c.condition.notfound = False
    c.condition.article = False
    c.condition.index = False
    c.condition.matches = False
    if not c.parameter1:
        c.condition.index = True
    elif os.path.isfile(
        os.path.join(
            config['pylons.paths']['templates'],
            'docs',
            os.path.normpath(c.parameter1.lower()) + '.tpl'
        )
    ):
        for category in c.loop.articles:
            for article in category['articles']:
                if c.parameter1 == article['url']:
                    c.article = article['title']
                    c.condition.article = True
                    break
    if c.parameter1 and not c.condition.article:
        c.loop.search = []
        for category in c.loop.articles:
            for article in category['articles']:
                if os.path.basename(
                    article['url']
                ).find(c.parameter1.lower()) != -1:
                    c.loop.search.append(article)
        c.condition.notfound = True
        c.condition.matches = (c.loop.search)
    return ''

def exittemplate(value = None):
    exit(value)

def format(string):
    string = templating.getvariable(string, '.', c)
    return json.dumps(string, sort_keys=True, indent=4)

def header():
    c.slackslog = request.POST
    if ('submit' in request.POST and
    request.POST['submit'] == _gettext('Search')):
        redirect(
            url(
                controller = 'root',
                action = 'template',
                templatefile = 'docs',
                parameter1 = request.POST['search'].replace(' ', '').lower()
            ),
            code = 303
        )
    #if ('submit' in request.POST and
    #request.POST['submit'] == _gettext('Update')):
    c.loop.languages = [
        {
            'id': 'en',
            'title': 'English',
            'selected': (get_lang() == 'en')
        }
    ]
    return ''

def increment(num):
    num = templating.getvariable(num, '.', c)
    return json.dumps(num + 1)

def indict(obj, key):
    obj = templating.getvariable(obj, '.', c)
    boolean = False
    if key in obj:
        boolean = True
    return json.dumps(boolean)

def isdict(obj):
    obj = templating.getvariable(obj, '.', c)
    boolean = False
    if isinstance(obj, dict):
        boolean = True
    return json.dumps(boolean)

def pygments(lexer, string):
    return highlight(string, get_lexer_by_name(lexer), HtmlFormatter())
    
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
    c.referrer = ''
    if 'referer' in request.headers:
        c.referrer = request.headers['referer']
    c.condition.error = False
    c.condition.first = True
    c.condition.referrer = ('referrer' in request.GET and
    request.GET['referrer'])
    c.key = -1
    c.log = {
        'hash': {},
        'contents': []
    }
    try:
        loaded = False
        if 'url' in request.GET:
            suit.log['contents'] = []
            c.log = urllib.urlopen(
                request.GET['url'],
                urllib.urlencode({
                    'slacks': 'true'
                })
            ).read()
            loaded = True
        elif ('submit' in request.POST and
        request.POST['submit'] == _gettext('Upload')):
            c.log = request.POST['file'].file.read()
            loaded = True
        if loaded:
            c.log = json.loads(c.log)
            if not c.log['contents']:
                raise
            for key, value in c.log['hash'].items():
                c.log['hash'][key] = json.loads(value)
    except (
        AttributeError,
        EOFError,
        IOError,
        NameError,
        TypeError,
        ValueError
    ):
        c.log = {
            'hash': {},
            'contents': []
        }
        c.condition.error = True
    return ''

def tokenshighlight(tokens, open, close, flat, end, string):
    tokens = templating.getvariable(tokens, '.', c)
    offset = 0
    original = string
    last = 0
    for value in tokens:
        start = value['bounds']['start']
        color = open
        if value['type'] == 'close':
            color = close
        elif value['type'] == 'flat':
            color = flat
        string = ''.join((
            string[0:last],
            code(escape(string[last:start + offset])),
            color,
            escape(
                string[start + offset:start + offset + len(value['string'])]
            ),
            end,
            string[start + offset + len(value['string']):len(string)]
        ))
        offset = len(string) - len(original)
        last = start + len(value['string']) + offset
    string = ''.join((
        string[0:last],
        code(escape(string[last:len(string)]))
     ))
    return string

def tryit():
    c.condition.rule = True
    c.rule = ''
    rules = {}
    c.executeconfig = {
        'entities': False,
        'escape': '\\',
        'insensitive': True,
        'linebreak': False,
        'log': True,
        'mismatched': False,
        'unclosed': False
    }
    if c.parameter1 == 'templating':
        from rulebox import templating
        rules = templating.rules.copy()
        rules['[template]'] = rules['[template]'].copy()
        rules['[template]']['var'] = rules['[template]']['var'].copy()
        rules['[template]']['var']['list'] = []
        c.rule = 'Templating'
    elif c.parameter1 == 'suitlons':
        from rulebox import suitlons
        rules = suitlons.rules.copy()
        c.rule = 'SUITlons'
    elif c.parameter1 == 'bbcode':
        from rulebox import bbcode
        rules = bbcode.rules.copy()
        #Load the BBCode templates
        for key, value in rules.items():
            if 'var' in value and 'label' in value['var']:
                rules[key] = rules[key].copy()
                rules[key]['var'] = rules[key]['var'].copy()
                rules[key]['var']['template'] = open(
                    os.path.join(
                        config['suit.templates'],
                        'bbcode',
                        value['var']['label'] + '.tpl'
                    )
                ).read()
        c.rule = 'BBCode'
        c.executeconfig['entities'] = True
        c.executeconfig['escape'] = ''
        c.executeconfig['linebreak'] = True
    elif c.parameter1 == None:
        c.condition.rule = False
    if 'submit' in request.POST and request.POST['submit']:
        c.executeconfig = {
            'entities': ('entities' in request.POST),
            'escape': request.POST['escape'],
            'insensitive': ('insensitive' in request.POST),
            'linebreak': ('linebreak' in request.POST),
            'log': ('log' in request.POST),
            'mismatched': ('mismatched' in request.POST),
            'unclosed': ('unclosed' in request.POST)
        }
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
    c.executed = c.template
    c.php = ''
    c.python = ''
    c.condition.php = False
    c.condition.python = False
    try:
        python = os.path.join(
            config['suit.templates'],
            'tryit',
            'code',
            c.parameter2 + '.py'
        )
        execfile(python)
        c.python = open(python).read()
        c.condition.python = True
        c.php = open(
            os.path.join(
                config['suit.templates'],
                'tryit',
                'code',
                c.parameter2 + '.php'
            )
        ).read()
        c.condition.php = True
    except (IOError, TypeError):
        pass
    if c.executeconfig['entities']:
        c.executed = escape(c.executed, True)
    if c.executeconfig['linebreak']:
        c.executed = c.executed.replace('\n', '<br />\n')
    c.executed = suit.execute(rules, c.executed, c.executeconfig)
    return ''