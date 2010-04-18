# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

# Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
# http://www.suitframework.com/
# http://www.suitframework.com/docs/credits

"""
SUIT Framework (Scripting Using Integrated Templates) allows developers to
define their own syntax for transforming templates by using rules.

-----------------------------
Example Usage
-----------------------------

>>> import suit
>>> from rulebox import templating # easy_install rulebox
>>> template = open('template.tpl').read()
>>> # Template contains "Hello, <strong>[var]username[/var]</strong>!"
>>> templating.var.username = 'Brandon'
>>> print suit.execute(templating.rules, template) # Result: Hello, Brandon!

-----------------------------
Caching and Logging
-----------------------------

Throughout SUIT, two dicts are used by the cache and tokens functions.

**cache**
    Saves processing time by storing the results of these functions.
**log**
    Contains information on how the execute function works.

For both ``log`` and ``cache``, the `hash` key contains the actual data. The
others reference this to deal with redundant items.
"""

__version__ = '2.0.0'

from hashlib import md5
try:
    import simplejson as json
except ImportError:
    import json

__all__ = [
    'cache', 'close', 'closed', 'configitems', 'defaultconfig', 'escape',
    'evalrules', 'execute', 'log', 'loghash', 'parse', 'ruleitems',
    'separators', 'tokens', 'treeappend', 'walk'
]

cache = {
    'hash': {},
    'parse': {},
    'tokens': {}
}

log = {
    'hash': {},
    'entries': []
}

separators = (',', ':')

def close(append, pop, rules, tree, skip):
    """Handle a closed tag"""
    rule = rules[pop['rule']]
    # If this rule does not create other rules
    if not 'create' in rule:
        # If the inner string is not empty, add it to the rule
        if append:
            pop['contents'].append(append)
        tree = treeappend((pop,), tree)
    else:
        # If this node is closed
        if closed(pop):
            create = rule['create']
            # Prepare to append the rule this rule creates
            append = {
                'contents': [],
                # Store the contents inside of the original rule
                'create': append,
                # Store the entire rule
                'createrule': ''.join((
                    pop['rule'],
                    append,
                    rule['close']
                )),
                'rule': create
            }
            # If the skip key is true, skip over everything between this open
            # string and its close string
            if ('skip' in rules[create] and rules[create]['skip']):
                skip = create
        else:
            # Prepare to add the open string
            append = ''.join((
                pop['rule'],
                append
            ))
        tree.append(append)
    return {
        'skip': skip,
        'tree': tree
    }

def closed(node):
    """Check whether or not this item is an dict and has been closed"""
    return (
        not isinstance(node, dict) or
        (
            'closed' in node and
            node['closed']
        )
    )

def configitems(config, items):
    """Get the specified items from the config"""
    newconfig = {}
    for value in items:
        if value in config:
            newconfig[value] = config[value]
    return newconfig

def defaultconfig(config):
    """Return a default config if required keys are not present for a given
    dict
    """
    if config == None:
        config = {}
    if not 'escape' in config:
        config['escape'] = '\\'
    if not 'insensitive' in config:
        config['insensitive'] = True
    # Do you want to log this entry?
    if not 'log' in config:
        config['log'] = True
    # If the close string doesn't match the open string, should it still close?
    if not 'mismatched' in config:
        config['mismatched'] = False
    # If a tag was opened but not closed, should it still walk?
    if not 'unclosed' in config:
        config['unclosed'] = False
    return config

def escape(escapestring, position, string, insensitive = True):
    """Handle escape strings for this position"""
    count = 0
    caseescape = escapestring
    casestring = string
    if insensitive:
        caseescape = caseescape.lower()
        casestring = casestring.lower()
    # If the escape string is not empty
    if escapestring:
        focus = position - len(escapestring)
        # Count how many escape characters are directly to the left of this
        # position
        while (focus == abs(focus) and
        casestring[focus:focus + len(escapestring)] == caseescape):
            count += len(escapestring)
            focus = position - count - len(escapestring)
        # Adjust the count based on the length
        count = count / len(escapestring)
    # If the number of escape strings directly to the left of this position are
    # odd, the position should be overlooked
    odd = count % 2
    # If the count is odd, (x + 1) / 2 of them should be removed
    if odd:
        count += 1
    count = (count / 2)
    # Adjust the position to after the remaining escape strings
    position -= len(escapestring) * count
    # Remove the decided number of escape strings
    string = ''.join((
        string[0:position],
        string[position + len(escapestring) * count:len(string)]
    ))
    return {
        'odd': odd,
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
    """Hash specific keys for logging"""
    newlog = {}
    for key, value in entry.items():
        if key in items:
            dumped = json.dumps(
                value,
                separators=separators
            )
            hashkey = md5(dumped).hexdigest()
            log['hash'][hashkey] = dumped
            value = hashkey
        newlog[key] = value
    return newlog

def parse(rules, pos, string, config = None):
    """Generate the tree for execute"""
    config = defaultconfig(config)
    # Generate a dict key for a given parameters to save to and load from
    # cache. Thus, the cache key will be the same if the parameters are the
    # same
    cachekey = md5(
        json.dumps(
            (
                ruleitems(rules, ('close', 'create', 'skip')),
                pos,
                string,
                configitems(config, ('escape', 'insensitive', 'mismatched'))
            ),
            separators=separators
        )
    ).hexdigest()
    # If a tree is cached for this case, load it
    if cachekey in cache['parse']:
        return json.loads(cache['hash'][cache['parse'][cachekey]])
    # Contains a set of the flat rules that have been opened and not closed
    flat = set([])
    # The position after the last string analyzed
    last = 0
    # The skip rule, if opened
    skip = False
    # How many additional skip rules to account for
    skipoffset = 0
    # The original string
    temp = string
    # The string broken into a tree
    tree = []
    for value in pos:
        # Adjust position to changes in length
        position = value['token']['start'] + len(string) - len(temp)
        escapeinfo = escape(
            config['escape'],
            position,
            string,
            config['insensitive']
        )
        # If no unclosed skip rules have been opened or said rule explicitly
        # says to escape
        escaping = (
            not skip or
            (
                'skipescape' in rules[skip] and
                rules[skip]['skipescape']
            )
        )
        flatopen = (value['type'] == 'flat' and not value['rule'] in flat)
        # If this is an open string
        if value['type'] == 'open' or flatopen:
            rule = rules[value['rule']]
            # If no unclosed skip rules have been opened
            if not skip:
                position = escapeinfo['position']
                string = escapeinfo['string']
                # If this position should not be overlooked
                if not escapeinfo['odd']:
                    # If the inner string is not empty, add it to the tree
                    append = string[last:position]
                    # Adjust to after this string
                    last = position + len(value['rule'])
                    tree = treeappend((append,), tree)
                    # Add the rule to the tree
                    tree.append({
                        'contents': [],
                        'rule': value['rule']
                    })
                    # If the skip key is true, skip over everything between
                    # this open string and its close string
                    if 'skip' in rule and rule['skip']:
                        skip = value['rule']
                    # If this rule is flat, the next instance of it will be a
                    # closing string
                    flat.add(value['rule'])
            else:
                skipclose = [rule['close']]
                if 'create' in rule:
                    skipclose.append(rules[rule['create']]['close'])
                # If the close string matches the rule or the rule it creates
                if rules[skip]['close'] in skipclose:
                    if escaping:
                        position = escapeinfo['position']
                        string = escapeinfo['string']
                    # If this position should not be overlooked, account for it
                    if not escapeinfo['odd']:
                        skipoffset += 1
        # Else, if no unclosed skip rules have been opened or the close string
        # for this rule matches it
        elif not skip or value['rule'] == rules[skip]['close']:
            if escaping:
                position = escapeinfo['position']
                string = escapeinfo['string']
            # If this position should not be overlooked
            if not escapeinfo['odd']:
                # If there is an offset, decrement it
                if skipoffset:
                    skipoffset -= 1
                # Else, if the tree is not empty and last node is not closed
                elif tree and not closed(tree[len(tree) - 1]):
                    # Stop skipping
                    skip = False
                    pop = tree.pop()
                    # If this close string matches the last rule's or the
                    # config explicitly says to execute a mismatched case
                    if (rules[pop['rule']]['close'] == value['rule'] or
                    config['mismatched']):
                        # Mark the rule as closed
                        pop['closed'] = True
                        result = close(
                            string[last:position],
                            pop,
                            rules,
                            tree,
                            skip
                        )
                        skip = result['skip']
                        tree = result['tree']
                        flat.discard(value['rule'])
                        # Adjust to after this string
                        last = position + len(value['rule'])
                    # Else, add the opening string and the contents of the rule
                    else:
                        tree = treeappend(
                            (pop['rule'],) + pop['contents'],
                            tree
                        )
    # Prepare to add everything after the last string analyzed
    append = string[last:len(string)]
    # While the tree is not empty and the last node is not closed
    while (tree and not closed(tree[len(tree) - 1])):
        # Add to the last node
        pop = tree.pop()
        tree = close(append, pop, rules, tree, skip)['tree']
        # Make the last node the next thing to append
        append = tree.pop()
    # Add to the tree if necessary
    if append:
        tree.append(append)
    tree = {
        'contents': tree,
        'closed': True
    }
    # Cache the tree
    dumped = json.dumps(tree, separators=separators)
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
    # Generate a dict key for a given parameters to save to and load from
    # cache. Thus, the cache key will be the same if the parameters are the
    # same
    cachekey = md5(
            json.dumps(
            (
                ruleitems(rules, ('close',)),
                string,
                configitems(config, ('insensitive',))
            ),
            separators=separators
        )
    ).hexdigest()
    # If positions are cached for this case, load them
    if cachekey in cache['tokens']:
        return json.loads(cache['hash'][cache['tokens'][cachekey]])
    pos = []
    strings = []
    for key, value in rules.items():
        # No need adding the open string if no close string provided
        if 'close' in value:
            # Open strings open a block. Close strings close a block
            # Flat strings are open or close strings depending on context
            stringtype = 'flat'
            # If the open string is the same as the close string, it is flat
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
    # Order the strings by the length, in descending order, so that bigger
    # strings are given priority over smaller strings
    strings.sort(key=lambda item: len(item['rule']), reverse=True)
    if config['insensitive']:
        string = string.lower()
    for value in strings:
        # Only proceed if there is a rule to match against
        if value['rule']:
            caserule = value['rule']
            if config['insensitive']:
                caserule = caserule.lower()
            length = len(caserule)
            # Attempt to match against an opening string first
            position = string.find(caserule)
            while position != -1:
                end = position + length
                success = True
                for value2 in pos:
                    token = value2['token']
                    startrange = (
                        position >= token['start'] and
                        position < token['end']
                    )
                    endrange = (
                        end > token['start'] and
                        end < token['end']
                    )
                    # If this instance is in this reserved range, ignore it
                    if startrange or endrange:
                        success = False
                        break
                if success:
                    # If this string instance is not in any reserved range,
                    # then append it to the positions list
                    pos.append(
                        {
                            'rule': value['rule'],
                            'token':
                            {
                                'start': position,
                                'end': end
                            },
                            'type': value['type']
                        }
                    )
                # Find the next position of the string, and continue until
                # there are no more matches
                position = string.find(caserule, position + 1)
    # Order the positions from smallest to biggest
    pos.sort(key=lambda item: item['token']['start'])
    # Cache the positions
    dumped = json.dumps(pos, separators=separators)
    hashkey = md5(dumped).hexdigest()
    cache['hash'][hashkey] = dumped
    cache['tokens'][cachekey] = hashkey
    return pos

def treeappend(append, tree):
    """Add to the tree in the appropriate place if necessary"""
    if append:
        # If the tree is not empty and the last node is not closed
        if tree and not closed(tree[len(tree) - 1]):
            # Add to the node
            pop = tree.pop()
            for value in append:
                pop['contents'].append(value)
            tree.append(pop)
        else:
            # Add to the trunk
            for value in append:
                tree.append(value)
    return tree

def walk(rules, tree, config = None):
    """Walk through the tree and generate the string"""
    config = defaultconfig(config)
    string = ''
    for key, value in enumerate(tree['contents']):
        # If this item is a dict
        if isinstance(value, dict):
            # If the tag has been closed or the config explicitly says to walk
            # through unclosed nodes, walk through the contents with its rule
            if (
                (
                    'closed' in value and
                    value['closed']
                ) or
                config['unclosed']
            ):
                # Give the rule functions parameters to work with
                params = {
                    'config': config,
                    'rules': rules,
                    'string': '',
                    'tree': value
                }
                params['tree']['key'] = key
                # Allow reference to the parent branch
                params['tree']['parent'] = tree
                if 'rule' in value and 'functions' in rules[value['rule']]:
                    # Run the specified functions
                    for value2 in rules[value['rule']]['functions']:
                        params = value2(params)
                # Add the resulting string
                string += unicode(params['string'])
            # Else, add the open string and the result of walking through it
            else:
                string += ''.join((
                    value['rule'],
                    walk(rules, value, config)
                ))
        # Else, add the string
        else:
            string += value
    return string