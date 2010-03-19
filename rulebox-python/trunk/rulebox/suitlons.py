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

def gettext(params):
    """Grabs a gettext string."""
    params['string'] = _(params['string'])
    return params

def templates(params):
    """Grab a template from a file"""
    try:
        filename = os.path.normpath(params['string'])
        filepath = os.path.abspath(
            os.path.join(config['pylons.paths']['templates'], filename)
        )
        params['string'] = ''
        if filepath.startswith(config['pylons.paths']['templates']):
            params['string'] = open(filepath).read()
    except IOError:
        pass
    return params

def tmpl_context(params):
    """Rip-off of SUIT's templating.default [var] rule. Reads variables from the
    tmpl_context.
    """
    for key, value in enumerate(
        params['string'].split(params['var']['delimiter'])
    ):
        if key == 0:
            params['string'] = getattr(c, value)
        else:
            try:
                params['string'] = params['string'][value]
            except (AttributeError, TypeError):
                try:
                    params['string'] = params['string'][int(value)]
                except (AttributeError, TypeError, ValueError):
                    params['string'] = getattr(
                        params['string'],
                        value
                    )
    if params['var']['json']:
        params['string'] = json.dumps(params['string'])
    return params

def url_for(params):
    """Returns a URL for the given URL settings supplied as parameters."""
    url_params = {}
    for key, value in params['var'].items():
        url_params[str(key)] = value
    params['string'] = url(**url_params)
    return params

suitrules = templating.rules.copy()

# Adjust the templating.default rules for Pylons' convenience.
suitrules['[assign]'] = suitrules['[assign]'].copy()
suitrules['[assign]']['var'] = suitrules['[assign]']['var'].copy()
suitrules['[assign]']['var']['var'] = suitrules[
    '[assign]'
]['var']['var'].copy()
suitrules['[assign]']['var']['var']['owner'] = c

suitrules['[c]'] = suitrules['[var]'].copy()
suitrules['[c]']['close'] = '[/c]'
suitrules['[c]']['var'] = suitrules['[c]']['var'].copy()
suitrules['[c]']['var']['var'] = suitrules['[c]']['var']['var'].copy()
suitrules['[c]']['var']['var']['owner'] = c
suitrules['[c'] = suitrules['[var'].copy()
suitrules['[c']['create'] = '[c]'

suitrules['[entities]'] = suitrules['[entities]'].copy()
suitrules['[entities]']['postwalk'] = [templating.entities]

suitrules['[loop]'] = suitrules['[loop]'].copy()
suitrules['[loop]']['var'] = suitrules['[loop]']['var'].copy()
suitrules['[loop]']['var']['var'] = suitrules['[loop]']['var']['var'].copy()
suitrules['[loop]']['var']['var']['owner'] = c

suitrules['[template]'] = suitrules['[template]'].copy()
suitrules['[template]']['postwalk'] = [templates]

del suitrules['[var]']
del suitrules['[var']

pylonsrules = {
    '[gettext]':
    {
        'close': '[/gettext]',
        'postwalk': [gettext]
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
            'equal': templating.default['equal'],
            'log': templating.default['log'],
            'onesided': True,
            'quote': templating.default['quote'],
            'var': {}
        }
    }
}

rules = dict(suitrules, **pylonsrules)