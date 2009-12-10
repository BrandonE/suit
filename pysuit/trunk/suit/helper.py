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
import pickle
class Helper(object):
    """Functions that help the SUIT class"""
    def __init__(self, owner):
        self.owner = owner

    def closingstring(self, params):
        """Handle a closing string instance in the parser"""
        if params['skipnode']:
            skippop = params['skipnode'].pop()
        else:
            skippop = False
        skipignore = False
        #If a value was not popped or the closing string for this node matches
        #it
        if (skippop == False or
        params['nodes'][params['node']]['close'] == skippop):
            #If a value was not popped or this position should not be
            #overlooked or we explictly say to escape
            if (skippop == False or
            not params['unescape']['condition'] or
            ('skipescape' in params['nodes'][params['node']] and
            params['nodes'][params['node']]['skipescape'])):
                params['position'] = params['unescape']['position']
                params['return'] = params['unescape']['string']
            #If this position should not be overlooked
            if not params['unescape']['condition']:
                #If there is an offset, decrement it
                if params['skipoffset']:
                    params['skipoffset'] -= 1
                    skipignore = True
                #If the stack is not empty
                elif params['stack']:
                    params['open'] = params['stack'].pop()
                    close = params['nodes'][params['node']]['close']
                    #If this closing string matches the last node's
                    if params['open']['node']['close'] == close:
                        params = self.transform(params)
                    params['skipignore'] = False
        #Else, put the popped value back
        else:
            params['skipnode'].append(skippop)
            skipignore = True
        #If the ignoring should not be skipped, this position should not be
        #overlooked, and the stack is not empty
        if (skipignore and
        params['skipignore'] and
        not params['unescape']['condition'] and
        params['skipignorestack']):
            pop = params['skipignorestack'].pop()
            #If this closing string matches the last node's, reserve the space
            if pop['close'] == params['nodes'][params['node']]['close']:
                params['ignored'].append((
                    pop['position'],
                    params['position'] +
                    len(params['nodes'][params['node']]['close'])
                ))
        return params

    def parseconfig(self, config):
        """Populate the config for the parse function"""
        if config == None:
            config = {}
        if not 'escape' in config:
            config['escape'] = self.owner.escapestring
        if not 'preparse' in config:
            config['preparse'] = False
        if not 'taken' in config:
            config['taken'] = {}
        return config

    def parsepositions(self, nodes, returnvalue, taken):
        """Find the positions of strings for the parse function"""
        strings = {}
        for value in nodes.items():
            #If the close string exists, then there might be some instances to
            #parse
            if 'close' in value[1]:
                strings[value[0]] = (value[0], 0)
                strings[value[1]['close']] = (value[0], 1)
        strings = strings.items()
        #Order the strings by the length, descending
        strings.sort(key = lambda item: len(item[0]), reverse = True)
        params = {
            'function': 'parse',
            'pos': {},
            'repeated': [],
            'return': returnvalue,
            'strings': strings,
            'taken': []
        }
        params['taken'].extend(taken)
        return self.positions(params)

    def positions(self, params):
        """Find the positions of strings"""
        for params['value'] in params['strings']:
            #If the string has not already been used
            if not params['value'][0] in params['repeated']:
                #Find the next position of the string
                params = self.positionsloop(params)
                #Make sure this string is not repeated
                params['repeated'].append(params['value'][0])
        return params['pos']

    def positionsloop(self, params):
        """Handle the loop to find the positions of strings"""
        position = self.strpos(
            params['return'],
            params['value'][0],
            0,
            params['function']
        )
        while position != -1:
            success = True
            for value in params['taken']:
                #If this string instance is in this reserved range
                if ((
                    position > value[0] and
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
            position = self.strpos(
                params['return'],
                params['value'][0],
                position + 1,
                params['function']
            )
        return params

    def strpos(self, haystack, needle, offset = 0, function = None):
        """Find the position insensitively or sensitively based on the
        configuration"""
        #If a function name was provided,
        #increment the number of times that the function called strpos by 1
        if function != None:
            self.owner.debug['strpos'][function]['call'] += 1
        #Find the position insensitively or sensitively based on the
        #configuration
        if self.owner.insensitive:
            return haystack.upper().find(needle.upper(), offset)
        else:
            return haystack.find(needle, offset)

    def transform(self, params):
        """Transform the string in between the opening and closing strings"""
        success = True
        clone = []
        for value in params['ignored']:
            thissuccess = True
            length = len(params['open']['node']['close'])
            #If this ignored node is in this case
            if (params['open']['position'] < value[0] and
            params['position'] + length > value[1]):
                success = False
                if ('ignore' in params['open']['node'] and
                params['open']['node']['ignore']):
                    thissuccess = False
            if thissuccess:
                clone.append(value)
        params['ignored'] = clone
        #If the node should not be ignored, and either this does not contain a
        #ignored node or the node strips the opening and closing string, parse
        if ((
            not 'ignore' in params['open']['node'] or
            not params['open']['node']['ignore']
        ) and
        (
            success or
            ('strip' in params['open']['node'] and
            params['open']['node']['strip'])
        )):
            start = params['open']['position'] + len(params['open']['open'])
            params['case'] = params['return'][start:params['position']]
            params['suit'] = self.owner
            if 'var' in params['open']['node']:
                params['var'] = params['open']['node']['var']
            #If a function is provided
            if 'function' in params['open']['node']:
                for value in params['open']['node']['function']:
                    #Transform the string in between the opening and closing
                    #strings.
                    params = value(params)
            else:
                #Replace the opening and closing strings
                params['case'] = ''.join((
                    params['open']['open'],
                    params['case'],
                    params['open']['node']['close']
                ))
                params['offset'] = len(params['open']['open'])
            start = params['position'] + len(params['open']['node']['close'])
            #Replace everything including and between the opening and closing
            #strings with the transformed string
            params['return'] = ''.join((
                params['return'][0:params['open']['position']],
                params['case'],
                params['return'][start:len(params['return'])]
            ))
            params['last'] = params['open']['position'] + len(params['case'])
            params = preparse(params)
        else:
            #If the node should be ignored, reserve the space
            if ('ignore' in params['open']['node'] and
            params['open']['node']['ignore']):
                params['ignored'].append([
                    params['open']['position'],
                    params['position'] + len(params['open']['node']['close'])
                ])
            #If this is an attribute node, put the popped value back
            if 'attribute' in params['open']['node']:
                params['stack'].append(params['open'])
        return params

def openingstring(params):
    """Handle an opening string instance in the parser"""
    if params['skipnode']:
        skippop = params['skipnode'].pop()
    else:
        skippop = False
    #If a value was not popped from skipnode
    if skippop == False:
        params['position'] = params['unescape']['position']
        params['return'] = params['unescape']['string']
        #If this position should not be overlooked
        if not params['unescape']['condition']:
            params = stack(params)
    else:
        #Put it back
        params['skipnode'].append(skippop)
        skipclose = [params['nodes'][params['node']]['close']]
        if 'attribute' in params['nodes'][params['node']]:
            attribute = params['nodes'][params['node']]['attribute']
            skipclose.append(params['nodes'][attribute]['close'])
        #If the closing string for this node matches it
        if skippop in skipclose:
            #If this position should not be overlooked or we explictly say to
            #escape
            if (not params['unescape']['condition'] or
            ('skipescape' in params['nodes'][params['node']] and
            params['nodes'][params['node']]['skipescape'])):
                params['position'] = params['unescape']['position']
                params['return'] = params['unescape']['string']
            #If this position should not be overlooked
            if not params['unescape']['condition']:
                #Account for it
                params['skipnode'].append(skippop)
                params['skipoffset'] += 1
        #If the ignoring should not be skipped and this node should be ignored
        #and this position should not be overlooked, prepare to reserve the
        #space
        if (params['skipignore'] and
        'ignore' in params['nodes'][params['node']] and
        params['nodes'][params['node']]['ignore'] and
        not params['unescape']['condition']):
            params['skipignorestack'].append({
                'close': params['nodes'][params['node']]['close'],
                'position': params['position']
            })
    return params

def parsecache(nodes, returnvalue, config):
    """Generate the cache key for the parse function"""
    values = []
    for value in nodes:
        array = [value[0]]
        if 'close' in value[1]:
            array.append(value[1]['close'])
        values.append(array)
    return hash((
            returnvalue,
            pickle.dumps(values),
            pickle.dumps(config['taken'])
    ))

def parseunescape(position, escape, string):
    """Unescape at this position"""
    count = 0
    #If the escape string is not empty
    if escape:
        start = position - len(escape)
        #Count how many escape characters are directly to the left of this
        #position
        while (abs(start) == start and
        string[start:start + len(escape)] == escape):
            count += len(escape)
            start = position - count - len(escape)
        #Determine how many escape strings are directly to the left of this
        #position
        count = count / len(escape)
    #If the number of escape strings directly to the left of this position are
    #odd, the position should be overlooked
    condition = count % 2
    #If the condition is true, (x + 1) / 2 of them should be removed
    if condition:
        count += 1
    #Adjust the position
    position -= len(escape) * (count / 2)
    #Remove the decided number of escape strings
    string = ''.join((
        string[0:position],
        string[position + len(escape) * (count / 2):len(string)]
    ))
    return {
        'condition': condition,
        'position': position,
        'string': string
    }

def preparse(params):
    """Adjust ignored and taken ranges"""
    clone = []
    for value in params['ignored']:
        length = len(params['open']['node']['close'])
        #If this reserved range is in this case, adjust the range to the
        #removal of the opening string and trimming
        if (params['open']['position'] < value[0] and
        params['position'] + length > value[1]):
            value[0] += params['offset'] - len(params['open']['open'])
            value[1] += params['offset'] - len(params['open']['open'])
        clone.append(value)
    params['ignored'] = clone
    #Only continue if we are preparsing
    if not params['preparse']:
        return params
    clone = []
    for value in params['taken']:
        success = True
        length = len(params['open']['node']['close'])
        #If this reserved range is in this case
        if (params['open']['position'] < value[0] and
        params['position'] + length > value[1]):
            #If the node does not just strip the opening and closing
            #string, remove the range
            if (not 'strip' in params['open']['node'] or
            not params['open']['node']['strip']):
                success = False
            #Else, adjust the range to the removal of the opening string
            #and trimming
            else:
                value[0] += params['offset'] - len(params['open']['open'])
                value[1] += params['offset'] - len(params['open']['open'])
        if success:
            clone.append(value)
    params['taken'] = clone
    #If the node does not just strip the opening and closing string, this is
    #not an attribute node, and the case is not empty, reserve the transformed
    #case
    if ((not 'strip' in params['open']['node'] or
    not params['open']['node']['strip']) and
    not 'attribute' in params['open']['node'] and
    params['case']):
        params['taken'].append([
            params['open']['position'],
            params['last']
        ])
    return params

def stack(params):
    """Add the opening string to the stack"""
    #Add the opening string to the stack
    clone = {}
    for value in params['nodes'][params['node']].items():
        clone[value[0]] = value[1]
    if 'function' in clone:
        function = clone['function']
        clone['function'] = []
        for value in function:
            clone['function'].append(value)
    params['stack'].append({
        'node': clone,
        'open': params['node'],
        'position': params['position']
    })
    #If the skip key is true, skip over everything between this opening string
    #and its closing string
    if ('skip' in params['nodes'][params['node']] and
    params['nodes'][params['node']]['skip']):
        params['skipnode'].append(params['nodes'][params['node']]['close'])
        if ('skipignore' in params['nodes'][params['node']] and
        params['nodes'][params['node']]['skipignore']):
            params['skipignore'] = True
    return params