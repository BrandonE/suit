from pylons import tmpl_context as c
c.username = 'Brandon'
c.loggedin = True
c.members = [
    {
        'name': 'Brandon',
        'admin': True,
        'banned': False
    },
    {
        'name': 'Chris',
        'admin': False,
        'banned': True
    }
]