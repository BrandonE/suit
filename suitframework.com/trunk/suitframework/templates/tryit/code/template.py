from rulebox.templating import var
var.username = 'Brandon'
var.loggedin = True
var.members = [
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