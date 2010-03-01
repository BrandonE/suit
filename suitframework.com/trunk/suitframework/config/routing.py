"""Routes configuration

The more specific and detailed routes should be defined first so they
may take precedent over the more generic routes. For more information
refer to the routes manual at http://routes.groovie.org/docs/
"""
from pylons import config
from routes import Mapper

def make_map():
    """Create, configure and return the routes Mapper"""
    map = Mapper(directory=config['pylons.paths']['controllers'],
                 always_scan=config['debug'])
    map.minimization = False
    map.append_slash = True

    # The ErrorController route (handles 404/500 error pages); it should
    # likely stay at the top, ensuring it can always be resolved
    map.connect('/error/{action}', controller='error')
    map.connect('/error/{action}/{id}', controller='error')

    # CUSTOM ROUTES HERE

    map.connect('/', controller='root', action='template', templatefile='index')
    map.connect('/{templatefile}/', controller='root', action='template')
    map.connect('/{templatefile}/{parameter1}/', controller='root', action='template')
    map.connect('/{templatefile}/{parameter1}/{parameter2}/', controller='root', action='template')
    map.redirect('/{templatefile}', '/{templatefile}/')
    map.redirect('/{templatefile}/{parameter1}', '/{templatefile}/{parameter1}/')
    map.redirect('/{templatefile}/{parameter1}/{parameter2}', '/{templatefile}/{parameter1}/{parameter2}/')

    return map