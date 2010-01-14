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
    'explodeunescape': {},
    'parse': {}
}

DEBUG = []

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
    params['nodes'][params['node']]['close'] == skippop['close']):
        #If it explictly says to escape
        if (escaping):
            params['position'] = params['unescape']['position']
            params['return'] = params['unescape']['string']
        #If this position should not be overlooked
        if not params['unescape']['condition']:
            #If there is an offset, decrement it
            if params['skipoffset']:
                params['skipoffset'] -= 1
            #If the stack is not empty
            elif params['openingstack']:
                params['open'] = params['openingstack'].pop()
                params['case'] = params['return'][
                    params['open']['position'] + len(
                        params['open']['open']
                    ):params['position']
                ]
                #If this closing string matches the last node's or it
                #explicitly says to parse a malformed template
                if (params['open']['node']['close'] == params['nodes'][
                    params['node']
                ]['close'] or
                params['config']['malformed']):
                    params = transform(params)
                else:
                    params['last'] = params['position'] + len(
                        params['nodes'][params['node']]['close']
                    )
                    params = ranges(params)
            else:
                if not params['config']['malformed']:
                    params['preparse']['taken'].append((
                        params['position'],
                        params['position'] + len(
                            params['nodes'][params['node']]['close']
                        )
                    ))
                else:
                    params['open'] = {
                        'node': params['nodes'][params['node']],
                        'open': '',
                        'position': 0
                    }
                    params['case'] = params['return'][
                        params['open']['position'] + len(
                            params['open']['open']
                        ):params['position']
                    ]
                    params = transform(params)
        #Else, reserve the range
        else:
            params['preparse']['taken'].append((
                params['position'],
                params['position'] + len(
                    params['nodes'][params['node']]['close']
                )
            ))
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
            result = stack(
                params['nodes'][params['node']],
                params['node'],
                params['position']
            )
            params['openingstack'].extend(result['openingstack'])
            params['skipstack'].extend(result['skipstack'])
        #Else, reserve the range
        else:
            params['preparse']['taken'].append((
                params['position'],
                params['position'] + len(params['node'])
            ))
    else:
        #Put it back
        params['skipstack'].append(skippop)
        skipclose = [params['nodes'][params['node']]['close']]
        if 'attribute' in params['nodes'][params['node']]:
            attribute = params['nodes'][params['node']]['attribute']
            skipclose.append(params['nodes'][attribute]['close'])
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

def parse(nodes, returnvalue, config = None):
    """Parse string using nodes"""
    if config == None:
        config = {}
    if not 'escape' in config:
        config['escape'] = '\\'
    if not 'insensitive' in config:
        config['insensitive'] = True
    if not 'malformed' in config:
        config['malformed'] = False
    if not 'preparse' in config:
        config['preparse'] = False
    if not 'taken' in config:
        config['taken'] = {}
    cachekey = hash((
                returnvalue,
                pickle.dumps(nodes),
                pickle.dumps(config['taken'])
        ))
    #If positions are cached for this case, load them
    if cachekey in CACHE['parse']:
        pos = CACHE['parse'][cachekey]
    else:
        strings = {}
        for value in nodes.items():
            strings[value[0]] = (value[0], 0)
            if 'close' in value[1]:
                strings[value[1]['close']] = (value[0], 1)
        strings = strings.items()
        #Order the strings by the length, descending
        strings.sort(key = lambda item: len(item[0]), reverse = True)
        params = {
            'insensitive': config['insensitive'],
            'pos': {},
            'repeated': [],
            'return': returnvalue,
            'strings': strings,
            'taken': []
        }
        params['taken'].extend(config['taken'])
        pos = positions(params)
        #Order the positions from smallest to biggest
        pos = sorted(pos.items())
        #Cache the positions
        CACHE['parse'][cachekey] = pos
    inspection = inspect.stack()
    DEBUG.append({
        'file': inspection[1][1],
        'line': inspection[1][2],
        'steps':
        [
            {
                'return': returnvalue
            }
        ]
    })
    params = {
        'config': config,
        'ignored': [],
        'last': 0,
        'nodes': nodes,
        'openingstack': [],
        'preparse': {
            'ignored': [],
            'taken': []
        },
        'return': returnvalue,
        'returnoffset': 0,
        'skipstack': [],
        'skipoffset': 0,
        'temp': returnvalue
    }
    for value in pos:
        #Adjust position to changes in length
        params['position'] = value[0] + params['returnoffset']
        params = step(params, value[1])
        if not params['parse']:
            break
    params = remaining(params)
    tree()
    if not params['config']['preparse']:
        returnvalue = params['return']
    else:
        returnvalue = {
            'ignored': params['preparse']['ignored'],
            'return': params['return'],
            'taken': params['preparse']['taken']
        }
    return returnvalue

def positions(params):
    """Find the positions of strings"""
    for params['value'] in params['strings']:
        #If the string has not already been used
        if not params['value'][0] in params['repeated']:
            #Find the next position of the string
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

def ranges(params):
    """Adjust ignored and taken ranges"""
    for key, value in enumerate(params['preparse']['ignored']):
        #If this reserved range is in this case, adjust the range to the
        #removal of the opening string and trimming
        if (
            params['open']['position'] < value[0] and
            params['position'] + len(
                params['nodes'][params['node']]['close']
            ) > value[1]
        ):
            params['preparse']['ignored'][key][0] += params['offset']
            params['preparse']['ignored'][key][1] += params['offset']
    clone = []
    for value in params['preparse']['taken']:
        #If this reserved range is in this case
        if (
            params['open']['position'] < value[0] and
            params['position'] + len(
                params['nodes'][params['node']]['close']
            ) > value[1]
        ):
            #If the node does not transform the case, adjust the range to the
            #removal of the opening string and trimming
            if ('transform' in params['open']['node'] and
            not params['open']['node']['transform']):
                value[0] += params['offset']
                value[1] += params['offset']
                clone.append(value)
        else:
            clone.append(value)
    params['preparse']['taken'] = clone
    #If the node transforms the case, this case should be taken, and the case
    #is not empty, reserve the transformed case
    if ((not 'transform' in params['open']['node'] or
    params['open']['node']['transform']) and
    params['taken'] and
    params['case']):
        params['preparse']['taken'].append([
            params['open']['position'],
            params['last']
        ])
    return params

def remaining(params):
    """Handle the remaining opening strings"""
    for value in params['openingstack']:
        if not params['config']['malformed']:
            params['preparse']['taken'].append((
                value['position'],
                value['position'] + len(value['open'])
            ))
        else:
            params['position'] = len(params['return'])
            params = step(params, (value['open'], 1))
    return params

def stack(node, opening, position):
    """Add the opening string to the stack"""
    #Add the opening string to the stack
    clone = node.copy()
    if 'function' in clone:
        clone['function'] = clone['function'][:]
    openingstack = [
        {
            'node': clone,
            'open': opening,
            'position': position
        }
    ]
    skipstack = []
    #If the skip key is true, skip over everything between this opening string
    #and its closing string
    if 'skip' in node and node['skip']:
        skipstack.append(node)
    return {
        'openingstack': openingstack,
        'skipstack': skipstack
    }

def step(params, value):
    """One step of the parse"""
    params['function'] = True
    params['node'] = value[0]
    params['offset'] = 0
    params['parse'] = True
    params['taken'] = True
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
    if value[1] == 0:
        params = openingstring(params)
    else:
        pop = DEBUG.pop()
        pop['steps'].append({
            'node': params['node'],
            'recurse': []
        })
        DEBUG.append(pop)
        params = closingstring(params)
        pop = DEBUG.pop()
        pop2 = pop['steps'].pop()
        pop2['ignored'] = params['preparse']['ignored']
        pop2['return'] = params['return']
        pop2['taken'] = params['preparse']['taken']
        pop['steps'].append(pop2)
        DEBUG.append(pop)
    #Adjust the offset
    params['returnoffset'] = len(params['return']) - len(params['temp'])
    return params

def strpos(haystack, needle, offset, insensitive):
    """Find the position insensitively or sensitively based on the
    configuration"""
    #Find the position insensitively or sensitively based on the configuration
    if insensitive:
        return haystack.upper().find(needle.upper(), offset)
    else:
        return haystack.find(needle, offset)

def transform(params):
    """Transform the string in between the opening and closing strings"""
    #If functions are provided
    if 'function' in params['open']['node']:
        success = True
        for value in params['preparse']['ignored']:
            length = len(params['open']['node']['close'])
            #If this ignored node is in this case
            if (params['open']['position'] < value[0] and
            params['position'] + length > value[1]):
                success = False
                break
        #If either this does not contain a ignored node or the node does not
        #transform the case
        if (
            success or
            (
                'transform' in params['open']['node'] and
                not params['open']['node']['transform']
            )
        ):
            if 'var' in params['open']['node']:
                params['var'] = params['open']['node']['var']
            for value in params['open']['node']['function']:
                #Transform the string in between the opening and closing
                #strings
                params = value(params)
                if not params['function']:
                    break
            params['case'] = str(params['case'])
            start = params['position'] + len(
                params['open']['node']['close']
            )
            #Replace everything including and between the opening and closing
            #strings with the transformed string
            params['return'] = ''.join((
                params['return'][0:params['open']['position']],
                params['case'],
                params['return'][start:len(params['return'])]
            ))
            params['last'] = params['open']['position'] + len(
                params['case']
            )
            params = ranges(params)
    return params

def tree():
    """Log the function call"""
    #Log the function call
    push = True
    pop = DEBUG.pop()
    if DEBUG:
        pop2 = DEBUG.pop()
        if pop2['steps']:
            pop3 = pop2['steps'].pop()
            if not 'return' in pop3:
                pop3['recurse'].append(pop)
                push = False
            pop2['steps'].append(pop3)
        DEBUG.append(pop2)
    if push:
        DEBUG.append(pop)