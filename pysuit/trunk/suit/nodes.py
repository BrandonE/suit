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
import helper
import pickle

def attribute(params):
    """Create node out of attributes"""
    node = params['nodes'][params['open']['node']['attribute']]
    node = {}
    for value in params['nodes'][params['open']['node']['attribute']].items():
        node[value[0]] = value[1]
    var = node['var']
    node['var'] = {}
    for value in var.items():
        node['var'][value[0]] = value[1]
    #Define the variables
    split = params['suit'].explodeunescape(
        ''.join((params['var']['quote'], params['var']['separator'])),
        params['case'],
        params['escape']
    )
    for key, value in enumerate(split):
        splitequal = value.split(params['var']['equal'], 1)
        #If the syntax is valid and the variable is not whitelisted or
        #blacklisted, define the variable
        if (len(splitequal) == 2 and
        splitequal[1][0] == params['var']['quote'] and
        (not 'list' in params['var'] or
        ((not 'blacklist' in params['var'] or
        not params['var']['blacklist']) and
        splitequal[0] in params['var']['list']) or
        ('blacklist' in params['var'] and
        params['var']['blacklist'] and
        not splitequal[0] in params['var']['list']))):
            nodes = params['nodes']
            splitequal[1] = params['suit'].parse(nodes, splitequal[1])
            splitequal[0] = params['suit'].parse(nodes, splitequal[0])
            node['var'][splitequal[0]] = splitequal[1][1:len(splitequal[1])]
        split[key] = params['var']['equal'].join(splitequal)
    stack = {
        'node': ''.join((
            params['open']['open'],
            ''.join((
                params['var']['quote'],
                params['var']['separator']
            )).join(split),
            params['open']['node']['close']
        )),
        'nodes': {},
        'position': params['open']['position'],
        'stack': [],
        'skipnode': [],
        'skipignore': False
    }
    stack['nodes'][stack['node']] = node
    stack = helper.stack(stack)
    params['stack'].extend(stack['stack'])
    params['skipnode'].extend(stack['skipnode'])
    params['skipignore'] = stack['skipignore']
    params['preparsenodes'][stack['node']] = node
    params['case'] = stack['node']
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

def getsection(params):
    """Function used by the node generated in Section.get"""
    #Add the case to the sections array
    params['suit'].section.sections.append(params['case'])
    params['case'] = ''
    return params

def loop(params):
    """Loop a string with different variables"""
    realnodes = {}
    loopvars = {}
    for key, value in params['nodes'].items():
        #If the node should not be ignored
        if not 'ignore' in value or not value['ignore']:
            #If the node exists already, merge its loopvars later
            if key == params['var']['node']['open']:
                loopvars = value['var']['var']
            #Else, add it to the array
            else:
                realnodes[key] = value
    iterationvars = []
    result = {
        'ignore': {},
        'same': {}
    }
    for value in pickle.loads(params['var']['vars']):
        var = {
            params['var']['node']['open']:
            {
                'close': params['var']['node']['close'],
                'function': [loopvariables],
                'var':
                {
                    'escape': params['escape'],
                    'separator': params['var']['node']['separator'],
                    'var': dict(value.items() + loopvars.items())
                } #This will be used by the function
            }
        }
        result = looppreparse(
            var[params['var']['node']['open']]['var']['var'],
            result
        )
        iterationvars.append(var)
    iterations = []
    if iterationvars:
        if result['ignore']:
            nodes = {
                params['var']['node']['open']:
                {
                    'close': params['var']['node']['close'],
                    'function': [loopvariables],
                    'ignore': True
                }
            }
            for value in result['same'].items():
                nodes[
                    ''.join((
                        params['var']['node']['open'],
                        value[0]
                    ))
                ] = {
                    'close': params['var']['node']['close'],
                    'function': [loopvariable],
                    'var': value[1] #This will be used by the function
                }
        else:
            nodes = {
                params['var']['node']['open']: iterationvars[0][
                    params['var']['node']['open']
                ]
            }
        config = {
            'escape': params['escape'],
            'preparse': True
        }
        if 'label' in params['var']:
            config['label'] = params['var']['label']
        #Parse everything possible without iteration
        result = params['suit'].parse(
            dict(
                realnodes.items() +
                nodes.items()
            ),
            params['case'],
            config
        )
        for key, value in enumerate(iterationvars):
            config = {
                'taken': result['taken']
            }
            if 'label' in params['var']:
                config['label'] = ''.join((params['var']['label'], str(key)))
            #Parse for this iteration
            thiscase = params['suit'].parse(
                dict(
                    realnodes.items() +
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

def loopvariable(params):
    """Parse a particular variable in a loop"""
    params['case'] = params['var']
    return params

def loopvariables(params):
    """Parse variables in a loop"""
    #Split up the file, paying attention to escape strings
    split = params['suit'].explodeunescape(
        params['var']['separator'],
        params['case'],
        params['escape']
    )
    params['case'] = params['var']['var']
    for value in split:
        params['case'] = params['case'][value]
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
        params['var']['separator'],
        params['case'],
        params['escape']
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
        params['case'] = params['suit'].gettemplate(template, code, params['var']['label'])
    else:
        params['case'] = params['suit'].gettemplate(template, code)
    return params

def variables(params):
    """Parse variables"""
    #Split up the file, paying attention to escape strings
    split = params['suit'].explodeunescape(
        params['var']['separator'],
        params['case'],
        params['escape']
    )
    params['case'] = params['suit'].vars
    for value in split:
        params['case'] = params['case'][value]
    return params