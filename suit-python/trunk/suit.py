"""
**@This program is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@This program is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
"""
import copy
from hashlib import md5
try:
    import simplejson as json
except ImportError:
    import json

__all__ = [
    'cache', 'close', 'closingstring', 'compile', 'evalrules', 'execute',
    'functions', 'log', 'loghash', 'notclosed', 'MyClass', 'openingstring',
    'parse', 'positions', 'positionsloop', 'ruleitems', 'Singleton', 'skip',
    'tokens', 'walk'
]

__version__ = '2.0.0'

cache = {
    'hash': {},
    'parse': {},
    'tokens': {}
}

log = {
    'hash': {},
    'entries': []
}

class Class():
    pass

var = Class()

def close(params, pop, closed):
    """Close the rule"""
    append = params['string'][params['last']:params['position']]
    if not 'create' in params['rules'][pop['rule']]:
        pop['closed'] = closed
        #If the inner string is not empty, add it to the rule
        if append:
            pop['contents'].append(append)
        #Add the rule to the tree
        if notclosed(params['tree']):
            pop2 = params['tree'].pop()
            pop2['contents'].append(pop)
            pop = pop2
        params['tree'].append(pop)
        params['flat'].discard(params['rule'])
    else:
        create = params['rules'][pop['rule']]['create']
        append = {
            'contents': [],
            'create': append,
            'createrule': ''.join((
                pop['rule'],
                append,
                params['rules'][pop['rule']]['close']
            )),
            'rule': create
        }
        params['tree'].append(append)
        #If the skip key is true, skip over everything between this opening
        #string and its closing string
        if ('skip' in params['rules'][create] and
        params['rules'][create]['skip']):
            params['skipstack'].append(params['rules'][create])
    params['last'] = params['position'] + len(params['rule'])
    return params

def closingstring(params):
    """Handle a closing string instance in the parser"""
    #If a value was not popped or the closing string for this rule matches it
    if (params['skip'] == False or
    params['rule'] == params['skip']['close']):
        #If it explictly says to escape
        if params['escaping']:
            params['position'] = params['unescape']['position']
            params['string'] = params['unescape']['string']
        #If this position should not be overlooked
        if not params['unescape']['condition']:
            #If there is an offset, decrement it
            if params['skipoffset']:
                params['skipoffset'] -= 1
            elif notclosed(params['tree']):
                pop = params['tree'].pop()
                #If this closing string matches the last rule's or it
                #explicitly says to execute a mismatched case
                if (params['rules'][
                    pop['rule']
                ]['close'] == params['rule'] or
                params['config']['mismatched']):
                    params = close(params, pop, True)
                #Else, put the string back
                else:
                    if notclosed(params['tree']):
                        pop2 = params['tree'].pop()
                        pop2['contents'].append(pop['rule'])
                        for value in pop['contents']:
                            pop2['contents'].append(value)
                        params['tree'].append(pop2)
                    else:
                        params['tree'].append(pop['rule'])
                        for value in pop['contents']:
                            params['tree'].append(value)
    #Else, put the popped value back
    else:
        params['skipstack'].append(params['skip'])
    return params

def configitems(config, items):
    """Get the specified items from the config"""
    newconfig = {}
    for value in items:
        if value in config:
            newconfig[value] = config[value]
    return newconfig

def defaultconfig(config):
    """Set the default config"""
    if not 'escape' in config:
        config['escape'] = '\\'
    if not 'insensitive' in config:
        config['insensitive'] = True
    if not 'log' in config:
        config['log'] = True
    if not 'mismatched' in config:
        config['mismatched'] = False
    if not 'unclosed' in config:
        config['unclosed'] = False
    return config

def execute(rules, string, config = {}):
    """Translate string using rules"""
    config = defaultconfig(config)
    pos = tokens(rules, string, config)
    tree = parse(rules, pos, string, config)
    if config['log']:
        log['entries'].append(
            loghash(
                {
                    'config': config,
                    'entries': [],
                    'parse': tree,
                    'rules': ruleitems(rules, ('close', 'create', 'skip')),
                    'string': string,
                    'tokens': pos
                },
                ('config', 'parse', 'rules', 'string', 'tokens')
            )
        )
    result = walk(rules, tree, config)
    if config['log']:
        pop = log['entries'].pop()
        pop['walk'] = result
        pop = loghash(pop, ('walk',))
        length = len(log['entries'])
        if length:
            log['entries'][length - 1]['entries'].append(pop)
        else:
            log['entries'].append(pop)
    return result

def loghash(entry, items):
    """Hash the keys for logging"""
    newlog = {}
    for key, value in entry.items():
        if key in items:
            dumped = json.dumps(
                value,
                separators = (',', ':')
            )
            hashkey = md5(dumped).hexdigest()
            log['hash'][hashkey] = dumped
            value = hashkey
        newlog[key] = value
    return newlog

def notclosed(tree):
    """Check whether or not the last item is a closed rule"""
    #If the tree is not empty and the last item is an array and has not been
    #closed
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
    #If a value was not popped from skipstack
    if params['skip'] == False:
        params['position'] = params['unescape']['position']
        params['string'] = params['unescape']['string']
        #If this position should not be overlooked
        if not params['unescape']['condition']:
            #If the inner string is not empty, add it to the tree
            append = params['string'][params['last']:params['position']]
            params['last'] = params['position'] + len(params['rule'])
            #Add the text to the tree if necessary
            if notclosed(params['tree']):
                pop = params['tree'].pop()
                if append:
                    pop['contents'].append(append)
                params['tree'].append(pop)
            elif append:
                params['tree'].append(append)
            #Add the rule to the tree
            append = {
                'contents': [],
                'rule': params['rule']
            }
            params['tree'].append(append)
            #If the skip key is true, skip over everything between this opening
            #string and its closing string
            if ('skip' in params['rules'][params['rule']] and
            params['rules'][params['rule']]['skip']):
                params['skipstack'].append(params['rules'][params['rule']])
            params['flat'].add(params['rule'])
    else:
        #Put it back
        params['skipstack'].append(params['skip'])
        skipclose = [params['rules'][params['rule']]['close']]
        if 'create' in params['rules'][params['rule']]:
            create = params['rules'][params['rule']]['create']
            skipclose.append(params['rules'][create]['close'])
        #If the closing string for this rule matches it
        if params['skip']['close'] in skipclose:
            #If it explictly says to escape
            if (params['escaping']):
                params['position'] = params['unescape']['position']
                params['string'] = params['unescape']['string']
            #If this position should not be overlooked
            if not params['unescape']['condition']:
                #Account for it
                params['skipstack'].append(params['skip'])
                params['skipoffset'] += 1
    return params

def parse(rules, pos, string, config = {}):
    """Generate the tree for execute"""
    config = defaultconfig(config)
    cachekey = md5(
        json.dumps(
            (
                string,
                ruleitems(rules, ('close', 'create', 'skip')),
                configitems(config, ('escape', 'insensitive', 'mismatched'))
            ),
            separators = (',', ':')
        )
    ).hexdigest()
    #If a tree is cached for this case, load it
    if cachekey in cache['parse']:
        return json.loads(cache['hash'][cache['parse'][cachekey]])
    params = {
        'config': config,
        'flat': set([]),
        'last': 0,
        'rules': rules,
        'skipstack': [],
        'skipoffset': 0,
        'string': string,
        'temp': string,
        'tree': []
    }
    for key, value in pos:
        #Adjust position to changes in length
        params['rule'] = value['rule']
        params['position'] = key + len(
            params['string']
        ) - len(params['temp'])
        params['unescape'] = {
            'position': params['position'],
            'string': params['string']
        }
        count = 0
        #If the escape string is not empty
        if params['config']['escape']:
            start = params['unescape']['position'] - len(
                params['config']['escape']
            )
            #Count how many escape characters are directly to the left of this
            #position
            while (abs(start) == start and
            params['unescape']['string'][
                start:
                start + len(params['config']['escape'])
            ] == params['config']['escape']):
                count += len(params['config']['escape'])
                start = params['unescape']['position'] - count - len(
                    params['config']['escape']
                )
            #Determine how many escape strings are directly to the left of this
            #position
            count = count / len(params['config']['escape'])
        #If the number of escape strings directly to the left of this position
        #are odd, the position should be overlooked
        params['unescape']['condition'] = count % 2
        #If the condition is true, (x + 1) / 2 of them should be removed
        if params['unescape']['condition']:
            count += 1
        #Adjust the position
        params['unescape']['position'] -= len(
            params['config']['escape']
        ) * (count / 2)
        #Remove the decided number of escape strings
        params['unescape']['string'] = ''.join((
            params['unescape']['string'][0:params['unescape']['position']],
            params['unescape']['string'][
                params['unescape']['position'] + len(
                    params['config']['escape']
                ) * (count / 2):
                len(params['unescape']['string'])
            ]
        ))
        params['escaping'] = True
        params['skip'] = False
        if params['skipstack']:
            params['escaping'] = False
            if 'skipescape' in params['skipstack'][
                len(params['skipstack']) - params['skipoffset'] - 1
            ]:
                params['escaping'] = params['skipstack'][0]['skipescape']
            params['skip'] = params['skipstack'].pop()
        #Run the appropriate function for the string
        function = openingstring
        if (
            value['type'] == 'close' or
            (
                value['type'] == 'flat' and
                params['rule'] in params['flat']
            )
        ):
            function = closingstring
        params = function(params)
    string = params['string'][params['last']:len(params['string'])]
    #If the ending string is not empty, add it to the tree
    if string:
        if notclosed(params['tree']):
            pop = params['tree'].pop()
            params['position'] = len(params['string'])
            params = close(params, pop, False)
        else:
            params['tree'].append(string)
    params['tree'] = {
        'contents': params['tree']
    }
    #Cache the tree
    dumped = json.dumps(params['tree'], separators = (',', ':'))
    hashkey = md5(dumped).hexdigest()
    cache['hash'][hashkey] = dumped
    cache['parse'][cachekey] = hashkey
    return params['tree']

def ruleitems(rules, items):
    """Get the specified items from the rules"""
    newrules = {}
    for key, value in rules.items():
        newrules[key] = {}
        for value2 in items:
            if value2 in value:
                newrules[key][value2] = value[value2]
    return newrules

def tokens(rules, string, config = {}):
    """Generate the tokens for execute"""
    config = defaultconfig(config)
    cachekey = md5(
            json.dumps(
            (
                string,
                ruleitems(rules, ('close')),
                configitems(config, ('insensitive',))
            ),
            separators = (',', ':')
        )
    ).hexdigest()
    #If positions are cached for this case, load them
    if cachekey in cache['tokens']:
        return json.loads(cache['hash'][cache['tokens'][cachekey]])
    pos = {}
    strings = []
    taken = []
    for key, value in rules.items():
        if 'close' in value:
            stringtype = 'flat'
            if key != value['close']:
                stringtype = 'open'
                strings.append({
                    'rule': value['close'],
                    'type': 'close'
                })
            strings.append({
                'rule': key,
                'type': stringtype
            })
    #Order the strings by the length, descending
    strings.sort(key = lambda item: len(item['rule']), reverse = True)
    if config['insensitive']:
        string = string.lower()
    for value in strings:
        if value['rule']:
            if config['insensitive']:
                value['rule'] = value['rule'].lower()
            position = string.find(value['rule'])
            while position != -1:
                success = True
                for value2 in taken:
                    #If this string instance is in this reserved range
                    if ((
                        position >= value2['start'] and
                        position < value2['end']
                    ) or
                    (
                        position + len(value['rule']) > value2['start'] and
                        position + len(value['rule']) < value2['end']
                    )):
                        success = False
                        break
                #If this string instance is not in any reserved range
                if success:
                    #Add the position
                    pos[position] = value
                    #Reserve all positions taken up by this string instance
                    taken.append({
                        'start': position,
                        'end': position + len(value['rule'])
                    })
                #Find the next position of the string
                position = string.find(value['rule'], position + 1)
    #Order the positions from smallest to biggest
    pos = sorted(pos.items())
    #Cache the positions
    dumped = json.dumps(pos, separators = (',', ':'))
    hashkey = md5(dumped).hexdigest()
    cache['hash'][hashkey] = dumped
    cache['tokens'][cachekey] = hashkey
    return pos

def walk(rules, tree, config = {}):
    """Walk through the tree and generate the string"""
    config = defaultconfig(config)
    string = ''
    for key, value in enumerate(tree['contents']):
        if isinstance(value, dict):
            #If the tag has been closed or it explicitly says to execute
            #unopened strings, walk through the contents with its rule
            if (
                config['unclosed'] or
                (
                    'closed' in value and
                    value['closed']
                )
            ):
                params = {
                    'config': config,
                    'rules': rules,
                    'string': '',
                    'tree': value
                }
                params['tree']['key'] = key
                params['tree']['parent'] = tree
                if 'rule' in value and 'functions' in rules[value['rule']]:
                    #Transform the string with the specified functions
                    for value2 in rules[value['rule']]['functions']:
                        params = value2(params)
                    string += str(params['string'])
            #Else, execute it, ignoring the original opening string, with no
            #rule
            else:
                result = walk(rules, value, config)
                if 'rule' in value:
                    string += value['rule']
                string += result
        else:
            string += value
    return string