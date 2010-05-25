from rulebox.templating import var
from suitframework.lib import helperstry

def currenttime():
    import time
    return time.strftime('%m/%d/%Y %H:%M:%S', time.localtime())

def pygments(lexer, string):
    from pygments import highlight
    from pygments.lexers import get_lexer_by_name
    from pygments.formatters import HtmlFormatter
    return highlight(string, get_lexer_by_name(lexer), HtmlFormatter())

helperstry.currenttime = currenttime
var.username = 'Brandon'
var.loggedin = True
var.members = [
    {
        'name': 'Brandon',
        'admin': True,
        'banned': False
    },
    {
        'name': 'Chris',
        'admin': False,
        'banned': True
    }
]
var.owner = 'Brandon'
var.ownerfull = 'Brandon Evans'