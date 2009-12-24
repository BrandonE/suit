"""
**@This program is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@This program is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
"""
import copy
import helper
import pickle

def assign(params):
    """Assign variable in template"""
    if params['var']['var']:
        params['suit'].vars[params['var']['var']] = params['case']
    params['case'] = ''
    return params

def attribute(params):
    """Create node out of attributes"""
    node = copy.deepcopy(params['nodes'][params['open']['node']['attribute']])
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
                if (
                    'list' in params['var'] and
                    (
                        (
                            (
                                not 'blacklist' in params['var'] or
                                not params['var']['blacklist']
                            ) and
                            not name in params['var']['list']
                        ) or
                        (
                            'blacklist' in params['var'] and
                            params['var']['blacklist'] and
                            name in params['var']['list']
                        )
                    )
                ):
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
                if (result['return'].lower() == 'false'):
                    result['return'] = ''
                node['var'][name] = result['return']
            else:
                ignore = True
                break
    params['case'] = ''.join((
            params['open']['open'],
            params['case'],
            params['open']['node']['close']
        ))
    params['taken'] = False
    if not ignore:
        #Add the new node to the stack
        stack = {
            'node': params['case'],
            'nodes': {},
            'position': params['open']['position'],
            'skipnode': [],
            'stack': []
        }
        stack['nodes'][stack['node']] = node
        stack = helper.stack(stack)
        params['stack'].extend(stack['stack'])
        params['skipnode'].extend(stack['skipnode'])
        params['preparse']['nodes'][stack['node']] = node
    #Else, ignore this case
    else:
        params['ignore'] = True
    return params

def comments(params):
    """Hide a string"""
    params['case'] = ''
    return params

def condition(params):
    """Strip node tags or hide a string"""
    #Calculate how many characters were stripped
    params['offset'] = params['case'].lstrip(params['var']['trim'])
    params['offset'] = len(params['offset']) - len(params['case'])
    #Trim the case if requested
    params['case'] = params['case'].strip(params['var']['trim'])
    #Hide the case if necessary
    if ((params['var']['condition'] and
    params['var']['else']) or
    (not params['var']['condition'] and not params['var']['else'])):
        params['case'] = ''
    return params

def conditionskip(params):
    """Skip if the string should be hidden"""
    if params['stack']:
        pop = params['stack'].pop()
        #If the case was not hidden, do not skip over everything between this
        #opening string and its closing string
        if ((pop['node']['var']['condition'] and
        not pop['node']['var']['else']) or
        (not pop['node']['var']['condition'] and
        pop['node']['var']['else'])):
            params['skipnode'].pop()
        params['stack'].append(pop)
    return params

def escape(params):
    """Create an escaped area"""
    #Calculate how many characters were stripped
    params['offset'] = params['case'].lstrip(params['var'])
    params['offset'] = len(params['offset']) - len(params['case'])
    #Trim the case if requested
    params['case'] = params['case'].strip(params['var'])
    return params

def evaluation(params):
    """Evaluate a Python statement"""
    params['case'] = eval(params['case'])
    return params

def loop(params):
    """Loop a string with different variables"""
    iterationvars = []
    result = {
        'ignore': {},
        'same': {}
    }
    for value in pickle.loads(params['var']['vars']):
        var = {
            params['var']['node']: copy.deepcopy(
                params['nodes'][params['var']['node']]
            )
        }
        try:
            for value2 in value.items():
                var[params['var']['node']]['var']['var'][value2[0]] = value2[1]
        except (AttributeError, TypeError):
            for value2 in dir(value):
                if (not value2.startswith('_') and
                not callable(getattr(value, value2))):
                    var[params['var']['node']]['var']['var'][value2] = getattr(
                        value,
                        value2
                    )
        result = looppreparse(
            var[params['var']['node']]['var']['var'],
            result
        )
        iterationvars.append(var)
    iterations = []
    if iterationvars:
        nodes = {
            params['var']['node']: copy.deepcopy(
                iterationvars[0][params['var']['node']]
            )
        }
        if result['ignore']:
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
            thiscase = params['suit'].parse(
                dict(
                    params['nodes'].items() +
                    result['nodes'].items() +
                    value.items()
                ),
                result['return'],
                config
            )
            #Trim the result if requested
            thiscase = thiscase.lstrip(params['var']['trim'])
            if len(iterationvars) == key + 1:
                thiscase = thiscase.rstrip(params['var']['trim'])
            #Append the result
            iterations.append(thiscase)
    #Implode the iterations
    params['case'] = params['var']['delimiter'].join(iterations)
    return params

def looppreparse(iterationvars, returnvalue):
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
                if value[1] != value2[1] and value[0] == value2[0]:
                    different = True
                else:
                    clone[value2[0]] = value2[1]
            returnvalue['same'] = clone
            #If there is an instance of a node that has the same opening string
            #but is different overall, same it
            if different:
                returnvalue['ignore'][value[0]] = value[1]
            #Else, prepare to preparse it
            elif not value[0] in returnvalue['same']:
                returnvalue['same'][value[0]] = value[1]
    return returnvalue

def loopvariables(params):
    """Parse variables in a loop"""
    if not params['case'] in params['var']['ignore']:
        #Split up the file, paying attention to escape strings
        split = params['suit'].explodeunescape(
            params['var']['delimiter'],
            params['case'],
            params['config']['escape']
        )
        params['case'] = params['var']['var']
        for value in split:
            try:
                params['case'] = params['case'][value]
            except AttributeError:
                try:
                    params['case'] = params['case'][int(value)]
                except AttributeError:
                    print params['var']['var']
                    params['case'] = getattr(params['case'], value)
    else:
        params['case'] = ''.join((
            params['open']['open'],
            params['case'],
            params['open']['node']['close']
        ))
        params['ignore'] = True
        params['taken'] = False
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
    """Return early from a parse call"""
    params['case'] = ''
    for key, value in enumerate(params['stack']):
        if not 'function' in value['node']:
            params['stack'][key]['node']['function'] = []
        #Make all of the nodes remove all content in the case that takes place
        #after this return.
        params['stack'][key]['node']['function'].insert(0, returningfirst)
        #Make the last node to be closed remove everything after this return.
        if key == 0:
            params['stack'][key]['node']['function'].append(returninglast)
        params['skipnode'].append(value['node']['close'])
    #If the stack is empty, remove everything after this return.
    if not params['stack']:
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
        #If this is the template file, get the file's content
        if key == 0:
            template = open(
                ''.join
                ((
                    params['var']['files']['templates'],
                    '/',
                    value,
                    '.',
                    params['var']['filetypes']['templates']
                ))
            ).read()
        #Else, prepare to include the file
        else:
            code.append(
                ''.join
                ((
                    params['var']['files']['code'],
                    '/',
                    value,
                    '.',
                    params['var']['filetypes']['code']
                )).replace('../', '').replace('..\\', '')
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
            params['ignore'] = True
            params['taken'] = False
    except Exception as inst:
        if params['var']['var']:
            params['suit'].vars[params['var']['var']] = inst
        params['case'] = ''
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