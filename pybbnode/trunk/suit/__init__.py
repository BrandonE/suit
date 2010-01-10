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
http://www.selfframework.com/
http://www.selfframework.com/docs/credits
"""
import inspect
import pickle

__version__ = '0.0.2'

class SUIT(object):
    """An open-source templating framework that allows you to define your own
    syntax through nodes."""
    def __init__(self):
        self.cache = {
            'escape': {},
            'explodeunescape': {},
            'parse': {}
        }
        self.debug = {
            'parse': [],
            'strpos':
            {
                'escape':
                {
                    'cache': 0,
                    'call': 0
                },
                'explodeunescape':
                {
                    'cache': 0,
                    'call': 0
                },
                'parse':
                {
                    'cache': 0,
                    'call': 0
                }
            },
            'template': []
        }
        self.template = ''
        self.vars = {}

    def closingstring(self, params):
        """Handle a closing string instance in the parser"""
        if params['skipnode']:
            if 'skipescape' in params['skipnode'][
                len(params['skipnode']) - params['skipoffset'] - 1
            ]:
                escape = params['skipnode'][0]['skipescape']
            else:
                escape = False
            skippop = params['skipnode'].pop()
        else:
            escape = True
            skippop = False
        #If a value was not popped or the closing string for this node matches
        #it
        if (skippop == False or
        params['nodes'][params['node']]['close'] == skippop['close']):
            #If it explictly says to escape
            if (escape):
                params['position'] = params['unescape']['position']
                params['return'] = params['unescape']['string']
            #If this position should not be overlooked
            if not params['unescape']['condition']:
                #If there is an offset, decrement it
                if params['skipoffset']:
                    params['skipoffset'] -= 1
                #If the stack is not empty
                elif params['stack']:
                    params['open'] = params['stack'].pop()
                    params['case'] = params['return'][
                        params['open']['position'] + len(
                            params['open']['open']
                        ):params['position']
                    ]
                    #If this closing string matches the last node's
                    if params['open']['node']['close'] == params['nodes'][
                        params['node']
                    ]['close']:
                        params = self.transform(params)
                    else:
                        params['last'] = params['position'] + len(
                            params['nodes'][params['node']]['close']
                        )
                        params = self.preparse(params)
                else:
                    params['preparse']['taken'].append((
                        params['position'],
                        params['position'] + len(
                            params['nodes'][params['node']]['close']
                        )
                    ))
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
            params['skipnode'].append(skippop)
        return params

    def escape(self, strings, returnvalue, escape = '\\', insensitive = True):
        cache = hash((returnvalue, pickle.dumps(strings)))
        #If positions are cached for this case, load them
        if cache in self.cache['escape']:
            pos = self.cache['escape'][cache]
            self.debug['strpos']['escape']['cache'] += 1
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
                'function': 'escape',
                'insensitive': insensitive,
                'pos': {},
                'repeated': [],
                'return': returnvalue,
                'strings': positionstrings,
                'taken': []
            }
            pos = self.positions(params)
            #On top of the strings to be escaped, the last position in the
            #string should be checked for escape strings
            pos[len(returnvalue)] = None
            #Order the positions from smallest to biggest
            pos = sorted(pos.items())
            #Cache the positions
            self.cache['escape'][cache] = pos
        offset = 0
        for value in pos:
            #Adjust position to changes in length
            position = value[0] + offset
            count = 0
            #If the escape string is not empty
            if escape:
                start = position - len(escape)
                #Count how many escape characters are directly to the left of
                #this position
                while (abs(start) == start and
                returnvalue[start:start + len(escape)] == escape):
                    count += len(escape)
                    start = position - count - len(escape)
                #Determine how many escape strings are directly to the left of
                #this position
                count = count / len(escape)
            #Replace the escape strings with two escape strings, escaping each
            #of them
            returnvalue = ''.join((
                returnvalue[0:position - (count * len(escape))],
                escape * (count * 2),
                returnvalue[position:len(returnvalue)]
            ))
            #Adjust the offset
            offset += count * len(escape)
        #Escape every string
        for value in strings:
            returnvalue = returnvalue.replace(
                value,
                ''.join((
                    escape,
                    value
                ))
            )
        return returnvalue

    def explodeunescape(self, explode, glue, escape = '\\', insensitive = True):
        array = []
        cache = hash((glue, explode))
        #If positions are cached for this case, load them
        if cache in self.cache['explodeunescape']:
            pos = self.cache['explodeunescape'][cache]
            self.debug['strpos']['explodeunescape']['cache'] += 1
        else:
            pos = []
            position = self.strpos(
                glue,
                explode,
                0,
                insensitive,
                'explodeunescape'
            )
            #Find the next position of the string
            while position != -1:
                pos.append(position)
                position = self.strpos(
                    glue,
                    explode,
                    position + 1,
                    insensitive,
                    'explodeunescape'
                )
            #On top of the explode string to be escaped, the last position in
            #the string should be checked for escape strings
            pos.append(len(glue))
            #Cache the positions
            self.cache['explodeunescape'][cache] = pos
        offset = 0
        last = 0
        temp = glue
        for value in pos:
            #Adjust position to changes in length
            value += offset
            count = 0
            #If the escape string is not empty
            if escape:
                start = value - len(escape)
                #Count how many escape characters are directly to the left of
                #this position
                while (abs(start) == start and
                glue[start:start + len(escape)] == escape):
                    count += len(escape)
                    start = value - count - len(escape)
                #Determine how many escape strings are directly to the left of
                #this position
                count = count / len(escape)
            condition = count % 2
            #If the number of escape strings directly to the left of this
            #position are odd, (x + 1) / 2 of them should be removed
            if condition:
                count += 1
            #If there are escape strings directly to the left of this position
            if count:
                #Remove the decided number of escape strings
                glue = ''.join((
                    glue[0:value - ((count / 2) * len(escape))],
                    glue[value:len(glue)]
                ))
                #Adjust the value
                value -= (count / 2) * len(escape)
            if not condition:
                #This separator is not overlooked, so append the accumulated
                #value to the return array
                array.append(glue[last:value])
                #Make sure not to include anything we appended in a future
                #value
                last = value + len(explode)
            #Adjust the offset
            offset = len(glue) - len(temp)
        return array

    def gettemplate(self, returnvalue, code = None, label = None):
        debug = inspect.stack()
        debug = {
            'code': [],
            'file': debug[1][2],
            'label': label,
            'line': debug[1][3],
            'template': returnvalue
        }
        if code:
            for value in code:
                debug['code'].append([
                    value,
                    True,
                    False
                ])
                last = len(debug['code']) - 1
                suit = self
                self.template = returnvalue
                #Execute the code file and set the return value to the
                #modified template
                execfile(value)
                returnvalue = self.template
                debug['code'][last] = [
                    debug['code'][last][1],
                    debug['code'][last][2],
                    returnvalue
                ]
        #If a label was provided, log this function
        if label != None:
            self.debug['template'].append(debug)
        return returnvalue

    def openingstring(self, params):
        """Handle an opening string instance in the parser"""
        if params['skipnode']:
            if 'skipescape' in params['skipnode'][
                len(params['skipnode']) - params['skipoffset'] - 1
            ]:
                escape = params['skipnode'][0]['skipescape']
            else:
                escape = False
            skippop = params['skipnode'].pop()
        else:
            escape = True
            skippop = False
        #If a value was not popped from skipnode
        if skippop == False:
            params['position'] = params['unescape']['position']
            params['return'] = params['unescape']['string']
            #If this position should not be overlooked
            if not params['unescape']['condition']:
                result = self.stack(
                    params['nodes'][params['node']],
                    params['node'],
                    params['position']
                )
                params['stack'].extend(result['stack'])
                params['skipnode'].extend(result['skipnode'])
            #Else, reserve the range
            else:
                params['preparse']['taken'].append((
                    params['position'],
                    params['position'] + len(params['node'])
                ))
        else:
            #Put it back
            params['skipnode'].append(skippop)
            skipclose = [params['nodes'][params['node']]['close']]
            if 'attribute' in params['nodes'][params['node']]:
                attribute = params['nodes'][params['node']]['attribute']
                skipclose.append(params['nodes'][attribute]['close'])
            #If the closing string for this node matches it
            if skippop['close'] in skipclose:
                #If it explictly says to escape
                if (escape):
                    params['position'] = params['unescape']['position']
                    params['return'] = params['unescape']['string']
                #If this position should not be overlooked
                if not params['unescape']['condition']:
                    #Account for it
                    params['skipnode'].append(skippop)
                    params['skipoffset'] += 1
        return params

    def parse(self, nodes, returnvalue, config = None):
        debug = inspect.stack()
        debug = {
                'before': returnvalue,
                'file': debug[1][2],
                'line': debug[1][3],
                'return': ''
            }
        config = self.parseconfig(config)
        if 'label' in config:
            debug['label'] = config['label']
        cache = self.parsecache(nodes, returnvalue, config)
        #If positions are cached for this case, load them
        if cache in self.cache['parse']:
            pos = self.cache['parse'][cache]
            self.debug['strpos']['parse']['cache'] += 1
        else:
            pos = self.parsepositions(
                nodes,
                returnvalue,
                config['taken'],
                config['insensitive']
            )
            #Order the positions from smallest to biggest
            pos = sorted(pos.items())
            #Cache the positions
            self.cache['parse'][cache] = pos
        preparse = {
            'ignored': [],
            'taken': []
        }
        params = {
            'config': config,
            'ignored': [],
            'last': 0,
            'preparse': {
                'ignored': [],
                'nodes': {},
                'taken': []
            },
            'skipnode': [],
            'skipoffset': 0,
            'stack': []
        }
        offset = 0
        temp = returnvalue
        for value in pos:
            #Adjust position to changes in length
            position = value[0] + offset
            params['function'] = True
            params['node'] = value[1][0]
            params['nodes'] = nodes
            params['offset'] = 0
            params['parse'] = True
            params['position'] = position
            params['return'] = returnvalue
            params['taken'] = True
            params['unescape'] = self.parseunescape(
                position,
                params['config']['escape'],
                returnvalue
            )
            params['usetaken'] = True
            function = self.closingstring
            #If this is the opening string and it should not be skipped over
            if value[1][1] == 0:
                function = self.openingstring
            params = function(params)
            returnvalue = params['return']
            #If the stack is empty
            if not params['stack']:
                #It is impossible that a skipped over node is in another node,
                #so permanently reserve it and start the process over again
                preparse['ignored'].extend(params['preparse']['ignored'])
                params['preparse']['ignored'] = []
                #If we are preparsing
                if params['config']['preparse']:
                    #The ranges can not be inside another node, so permanently
                    #reserve it and start the process over again
                    preparse['taken'].extend(params['preparse']['taken'])
                    params['preparse']['taken'] = []
            #Adjust the offset
            offset = len(returnvalue) - len(temp)
            if not params['parse']:
                break
        debug['return'] = returnvalue
        if params['config']['preparse']:
            for value in params['stack']:
                preparse['taken'].append((
                    value['position'],
                    value['position'] + len(value['open'])
                ))
            returnvalue = {
                'ignored': preparse['ignored'],
                'nodes': params['preparse']['nodes'],
                'return': returnvalue,
                'taken': preparse['taken']
            }
            debug['preparse'] = preparse
        #If a label was provided, log this function
        if 'label' in config:
            self.debug['parse'].append(debug)
        return returnvalue

    def parsecache(self, nodes, returnvalue, config):
        """Generate the cache key for the parse function"""
        values = []
        for value in nodes.items():
            array = [value[0]]
            if 'close' in value[1]:
                array.append(value[1]['close'])
            values.append(array)
        return hash((
                returnvalue,
                pickle.dumps(values),
                pickle.dumps(config['taken'])
        ))

    def parseconfig(self, config):
        """Populate the config for the parse function"""
        if config == None:
            config = {}
        if not 'escape' in config:
            config['escape'] = '\\'
        if not 'insensitive' in config:
            config['insensitive'] = True
        if not 'preparse' in config:
            config['preparse'] = False
        if not 'taken' in config:
            config['taken'] = {}
        return config

    def parsepositions(self, nodes, returnvalue, taken, insensitive):
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
            'insensitive': insensitive,
            'pos': {},
            'repeated': [],
            'return': returnvalue,
            'strings': strings,
            'taken': []
        }
        params['taken'].extend(taken)
        return self.positions(params)

    def parseunescape(self, position, escape, string):
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
            params['insensitive'],
            params['function']
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
            position = self.strpos(
                params['return'],
                params['value'][0],
                position + 1,
                params['insensitive'],
                params['function']
            )
        return params

    def preparse(self, params):
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
        #Only continue if the call specifies to preparse
        if not params['config']['preparse']:
            return params
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
            params['preparse']['taken'].append((
                params['open']['position'],
                params['last']
            ))
        return params

    def stack(self, node, openingstring, position):
        """Add the opening string to the stack"""
        #Add the opening string to the stack
        clone = node.copy()
        if 'function' in clone:
            clone['function'] = clone['function'][:]
        stack = [
            {
                'node': clone,
                'open': openingstring,
                'position': position
            }
        ]
        skipnode = []
        #If the skip key is true, skip over everything between this opening
        #string and its closing string
        if 'skip' in node and node['skip']:
            skipnode.append(node)
        return {
            'stack': stack,
            'skipnode': skipnode
        }

    def strpos(self, haystack, needle, offset, insensitive, function):
        """Find the position insensitively or sensitively based on the
        configuration"""
        #If a function name was provided,
        #increment the number of times that the function called strpos by 1
        if function != None:
            self.debug['strpos'][function]['call'] += 1
        #Find the position insensitively or sensitively based on the
        #configuration
        if insensitive:
            return haystack.upper().find(needle.upper(), offset)
        else:
            return haystack.find(needle, offset)

    def transform(self, params):
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
            #If either this does not contain a ignored node or the node does
            #not transform the case
            if (
                success or
                (
                    'transform' in params['open']['node'] and
                    not params['open']['node']['transform']
                )
            ):
                params['suit'] = self
                if 'var' in params['open']['node']:
                    params['var'] = params['open']['node']['var']
                for value in params['open']['node']['function']:
                    #Transform the string in between the opening and closing
                    #strings.
                    params = value(params)
                    if not params['function']:
                        break
                params['case'] = str(params['case'])
                start = params['position'] + len(
                    params['open']['node']['close']
                )
                #Replace everything including and between the opening and
                #closing strings with the transformed string
                params['return'] = ''.join((
                    params['return'][0:params['open']['position']],
                    params['case'],
                    params['return'][start:len(params['return'])]
                ))
                params['last'] = params['open']['position'] + len(
                    params['case']
                )
                params = self.preparse(params)
        return params