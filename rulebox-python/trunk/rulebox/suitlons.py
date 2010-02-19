"""
**@This file is part of Rulebox.
**@Rulebox is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@Rulebox is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with Rulebox.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
"""
import os
import pickle
try:
    import simplejson as json
except ImportError:
    import json
from pylons import config, tmpl_context as c, url
from pylons.i18n import ugettext as _gettext
from rulebox import templating
from suitframework.lib.templating import render
import suit
from webhelpers.html import escape

__version__ = '0.0.0'

def assign(params):
    """Assign variable in template"""
    if params['var']['var']:
        #Split up the file, paying attention to escape strings
        split = suit.explodeunescape(
            params['var']['delimiter'],
            params['var']['var'],
            params['config']['escape']
        )
        assignvariable(split, params['tree']['case'], c)
    params['tree']['case'] = ''
    return params

def assignvariable(split, assignment, var):
    """Assign a variable based on split"""
    for key, value in enumerate(split):
        if key < len(split) - 1:
            try:
                var = var[value]
            except (AttributeError, TypeError):
                try:
                    var = var[int(value)]
                except (AttributeError, TypeError, ValueError):
                    var = getattr(var, value)
    try:
        var[split[len(split) - 1]] = assignment
    except (AttributeError, TypeError):
        setattr(var, split[len(split) - 1], assignment)

def code(params):
    """Execute a code file"""
    try:
        module = os.path.join(config['suit.templates'], 'logic',
            '%s.py' % (params['tree']['case'].replace('.', '/'))
        )
        execfile(module)
    except ImportError:
        pass
    params['tree']['case'] = ''
    return params

def gettext(params):
    """Grabs a gettext string."""
    params['tree']['case'] = _gettext(params['tree']['case'])
    return params

def filtering(params):
    """Escapes HTML; by default, all rules follow this basic rule to simulate
    how Pylons configured Mako to run.
    """
    if (not params['var']['json'] and not params['var']['serialize']
            and params['var']['entities']
    ):
        params['tree']['case'] = escape(params['tree']['case'])
    return params

def tmpl_context(params):
    """Rip-off of SUIT's default [var] rule. Reads variables from the
    tmpl_context.
    """
    split = suit.explodeunescape(
        params['var']['delimiter'],
        params['tree']['case'],
        params['config']['escape']
    )
    for key, value in enumerate(split): 
        if key == 0:
            params['tree']['case'] = getattr(c, value)
        else:
            try:
                params['tree']['case'] = params['tree']['case'][value] 
            except (AttributeError, TypeError):
                try:
                    params['tree']['case'] = params['tree']['case'][int(value)]
                except (AttributeError, TypeError, ValueError):
                    params['tree']['case'] = getattr(params['tree']['case'], value)
    if params['var']['json']:
        params['tree']['case'] = json.dumps(params['tree']['case'])
    if params['var']['serialize']:
        params['tree']['case'] = pickle.dumps(params['tree']['case'])
    return params

def templates(params):
    """Grab a template from a file"""
    #If the template is not whitelisted or blacklisted
    try:
        filepath = os.path.join(config['suit.templates'], 
            os.path.normpath(params['tree']['case'])
        )
        params['tree']['case'] = open(filepath).read()
    except IOError:
        params['tree']['case'] = ''
    return params

def url_for(params): 
    """Returns a URL for the given URL settings supplied as parameters.""" 
    url_params = {} 
    for k, v in params['var'].items(): 
        url_params[str(k)] = v 
    params['tree']['case'] = url(**url_params) 
    return params
suitrules = templating.RULES.copy()

decode = ('entities', 'json', 'serialize')
list = ('entities', 'json', 'serialize')

# Adjust the default rules for Pylons' convenience.
suitrules['[assign]']['var']['var']['delimiter'] = '.'
suitrules['[try]']['var']['var']['delimiter'] = '.'
suitrules['[loopvar]']['var']['var']['delimiter'] = '.'
suitrules['[var]']['var']['var']['delimiter'] = '.'

suitrules['[assign]']['postwalk'] = [templating.attribute, assign]
suitrules['[template]']['postwalk'] = [templates]
suitrules['[code]']['postwalk'] = [code]

suitrules['[loopvar]']['var']['var']['decode'] = decode
suitrules['[var]']['var']['var']['decode'] = decode

suitrules['[loopvar]']['var']['list'] = list
suitrules['[var]']['var']['list'] = list

suitrules['[loopvar]']['var']['var']['entities'] = 'true'
suitrules['[var]']['var']['var']['entities'] = 'true'

suitrules['[var]']['postwalk'].append(filtering)
suitrules['[loopvar]']['postwalk'].append(filtering)

pylonsrules = {
    '[c]':
    {
        'close': '[/c]',
        'postwalk': [
            templating.attribute,
            templating.jsondecode,
            tmpl_context,
            filtering
        ],
        'var':
        {
            'equal': '=',
            'list': ('entities', 'json', 'serialize'),
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('entities', 'json', 'serialize'),
                'delimiter': '.',
                'entities': 'true',
                'json': 'false',
                'serialize': 'false'
            }
        }
    },
    '[c':
    {
        'close': ']',
        'create': '[c]',
        'skip': True
    },
    '[gettext]': 
    { 
        'close': '[/gettext]',
        'postwalk': [gettext],
        'var':
        {
            'equal': '=',
            'quote': ('"', '\''),
            'var': {}
        }
    },
    '[url': 
    {
        'close': '/]',
        'postwalk': [
            templating.attribute,
            url_for
        ], 
        'skip': True,
        'var':
        {
            'equal': '=',
            'onesided': True,
            'quote': ('"', '\''),
            'var': {}
        }
    }
}

RULES = dict(suitrules, **pylonsrules)