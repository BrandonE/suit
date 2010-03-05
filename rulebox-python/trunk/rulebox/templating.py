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
import os
import re
import cgi
try:
    import simplejson as json
except ImportError:
    import json

import suit

__all__ = [
    'assign', 'assignvariable', 'attribute', 'bracket', 'code', 'comments',
    'condition', 'entities', 'escape', 'evaluation', 'execute', 'decode',
    'listing', 'loop', 'replace', 'returning', 'returningfunction',
    'skip', 'templates', 'trim', 'trimexecute', 'trying', 'variables'
]

def assign(params):
    """Assign variable in template"""
    #If a variable is provided
    if params['var']['var']:
        if params['var']['json']:
            params['tree']['case'] = json.loads(params['tree']['case'])
        assignvariable(
            params['var']['var'],
            params['var']['delimiter'],
            params['tree']['case'],
            params['var']['owner']
        )
    params['tree']['case'] = ''
    return params

def assignvariable(string, split, assignment, var):
    """Assign a variable based on split"""
    split = string.split(split)
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
    var = params['var']
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
        split = case.split(quote)
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

def decode(params):
    """Decode a JSON String"""
    params['var'] = params['var'].copy()
    for value in params['var']['decode']:
        params['var'][value] = json.loads(params['var'][value])
    return params

def entities(params):
    """Convert HTML characters to their respective entities"""
    if not params['var']['json'] and params['var']['entities']:
        params['tree']['case'] = cgi.escape(str(params['tree']['case']))
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

def functions(params):
    """Perform a function call"""
    params['tree']['case'] = ''
    if 'string' in params['var']:
        params['tree']['case'] = params['var']['string']
    if params['var']['function'] and params['var']['owner']:
        kwargs = params['var'].copy()
        del kwargs['function']
        del kwargs['owner']
        params['tree']['case'] = getattr(
            params['var']['owner'],
            params['var']['function']
        )(**kwargs)
    return params

def listing(name, var):
    """Check if the variable is whitelisted or blacklisted"""
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
        return False
    return True

def loop(params):
    """Loop a string with different variables"""
    iterations = []
    tree = {
        'case': '',
        'contents': params['tree']['contents'],
        'parallel': []
    }
    for key, value in enumerate(params['var']['in']):
        old = {}
        if 'key' in params['var']:
            try:
                if params['var']['key'] in params['var']['owner']:
                    old['dictkey'] = params['var']['owner'][
                        params['var']['key']
                    ]
                params['var']['owner'][params['var']['key']] = key
            except (AttributeError, TypeError):
                if hasattr(params['var']['owner'], params['var']['key']):
                    old['objkey'] = getattr(
                        params['var']['owner'],
                        params['var']['key']
                    )
                setattr(params['var']['owner'], params['var']['key'], key)
        if 'value' in params['var']:
            try:
                if params['var']['value'] in params['var']['owner']:
                    old['dictvalue'] = params['var']['owner'][
                        params['var']['value']
                    ]
                params['var']['owner'][params['var']['value']] = value
            except (AttributeError, TypeError):
                if hasattr(params['var']['owner'], params['var']['value']):
                    old['objvalue'] = getattr(
                        params['var']['owner'],
                        params['var']['value']
                    )
                setattr(params['var']['owner'], params['var']['value'], value)
        #Execute for this iteration
        iterations.append(
            suit.walk(params['rules'], tree, params['config'])['tree']['case']
        )
        if 'recurse' in params['var'] and params['var']['recurse']:
            if 'dictkey' in old:
                params['var']['owner'][params['var']['key']] = old['dictkey']
            if 'objkey' in old:
                setattr(
                    params['var']['owner'],
                    params['var']['key'],
                    old['objkey']
                )
            if 'dictvalue' in old:
                params['var']['owner'][
                    params['var']['value']
                ] = old['dictvalue']
            if 'objvalue' in old:
                setattr(
                    params['var']['owner'],
                    params['var']['value'],
                    old['objvalue']
                )
    #Implode the iterations
    params['tree']['case'] = params['var']['delimiter'].join(iterations)
    params['walk'] = False
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

def transform(params):
    """Send case as argument for functions"""
    params['var']['string'] = params['tree']['case']
    return params

def trim(params):
    """Prepare the trim rules"""
    params['tree']['case'] = suit.execute(
        {
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
        },
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
        #If a variable is provided
        if params['var']['var']:
            assignvariable(
                params['var']['var'],
                params['var']['delimiter'],
                inst,
                params['var']['owner']
            )
        params['tree']['case'] = ''
    return params

def variables(params):
    """Parse variables"""
    for key, value in enumerate(
        params['tree']['case'].split(params['var']['delimiter'])
    ):
        if key == 0:
            params['tree']['case'] = getattr(params['var']['owner'], value)
        else:
            try:
                params['tree']['case'] = params['tree']['case'][value]
            except (AttributeError, TypeError):
                try:
                    params['tree']['case'] = params['tree']['case'][
                        int(value)
                    ]
                except (AttributeError, TypeError, ValueError):
                    params['tree']['case'] = getattr(
                        params['tree']['case'], value
                    )
    if params['var']['json']:
        params['tree']['case'] = json.dumps(params['tree']['case'])
    return params

rules = {
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
            decode,
            assign
        ],
        'var':
        {
            'equal': '=',
            'list': ('json', 'var'),
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('json',),
                'delimiter': '.',
                'json': 'false',
                'owner': suit.var,
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
    '[call':
    {
        'close': '/]',
        'postwalk': [
            attribute,
            functions
        ],
        'skip': True,
        'var':
        {
            'equal': '=',
            'onesided': True,
            'quote': ('"', '\''),
            'var':
            {
                'function': '',
                'owner': None
            }
        }
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
        'postwalk': [entities],
        'var':
        {
            'entities': True,
            'json': False
        }
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
            decode,
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
            decode,
            loop
        ],
        'var':
        {
            'blacklist': True,
            'equal': '=',
            'list': ('decode', 'owner'),
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('in', 'recurse'),
                'delimiter': '',
                'in': '[]',
                'owner': suit.var,
                'recurse': 'false'
            }
        }
    },
    '[loop':
    {
        'close': ']',
        'create': '[loop]',
        'skip': True
    },
    '[return':
    {
        'close': '/]',
        'postwalk':
        [
            attribute,
            decode,
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
        'skip': True,
        'skipescape': True
    },
    '[template]':
    {
        'close': '[/template]',
        'postwalk': [templates],
        'var': {}
    },
    '[transform]':
    {
        'close': '[/transform]',
        'postwalk': [
            attribute,
            transform,
            functions
        ],
        'var':
        {
            'equal': '=',
            'quote': ('"', '\''),
            'var':
            {
                'function': '',
                'owner': None,
                'string': ''
            }
        }
    },
    '[transform':
    {
        'close': ']',
        'create': '[transform]',
        'skip': True
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
                'owner': suit.var,
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
            decode,
            variables,
            entities
        ],
        'var':
        {
            'equal': '=',
            'list': ('entities', 'json'),
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('entities', 'json'),
                'delimiter': '.',
                'entities': 'true',
                'json': 'false',
                'owner': suit.var
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

evalrules = {
    '[eval]':
    {
        'close': '[/eval]',
        'postwalk': [evaluation]
    }
}