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
import inspect
import os
import pickle
import helper
import section

__version__ = '0.0.2'

class SUIT:
    """An open-source templating framework that allows you to define your own
    syntax through nodes."""
    def __init__(self, config):
        """http://www.suitframework.com/docs/SUIT+Construct"""
        self.helper = helper.Helper(self)
        self.section = section.Section(self)
        self.config = config
        self.template = ''
        self.extra = {
            'cache':
            {
                'escape': {},
                'explodeunescape': {},
                'parse': {}
            },
            'offset': 0,
            'sections': []
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
        self.vars = {}

    def escape(self, strings, returnvalue, escape = None):
        """http://www.suitframework.com/docs/escape"""
        if escape == None:
            escape = self.config['parse']['escape']
        cache = hash((returnvalue, pickle.dumps(strings)))
        #If positions are cached for this case, load them
        if cache in self.extra['cache']['escape']:
            pos = self.extra['cache']['escape'][cache]
            self.debug['strpos']['escape']['cache'] += 1
        else:
            #Order the strings by length, descending
            strings.sort(key = lambda item: not len(item), reverse = True)
            params = {
                'function': 'escape',
                'key': None,
                'pos': {},
                'repeated': [],
                'return': returnvalue,
                'strings': strings,
                'taken': []
            }
            pos = self.helper.positions(params)['pos']
            #On top of the strings to be escaped, the last position in the
            #string should be checked for escape strings
            pos[len(returnvalue)] = None
            #Order the positions from smallest to biggest
            pos = sorted(pos.items())
            #Cache the positions
            self.extra['cache']['escape'][cache] = pos
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

    def explodeunescape(self, explode, glue, escape = None):
        """http://www.suitframework.com/docs/explodeunescape"""
        array = []
        if escape == None:
            escape = self.config['parse']['escape']
        cache = hash((glue, explode))
        #If positions are cached for this case, load them
        if cache in self.extra['cache']['explodeunescape']:
            pos = self.extra['cache']['explodeunescape'][cache]
            self.debug['strpos']['explodeunescape']['cache'] += 1
        else:
            pos = []
            position = self.helper.strpos(glue, explode, 0, 'explodeunescape')
            #Find the next position of the string
            while position != -1:
                pos.append(position)
                position = self.helper.strpos(
                    glue,
                    explode,
                    position + 1,
                    'explodeunescape'
                )
            #On top of the explode string to be escaped, the last position in
            #the string should be checked for escape strings
            pos.append(len(glue))
            #Cache the positions
            self.extra['cache']['explodeunescape'][cache] = pos
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
        """http://www.suitframework.com/docs/gettemplate"""
        backtrace = inspect.stack()
        debug = {
            'code': [],
            'file': backtrace[1][2],
            'label': label,
            'line': backtrace[1][3],
            'template': returnvalue
        }
        if code:
            for value in code:
                #If the code file exists
                if os.path.exists(value):
                    debug['code'].append([
                        value,
                        True,
                        False
                    ])
                    last = len(debug['code']) - 1
                    suit = self
                    suit.template = returnvalue
                    #Execute the code file and set the return value to the
                    #modified template
                    execfile(value)
                    returnvalue = suit.template
                    debug['code'][last] = [
                        debug['code'][last][1],
                        debug['code'][last][2],
                        returnvalue
                    ]
                else:
                    debug['code'].append([
                        value,
                        False,
                        returnvalue
                    ])
        #If a label was provided, log this function
        if label != None:
            self.debug['parse'].append(debug)
        return returnvalue

    def parse(self, nodes, returnvalue, config = None):
        """http://www.suitframework.com/docs/parse"""
        debug = inspect.stack()
        debug = {
                'before': returnvalue,
                'file': debug[1][2],
                'line': debug[1][3],
                'return': ''
            }
        config = self.helper.parseconfig(config)
        if 'label' in config:
            debug['label'] = config['label']
        #Order the nodes by the length of the opening string, descending
        iteratenodes = sorted(nodes.items(), reverse = True)
        cache = helper.parsecache(iteratenodes, returnvalue, config)
        #If positions are cached for this case, load them
        if cache in self.extra['cache']['parse']:
            pos = self.extra['cache']['parse'][cache]
            self.debug['strpos']['parse']['cache'] += 1
        else:
            pos = self.helper.parsepositions(
                iteratenodes,
                returnvalue,
                config['taken']
            )
            #Order the positions from smallest to biggest
            pos = sorted(pos.items())
            #Cache the positions
            self.extra['cache']['parse'][cache] = pos
        preparse = {
            'taken': [],
            'ignored': []
        }
        params = {
            'ignored': [],
            'skipnode': [],
            'stack': [],
            'taken': []
        }
        offset = 0
        temp = returnvalue
        for value in pos:
            #Adjust position to changes in length
            position = value[0] + offset
            params = {
                'escape': config['escape'],
                'ignored': params['ignored'],
                'node': value[1][0],
                'nodes': iteratenodes,
                'position': position,
                'preparse': config['preparse'],
                'realnodes': nodes,
                'return': returnvalue,
                'skipnode': params['skipnode'],
                'stack': params['stack'],
                'taken': params['taken']
            }
            function = self.helper.closingstring
            #If this is the opening string and it should not be skipped over
            if value[1][1] == 0:
                function = self.helper.openingstring
            params = function(params)
            returnvalue = params['return']
            #If the stack is empty
            if not params['stack']:
                #It is impossible that a skipped over node is in another node
                preparse['ignored'].extend(params['ignored'])
                params['ignored'] = []
                #If we are preparsing
                if config['preparse']:
                    #The ranges can not be inside another node, so permanently
                    #reserve it and start the process over again
                    preparse['taken'].extend(params['taken'])
                    params['taken'] = []
            #Adjust the offset
            offset = len(returnvalue) - len(temp)
        debug['return'] = returnvalue
        if config['preparse']:
            returnvalue = {
                'return': returnvalue,
                'taken': params['taken']
            }
            debug['preparse'] = preparse
        #If a label was provided, log this function
        if 'label' in config:
            self.debug['parse'].append(debug)
        return returnvalue

    def parseunescape(self, pos, string, escape = None):
        """http://www.suitframework.com/docs/parseunescape"""
        if escape == None:
            escape = self.config['parse']['escape']
        count = 0
        #If the escape string is not empty
        if escape:
            start = pos - len(escape)
            #Count how many escape characters are directly to the left of this
            #position
            while (abs(start) == start and
            string[start:start + len(escape)] == escape):
                count += len(escape)
                start = pos - count - len(escape)
            #Determine how many escape strings are directly to the left of this
            #position
            count = count / len(escape)
        #If the number of escape strings directly to the left of this position
        #are odd, the position should be overlooked
        condition = count % 2
        #If the number of escape strings directly to the left of this position
        #are odd, (x + 1) / 2 of them should be removed
        if condition:
            count += 1
        #Adjust the position
        pos -= len(escape) * (count / 2)
        #Remove the decided number of escape strings
        string = ''.join((
            string[0:pos],
            string[pos + len(escape) * (count / 2):len(string)]
        ))
        return {
            'condition': condition,
            'pos': pos,
            'string': string
        }