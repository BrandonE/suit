#**@This program is free software: you can redistribute it and/or modify
#**@it under the terms of the GNU General Public License as published by
#**@the Free Software Foundation, either version 3 of the License, or
#**@(at your option) any later version.
#**@This program is distributed in the hope that it will be useful,
#**@but WITHOUT ANY WARRANTY; without even the implied warranty of
#**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#**@GNU General Public License for more details.
#**@You should have received a copy of the GNU General Public License
#**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

#Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
#http://www.suitframework.com/
#http://www.suitframework.com/docs/credits

"""
SUIT Framework (Scripting Using Integrated Templates) allows developers to
define their own syntax for transforming templates by using rules.

Example usage:

import suit
from rulebox import templating #easy_install rulebox
templating.var.username = 'Brandon'
print suit.execute(
    templating.rules,
    'Hello, [var]username[/var]!'
) #Hello, Brandon!
"""

from hashlib import md5
try:
    import simplejson as json
except ImportError:
    import json

__all__ = [
    'cache', 'close', 'evalrules', 'execute', 'log', 'loghash', 'notclosed',
    'parse', 'ruleitems', 'tokens', 'walk'
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

def close(append, pop, rules, tree, skipstack):
    """Close the rule"""
    if not 'create' in rules[pop['rule']]:
        #If the inner string is not empty, add it to the rule
        if append:
            pop['contents'].append(append)
        #Add the rule to the tree
        if notclosed(tree):
            pop2 = tree.pop()
            pop2['contents'].append(pop)
            pop = pop2
        tree.append(pop)
    else:
        create = rules[pop['rule']]['create']
        append = {
            'contents': [],
            'create': append,
            'createrule': ''.join((
                pop['rule'],
                append,
                rules[pop['rule']]['close']
            )),
            'rule': create
        }
        tree.append(append)
        #If the skip key is true, skip over everything between this opening
        #string and its closing string
        if ('skip' in rules[create] and rules[create]['skip']):
            skipstack.append(rules[create])
    return {
        'skipstack': skipstack,
        'tree': tree
    }

def configitems(config, items):
    """Get the specified items from the config"""
    newconfig = {}
    for value in items:
        if value in config:
            newconfig[value] = config[value]
    return newconfig

def defaultconfig(config):
    """Set the default config"""
    if config == None:
        config = {}
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

def escape(escapestring, position, string):
    """Handle escape strings for this position"""
    count = 0
    #If the escape string is not empty
    if escapestring:
        start = position - len(
            escapestring
        )
        #Count how many escape characters are directly to the left of this
        #position
        while (abs(start) == start and
        string[
            start:
            start + len(escapestring)
        ] == escapestring):
            count += len(escapestring)
            start = position - count - len(
                escapestring
            )
        #Determine how many escape strings are directly to the left of this
        #position
        count = count / len(escapestring)
    #If the number of escape strings directly to the left of this position are
    #odd, the position should be overlooked
    condition = count % 2
    #If the condition is true, (x + 1) / 2 of them should be removed
    if condition:
        count += 1
    #Adjust the position
    position -= len(
        escapestring
    ) * (count / 2)
    #Remove the decided number of escape strings
    string = ''.join((
        string[0:position],
        string[
            position + len(
                escapestring
            ) * (count / 2):
            len(string)
        ]
    ))
    return {
        'condition': condition,
        'position': position,
        'string': string
    }

def execute(rules, string, config = None):
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
    string = walk(rules, tree, config)
    if config['log']:
        pop = log['entries'].pop()
        pop['walk'] = string
        pop = loghash(pop, ('walk',))
        length = len(log['entries'])
        if length:
            log['entries'][length - 1]['entries'].append(pop)
        else:
            log['entries'].append(pop)
    return string

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

def parse(rules, pos, string, config = None):
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
    flat = set([])
    last = 0
    skipoffset = 0
    skipstack = []
    temp = string
    tree = []
    for value in pos:
        #Adjust position to changes in length
        position = value['token']['start'] + len(
            string
        ) - len(temp)
        unescape = escape(config['escape'], position, string)
        escaping = True
        skip = False
        if skipstack:
            escaping = False
            if 'skipescape' in skipstack[
                len(skipstack) - skipoffset - 1
            ]:
                escaping = skipstack[0]['skipescape']
            skip = skipstack.pop()
        #If this is an opening string
        if (
            value['type'] == 'open' or
            (
                value['type'] == 'flat' and
                not value['rule'] in flat
            )
        ):
            #If a value was not popped from skipstack
            if skip == False:
                position = unescape['position']
                string = unescape['string']
                #If this position should not be overlooked
                if not unescape['condition']:
                    #If the inner string is not empty, add it to the tree
                    append = string[last:position]
                    last = position + len(value['rule'])
                    #Add the text to the tree if necessary
                    if notclosed(tree):
                        pop = tree.pop()
                        if append:
                            pop['contents'].append(append)
                        tree.append(pop)
                    elif append:
                        tree.append(append)
                    #Add the rule to the tree
                    append = {
                        'contents': [],
                        'rule': value['rule']
                    }
                    tree.append(append)
                    #If the skip key is true, skip over everything between this
                    #opening string and its closing string
                    if ('skip' in rules[value['rule']] and
                    rules[value['rule']]['skip']):
                        skipstack.append(rules[value['rule']])
                    flat.add(value['rule'])
            else:
                #Put it back
                skipstack.append(skip)
                skipclose = [rules[value['rule']]['close']]
                if 'create' in rules[value['rule']]:
                    create = rules[value['rule']]['create']
                    skipclose.append(rules[create]['close'])
                #If the closing string for this rule matches it
                if skip['close'] in skipclose:
                    #If it explictly says to escape
                    if (escaping):
                        position = unescape['position']
                        string = unescape['string']
                    #If this position should not be overlooked
                    if not unescape['condition']:
                        #Account for it
                        skipstack.append(skip)
                        skipoffset += 1
        else:
            #If a value was not popped or the closing string for this rule
            #matches it
            if (skip == False or value['rule'] == skip['close']):
                #If it explictly says to escape
                if escaping:
                    position = unescape['position']
                    string = unescape['string']
                #If this position should not be overlooked
                if not unescape['condition']:
                    #If there is an offset, decrement it
                    if skipoffset:
                        skipoffset -= 1
                    elif notclosed(tree):
                        pop = tree.pop()
                        #If this closing string matches the last rule's or it
                        #explicitly says to execute a mismatched case
                        if (rules[pop['rule']]['close'] == value['rule'] or
                        config['mismatched']):
                            pop['closed'] = True
                            result = close(
                                string[last:position],
                                pop,
                                rules,
                                tree,
                                skipstack
                            )
                            skipstack = result['skipstack']
                            tree = result['tree']
                            flat.discard(value['rule'])
                            last = position + len(value['rule'])
                        #Else, put the string back
                        else:
                            if notclosed(tree):
                                pop2 = tree.pop()
                                pop2['contents'].append(pop['rule'])
                                for value in pop['contents']:
                                    pop2['contents'].append(value)
                                tree.append(pop2)
                            else:
                                tree.append(pop['rule'])
                                for value in pop['contents']:
                                    tree.append(value)
            #Else, put the popped value back
            else:
                skipstack.append(skip)
    string = string[last:len(string)]
    #If the ending string is not empty, add it to the tree
    if string:
        if notclosed(tree):
            pop = tree.pop()
            position = len(string)
            tree = close(string, pop, rules, tree, skipstack)['tree']
        else:
            tree.append(string)
    tree = {
        'contents': tree
    }
    #Cache the tree
    dumped = json.dumps(tree, separators = (',', ':'))
    hashkey = md5(dumped).hexdigest()
    cache['hash'][hashkey] = dumped
    cache['parse'][cachekey] = hashkey
    return tree

def ruleitems(rules, items):
    """Get the specified items from the rules"""
    newrules = {}
    for key, value in rules.items():
        newrules[key] = {}
        for value2 in items:
            if value2 in value:
                newrules[key][value2] = value[value2]
    return newrules

def tokens(rules, string, config = None):
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
    pos = []
    strings = []
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
                for value2 in pos:
                    token = value2['token']
                    #If this string instance is in this reserved range
                    if (
                        (
                            position >= token['start'] and
                            position < token['end']
                        ) or
                        (
                            position + len(value['rule']) > token['start'] and
                            position + len(value['rule']) < token['end']
                        )
                    ):
                        success = False
                        break
                #If this string instance is not in any reserved range
                if success:
                    #Add the position
                    pos.append(
                        {
                            'rule': value['rule'],
                            'token':
                            {
                                'start': position,
                                'end': position + len(value['rule'])
                            },
                            'type': value['type']
                        }
                    )
                #Find the next position of the string
                position = string.find(value['rule'], position + 1)
    #Order the positions from smallest to biggest
    pos.sort(key = lambda item: item['token']['start'])
    #Cache the positions
    dumped = json.dumps(pos, separators = (',', ':'))
    hashkey = md5(dumped).hexdigest()
    cache['hash'][hashkey] = dumped
    cache['tokens'][cachekey] = hashkey
    return pos

def walk(rules, tree, config = None):
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
                    string += unicode(params['string'])
            #Else, execute it, ignoring the original opening string, with no
            #rule
            else:
                if 'rule' in value:
                    string += value['rule']
                string += walk(rules, value, config)
        else:
            string += value
    return string