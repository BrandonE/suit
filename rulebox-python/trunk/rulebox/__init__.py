#**@This file is part of Rulebox.
#**@Rulebox is free software: you can redistribute it and/or modify
#**@it under the terms of the GNU General Public License as published by
#**@the Free Software Foundation, either version 3 of the License, or
#**@(at your option) any later version.
#**@Rulebox is distributed in the hope that it will be useful,
#**@but WITHOUT ANY WARRANTY; without even the implied warranty of
#**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#**@GNU General Public License for more details.
#**@You should have received a copy of the GNU General Public License
#**@along with Rulebox.  If not, see <http://www.gnu.org/licenses/>.

#Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
#http://www.suitframework.com/
#http://www.suitframework.com/docs/credits

"""
A package containing various sets of rules for use with SUIT.

Example usage:

import suit
from rulebox import templating #easy_install rulebox
template = open('template.tpl').read()
#Template contains "Hello, <strong>[var]username[/var]</strong>!"
templating.var.username = 'Brandon'
print suit.execute(templating.rules, template)
#Result: Hello, <strong>Brandon!</strong>

Basic usage; see http://www.suitframework.com/docs/ for other uses.
"""

__version__ = '1.0.0'