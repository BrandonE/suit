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
import sys
import pickle
try:
    import simplejson as json
except ImportError:
    import json

import suit
from pylons import config, tmpl_context as c, url
from pylons.i18n import ugettext as _
from webhelpers.html import escape

from rulebox import templating

__all__ = [
    'assign', 'gettext', 'helpers', 'filtering', 'rules', 'templates',
    'tmpl_context', 'url_for'
]

def assign(params):
    """Assign variable in template"""
    if params['var']['var']:
        #Split up the file, paying attention to escape strings
        split = suit.explodeunescape(
            params['var']['delimiter'],
            params['var']['var'],
            params['config']['escape']
        )
        templating.assignvariable(split, params['tree']['case'], c)
    params['tree']['case'] = ''
    return params

def gettext(params):
    """Grabs a gettext string."""
    params['tree']['case'] = _(params['tree']['case'])
    return params

def helpers(params):
    helper = getattr(params['var']['helpers'], params['tree']['case'])()
    params['tree']['case'] = ''
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

def templates(params):
    """Grab a template from a file"""
    #If the template is not whitelisted or blacklisted
    try:
        filepath = os.path.join(config['pylons.paths']['templates'],
            os.path.normpath(params['tree']['case'])
        )
        params['tree']['case'] = open(filepath).read()
    except IOError:
        params['tree']['case'] = ''
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
                    params['tree']['case'] = getattr(
                        params['tree']['case'],
                        value
                    )
    if params['var']['json']:
        params['tree']['case'] = json.dumps(params['tree']['case'])
    if params['var']['serialize']:
        params['tree']['case'] = pickle.dumps(params['tree']['case'])
    return params

def url_for(params):
    """Returns a URL for the given URL settings supplied as parameters."""
    url_params = {}
    for key, value in params['var'].items():
        url_params[str(key)] = value
    params['tree']['case'] = url(**url_params)
    return params

suitrules = templating.rules.copy()

decode = ('entities', 'json', 'serialize')
whitelist = ('entities', 'json', 'serialize')

# Adjust the default rules for Pylons' convenience.
suitrules['[assign]'] = suitrules['[assign]'].copy()
suitrules['[assign]']['postwalk'] = [templating.attribute, assign]

suitrules['[loopvar]'] = suitrules['[loopvar]'].copy()
suitrules['[loopvar]']['var'] = suitrules['[loopvar]']['var'].copy()
suitrules['[loopvar]']['var']['var'] = (
    suitrules['[loopvar]']['var']['var'].copy()
)
suitrules['[loopvar]']['var']['var']['decode'] = decode
suitrules['[loopvar]']['var']['list'] = whitelist
suitrules['[loopvar]']['var']['var']['entities'] = 'true'
suitrules['[loopvar]']['postwalk'] = suitrules['[loopvar]']['postwalk'][:]
suitrules['[loopvar]']['postwalk'].append(filtering)

suitrules['[template]'] = suitrules['[template]'].copy()
suitrules['[template]']['postwalk'] = [templates]

del suitrules['[var]']
del suitrules['[code]']

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
    '[h]':
    {
        'close': '[/h]',
        'postwalk': [helpers],
        'var': {}
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

rules = dict(suitrules, **pylonsrules)