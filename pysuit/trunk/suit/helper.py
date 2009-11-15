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
            skippop = params['skipnode'].pop()
        else:
            skippop = False
        result = self.owner.parseunescape(
            params['position'],
            params['return'],
            params['escape']
        )
        #If this should not be skipped over
        if (skippop == False or
        (params['nodes'][params['node']][1]['close'] == skippop and
        not result['condition'])):
            params['position'] = result['pos']
            params['return'] = result['string']
            params['open'] = None
            #If this position should not be overlooked and the stack is not
            #empty
            if (not params['skipnode'] and
            not result['condition'] and
            params['stack']):
                params['open'] = params['stack'].pop()
                original = params['nodes'][params['node']][1]['close']
                #If this closing string matches the last node's
                if (params['open'][0][1]['close'] == original):
                    params = self.transform(params)
        #Else, put the popped value back
        else:
            params['skipnode'].append(skippop)
        return params

    def openingstring(self, params):
        """Handle an opening string instance in the parser"""
        if params['skipnode']:
            skippop = params['skipnode'].pop()
        else:
            skippop = False
        result = self.owner.parseunescape(
            params['position'],
            params['return'],
            params['escape']
        )
        #If this should not be skipped over
        if (skippop == False or
        (params['nodes'][params['node']][1]['close'] == skippop and
        not result['condition'])):
            params['position'] = result['pos']
            params['return'] = result['string']
            #If this position should not be overlooked
            if (not params['skipnode'] and
            skippop == False and
            not result['condition']):
                #Add the opening string to the stack
                params['stack'].append((
                    params['nodes'][params['node']],
                    params['position']
                ))
            #If we popped a value or the skip key is true, skip over everything
            #between this opening string and its closing string
            if (skippop != False or
            ('skip' in params['nodes'][params['node']][1] and
            params['nodes'][params['node']][1]['skip'])):
                close = params['nodes'][params['node']][1]['close']
                params['skipnode'].append(close)
        #If we popped a value, put it back
        if skippop != False:
            params['skipnode'].append(skippop)
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

    def strpos(self, haystack, needle, offset = 0, function = None):
        """Find the position insensitively or sensitively based on the
        configuration"""
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
            signature = {
                'case': params['return'][start:params['position']],
                'escape': params['escape'],
                'nodes': params['realnodes'],
                'offset': 0,
                'suit': self.owner
            }
            if 'var' in params['open'][0][1]:
                signature['var'] = params['open'][0][1]['var']
            #If a function is provided
            if 'function' in params['open'][0][1]:
                #Transform the string in between the opening and closing
                #strings.
                signature = params['open'][0][1]['function'](signature)
            else:
                #Replace the opening and closing strings
                signature['case'] = ''.join((
                    params['open'][0][0],
                    signature['case'],
                    params['open'][0][1]['close']
                ))
                signature['offset'] = len(params['open'][0][0])
            start = params['position'] + len(params['open'][0][1]['close'])
            #Replace everything including and between the opening and closing
            #strings with the transformed string
            params['return'] = ''.join((
                params['return'][0:params['open'][1]],
                signature['case'],
                params['return'][start:len(params['return'])]
            ))
            params['case'] = signature['case']
            params['offset'] = signature['offset']
            params = preparse(params)
        #Else if the node should be ignored, reserve the space
        elif ('ignore' in params['open'][0][1] and
        params['open'][0][1]['ignore']):
            params['ignored'].append((
                params['open'][1],
                params['position'] + len(params['open'][0][1]['close'])
            ))
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

def preparse(params):
    """Adjust ignored and taken ranges"""
    clone = []
    for value in params['ignored']:
        length = len(params['open'][0][1]['close'])
        #If this reserved range is in this case, adjust the range to the
        #removal of the opening string and trimming
        if (params['open'][1] < value[0] and
        params['position'] + length > value[1]):
            value[0] += params['offset'] - len(params['open'][0][0])
            value[1] += params['offset'] - len(params['open'][0][0])
        clone.append(value)
    params['ignored'] = clone
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
                value[0] += params['offset'] - len(params['open'][0][0])
                value[1] += params['offset'] - len(params['open'][0][0])
        if success:
            clone.append(value)
    params['taken'] = clone
    #If the node does not just strip the opening and closing string and
    #the case is not empty, reserve the transformed case
    if ((not 'strip' in params['open'][0][1] or
    not params['open'][0][1]['strip']) and
    params['case']):
        params['taken'].append([
            params['open'][1],
            params['open'][1] + len(params['case'])
        ])
    return params