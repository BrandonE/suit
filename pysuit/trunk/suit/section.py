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

class Section(object):
    """Functions that generate nodes or use custom ones"""
    def __init__(self, owner):
        self.owner = owner
        self.sections = []

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
                'function': [nodes.getsection],
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
        self.sections = []
        #Unescape when applicable, and populate sections with the inside of
        #each section
        content = self.owner.parse(node, content, config)
        return {
            'content': content,
            'sections': self.sections
        }