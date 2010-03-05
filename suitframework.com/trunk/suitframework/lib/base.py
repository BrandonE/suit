"""The base Controller API

Provides the BaseController class for subclassing.
"""
from pylons import tmpl_context as c
from pylons.controllers import WSGIController
from pylons.i18n import ugettext as _

from suit import MyClass

from suitframework.lib.templating import render, render_page
from suitframework.lib import helpers as h
from suitframework.model import meta

class BaseController(WSGIController):

    def __before__(self): 
        c.loop = MyClass()
        c.condition = MyClass()

    def __call__(self, environ, start_response):
        """Invoke the Controller"""
        # WSGIController.__call__ dispatches to the Controller method
        # the request is routed to. This routing information is
        # available in environ['pylons.routes_dict']
        try:
            return WSGIController.__call__(self, environ, start_response)
        finally:
            meta.Session.remove()