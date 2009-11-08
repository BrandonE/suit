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

class Helper:
    """Functions that help the SUIT class"""
    def __init__(self, owner):
        self.owner = owner

    def closingstring(self, params):
        """Handle a closing string instance in the parser"""
        if params['skipnode']:
            test = True
        else:
            test = False
        if test:
            skippop = params['skipnode'].pop()
        else:
            skippop = False
        result = self.owner.parseunescape(
            params['position'],
            params['return'],
            params['escape']
        )
        #If this should not be skipped over
        if (not test or
        (params['nodes'][params['node']][1]['close'] == skippop[1]['close'] and
        not result['condition'])):
            params['position'] = result['pos']
            params['return'] = result['content']
            params['open'] = None
            #If this position should not be overlooked and the stack is not
            #empty
            if not result['condition'] and params['stack']:
                params['open'] = params['stack'].pop()
            original = params['nodes'][params['node']][1]['close']
            #If this closing string matches the last node's
            if (params['open'] and params['open'][0][1]['close'] == original):
                params = self.transform(params)
        #Else, put the popped value back
        else:
            params['skipnode'].append(skippop)
        return params

    def openingstring(self, params):
        """Handle an opening string instance in the parser"""
        result = self.owner.parseunescape(
            params['position'],
            params['return'],
            params['escape']
        )
        params['position'] = result['pos']
        params['return'] = result['content']
        #If this position should not be overlooked
        if not result['condition']:
            #Add the opening string to the stack
            params['stack'].append((
                params['nodes'][params['node']],
                params['position']
            ))
            #If the skip key is true, skip over everything inside
            if ('skip' in params['nodes'][params['node']][1] and
            params['nodes'][params['node']][1]['skip']):
                params['skipnode'].append(params['nodes'][params['node']])
        return params

    def parseconfig(self, config):
        """Populate the config for the parse function"""
        if config == None:
            config = {}
        if not 'escape' in config:
            config['escape'] = self.owner.config['parse']['escape']
        if not 'preparse' in config:
            config['preparse'] = False
        if not 'taken' in config:
            config['taken'] = {}
        return config

    def parsepositions(self, nodes, returnvalue, taken):
        """Find the positions of strings for the parse function"""
        params = {
            'pos': {},
            'repeated': [],
            'taken': []
        }
        params['taken'].extend(taken)
        for key, value in enumerate(nodes):
            #If the close string exists, then there might be some instances to
            #parse
            if 'close' in value[1]:
                node = (value[0], value[1]['close'])
                params = {
                    'function': 'parse',
                    'key': key,
                    'pos': params['pos'],
                    'repeated': params['repeated'],
                    'return': returnvalue,
                    'strings': node,
                    'taken': params['taken']
                }
                params = self.positions(params)
        return params['pos']

    def positions(self, params):
        """Find the positions of strings"""
        for params['nodekey'], params['nodevalue'] in enumerate(
            params['strings']
        ):
            params['nodevalue'] = str(params['nodevalue'])
            #If the string has not already been used
            if not params['nodevalue'] in params['repeated']:
                #Find the next position of the string
                params = self.positionsloop(params)
                #Make sure this string is not repeated
                params['repeated'].append(params['nodevalue'])
        return params

    def positionsloop(self, params):
        """Handle the loop to find the positions of strings"""
        position = self.strpos(
            params['return'],
            params['nodevalue'],
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
                    position + len(params['nodevalue']) > value[0] and
                    position + len(params['nodevalue']) < value[1]
                )):
                    success = False
                    break
            #If this string instance is not in any reserved range
            if success:
                #Add the position
                params['pos'][position] = (params['key'], params['nodekey'])
                #Reserve all positions taken up by this string instance
                params['taken'].append((
                    position,
                    position + len(params['nodevalue'])
                ))
            #Find the next position of the string
            position = self.strpos(
                params['return'],
                params['nodevalue'],
                position + 1,
                'parse'
            )
        return params

    def offset(self, params):
        """Adjust ignored and taken ranges"""
        for key, value in enumerate(params['ignored']):
            length = len(params['open'][0][1]['close'])
            #If this reserved range is in this case, adjust the range to the
            #removal of the opening string and trimming
            if (params['open'][1] < value[0] and
            params['position'] + length > value[1]):
                offset = self.owner.extra['offset']
                length = len(params['open'][0][0])
                params['ignored'][key][0] += offset - length
                params['ignored'][key][1] += offset - length
        #Only continue if we are preparsing
        if not params['preparse']:
            return params
        clone = []
        for value in params['taken']:
            success = True
            length = len(params['open'][0][1]['close'])
            #If this reserved range is in this case
            if (params['open'][1] < value[0] and
            params['position'] + length > value[1]):
                #If the node does not just strip the opening and closing
                #string, remove the range
                if (not 'strip' in params['open'][0][1] or
                not params['open'][0][1]['strip']):
                    success = False
                #Else, adjust the range to the removal of the opening string
                #and trimming
                else:
                    offset = self.owner.extra['offset']
                    length = len(params['open'][0][0])
                    value[0] += offset - length
                    value[1] += offset - length
            if success:
                clone.append(value)
        params['taken'] = clone
        #If the node does not just strip the opening and closing string,
        #reserve the transformed case
        if ((not 'strip' in params['open'][0][1] or
        not params['open'][0][1]['strip']) and
        params['open'][1] != params['open'][1] + len(params['string'])):
            params['taken'].append([
                params['open'][1],
                params['open'][1] + len(params['string'])
            ])
        return params

    def strpos(self, haystack, needle, offset = 0, function = None):
        """Find the position insensitively or sensitively based on the
        configuration"""
        haystack = str(haystack)
        needle = str(needle)
        offset = int(offset)
        #If a function name was provided,
        #increment the number of times that the function called strpos by 1
        if function != None:
            self.owner.debug['strpos'][function]['call'] += 1
        #Find the position insensitively or sensitively based on the
        #configuration
        if self.owner.config['flag']['insensitive']:
            return haystack.upper().find(needle.upper(), offset)
        else:
            return haystack.find(needle, offset)

    def transform(self, params):
        """Transform the string in between the opening and closing strings"""
        success = True
        clone = []
        for value in params['ignored']:
            thissuccess = True
            length = len(params['open'][0][1]['close'])
            #If this ignored node is in this case
            if (params['open'][1] < value[0] and
            params['position'] + length > value[1]):
                success = False
                if ('ignore' in params['open'][0][1] and
                params['open'][0][1]['ignore']):
                    thissuccess = False
            if thissuccess:
                clone.append(value)
        params['ignored'] = clone
        #If the node should not be ignored, and either this does not contain a
        #ignored node or the node strips the opening and closing string, parse
        if ((
            not 'ignore' in params['open'][0][1] or
            not params['open'][0][1]['ignore']
        ) and
        (
            success or
            ('strip' in params['open'][0][1] and
            params['open'][0][1]['strip'])
        )):
            start = params['open'][1] + len(params['open'][0][0])
            params['string'] = params['return'][start:params['position']]
            #If a function is provied, transform the string in between the
            #opening and closing strings. Else, replace the opening and closing
            #strings.
            if 'var' in params['open'][0][1]:
                var = params['open'][0][1]['var']
            else:
                var = None
            self.owner.extra['offset'] = 0
            #If a function is provided
            if 'function' in params['open'][0][1]:
                function = params['open'][0][1]['function']
                #Transform the string in between the opening and closing
                #strings. If the function uses params, send them
                if (not 'params' in params['open'][0][1] or
                params['open'][0][1]['params']):
                    signature = {
                        'case': params['string'],
                        'escape': params['escape'],
                        'nodes': params['realnodes'],
                        'suit': self.owner,
                        'var': var
                    }
                    params['string'] = function(signature)
                else:
                    params['string'] = function()
            else:
                #Replace the opening and closing strings
                params['string'] = ''.join((
                    params['open'][0][0],
                    params['string'],
                    params['open'][0][1]['close']
                ))
            start = params['position'] + len(params['open'][0][1]['close'])
            #Replace everything including and between the opening and closing
            #strings with the transformed string
            params['return'] = ''.join((
                params['return'][0:params['open'][1]],
                params['string'],
                params['return'][start:len(params['return'])]
            ))
            params = self.offset(params)
        #Else if the node should be ignored, reserve the space
        elif ('ignore' in params['open'][0][1] and
        params['open'][0][1]['ignore']):
            params['ignored'].append((
                params['open'][1],
                params['position'] + len(params['open'][0][1]['close'])
            ))
        return params

def debugpreparse(taken, ignored, returnvalue):
    """Mark every taken substring"""
    pos = {}
    for value in taken:
        pos[value[0]] = '[taken]'
        pos[value[1]] = '[/taken]'
    for value in ignored:
        pos[value[0]] = '[ignored]'
        pos[value[1]] = '[/ignored]'
    pos = sorted(pos.items())
    offset = 0
    for value in pos:
        position = value[0] + offset
        returnvalue = ''.join((
            returnvalue[0:position],
            value[1],
            returnvalue[position:len(returnvalue)]
        ))
        offset += len(value[1])
    return returnvalue

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