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
    #If this node is one sided, modify this node
    if 'onesided' in params['var'] and params['var']['onesided']:
        node = {
            'var': params['open']['node']['var']['var'].copy()
        }
    #Else, modify the node this is creating
    else:
        node = params['nodes'][params['open']['node']['attribute']].copy()
        node['var'] = node['var'].copy()
    result = attributedefine(params, node)
    params['case'] = ''.join((
            params['open']['open'],
            params['case'],
            params['open']['node']['close']
        ))
    params['taken'] = False
    if not result['ignored']:
        if 'onesided' in params['var'] and params['var']['onesided']:
            params['var'] = result['node']['var']
            params['taken'] = True
        else:
            #Add the new node to the stack
            stack = suit.stack(
                result['node'],
                params['case'],
                params['open']['position']
            )
            params['openingstack'].extend(stack['openingstack'])
            params['skipstack'].extend(stack['skipstack'])
    else:
        #Prevent all ranges containing this case from parsing
        params['openingstack'] = suit.ignore(params['openingstack'])
        params['preparse']['ignored'] = True
        if not 'onesided' in params['var'] or not params['var']['onesided']:
            #Prepare for the closing string
            node = {
                'close': params['nodes'][
                    params['open']['node'
                ]['attribute']]['close']
            }
            if 'skip' in params['nodes'][params['open']['node']['attribute']]:
                node['skip'] = params['nodes'][
                    params['open']['node']['attribute']
                ]['skip']
            stack = suit.stack(
                node,
                params['open']['node']['attribute'],
                params['open']['position']
            )
            params['openingstack'].extend(stack['openingstack'])
            params['skipstack'].extend(stack['skipstack'])
        else:
            params['function'] = False
    return params

def attributedefine(params, node):
    """Define the variables"""
    ignored = False
    quote = ''
    smallest = False
    for value in params['var']['quote']:
        position = suit.strpos(
            params['case'],
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
            params['case'],
            params['config']['escape']
        )
        del split[-1]
        for key, value in enumerate(split):
            #If this is the first iteration of the pair
            if key % 2 == 0:
                name = value.strip()
                #If the syntax is valid
                if (name[
                    len(name) - len(params['var']['equal'])
                ] == params['var']['equal']):
                    name = name[
                        0:len(name) - len(params['var']['equal'])
                    ]
                    #If the variable is whitelisted or blacklisted, do not
                    #prepare to define the variable
                    if (not listing(name, params['var'])):
                        name = ''
                else:
                    name = ''
            elif name:
                config = {
                    'escape': params['config']['escape'],
                    'preparse': True
                }
                #Define the variable
                result = suit.parse(params['nodes'], value, config)
                if not result['ignored']:
                    node['var'][name] = result['return']
                else:
                    ignored = True
                    break
    return {
        'ignored': ignored,
        'node': node
    }

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
    """Strip node tags or hide a string"""
    params['offset'] = -len(params['open']['open'])
    #Hide the case if necessary
    if (
        (
            params['var']['condition'] and
            params['var']['else']
        ) or
        (
            not params['var']['condition'] and not params['var']['else']
        )
    ):
        params['case'] = ''
    return params

def conditionstack(params):
    """Do not skip if the string should not be hidden"""
    if params['openingstack']:
        pop = params['openingstack'].pop()
        if ('var' in pop['node'] and
        'condition' in pop['node']['var'] and
        'else' in pop['node']['var']):
            conditionjson = json.loads(pop['node']['var']['condition'])
            elsejson = json.loads(pop['node']['var']['else'])
            try:
                boolean = False
                for value in conditionjson:
                    if value:
                        boolean = True
                        break
                conditionjson = boolean
            except TypeError:
                pass
            pop['node']['var']['condition'] = jsonencode(conditionjson)
            #If the case should not be hidden, do not skip over everything
            #between this opening string and its closing string
            if (
                (
                    (
                        conditionjson and
                        not elsejson
                    ) or
                    (
                        not conditionjson and
                        elsejson
                    )
                ) and
                'skip' in pop['node'] and
                pop['node']['skip']
            ):
                pop['node']['skip'] = False
                params['skipstack'].pop()
        #Else, if the node was ignored, do not skip over everything between
        #this opening string and its closing string
        elif (pop['node']['close'] == params['nodes'][
            params['open']['node']['attribute']
        ]['close'] and
        'skip' in pop['node'] and
        pop['node']['skip']):
            pop['node']['skip'] = False
            params['skipstack'].pop()
        params['openingstack'].append(pop)
    return params

def escape(params):
    """Escape the case"""
    return params

def evaluation(params):
    """Evaluate a Python statement"""
    params['case'] = eval(params['case'])
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
    result = {
        'ignore': params['nodes'][
            params['var']['node']]['var']['ignore'
        ].copy(),
        'same': {}
    }
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
        if hasattr(value, 'items'):
            for value2 in value.items():
                var[
                    params['var']['node']
                ]['var']['var'][value2[0]] = value2[1]
        else:
            for value2 in dir(value):
                if (not value2.startswith('_') and
                not callable(getattr(value, value2))):
                    var[params['var']['node']]['var']['var'][value2] = getattr(
                        value,
                        value2
                    )
        result = looppreparse(
            var[params['var']['node']]['var']['var'],
            len(iterationvars),
            result
        )
        iterationvars.append(var)
    iterations = []
    if iterationvars:
        nodes = {
            params['var']['node']: iterationvars[0][
                params['var']['node']
            ].copy()
        }
        nodes[params['var']['node']]['var'] = nodes[
            params['var']['node']
        ]['var'].copy()
        nodes[params['var']['node']]['var']['ignore'] = result['ignore']
        config = {
            'escape': params['config']['escape'],
            'insensitive': params['config']['insensitive'],
            'malformed': params['config']['malformed'],
            'preparse': True
        }
        #Parse everything possible without iteration
        result = suit.parse(
            dict(
                params['nodes'].items() +
                nodes.items()
            ),
            params['case'],
            config
        )
        for value in iterationvars:
            config = {
                'escape': params['config']['escape'],
                'insensitive': params['config']['insensitive'],
                'malformed': params['config']['malformed'],
                'preparse': True,
                'taken': result['taken']
            }
            #Parse for this iteration
            result2 = suit.parse(
                dict(
                    params['nodes'].items() +
                    value.items()
                ),
                result['return'],
                config
            )
            if not result2['ignored']:
                iterations.append(result2['return'])
            else:
                params['case'] = ''.join((
                    params['open']['open'],
                    params['case'],
                    params['open']['node']['close']
                ))
                params['taken'] = False
                #Prevent all ranges containing this case from parsing
                params['openingstack'] = suit.ignore(params['openingstack'])
                params['preparse']['ignored'] = True
                return params
    #Implode the iterations
    params['case'] = params['var']['delimiter'].join(iterations)
    return params

def looppreparse(iterationvars, iteration, returnvalue):
    """Populate the vars for preparsing"""
    for value in iterationvars.items():
        #If this node is not already being ignored
        if not value[0] in returnvalue['ignore']:
            different = False
            clone = {}
            for value2 in returnvalue['same'].items():
                #If this node has the same opening string as the one we are
                #checking but is different overall, remove the checking string
                #and note the difference
                if value[0] == value2[0] and value[1] != value2[1]:
                    different = True
                else:
                    clone[value2[0]] = value2[1]
            returnvalue['same'] = clone
            #If this is a new value, and this is not the first iteration,
            #remove the checking string and note the difference
            if not value[0] in returnvalue['same'] and iteration > 0:
                different = True
            #If there is an instance of a node that has the same opening string
            #but is different overall, same it
            if different:
                returnvalue['ignore'][value[0]] = value[1]
            #Else, prepare to preparse it
            elif not value[0] in returnvalue['same']:
                returnvalue['same'][value[0]] = value[1]
    return returnvalue

def loopstack(params):
    """Do not skip if specified"""
    if params['openingstack']:
        pop = params['openingstack'].pop()
        #If specified, do not skip over everything between
        #this opening string and its closing string
        if ('var' in pop['node'] and
        'skip' in pop['node']['var'] and
        not json.loads(pop['node']['var']['skip']) and
        'skip' in params['open']['node'] and
        params['open']['node']['skip']):
            params['skipstack'].pop()
        params['openingstack'].append(pop)
    return params

def loopvariables(params):
    """Parse variables in a loop"""
    #Split up the file, paying attention to escape strings
    split = suit.explodeunescape(
        params['var']['delimiter'],
        params['case'],
        params['config']['escape']
    )
    #If the case should not be ignored
    if not split[0] in params['var']['ignore']:
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
    else:
        params['case'] = ''.join((
            params['open']['open'],
            params['case'],
            params['open']['node']['close']
        ))
        params['taken'] = False
        #Prevent all ranges containing this case from parsing
        params['openingstack'] = suit.ignore(params['openingstack'])
        params['preparse']['ignored'] = True
    return params

def parse(params):
    """Parse the case"""
    config = {
        'escape': params['config']['escape'],
        'insensitive': params['config']['insensitive'],
        'malformed': params['config']['malformed']
    }
    params['case'] = suit.parse(
        params['nodes'],
        params['case'],
        config
    )
    return params

def replace(params):
    """Replace in the case"""
    params['case'] = params['case'].replace(
        params['var']['search'],
        params['var']['replace']
    )
    return params

def returning(params):
    """Return early from a parse call"""
    params['case'] = ''
    stack = params['openingstack'][:]
    stack.reverse()
    skipstack = []
    for key, value in enumerate(stack):
        #If the stack count has not been modified or it specifies this many
        #stacks
        if (not params['var']['openingstack'] or
        int(params['var']['openingstack']) > key):
            if not 'function' in value['node']:
                params['openingstack'][
                    len(stack) - 1 - key
                ]['node']['function'] = []
            #Make all of the nodes remove all content in the case that takes
            #place after this return.
            params['openingstack'][
                len(stack) - 1 - key
            ]['node']['function'].insert(
                0,
                returningfirst
            )
            #Make the last node to be closed remove everything after this
            #return
            if key == len(stack) - 1:
                params['openingstack'][0]['node']['function'].append(
                    returninglast
                )
            skipstack.append(value['node']['close'])
        else:
            break
    skipstack.reverse()
    params['skipstack'].extend(skipstack)
    #If the stack is empty, and the stack count has not been modified or it
    #specifies at least one stack, remove everything after this return.
    if (
        not params['openingstack'] and 
        (
            not params['var']['openingstack'] or
            int(params['var']['openingstack']) > 0
        )
    ):
        params['last'] = params['open']['position']
        params = returninglast(params)
    return params

def returningfirst(params):
    """Function placed in front of all the functions in a stack"""
    params['case'] = params['case'][
        0:
        (
            params['last'] - params['open']['position'] - len(
                params['open']['open']
            )
        )
    ]
    return params

def returninglast(params):
    """Function appended to the last node to be closed in the stack"""
    params['return'] = params['return'][0:params['last']]
    params['parse'] = False
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
    """Trim all unnecessary whitespace"""
    nodes = {
        '<pre':
        {
            'close': '</pre>',
            'function': [trimbefore],
            'skip': True
        },
        '<textarea':
        {
            'close': '</textarea>',
            'function': [trimbefore],
            'skip': True
        }
    }
    suit.last = 0
    params['case'] = suit.parse(nodes, params['case'])
    copy = params['case'][suit.last:len(params['case'])]
    if not suit.last:
        copy = copy.lstrip()
    replaced = re.sub('(?m)[\s]+$', '', copy)
    params['case'] = ''.join((
        params['case'][0:suit.last],
        replaced
    ))
    return params

def trimbefore(params):
    """Trim the whitespace before this instance"""
    original = params['return'][params['last']:params['open']['position']]
    copy = original
    if not params['last']:
        copy = copy.lstrip()
    replaced = re.sub('(?m)[\s]+$', '', original.lstrip())
    params['return'] = ''.join((
        params['return'][0:params['last']],
        replaced,
        params['return'][params['open']['position']:len(params['return'])]
    ))
    params['open']['position'] += len(replaced) - len(original)
    params['position'] += len(replaced) - len(original)
    params['case'] = ''.join((
        params['open']['open'],
        params['case'],
        params['open']['node']['close']
    ))
    params['taken'] = False
    suit.last = params['open']['position'] + len(
        params['case']
    )
    return params

def trying(params):
    """Try and use exceptions on parsing"""
    if params['var']['var']:
        setattr(suit, params['var']['var'], '')
    try:
        config = {
            'escape': params['config']['escape'],
            'insensitive': params['config']['insensitive'],
            'malformed': params['config']['malformed'],
            'preparse': True
        }
        result = suit.parse(
            params['nodes'],
            params['case'],
            config
        )
        if not result['ignored']:
            params['case'] = result['return']
        #Else, ignore this case
        else:
            params['case'] = ''.join((
                params['open']['open'],
                params['case'],
                params['open']['node']['close']
            ))
            params['taken'] = False
            #Prevent all ranges containing this case from parsing
            params['openingstack'] = suit.ignore(params['openingstack'])
            params['preparse']['ignored'] = True
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
        'close': ']'
    },
    '[assign]':
    {
        'close': '[/assign]',
        'stringfunctions': [assign],
        'var':
        {
            'delimiter': '=>',
            'var': ''
        }
    },
    '[assign':
    {
        'close': ']',
        'stringfunctions': [attribute],
        'create': '[assign]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'list': ('var',),
            'quote': ('"', '\'')
        }
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
        'stringfunctions': [escape],
        'skip': True,
        'skipescape': True,
        'var': '\r\n\t '
    },
    '[if]':
    {
        'close': '[/if]',
        'stringfunctions': [
            jsondecode,
            condition
        ],
        'skip': True,
        'transform': False,
        'var':
        {
            'condition': 'false',
            'decode': ('condition', 'else'),
            'else': 'false'
        }
    },
    '[if':
    {
        'close': ']',
        'stringfunctions':
        [
            attribute,
            conditionstack
        ],
        'create': '[if]',
        'skip': True,
        'var':
        {
            'blacklist': True,
            'equal': '=',
            'list': ('decode',),
            'quote': ('"', '\'')
        }
    },
    '[loop]':
    {
        'close': '[/loop]',
        'stringfunctions': [
            jsondecode,
            loop
        ],
        'skip': True,
        'var':
        {
            'decode': ('skip', 'vars'),
            'delimiter': '',
            'node': '[loopvar]',
            'skip': 'true',
            'vars': '[]'
        }
    },
    '[loop':
    {
        'close': ']',
        'stringfunctions': [
            attribute,
            loopstack
        ],
        'create': '[loop]',
        'skip': True,
        'var':
        {
            'blacklist': True,
            'equal': '=',
            'list': ('decode', 'node'),
            'quote': ('"', '\'')
        }
    },
    '[loopvar]':
    {
        'close': '[/loopvar]',
        'stringfunctions': [
            jsondecode,
            loopvariables
        ],
        'var':
        {
            'decode': ('json', 'serialize'),
            'delimiter': '=>',
            'ignore': {},
            'json': 'false',
            'serialize': 'false',
            'var': {}
        }
    },
    '[loopvar':
    {
        'close': ']',
        'stringfunctions': [attribute],
        'create': '[loopvar]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'list': ('json', 'serialize'),
            'quote': ('"', '\'')
        }
    },
    '[parse]':
    {
        'close': '[/parse]',
        'stringfunctions': [parse],
        'var': {}
    },
    '[parse':
    {
        'close': ']',
        'stringfunctions': [attribute],
        'create': '[parse]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'quote': ('"', '\'')
        }
    },
    '[replace]':
    {
        'close': '[/replace]',
        'stringfunctions': [replace],
        'var':
        {
            'replace': '',
            'search': ''
        }
    },
    '[replace':
    {
        'close': ']',
        'stringfunctions': [attribute],
        'create': '[replace]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'quote': ('"', '\'')
        }
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
            'list': ('stack',),
            'onesided': True,
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('stack',),
                'stack': 'false'
            }
        }
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
        'stringfunctions': [trying],
        'skip': True,
        'var':
        {
            'delimiter': '=>',
            'var': ''
        }
    },
    '[try':
    {
        'close': ']',
        'stringfunctions': [attribute],
        'create': '[try]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'list': ('var',),
            'quote': ('"', '\'')
        }
    },
    '[var]':
    {
        'close': '[/var]',
        'stringfunctions': [
            jsondecode,
            variables
        ],
        'var':
        {
            'decode': ('json', 'serialize'),
            'delimiter': '=>',
            'json': 'false',
            'serialize': 'false'
        }
    },
    '[var':
    {
        'close': ']',
        'stringfunctions': [attribute],
        'create': '[var]',
        'skip': True,
        'var':
        {
            'equal': '=',
            'list': ('json', 'serialize'),
            'quote': ('"', '\'')
        }
    }
}

EVALNODES = {
    '[eval]':
    {
        'close': '[/eval]',
        'stringfunctions': [evaluation]
    }
}