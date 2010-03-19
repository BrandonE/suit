import suit
from pylons import request, response, tmpl_context as c
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
    rules = templating.RULES
    c.rule = 'Templating'
elif c.parameter1 == 'suitlons':
    from rulebox import suitlons
    rules = suitlons.RULES
    c.rule = 'SUITlons'
elif c.parameter1 == 'bbcode':
    from rulebox import bbcode
    rules = bbcode.RULES
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
        'logic',
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
            'logic',
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