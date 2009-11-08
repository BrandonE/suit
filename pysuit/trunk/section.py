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
import nodes

class Section:
    """Functions that generate nodes or use custom ones"""
    def __init__(self, owner):
        self.owner = owner

    def condition(self, ifstring, boolean, elsestring = None, config = None):
        """http://www.suitframework.com/docs/condition"""
        returnvalue = {}
        if config == None:
            config = {}
        if not 'open' in config:
            config['open'] = self.owner.config['parse']['section']['open']
        if not 'close' in config:
            config['close'] = self.owner.config['parse']['section']['close']
        if not 'end' in config:
            config['end'] = self.owner.config['parse']['section']['end']
        if not 'trim' in config:
            config['trim'] = self.owner.config['parse']['section']['trim']
        ifstring = str(ifstring)
        config['open'] = str(config['open'])
        config['close'] = str(config['close'])
        config['end'] = str(config['end'])
        config['trim'] = str(config['trim'])
        #Add the if node
        returnvalue[
            ''.join((
                config['open'],
                ifstring,
                config['close']
            ))
        ] = {
            'close': ''.join((
                config['open'],
                config['end'],
                ifstring,
                config['close']
            )),
            'function': nodes.condition,
            'skip': not boolean, #If the string will be removed, there is no
            #reason to parse in between the opening and closing strings
            'strip': True, #If this boolean is true, the node strips
            #the opening and closing string
            'var':
            {
                'bool': boolean,
                'trim': config['trim']
            } #The string will be used by the function
        }
        #If an else statement is provided
        if elsestring != None:
            elsestring = str(elsestring)
            #Add the else node
            returnvalue[
                ''.join((
                    config['open'],
                    elsestring,
                    config['close']
                ))
            ] = {
                'close': ''.join((
                    config['open'],
                    config['end'],
                    elsestring,
                    config['close']
                )),
                'function': nodes.condition,
                'skip': boolean, #If the string will be removed, there is no
                #reason to parse in between the opening and closing strings
                'strip': True, #If this boolean is false, the node strips the
                #opening and closing string
                'var':
                {
                    'bool': not boolean,
                    'trim': config['trim']
                } #The string will be used by the function
            }
        return returnvalue

    def get(self, string, content, config = None):
        """http://www.suitframework.com/docs/get"""
        if config == None:
            config = {}
        if not 'open' in  config:
            config['open'] = self.owner.config['parse']['section']['open']
        if not 'close' in config:
            config['close'] = self.owner.config['parse']['section']['close']
        if not 'end' in config:
            config['end'] = self.owner.config['parse']['section']['end']
        if not 'escape' in config:
            config['escape'] = self.owner.config['parse']['escape']
        string = str(string)
        content = str(content)
        config['open'] = str(config['open'])
        config['close'] = str(config['close'])
        config['end'] = str(config['end'])
        config['escape'] = str(config['escape'])
        node = {
            ''.join((
                config['open'],
                string,
                config['close']
            )):
            {
                'close': ''.join((
                    config['open'],
                    config['end'],
                    string,
                    config['close']
                )),
                'function': nodes.getsection,
                'var':
                {
                    'open': ''.join((
                        config['open'],
                        string,
                        config['close']
                    )),
                    'close': ''.join((
                        config['open'],
                        config['end'],
                        string,
                        config['close']
                    ))
                } #The string will be used by the function
            }
        }
        self.owner.extra['sections'] = []
        #Unescape when applicable, and populate sections with the inside of
        #each section
        content = self.owner.parse(node, content, config)
        return {
            'content': content,
            'sections': self.owner.extra['sections']
        }

    def loop(self, string, array, implode = '', config = None):
        """http://www.suitframework.com/docs/loop"""
        if config == None:
            config = {}
        if not 'open' in config:
            config['open'] = self.owner.config['parse']['section']['open']
        if not 'close' in config:
            config['close'] = self.owner.config['parse']['section']['close']
        if not 'end' in config:
            config['end'] = self.owner.config['parse']['section']['end']
        if not 'loopopen' in config:
            config['loopopen'] = self.owner.config['parse']['loop']['open']
        if not 'loopclose' in config:
            config['loopclose'] = self.owner.config['parse']['loop']['close']
        if not 'separator' in config:
            config['separator'] = self.owner.config['parse']['separator']
        if not 'trim' in config:
            config['trim'] = self.owner.config['parse']['section']['trim']
        string = str(string)
        config['open'] = str(config['open'])
        config['close'] = str(config['close'])
        config['end'] = str(config['end'])
        config['loopopen'] = str(config['loopopen'])
        config['loopclose'] = str(config['loopclose'])
        config['separator'] = str(config['separator'])
        config['trim'] = str(config['trim'])
        returnvalue = {
            ''.join((
                config['open'],
                string,
                config['close']
            )):
            {
                'close': ''.join((
                    config['open'],
                    config['end'],
                    string,
                    config['close']
                )),
                'function': nodes.loop,
                'skip': True, #We want the function to run the parse, so there
                #is no reason to parse in between the opening and closing
                #strings
                'var':
                {
                    'array': array,
                    'config': config,
                    'implode': implode
                } #This will be used by the function
            }
        }
        return returnvalue