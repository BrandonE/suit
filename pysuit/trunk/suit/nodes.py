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

def assign(params):
    """Assign variable in template"""
    #If a variable is provided and it not is whitelisted or blacklisted
    if params['var']['var'] and listing(params['var']['var'], params['var']):
        #Split up the file, paying attention to escape strings
        split = params['suit'].explodeunescape(
            params['var']['delimiter'],
            params['var']['var'],
            params['config']['escape']
        )
        assignvariable(split, params['case'], params['suit'].vars)
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
    if not result['ignore']:
        if 'onesided' in params['var'] and params['var']['onesided']:
            params['var'] = result['node']['var']
            params['taken'] = True
        else:
            #Add the new node to the stack
            stack = params['suit'].stack(
                result['node'],
                params['case'],
                params['open']['position']
            )
            params['stack'].extend(stack['stack'])
            params['skipnode'].extend(stack['skipnode'])
            params['preparse']['nodes'][params['case']] = result['node']
    else:
        #Reserve the space
        params['preparse']['ignored'].append([
            params['open']['position'],
            params['position'] + len(params['open']['node']['close'])
        ])
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
            stack = params['suit'].stack(
                node,
                params['open']['node']['attribute'],
                params['open']['position']
            )
            params['stack'].extend(stack['stack'])
            params['skipnode'].extend(stack['skipnode'])
        else:
            params['function'] = False
    return params

def attributedefine(params, node):
    """Define the variables"""
    #Define the variables
    split = params['suit'].explodeunescape(
        params['var']['quote'],
        params['case'],
        params['config']['escape']
    )
    del split[-1]
    ignore = False
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
                #If the variable is whitelisted or blacklisted, do not prepare
                #to define the variable
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
            result = params['suit'].parse(params['nodes'], value, config)
            if not result['ignored']:
                if result['return'].lower() == 'false':
                    result['return'] = ''
                node['var'][name] = result['return']
            else:
                ignore = True
                break
    return {
        'ignore': ignore,
        'node': node
    }

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
    if params['stack']:
        pop = params['stack'].pop()
        if ('var' in pop['node'] and
        'condition' in pop['node']['var'] and
        'else' in pop['node']['var']):
            pop['node']['var']['condition'] = str(
                pop['node']['var']['condition']
            )
            if (pop['node']['var']['condition'] == '0' or
            pop['node']['var']['condition'].lower() == 'none' or
            pop['node']['var']['condition'] == '[]' or
            pop['node']['var']['condition'] == '{}' or
            pop['node']['var']['condition'] == '()'):
                pop['node']['var']['condition'] = ''
            #If the case should not be hidden, do not skip over everything
            #between this opening string and its closing string
            if (
                (
                    (
                        pop['node']['var']['condition'] and
                        not pop['node']['var']['else']
                    ) or
                    (
                        not pop['node']['var']['condition'] and
                        pop['node']['var']['else']
                    )
                ) and
                'skip' in pop['node'] and
                pop['node']['skip']
            ):
                pop['node']['skip'] = False
                params['skipnode'].pop()
        params['stack'].append(pop)
    return params

def escape(params):
    """Escape the case"""
    return params

def evaluation(params):
    """Evaluate a Python statement"""
    params['case'] = eval(params['case'])
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
    result = {
        'ignore': {},
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
        try:
            for value2 in value.items():
                var[params['var']['node']]['var']['var'][value2[0]] = value2[1]
        except (AttributeError, TypeError):
            for value2 in dir(value):
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
            'preparse': True
        }
        if 'label' in params['var']:
            config['label'] = params['var']['label']
        #Parse everything possible without iteration
        result = params['suit'].parse(
            dict(
                params['nodes'].items() +
                nodes.items()
            ),
            params['case'],
            config
        )
        for key, value in enumerate(iterationvars):
            config = {
                'escape': params['config']['escape'],
                'taken': result['taken']
            }
            if 'label' in params['var']:
                config['label'] = ''.join((params['var']['label'], str(key)))
            #Parse for this iteration
            iterations.append(params['suit'].parse(
                dict(
                    params['nodes'].items() +
                    result['nodes'].items() +
                    value.items()
                ),
                result['return'],
                config
            ))
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
    if params['stack']:
        pop = params['stack'].pop()
        #If specified, do not skip over everything between
        #this opening string and its closing string
        if ('var' in pop['node'] and
        'skip' in pop['node']['var'] and
        not pop['node']['var']['skip'] and
        'skip' in params['open']['node'] and
        params['open']['node']['skip']):
            params['skipnode'].pop()
        params['stack'].append(pop)
    return params

def loopvariables(params):
    """Parse variables in a loop"""
    #Split up the file, paying attention to escape strings
    split = params['suit'].explodeunescape(
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
        if params['var']['serialize']:
            params['case'] = pickle.dumps(params['case'])
    else:
        params['preparse']['ignored'].append([
            params['open']['position'],
            params['position'] + len(params['open']['node']['close'])
        ])
        params['case'] = ''.join((
            params['open']['open'],
            params['case'],
            params['open']['node']['close']
        ))
        params['taken'] = False
    return params

def parse(params):
    """Parse the case"""
    params['case'] = params['suit'].parse(params['nodes'], params['case'])
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
    stack = params['stack'][:]
    stack.reverse()
    skipnode = []
    for key, value in enumerate(stack):
        #If the stack count has not been modified or it specifies this many
        #stacks
        if not params['var']['stack'] or int(params['var']['stack']) > key:
            if not 'function' in value['node']:
                params['stack'][len(stack) - 1 - key]['node']['function'] = []
            #Make all of the nodes remove all content in the case that takes
            #place after this return.
            params['stack'][len(stack) - 1 - key]['node']['function'].insert(
                0,
                returningfirst
            )
            #Make the last node to be closed remove everything after this
            #return
            if key == len(stack) - 1:
                params['stack'][0]['node']['function'].append(returninglast)
            skipnode.append(value['node']['close'])
        else:
            break
    skipnode.reverse()
    params['skipnode'].extend(skipnode)
    #If the stack is empty, and the stack count has not been modified or it
    #specifies at least one stack, remove everything after this return.
    if (
        not params['stack'] and 
        (
            not params['var']['stack'] or
            int(params['var']['stack']) > 0
        )
    ):
        params['last'] = params['open']['position']
        params = returninglast(params)
    return params

def returningfirst(params):
    """Function placed in front of all the functions in a stack"""
    params['case'] = params['case'][0:(params['last'] -
    params['open']['position'] - len(params['open']['open']))]
    return params

def returninglast(params):
    """Function appended to the last node to be closed in the stack"""
    params['return'] = params['return'][0:params['last']]
    params['break'] = True
    return params

def templates(params):
    """Include a template"""
    #Split up the file, paying attention to escape strings
    split = params['suit'].explodeunescape(
        params['var']['delimiter'],
        params['case'],
        params['config']['escape']
    )
    code = []
    for key, value in enumerate(split):
        #If this is the template file, get the file's contents
        if key == 0:
            template = open(
                os.path.normpath(os.path.join(
                    params['var']['files']['templates'],
                    ''.join((
                        value,
                        '.',
                        params['var']['filetypes']['templates']
                    ))
                ))
            ).read()
        #Else, prepare to include the file
        else:
            code.append(
                os.path.normpath(os.path.join(
                    params['var']['files']['code'],
                    ''.join((
                        value,
                        '.',
                        params['var']['filetypes']['code']
                    ))
                ))
            )
    if 'label' in params['var']:
        params['case'] = params['suit'].gettemplate(
            template,
            code,
            params['var']['label']
        )
    else:
        params['case'] = params['suit'].gettemplate(template, code)
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
    params['suit'].vars['last'] = 0
    params['case'] = params['suit'].parse(nodes, params['case'])
    copy = params['case'][params['suit'].vars['last']:len(params['case'])]
    if not params['suit'].vars['last']:
        copy = copy.lstrip()
    replaced = re.sub('(?m)[\s]+$', '', copy)
    params['case'] = ''.join((
        params['case'][0:params['suit'].vars['last']],
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
    params['suit'].vars['last'] = params['open']['position'] + len(
        params['case']
    )
    return params

def trying(params):
    """Try and use exceptions on parsing"""
    if params['var']['var']:
        params['suit'].vars[params['var']['var']] = ''
    try:
        config = {
            'escape': params['config']['escape'],
            'preparse': True
        }
        result = params['suit'].parse(
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
            #Reserve the space
            params['preparse']['ignored'].append([
                params['open']['position'],
                params['position'] + len(params['open']['node']['close'])
            ])
    except Exception, inst:
        #If a variable is provided and it not is whitelisted or blacklisted
        if params['var']['var'] and listing(
            params['var']['var'],
            params['var']
        ):
            #Split up the file, paying attention to escape strings
            split = params['suit'].explodeunescape(
                params['var']['delimiter'],
                params['var']['var'],
                params['config']['escape']
            )
            assignvariable(split, inst, params['suit'].vars)
        params['case'] = ''
    return params

def unserialize(params):
    """Unserialize a variable"""
    params['var'][params['var']['unserialize']] = pickle.loads(
        params['var'][params['var']['unserialize']]
    )
    return params

def variables(params):
    """Parse variables"""
    #Split up the file, paying attention to escape strings
    split = params['suit'].explodeunescape(
        params['var']['delimiter'],
        params['case'],
        params['config']['escape']
    )
    params['case'] = params['suit'].vars
    for value in split:
        try:
            params['case'] = params['case'][value]
        except (AttributeError, TypeError):
            try:
                params['case'] = params['case'][int(value)]
            except (AttributeError, TypeError, ValueError):
                params['case'] = getattr(params['case'], value)
    if params['var']['serialize']:
        params['case'] = pickle.dumps(params['case'])
    return params