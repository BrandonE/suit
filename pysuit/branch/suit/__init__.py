"""
**@This file is part of PySUIT.
**@PySUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@PySUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with PySUIT.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 The SUIT Group.
http://www.selfframework.com/
http://www.selfframework.com/docs/credits
"""
import inspect
import pickle

__version__ = '0.0.2'

CACHE = {
    'escape': {},
    'execute':
    {
        'parse': {},
        'tokens': {}
    },
    'explodeunescape': {}
}

LOG = []

def close(params, pop, closed):
    string = params['return'][params['last']:params['position']]
    if not 'create' in params['nodes'][pop['node']]:
        pop['closed'] = closed
        #If the inner string is not empty, add it to the node
        if string:
            pop['contents'].append(string)
        #Add the node to the tree if necessary
        if notclosed(params['tree']):
            pop2 = params['tree'].pop()
            pop2['contents'].append(pop)
            pop = pop2
        params['tree'].append(pop)
    else:
        append = {
            'create': string,
            'node': params['nodes'][pop['node']]['create'],
            'contents': []
        }
        params['tree'].append(append)
        params['skipstack'] = skip(
            params['nodes'][params['nodes'][pop['node']]['create']],
            params['skipstack']
        )
    params['last'] = params['position'] + len(params['string'])
    return params

def closingstring(params):
    """Handle a closing string instance in the parser"""
    if params['skipstack']:
        if 'skipescape' in params['skipstack'][
            len(params['skipstack']) - params['skipoffset'] - 1
        ]:
            escaping = params['skipstack'][0]['skipescape']
        else:
            escaping = False
        skippop = params['skipstack'].pop()
    else:
        escaping = True
        skippop = False
    #If a value was not popped or the closing string for this node matches it
    if (skippop == False or
    params['string'] == skippop['close']):
        #If it explictly says to escape
        if escaping:
            params['position'] = params['unescape']['position']
            params['return'] = params['unescape']['string']
        #If this position should not be overlooked
        if not params['unescape']['condition']:
            #If there is an offset, decrement it
            if params['skipoffset']:
                params['skipoffset'] -= 1
            elif notclosed(params['tree']):
                pop = params['tree'].pop()
                #If this closing string matches the last node's or it
                #explicitly says to execute a mismatched case
                if (params['nodes'][
                    pop['node']
                ]['close'] == params['string'] or
                params['config']['mismatched']):
                    params = close(params, pop, True)
                #Else, put the string back
                else:
                    if notclosed(params['tree']):
                        pop2 = params['tree'].pop()
                        pop2['contents'].append(pop['node'])
                        for value in pop['contents']:
                            pop2['contents'].append(value)
                        params['tree'].append(pop2)
                    else:
                        params['tree'].append(pop['node'])
                        for value in pop['contents']:
                            params['tree'].append(value)
    #Else, put the popped value back
    else:
        params['skipstack'].append(skippop)
    return params

def escape(strings, returnvalue, escapestring = '\\', insensitive = True):
    """Escape a string"""
    cachekey = hash((returnvalue, pickle.dumps(strings)))
    #If positions are cached for this case, load them
    if cachekey in CACHE['escape']:
        pos = CACHE['escape'][cachekey]
    else:
        positionstrings = {}
        for value in strings:
            positionstrings[value] = None
        positionstrings = positionstrings.items()
        #Order the strings by the length, descending
        positionstrings.sort(
            key = lambda item: len(item[0]),
            reverse = True
        )
        params = {
            'insensitive': insensitive,
            'pos': {},
            'repeated': [],
            'return': returnvalue,
            'strings': positionstrings,
            'taken': []
        }
        pos = positions(params)
        #On top of the strings to be escaped, the last position in the string
        #should be checked for escape strings
        pos[len(returnvalue)] = None
        #Order the positions from smallest to biggest
        pos = sorted(pos.items())
        #Cache the positions
        CACHE['escape'][cachekey] = pos
    offset = 0
    for value in pos:
        #Adjust position to changes in length
        position = value[0] + offset
        count = 0
        #If the escape string is not empty
        if escapestring:
            start = position - len(escapestring)
            #Count how many escape characters are directly to the left of this
            #position
            while (abs(start) == start and
            returnvalue[start:start + len(escapestring)] == escapestring):
                count += len(escapestring)
                start = position - count - len(escapestring)
            #Determine how many escape strings are directly to the left of this
            #position
            count = count / len(escapestring)
        #Replace the escape strings with two escape strings, escaping each of
        #them
        returnvalue = ''.join((
            returnvalue[0:position - (count * len(escapestring))],
            escapestring * (count * 2),
            returnvalue[position:len(returnvalue)]
        ))
        #Adjust the offset
        offset += count * len(escapestring)
    #Escape every string
    for value in strings:
        returnvalue = returnvalue.replace(
            value,
            ''.join((
                escapestring,
                value
            ))
        )
    return returnvalue

def execute(nodes, returnvalue, config = None):
    """Parse string using nodes"""
    if config == None:
        config = {}
    if not 'escape' in config:
        config['escape'] = '\\'
    if not 'insensitive' in config:
        config['insensitive'] = True
    if not 'mismatched' in config:
        config['mismatched'] = False
    if not 'unclosed' in config:
        config['unclosed'] = False
    cachekey = hash((
        returnvalue,
        pickle.dumps(nodes),
        pickle.dumps(config['insensitive'])
    ))
    #If positions are cached for this case, load them
    if cachekey in CACHE['execute']['tokens']:
        executetokens = CACHE['execute']['tokens'][cachekey]
    else:
        executetokens = tokens(nodes, returnvalue, config)
        #Cache the tokens
        CACHE['execute']['tokens'][cachekey] = executetokens
    cachekey = hash((
        returnvalue,
        pickle.dumps(nodes),
        pickle.dumps(config['insensitive']),
        pickle.dumps(config['escape']),
        pickle.dumps(config['mismatched'])
    ))
    #If a tree is cached for this case, load it
    if cachekey in CACHE['execute']['parse']:
        tree = CACHE['execute']['parse'][cachekey]
    else:
        tree = {
            'contents': parse(nodes, returnvalue, config, executetokens)
        }
        if '' in nodes:
            tree['node'] = ''
        #Cache the tree
        CACHE['execute']['tokens'][cachekey] = tree
    LOG.append(tree)
    result = walk(nodes, tree, config)
    return result['contents']

def explodeunescape(explode, glue, escapestring = '\\', insensitive = True):
    """Split up the file, paying attention to escape strings"""
    array = []
    cachekey = hash((glue, explode))
    #If positions are cached for this case, load them
    if cachekey in CACHE['explodeunescape']:
        pos = CACHE['explodeunescape'][cachekey]
    else:
        pos = []
        position = strpos(
            glue,
            explode,
            0,
            insensitive
        )
        #Find the next position of the string
        while position != -1:
            pos.append(position)
            position = strpos(
                glue,
                explode,
                position + 1,
                insensitive
            )
        #On top of the explode string to be escaped, the last position in the
        #string should be checked for escape strings
        pos.append(len(glue))
        #Cache the positions
        CACHE['explodeunescape'][cachekey] = pos
    offset = 0
    last = 0
    temp = glue
    for value in pos:
        #Adjust position to changes in length
        value += offset
        count = 0
        #If the escape string is not empty
        if escapestring:
            start = value - len(escapestring)
            #Count how many escape characters are directly to the left of this
            #position
            while (abs(start) == start and
            glue[start:start + len(escapestring)] == escapestring):
                count += len(escapestring)
                start = value - count - len(escapestring)
            #Determine how many escape strings are directly to the left of this
            #position
            count = count / len(escapestring)
        condition = count % 2
        #If the number of escape strings directly to the left of this position
        #are odd, (x + 1) / 2 of them should be removed
        if condition:
            count += 1
        #If there are escape strings directly to the left of this position
        if count:
            #Remove the decided number of escape strings
            glue = ''.join((
                glue[0:value - ((count / 2) * len(escapestring))],
                glue[value:len(glue)]
            ))
            #Adjust the value
            value -= (count / 2) * len(escapestring)
        if not condition:
            #This separator is not overlooked, so append the accumulated value
            #to the return array
            array.append(glue[last:value])
            #Make sure not to include anything we appended in a future value
            last = value + len(explode)
        #Adjust the offset
        offset = len(glue) - len(temp)
    return array

def functions(params, function):
    for value in function:
        #Transform the string in between the opening and closing strings
        params = value(params)
        if not params['function']:
            break
    return params

def notclosed(tree):
    #If the tree is not empty and the last item is an array and has not been closed
    return (
        tree and
        isinstance(tree[len(tree) - 1], dict) and
        (
            not 'closed' in tree[len(tree) - 1] or
            not tree[len(tree) - 1]['closed']
        )
    )

def openingstring(params):
    """Handle an opening string instance in the parser"""
    if params['skipstack']:
        if 'skipescape' in params['skipstack'][
            len(params['skipstack']) - params['skipoffset'] - 1
        ]:
            escaping = params['skipstack'][0]['skipescape']
        else:
            escaping = False
        skippop = params['skipstack'].pop()
    else:
        escaping = True
        skippop = False
    #If a value was not popped from skipstack
    if skippop == False:
        params['position'] = params['unescape']['position']
        params['return'] = params['unescape']['string']
        #If this position should not be overlooked
        if not params['unescape']['condition']:
            #Add the string in between the last symbol and this to the tree
            append = params['return'][params['last']:params['position']]
            params['last'] = params['position'] + len(params['string'])
            #Add the text to the tree if necessary
            if notclosed(params['tree']):
                pop = params['tree'].pop()
                if append:
                    pop['contents'].append(append)
                params['tree'].append(pop)
            else:
                if append:
                    params['tree'].append(append)
            append = {
                'node': params['string'],
                'contents': []
            }
            params['tree'].append(append)
            params['skipstack'] = skip(params['nodes'][params['string']], params['skipstack'])
    else:
        #Put it back
        params['skipstack'].append(skippop)
        skipclose = [params['nodes'][params['string']]['close']]
        if 'create' in params['nodes'][params['string']]:
            create = params['nodes'][params['string']]['create']
            skipclose.append(params['nodes'][create]['close'])
        #If the closing string for this node matches it
        if skippop['close'] in skipclose:
            #If it explictly says to escape
            if (escaping):
                params['position'] = params['unescape']['position']
                params['return'] = params['unescape']['string']
            #If this position should not be overlooked
            if not params['unescape']['condition']:
                #Account for it
                params['skipstack'].append(skippop)
                params['skipoffset'] += 1
    return params

def parse(nodes, returnvalue, config, tokens):
    """Generate the tree for execute"""
    params = {
        'config': config,
        'last': 0,
        'nodes': nodes,
        'return': returnvalue,
        'returnoffset': 0,
        'skipstack': [],
        'skipoffset': 0,
        'temp': returnvalue,
        'tree': []
    }
    for value in tokens:
        #Adjust position to changes in length
        params['position'] = value[0] + params['returnoffset']
        params['string'] = value[1][0]
        position = params['position']
        string = params['return']
        count = 0
        #If the escape string is not empty
        if params['config']['escape']:
            start = position - len(params['config']['escape'])
            #Count how many escape characters are directly to the left of this
            #position
            while (abs(start) == start and
            string[
                start:
                start + len(params['config']['escape'])
            ] == params['config']['escape']):
                count += len(params['config']['escape'])
                start = position - count - len(params['config']['escape'])
            #Determine how many escape strings are directly to the left of this
            #position
            count = count / len(params['config']['escape'])
        #If the number of escape strings directly to the left of this position are
        #odd, the position should be overlooked
        condition = count % 2
        #If the condition is true, (x + 1) / 2 of them should be removed
        if condition:
            count += 1
        #Adjust the position
        position -= len(params['config']['escape']) * (count / 2)
        #Remove the decided number of escape strings
        string = ''.join((
            string[0:position],
            string[
                position + len(params['config']['escape']) * (count / 2):
                len(string)
            ]
        ))
        params['unescape'] = {
            'condition': condition,
            'position': position,
            'string': string
        }
        #If this is the opening string and it should not be skipped over
        function = closingstring
        if value[1][1] == 0:
            function = openingstring
        params = function(params)
        #Adjust the offset
    params['returnoffset'] = len(params['return']) - len(params['temp'])
    string = params['return'][params['last']:len(params['return'])]
    #If the ending string is not empty, add it to the tree
    if string:
        if notclosed(params['tree']):
            pop = params['tree'].pop()
            params['position'] = len(params['return'])
            params = close(params, pop, False)
        else:
            params['tree'].append(string)
    return params['tree']

def positions(params):
    """Find the positions of strings"""
    params['taken'] = []
    for params['value'] in params['strings']:
        #If the string has not already been used
        if not params['value'][0] in params['repeated']:
            params = positionsloop(params)
            #Make sure this string is not repeated
            params['repeated'].append(params['value'][0])
    return params['pos']

def positionsloop(params):
    """Handle the loop to find the positions of strings"""
    position = strpos(
        params['return'],
        params['value'][0],
        0,
        params['insensitive']
    )
    while position != -1:
        success = True
        for value in params['taken']:
            #If this string instance is in this reserved range
            if ((
                position >= value[0] and
                position < value[1]
            ) or
            (
                position + len(params['value'][0]) > value[0] and
                position + len(params['value'][0]) < value[1]
            )):
                success = False
                break
        #If this string instance is not in any reserved range
        if success:
            #Add the position
            params['pos'][position] = params['value'][1]
            #Reserve all positions taken up by this string instance
            params['taken'].append((
                position,
                position + len(params['value'][0])
            ))
        #Find the next position of the string
        position = strpos(
            params['return'],
            params['value'][0],
            position + 1,
            params['insensitive']
        )
    return params

def skip(node, skipstack):
    #If the skip key is true, skip over everything between this opening string
    #and its closing string
    if 'skip' in node and node['skip']:
        skipstack.append(node)
    return skipstack

def strpos(haystack, needle, offset, insensitive):
    """Find the position insensitively or sensitively based on the
    configuration"""
    #Find the position insensitively or sensitively based on the configuration
    if insensitive:
        return haystack.upper().find(needle.upper(), offset)
    else:
        return haystack.find(needle, offset)

def tokens(nodes, returnvalue, config):
    """Generate the tokens for execute"""
    strings = {}
    for value in nodes.items():
        strings[value[0]] = (value[0], 0)
        if 'close' in value[1]:
            strings[value[1]['close']] = (value[1]['close'], 1)
    strings = strings.items()
    #Order the strings by the length, descending
    strings.sort(key = lambda item: len(item[0]), reverse = True)
    params = {
        'insensitive': config['insensitive'],
        'pos': {},
        'repeated': [],
        'return': returnvalue,
        'strings': strings
    }
    #Order the positions from smallest to biggest
    return sorted(positions(params).items())

def walk(nodes, tree, config):
    params = {
        'config': config,
        'function': True,
        'nodes': nodes,
        'returnvar': None,
        'returnedvar': None,
        'returnfunctions': [],
        'tree': tree
    }
    if 'node' in tree:
        params['node'] = tree['node']
        if 'var' in nodes[tree['node']]:
            params['var'] = nodes[tree['node']]['var']
    if 'create' in tree:
        params['create'] = tree['create']
    if 'node' in tree and 'treefunctions' in nodes[tree['node']]:
        #Modify the tree with the functions meant to be executed before walking
        #through the tree
        params = functions(params, nodes[tree['node']]['treefunctions'])
        tree = params['tree']
    params['walk'] = True
    for key, value in enumerate(tree['contents']):
        if isinstance(tree['contents'][key], dict):
            result = walkarray(nodes, tree, config, params, key)
            params = result['params']
            tree = result['tree']
        if not params['walk']:
            break
    tree['contents'] = ''.join(tree['contents'])
    if 'node' in tree and 'stringfunctions' in nodes[tree['node']]:
        #Transform the case with the specified functions
        params['function'] = True
        params['case'] = tree['contents']
        params = functions(params, nodes[tree['node']]['stringfunctions'])
        tree['contents'] = str(params['case'])
    return {
        'contents': tree['contents'],
        'functions': params['returnfunctions'],
        'var': params['returnvar']
    }

def walkarray(nodes, tree, config, params, key):
    #If the tag has been closed or it explicitly says to execute unopened
    #strings, walk through the contents with its node
    if (
        config['unclosed'] or
        (
            'closed' in tree['contents'][key] and
            tree['contents'][key]['closed']
        )
    ):
        result = walk(nodes, tree['contents'][key], config)
        tree['contents'][key] = result['contents']
        #Modify the tree with the functions that have been returned
        params['function'] = True
        params['key'] = key
        params['returnedvar'] = result['var']
        params['tree'] = tree
        params = functions(params, result['functions'])
        del params['key']
        del params['returnedvar']
        tree = params['tree']
    #Else, execute it, ignoring the original opening string, with no node
    else:
        tree['contents'][key] = {
            'contents': tree['contents'][key]['contents']
        }
        result = walk(nodes, tree['contents'][key], config)
        tree['contents'][key] = ''.join((
            tree['contents'][key]['node'],
            result['contents']
        ))
    return {
        'params': params,
        'tree': tree
    }