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
def condition(params):
    """Function used by the node generated in Section.condition"""
    #Calculate how many characters were stripped
    offset = params['case'].lstrip(params['var']['trim'])
    params['suit'].extra['offset'] = len(offset) - len(params['case'])
    #Trim the case if requested
    params['case'] = params['case'].strip(params['var']['trim'])
    #If the boolean is true, strip the tags. If not, hide the entire thing
    if params['var']['bool']:
        return params['case']
    else:
        return ''

def getsection(params):
    """Function used by the node generated in Section.get"""
    #Add the case to the sections array
    params['suit'].extra['sections'].append(params['case'])
    #Replace the tags
    return ''.join(
        (
            params['var']['open'],
            params['case'],
            params['var']['close']
        )
    )

def loop(params):
    """Function used by the node generated in Section.loop"""
    iterations = []
    realnodes = {}
    loopvariables = {}
    for key, value in params['nodes'].items():
        #If the node should not be ignored
        if not 'ignore' in value or not value['ignore']:
            #If the node exists already, merge its loopvars later
            if key == params['var']['config']['loopopen']:
                loopvariables = value['var']['var']
            #Else, add it to the array
            else:
                realnodes[key] = value
    unique = []
    result = {
        'ignore': {},
        'same': {}
    }
    for value in params['var']['array']:
        if not 'nodes' in value:
            value['nodes'] = {}
        value['nodes'][params['var']['config']['loopopen']] = {
            'close': params['var']['config']['loopclose'],
            'function': loopvars,
            'var':
            {
                'escape': params['escape'],
                'separator': params['var']['config']['separator']
            } #This will be used by the function
        }
        loopopen = params['var']['config']['loopopen']
        if 'vars' in value:
            value['vars'].update(loopvariables)
            value['nodes'][loopopen]['var']['var'] = value['vars']
        else:
            value['nodes'][loopopen]['var']['var'] = loopvariables
        result = looppreparse(value['nodes'].items(), result)
        unique.append(value)
    config = {
        'escape': params['escape'],
        'preparse': True
    }
    if 'label' in params['var']['config']:
        config['label'] = params['var']['config']['label']
    #Parse everything possible without iteration
    result = params['suit'].parse(
        dict(
            realnodes.items() +
            result['same'].items() +
            result['ignore'].items()
        ),
        params['case'],
        config
    )
    for key, value in enumerate(unique):
        value['escape'] = params['escape']
        value['taken'] = result['taken']
        #Parse for this iteration
        thiscase = params['suit'].parse(
            dict(realnodes.items() + value['nodes'].items()),
            result['return'],
            value
        )
        #Trim the result if requested
        thiscase = thiscase.lstrip(params['var']['config']['trim'])
        if len(unique) == key + 1:
            thiscase = thiscase.rstrip(params['var']['config']['trim'])
        #Append the result
        iterations.append(thiscase)
    return params['var']['implode'].join(iterations)

def looppreparse(nodes, returnvalue):
    """Populate the nodes for preparsing"""
    for key, value in nodes:
        node = {
            'skip': ('skip' in value and value['skip']),
            'ignore': True
        }
        if 'close' in value:
            node['close'] = value['close']
        #If this node is not already being ignored
        if not key in returnvalue['ignore']:
            different = False
            clone = {}
            for key2, value2 in returnvalue['same'].items():
                #If this node has the same opening string as the one we are
                #checking but is different overall, remove the checking string
                #and note the difference
                if value != value2 and key == key2:
                    different = True
                else:
                    clone[key2] = value2
            returnvalue['same'] = clone
            #If there is an instance of a node that has the same opening string
            #but is different overall, same it
            if different:
                returnvalue['ignore'][key] = node
            #Else, prepare to preparse it
            elif not key in returnvalue['same']:
                returnvalue['same'][key] = value
        #Else, if the original does not parse in between the opening and
        #closing strings while the current one does, parse in between the
        #opening and closing strings
        elif (returnvalue['ignore'][key]['skip'] and
        (not 'ignored' in value or not value['ignored'])):
            returnvalue['ignore'][key]['skip'] = False
    return returnvalue

def loopvars(params):
    """Function used by the node generated in Nodes.loop"""
    #Split up the file, paying attention to escape strings
    split = params['suit'].explodeunescape(
        params['var']['separator'],
        params['case'],
        params['var']['escape']
    )
    var = params['var']['var']
    for value in split:
        var = var[value]
    return var