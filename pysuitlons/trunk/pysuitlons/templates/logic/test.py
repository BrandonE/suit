# As you can see,you also have `c` and gettext available when messing with
# code files. In the case that you need the helper functions, just import. 
c.test = _('Testing template code files. ')
suit.template = suit.parse(suit.vars['nodes'], suit.template)