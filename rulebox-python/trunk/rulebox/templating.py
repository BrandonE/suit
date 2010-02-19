"""
**@This file is part of Rulebox.
**@Rulebox is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@Rulebox is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with Rulebox.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
"""
import cgi
import os
import pickle
import re
import suit
try:
    import simplejson as json
except ImportError:
    import json
import sys

__version__ = '0.0.0'

def assign(params):
    """Assign variable in template"""
    #If a variable is provided and it not is whitelisted or blacklisted
    if params['var']['var'] and listing(params['var']['var'], params['var']):
        #Split up the file, paying attention to escape strings
        split = suit.explodeunescape(
            params['var']['delimiter'],
            params['var']['var'],
            params['config']['escape']
        )
        assignvariable(split, params['tree']['case'], suit)
    params['tree']['case'] = ''
    return params

def assignvariable(split, assignment, var):
    """Assign a variable based on split"""
    for key, value in enumerate(split):
        if key < len(split) - 1:
            try:
                var = var[value]
            except (AttributeError, TypeError):
                try:
                    var = var[int(value)]
                except (AttributeError, TypeError, ValueError):
                    var = getattr(var, value)
    try:
        var[split[len(split) - 1]] = assignment
    except (AttributeError, TypeError):
        setattr(var, split[len(split) - 1], assignment)

def attribute(params):
    """Create rule out of attributes"""
    var = params['var'].copy()
    params['var'] = params['var']['var'].copy()
    if 'onesided' in var and var['onesided']:
        case = params['tree']['case']
    elif 'create' in params:
        case = params['create']
    else:
        return params
    quote = ''
    smallest = False
    for value in var['quote']:
        haystack = case
        needle = value
        if params['config']['insensitive']:
            haystack = haystack.lower()
            needle = needle.lower()
        position = haystack.find(needle)
        if position != -1 and (smallest == False or position < smallest):
            quote = value
            smallest = position
    if quote:
        #Define the variables
        split = suit.explodeunescape(
            quote,
            case,
            params['config']['escape']
        )
        del split[-1]
        for key, value in enumerate(split):
            #If this is the first iteration of the pair
            if key % 2 == 0:
                name = value.strip()
                syntax = (name[len(name) - len(var['equal'])] == var['equal'])
                name = name[0:len(name) - len(var['equal'])]
                #If the syntax is not valid or variable is whitelisted or
                #blacklisted, do not prepare to define the variable
                if not syntax or not listing(name, var):
                    name = ''
            elif name:
                #Define the variable
                params['var'][name] = suit.execute(
                    params['rules'],
                    value,
                    params['config']
                )
    return params

def bracket(params):
    """Handle brackets unrelated to the rules"""
    params['tree']['case'] = ''.join((
        params['tree']['rule'],
        params['tree']['case'],
        params['rules'][params['tree']['rule']]['close']
    ))
    return params

def code(params):
    """Execute a code file"""
    #If the code file is not whitelisted or blacklisted
    if listing(params['tree']['case'], params['var']):
        params['tree']['case'] = os.path.normpath(params['tree']['case'])
        sys.path.append(os.path.dirname(params['tree']['case']))
        sys.path.reverse()
        __import__(os.path.basename(params['tree']['case']).split('.', 2)[0])
    params['tree']['case'] = ''
    return params

def comments(params):
    """Hide a string"""
    params['tree']['case'] = ''
    return params

def condition(params):
    """Hide the case if necessary"""
    #Hide the case if necessary
    if (
        (
            params['var']['condition'] and 
            params['var']['else']
        ) or
        (
            not params['var']['condition'] and
            not params['var']['else']
        )
    ):
        params['walk'] = False
    return params

def entities(params):
    """Convert HTML characters to their respective entities"""
    params['tree']['case'] = cgi.escape(params['tree']['case'])
    return params

def escape(params):
    """Escape the case"""
    params['tree']['case'] = suit.escape(
        params['var']['strings'],
        params['tree']['case'],
        params['config']['escape'],
        params['config']['insensitive']
    )
    return params

def evaluation(params):
    """Evaluate a Python statement"""
    params['tree']['case'] = eval(params['tree']['case'])
    return params

def execute(params):
    """Execute the case"""
    params['tree']['case'] = suit.execute(
        params['rules'],
        params['tree']['case'],
        params['config']
    )
    return params

def jsondecode(params):
    """Decode a JSON String"""
    params['var'] = params['var'].copy()
    for value in params['var']['decode']:
        params['var'][value] = json.loads(params['var'][value])
    return params

def listing(name, var):
    """Check if the variable is whitelisted or blacklisted"""
    returnvalue = True
    #If the variable is whitelisted or blacklisted
    if (
        'list' in var and
        (
            (
                (
                    not 'blacklist' in var or
                    not var['blacklist']
                ) and
                not name in var['list']
            ) or
            (
                'blacklist' in var and
                var['blacklist'] and
                name in var['list']
            )
        )
    ):
        returnvalue = False
    return returnvalue

def loop(params):
    """Loop a string with different variables"""
    iterationvars = []
    for value in params['var']['vars']:
        var = {
            params['var']['rule']: params['rules'][
                params['var']['rule']
            ].copy()
        }
        var[params['var']['rule']]['var'] = var[
            params['var']['rule']
        ]['var'].copy()
        var[params['var']['rule']]['var']['var'] = var[
            params['var']['rule']
        ]['var']['var'].copy()
        var[params['var']['rule']]['var']['var']['var'] = var[
            params['var']['rule']
        ]['var']['var']['var'].copy()
        if hasattr(value, 'items'):
            for key, value2 in value.items():
                var[
                    params['var']['rule']
                ]['var']['var']['var'][key] = value2
        else:
            for value2 in dir(value):
                if (not value2.startswith('_') and
                not callable(getattr(value, value2))):
                    var[
                        params['var']['rule']
                    ]['var']['var']['var'][value2] = getattr(
                        value,
                        value2
                    )
        iterationvars.append(var)
    iterations = []
    tree = {
        'case': '',
        'contents': params['tree']['contents'],
        'parallel': []
    }
    for value in iterationvars:
        #Parse for this iteration
        result = suit.walk(
            dict(params['rules'].items() + value.items()),
            tree,
            params['config']
        )
        iterations.append(result['tree']['case'])
    #Implode the iterations
    params['tree']['case'] = params['var']['delimiter'].join(iterations)
    params['walk'] = False
    return params

def loopvariables(params):
    """Parse variables in a loop"""
    #Split up the file, paying attention to escape strings
    split = suit.explodeunescape(
        params['var']['delimiter'],
        params['tree']['case'],
        params['config']['escape']
    )
    params['tree']['case'] = params['var']['var']
    for value in split:
        try:
            params['tree']['case'] = params['tree']['case'][value]
        except (AttributeError, TypeError):
            try:
                params['tree']['case'] = params['tree']['case'][int(value)]
            except (AttributeError, TypeError, ValueError):
                params['tree']['case'] = getattr(params['tree']['case'], value)
    if params['var']['json']:
        params['tree']['case'] = json.dumps(params['tree']['case'])
    if params['var']['serialize']:
        params['tree']['case'] = pickle.dumps(params['tree']['case'])
    return params

def replace(params):
    """Replace in the case"""
    params['tree']['case'] = params['tree']['case'].replace(
        params['var']['search'],
        params['var']['replace']
    )
    return params

def returning(params):
    """Prepare to return from this point on"""
    if params['var']['layers']:
        params['returnvar'] = {
            'returnfunctions': [returningfunction],
            'layers': params['var']['layers']
        }
        params['returnfunctions'] = params['returnvar']['returnfunctions']
    params['tree']['case'] = ''
    return params

def returningfunction(params):
    """Return from this point on"""
    if not isinstance(params['returnedvar']['layers'], bool):
        params['returnedvar']['layers'] -= 1
    if params['returnedvar']['layers']:
        params['returnvar'] = params['returnedvar']
        params['returnfunctions'] = params['returnedvar']['returnfunctions']
    params['walk'] = False
    return params

def skip(params):
    """Skip over the case"""
    return params

def templates(params):
    """Grab a template from a file"""
    #If the template is not whitelisted or blacklisted
    if listing(params['tree']['case'], params['var']):
        params['tree']['case'] = open(
            os.path.normpath(params['tree']['case'])
        ).read()
    else:
        params['tree']['case'] = ''
    return params

def trim(params):
    """Prepare the trim rules"""
    rules = {
        '':
        {
            'prewalk': [trimexecute]
        },
        '<pre':
        {
            'close': '</pre>'
        },
        '<textarea':
        {
            'close': '</textarea>'
        }
    }
    params['tree']['case'] = suit.execute(
        rules,
        params['tree']['case'],
        params['config']
    )
    params['tree']['case'] = params['tree']['case'].lstrip()
    return params

def trimexecute(params):
    """Trim unnecessary whitespace"""
    for value in enumerate(params['tree']['contents']):
        if isinstance(params['tree']['contents'][value[0]], dict):
            params['tree']['case'] += ''.join((
                params['tree']['contents'][value[0]]['rule'],
                params['tree']['contents'][value[0]]['contents'][0],
                params['rules'][
                    params['tree']['contents'][value[0]]['rule']
                ]['close']
            ))
        else:
            params['tree']['case'] += re.sub(
                '(?m)[\s]+$',
                '',
                params['tree']['contents'][value[0]]
            )
    params['walk'] = False
    return params

def trying(params):
    """Try and use exceptions on parsing"""
    if params['var']['var']:
        setattr(suit, params['var']['var'], '')
    try:
        params['tree']['case'] = suit.execute(
            params['rules'],
            params['tree']['case'],
            params['config']
        )
    except Exception, inst:
        #If a variable is provided and it not is whitelisted or blacklisted
        if params['var']['var'] and listing(
            params['var']['var'],
            params['var']
        ):
            #Split up the file, paying attention to escape strings
            split = suit.explodeunescape(
                params['var']['delimiter'],
                params['var']['var'],
                params['config']['escape']
            )
            assignvariable(split, inst, suit)
        params['tree']['case'] = ''
    return params

def unserialize(params):
    """Unserialize a variable"""
    params['var'] = params['var'].copy()
    for value in params['var']['decode']:
        params['var'][value] = pickle.loads(params['var'][value])
    return params

def variables(params):
    """Parse variables"""
    #If the variable is not whitelisted or blacklisted
    if listing(params['tree']['case'], params['var']):
        #Split up the file, paying attention to escape strings
        split = suit.explodeunescape(
            params['var']['delimiter'],
            params['tree']['case'],
            params['config']['escape']
        )
        params['tree']['case'] = suit
        for value in split:
            try:
                params['tree']['case'] = params['tree']['case'][value]
            except (AttributeError, TypeError):
                try:
                    params['tree']['case'] = params['tree']['case'][int(value)]
                except (AttributeError, TypeError, ValueError):
                    params['tree']['case'] = getattr(
                        params['tree']['case'], value
                    )
        if params['var']['json']:
            params['tree']['case'] = json.dumps(params['tree']['case'])
        if params['var']['serialize']:
            params['tree']['case'] = pickle.dumps(params['tree']['case'])
    else:
        params['tree']['case'] = ''
    return params

RULES = {
    '[':
    {
        'close': ']',
        'postwalk': [bracket]
    },
    '[assign]':
    {
        'close': '[/assign]',
        'postwalk': [
            attribute,
            assign
        ],
        'var':
        {
            'equal': '=',
            'list': ('var',),
            'quote': ('"', '\''),
            'var':
            {
                'delimiter': '.',
                'var': ''
            }
        }
    },
    '[assign':
    {
        'close': ']',
        'create': '[assign]',
        'skip': True
    },
    '[code]':
    {
        'close': '[/code]',
        'postwalk': [code],
        'var': {}
    },
    '[comment]':
    {
        'close': '[/comment]',
        'postwalk': [comments],
        'skip': True
    },
    '[entities]':
    {
        'close': '[/entities]',
        'postwalk': [entities]
    },
    '[escape]':
    {
        'close': '[/escape]',
        'postwalk': [
            attribute,
            jsondecode,
            escape
        ],
        'var':
        {
            'blacklist': True,
            'equal': '=',
            'list': ('decode',),
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('strings',),
                'strings': '[]',
            }
        }
    },
    '[escape':
    {
        'close': ']',
        'create': '[escape]',
        'skip': True
    },
    '[execute]':
    {
        'close': '[/execute]',
        'postwalk': [execute],
        'var': {}
    },
    '[if]':
    {
        'close': '[/if]',
        'prewalk': [
            attribute,
            jsondecode,
            condition
        ],
        'transform': False,
        'var':
        {
            'blacklist': True,
            'equal': '=',
            'list': ('decode',),
            'quote': ('"', '\''),
            'var':
            {
                'condition': 'false',
                'decode': ('condition', 'else'),
                'else': 'false'
            }
        }
    },
    '[if':
    {
        'close': ']',
        'create': '[if]',
        'skip': True
    },
    '[loop]':
    {
        'close': '[/loop]',
        'prewalk': [
            attribute,
            jsondecode,
            loop
        ],
        'var':
        {
            'equal': '=',
            'list': ('delimiter', 'vars'),
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('vars',),
                'delimiter': '',
                'rule': '[loopvar]',
                'vars': '[]'
            }
        }
    },
    '[loop':
    {
        'close': ']',
        'create': '[loop]',
        'skip': True
    },
    '[loopvar]':
    {
        'close': '[/loopvar]',
        'postwalk': [
            attribute,
            jsondecode,
            loopvariables
        ],
        'var':
        {
            'equal': '=',
            'list': ('json', 'serialize'),
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('json', 'serialize'),
                'delimiter': '.',
                'json': 'false',
                'serialize': 'false',
                'var': {}
            }
        }
    },
    '[loopvar':
    {
        'close': ']',
        'create': '[loopvar]',
        'skip': True
    },
    '[replace]':
    {
        'close': '[/replace]',
        'postwalk': [
            attribute,
            replace
        ],
        'var':
        {
            'equal': '=',
            'quote': ('"', '\''),
            'var':
            {
                'replace': '',
                'search': ''
            }
        }
    },
    '[replace':
    {
        'close': ']',
        'create': '[replace]',
        'skip': True
    },
    '[return':
    {
        'close': '/]',
        'postwalk':
        [
            attribute,
            jsondecode,
            returning
        ],
        'skip': True,
        'var':
        {
            'equal': '=',
            'list': ('layers',),
            'onesided': True,
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('layers',),
                'layers': 'true'
            }
        }
    },
    '[skip]':
    {
        'close': '[/skip]',
        'postwalk': [skip],
        'skip': True,
        'skipescape': True
    },
    '[template]':
    {
        'close': '[/template]',
        'postwalk': [templates],
        'var': {}
    },
    '[trim]':
    {
        'close': '[/trim]',
        'postwalk': [trim],
    },
    '[try]':
    {
        'close': '[/try]',
        'postwalk': [
            attribute,
            trying
        ],
        'skip': True,
        'var':
        {
            'equal': '=',
            'list': ('var',),
            'quote': ('"', '\''),
            'var':
            {
                'delimiter': '.',
                'var': ''
            }
        }
    },
    '[try':
    {
        'close': ']',
        'create': '[try]',
        'skip': True
    },
    '[var]':
    {
        'close': '[/var]',
        'postwalk': [
            attribute,
            jsondecode,
            variables
        ],
        'var':
        {
            'equal': '=',
            'list': ('json', 'serialize'),
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('json', 'serialize'),
                'delimiter': '.',
                'json': 'false',
                'serialize': 'false'
            }
        }
    },
    '[var':
    {
        'close': ']',
        'create': '[var]',
        'skip': True
    }
}

EVALRULES = {
    '[eval]':
    {
        'close': '[/eval]',
        'postwalk': [evaluation]
    }
}