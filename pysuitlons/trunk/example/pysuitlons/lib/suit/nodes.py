"""
**@This file is part of PySUIT.
**@PySUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@PySUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with PySUIT.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
"""
import os
import pickle
import re
import suit
import sys
try:
    import simplejson as json
except ImportError:
    import json

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
        assignvariable(split, params['case'], suit)
    params['case'] = ''
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
    """Create node out of attributes"""
    var = params['var'].copy()
    params['var'] = params['var']['var'].copy()
    if 'onesided' in var and var['onesided']:
        case = params['case']
    elif 'create' in params:
        case = params['create']
    else:
        return params
    quote = ''
    smallest = False
    for value in var['quote']:
        position = suit.strpos(
            case,
            value,
            0,
            params['config']['insensitive']
        )
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
                #If the syntax is valid
                if (name[len(name) - len(var['equal'])] == var['equal']):
                    name = name[0:len(name) - len(var['equal'])]
                    #If the variable is whitelisted or blacklisted, do not
                    #prepare to define the variable
                    if (not listing(name, var)):
                        name = ''
                else:
                    name = ''
            elif name:
                #Define the variable
                params['var'][name] = suit.execute(
                    params['nodes'],
                    value,
                    params['config']
                )
    return params

def bracket(params):
    """Handle brackets unrelated to the nodes"""
    params['case'] = ''.join((
        params['node'],
        params['case'],
        params['nodes'][params['node']]['close']
    ))
    return params

def code(params):
    """Execute a code file"""
    #If the code file is not whitelisted or blacklisted
    if listing(params['case'], params['var']):
        params['case'] = os.path.normpath(params['case'])
        sys.path.append(os.path.dirname(params['case']))
        sys.path.reverse()
        __import__(os.path.basename(params['case']).split('.', 2)[0])
    params['case'] = ''
    return params

def comments(params):
    """Hide a string"""
    params['case'] = ''
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
        params['tree'] = {
            'contents': [
                ''
            ]
        }
        params['case'] = ''
    return params

def escape(params):
    """Escape the case"""
    params['case'] = suit.escape(
        params['var']['strings'],
        params['case'],
        params['config']['escape'],
        params['config']['insensitive']
    )
    return params

def evaluation(params):
    """Evaluate a Python statement"""
    params['case'] = eval(params['case'])
    return params

def execute(params):
    """Execute the case"""
    params['case'] = suit.execute(
        params['nodes'],
        params['case'],
        params['config']
    )
    return params

def jsondecode(params):
    """Decode a JSON String"""
    params['var'] = params['var'].copy()
    for value in params['var']['decode']:
        params['var'][value] = json.loads(params['var'][value])
    return params

def jsonencode(obj, first = True):
    """Encode a JSON String"""
    try:
        json.dumps(obj)
    except TypeError:
        if hasattr(obj, 'items'):
            for value in obj.items():
                obj[value[0]] = jsonencode(value[1], False)
        else:
            try:
                for key, value in enumerate(obj):
                    obj[key] = jsonencode(value, False)
            except (TypeError, RuntimeError):
                new = {}
                for value in dir(obj):
                    if (not value.startswith('_') and
                    not callable(getattr(obj, value))):
                        new[value] = jsonencode(
                            getattr(
                                obj,
                                value
                            ),
                            False
                        )
                obj = new
    if first:
        obj = json.dumps(obj, separators = (',',':'))
    return obj

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
            params['var']['node']: params['nodes'][
                params['var']['node']
            ].copy()
        }
        var[params['var']['node']]['var'] = var[
            params['var']['node']
        ]['var'].copy()
        var[params['var']['node']]['var']['var'] = var[
            params['var']['node']
        ]['var']['var'].copy()
        var[params['var']['node']]['var']['var']['var'] = var[
            params['var']['node']
        ]['var']['var']['var'].copy()
        if hasattr(value, 'items'):
            for value2 in value.items():
                var[
                    params['var']['node']
                ]['var']['var']['var'][value2[0]] = value2[1]
        else:
            for value2 in dir(value):
                if (not value2.startswith('_') and
                not callable(getattr(value, value2))):
                    var[
                        params['var']['node']
                    ]['var']['var']['var'][value2] = getattr(
                        value,
                        value2
                    )
        iterationvars.append(var)
    iterations = []
    tree = {
        'contents': params['tree']['contents']
    }
    for value in iterationvars:
        #Parse for this iteration
        result = suit.walk(
            dict(params['nodes'].items() + value.items()),
            tree,
            params['config']
        )
        iterations.append(result['contents'])
    #Implode the iterations
    params['tree'] = {
        'contents': [
            params['var']['delimiter'].join(iterations)
        ]
    }
    return params

def loopvariables(params):
    """Parse variables in a loop"""
    #Split up the file, paying attention to escape strings
    split = suit.explodeunescape(
        params['var']['delimiter'],
        params['case'],
        params['config']['escape']
    )
    params['case'] = params['var']['var']
    for value in split:
        try:
            params['case'] = params['case'][value]
        except (AttributeError, TypeError):
            try:
                params['case'] = params['case'][int(value)]
            except (AttributeError, TypeError, ValueError):
                params['case'] = getattr(params['case'], value)
    if params['var']['json']:
        params['case'] = jsonencode(params['case'])
    if params['var']['serialize']:
        params['case'] = pickle.dumps(params['case'])
    return params

def replace(params):
    """Replace in the case"""
    params['case'] = params['case'].replace(
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
    params['case'] = ''
    return params

def returningfunction(params):
    """Return from this point on"""
    params['tree']['contents'] = params['tree']['contents'][
        0:params['key'] + 1
    ]
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
    if listing(params['case'], params['var']):
        params['case'] = open(
            os.path.normpath(params['case'])
        ).read()
    else:
        params['case'] = ''
    return params

def trim(params):
    """Prepare the trim rules"""
    nodes = {
        '':
        {
            'treefunctions': [trimexecute]
        },
        '<pre':
        {
            'close': '</pre>',
            'stringfunctions': [trimarea]
        },
        '<textarea':
        {
            'close': '</textarea>',
            'stringfunctions': [trimarea]
        }
    }
    params['case'] = suit.execute(
        nodes,
        params['case'],
        params['config']
    )
    params['case'] = params['case'].lstrip()
    return params

def trimarea(params):
    """Ignore the specified tags"""
    params['case'] = ''.join((
        params['node'],
        params['case'],
        params['nodes'][params['node']]['close']
    ))
    return params

def trimexecute(params):
    """Trim unnecessary whitespace"""
    for value in enumerate(params['tree']['contents']):
        if isinstance(params['tree']['contents'][value[0]], dict):
            result = suit.walkarray(
                params['nodes'],
                params['tree'],
                params['config'],
                params,
                value[0]
            )
            params = result['params']
            params['tree'] = result['tree']
        else:
            params['tree']['contents'][value[0]] = re.sub(
                '(?m)[\s]+$',
                '',
                params['tree']['contents'][value[0]]
            )
    return params

def trying(params):
    """Try and use exceptions on parsing"""
    if params['var']['var']:
        setattr(suit, params['var']['var'], '')
    try:
        params['case'] = suit.execute(
            params['nodes'],
            params['case'],
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
        params['case'] = ''
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
    if listing(params['case'], params['var']):
        #Split up the file, paying attention to escape strings
        split = suit.explodeunescape(
            params['var']['delimiter'],
            params['case'],
            params['config']['escape']
        )
        params['case'] = suit
        for value in split:
            try:
                params['case'] = params['case'][value]
            except (AttributeError, TypeError):
                try:
                    params['case'] = params['case'][int(value)]
                except (AttributeError, TypeError, ValueError):
                    params['case'] = getattr(params['case'], value)
        if params['var']['json']:
            params['case'] = jsonencode(params['case'])
        if params['var']['serialize']:
            params['case'] = pickle.dumps(params['case'])
    else:
        params['case'] = ''
    return params

NODES = {
    '[':
    {
        'close': ']',
        'stringfunctions': [bracket]
    },
    '[assign]':
    {
        'close': '[/assign]',
        'stringfunctions': [
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
                'delimiter': '=>',
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
        'stringfunctions': [code],
        'var': {}
    },
    '[comment]':
    {
        'close': '[/comment]',
        'stringfunctions': [comments],
        'skip': True
    },
    '[escape]':
    {
        'close': '[/escape]',
        'stringfunctions': [
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
        'stringfunctions': [execute],
        'var': {}
    },
    '[if]':
    {
        'close': '[/if]',
        'treefunctions': [
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
        'treefunctions': [
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
                'node': '[loopvar]',
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
        'stringfunctions': [
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
                'delimiter': '=>',
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
        'stringfunctions': [replace],
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
        'stringfunctions':
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
        'stringfunctions': [skip],
        'skip': True,
        'skipescape': True
    },
    '[template]':
    {
        'close': '[/template]',
        'stringfunctions': [templates],
        'var': {}
    },
    '[trim]':
    {
        'close': '[/trim]',
        'stringfunctions': [trim],
    },
    '[try]':
    {
        'close': '[/try]',
        'stringfunctions': [
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
                'delimiter': '=>',
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
        'stringfunctions': [
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
                'delimiter': '=>',
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

EVALNODES = {
    '[eval]':
    {
        'close': '[/eval]',
        'stringfunctions': [evaluation]
    }
}