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
    'cache', 'cacherules', 'close', 'closingstring', 'escape', 'evalrules',
    'execute', 'explodeunescape', 'functions', 'log', 'notclosed', 'MyClass',
    'openingstring', 'parse', 'positions', 'positionsloop', 'rules',
    'Singleton', 'skip', 'tokens', 'walk', 'walkarray'
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

class Singleton(type):
    """Singleton implementation"""
    def __init__(mcs, name, bases, dictionary):
        super(Singleton, mcs).__init__(name, bases, dictionary)
        mcs.instance = None

    def __call__(mcs, *args, **kw):
        if mcs.instance is None:
            mcs.instance = super(Singleton, mcs).__call__(*args, **kw)
        return mcs.instance

class MyClass(object):
    """Wrapper for Singleton"""
    __metaclass__ = Singleton

var = MyClass()

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
        append = {
            'contents': [],
            'create': append,
            'rule': params['rules'][pop['rule']]['create']
        }
        params['tree'].append(append)
        params['skipstack'] = skip(
            params['rules'][params['rules'][pop['rule']]['create']],
            params['skipstack']
        )
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

def execute(rules, string, config = {}):
    """Translate string using rules"""
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
    cachekey = md5(
            json.dumps(
            (
                string,
                ruleitems(rules, ('close')),
                config['insensitive']
            ),
            separators = (',', ':')
        )
    ).hexdigest()
    #If positions are cached for this case, load them
    if cachekey in cache['tokens']:
        pos = cache['hash'][cache['tokens'][cachekey]]
    else:
        pos = tokens(rules, string, config)
        #Cache the positions
        hashkey = md5(json.dumps(pos, separators = (',', ':'))).hexdigest()
        cache['hash'][hashkey] = pos
        cache['tokens'][cachekey] = hashkey
    cachekey = md5(
        json.dumps(
            (
                string,
                ruleitems(rules, ('close', 'create', 'skip')),
                config['insensitive'],
                config['escape'],
                config['mismatched']
            ),
            separators = (',', ':')
        )
    ).hexdigest()
    #If a tree is cached for this case, load it
    if cachekey in cache['parse']:
        tree = cache['hash'][cache['parse'][cachekey]]
    else:
        tree = {
            'contents': parse(rules, string, config, pos)
        }
        if '' in rules:
            tree['rule'] = ''
        #Cache the tree
        hashkey = md5(json.dumps(tree, separators = (',', ':'))).hexdigest()
        cache['hash'][hashkey] = tree
        cache['parse'][cachekey] = hashkey
    if config['log']:
        key = len(log['entries'])
        log['entries'].append({
            'config': config,
            'parse': tree,
            'rules': ruleitems(rules, ('close', 'create', 'skip')),
            'string': string,
            'tokens': pos
        })
    result = walk(rules, tree, config)['string']
    if config['log']:
        log['entries'][key]['walk'] = result
        log['entries'][key] = loghash(log['entries'][key])
    return result

def functions(params, function):
    """Run through the provided functions"""
    for value in function:
        params = value(params)
        if not params['function']:
            break
    return params

def loghash(entry):
    """Hash the keys for logging"""
    for key, value in entry.items():
        hashkey = md5(json.dumps(value, separators = (',', ':'))).hexdigest()
        log['hash'][hashkey] = value
        entry[key] = hashkey
    return entry

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
            params['skipstack'] = skip(
                params['rules'][params['rule']],
                params['skipstack']
            )
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

def parse(rules, string, config, pos):
    """Generate the tree for execute"""
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
    return params['tree']

def positions(params):
    """Find the positions of strings"""
    params['taken'] = []
    if params['insensitive']:
        params['string'] = params['string'].lower()
    for params['key'], params['value'] in params['strings']:
        #If the string has not already been used
        if not params['key'] in params['repeated']:
            params = positionsloop(params)
            #Make sure this string is not repeated
            params['repeated'].append(params['key'])
    return params['pos']

def positionsloop(params):
    """Handle the loop to find the positions of strings"""
    if not params['key']:
        return params
    needle = params['key']
    if params['insensitive']:
        needle = needle.lower()
    position = params['string'].find(needle)
    while position != -1:
        success = True
        for value in params['taken']:
            #If this string instance is in this reserved range
            if ((
                position >= value['start'] and
                position < value['end']
            ) or
            (
                position + len(params['key']) > value['start'] and
                position + len(params['key']) < value['end']
            )):
                success = False
                break
        #If this string instance is not in any reserved range
        if success:
            #Add the position
            params['pos'][position] = params['value']
            #Reserve all positions taken up by this string instance
            params['taken'].append({
                'start': position,
                'end': position + len(params['key'])
            })
        #Find the next position of the string
        position = params['string'].find(needle, position + 1)
    return params

def ruleitems(rules, items):
    """Get the specified items from the rules"""
    newrules = {}
    for key, value in rules.items():
        newrules[key] = {}
        for value2 in items:
            if value2 in value:
                newrules[key][value2] = value[value2]
    return newrules

def skip(rule, skipstack):
    """Skip parsing if necessary"""
    #If the skip key is true, skip over everything between this opening string
    #and its closing string
    if 'skip' in rule and rule['skip']:
        skipstack.append(rule)
    return skipstack

def tokens(rules, string, config):
    """Generate the tokens for execute"""
    strings = {}
    for key, value in rules.items():
        if 'close' in value and key == value['close']:
            strings[key] = {
                'rule': key,
                'type': 'flat'
            }
        else:
            strings[key] = {
                'rule': key,
                'type': 'open'
            }
            if 'close' in value:
                strings[value['close']] = {
                    'rule': value['close'],
                    'type': 'close'
                }
    strings = strings.items()
    #Order the strings by the length, descending
    strings.sort(key = lambda item: len(item[0]), reverse = True)
    params = {
        'insensitive': config['insensitive'],
        'pos': {},
        'repeated': [],
        'string': string,
        'strings': strings
    }
    #Order the positions from smallest to biggest
    return sorted(positions(params).items())

def walk(rules, tree, config, recursed = False):
    """Walk through the tree"""
    if not recursed:
        tree = copy.deepcopy(tree)
    params = {
        'config': config,
        'function': True,
        'rules': rules,
        'returnvar': None,
        'returnedvar': None,
        'returnfunctions': [],
        'string': '',
        'tree': tree,
        'walk': True
    }
    if ('rule' in params['tree'] and
    'var' in params['rules'][params['tree']['rule']]):
        params['var'] = params['rules'][params['tree']['rule']]['var']
    if 'create' in params['tree']:
        params['create'] = params['tree']['create']
    if ('rule' in params['tree'] and
    'prewalk' in params['rules'][params['tree']['rule']]):
        #Run the functions meant to be executed before walking through the tree
        params = functions(
            params,
            params['rules'][params['tree']['rule']]['prewalk']
        )
    for value in enumerate(params['tree']['contents']):
        if not params['walk']:
            break
        if isinstance(params['tree']['contents'][value[0]], dict):
            params = walkarray(params, value[0])
        else:
            params['string'] += params['tree']['contents'][value[0]]
    if ('rule' in params['tree'] and
    'postwalk' in params['rules'][params['tree']['rule']]):
        params['function'] = True
        #Transform the case with the specified functions
        params = functions(
            params,
            params['rules'][params['tree']['rule']]['postwalk']
        )
    params['string'] = str(params['string'])
    return {
        'string': params['string'],
        'functions': params['returnfunctions'],
        'tree': params['tree'],
        'var': params['returnvar']
    }

def walkarray(params, key):
    """Recurse through the branch"""
    #If the tag has been closed or it explicitly says to execute unopened
    #strings, walk through the contents with its rule
    if (
        params['config']['unclosed'] or
        (
            'closed' in params['tree']['contents'][key] and
            params['tree']['contents'][key]['closed']
        )
    ):
        result = walk(
            params['rules'],
            params['tree']['contents'][key],
            params['config'],
            True
        )
        params['tree']['contents'][key] = result['tree']
        params['string'] += result['string']
        #Run the functions that have been returned
        params['key'] = key
        params['returnedvar'] = result['var']
        params = functions(params, result['functions'])
        del params['key']
        del params['returnedvar']
    #Else, execute it, ignoring the original opening string, with no rule
    else:
        tree = {
            'contents': params['tree']['contents'][key]['contents']
        }
        result = walk(params['rules'], tree, params['config'], True)
        if 'rule' in params['tree']['contents'][key]:
            params['string'] += params['tree']['contents'][
                key
            ]['rule']
        params['string'] += result['string']
    return params