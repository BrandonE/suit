import logging

from pylons import request, response, session, c, url
from pylons.controllers.util import abort, redirect_to

from pysuitlons.lib.base import *
from pysuitlons.model import meta

log = logging.getLogger(__name__)

class RootController(BaseController):

    def index(self):
        c.context = 'This is an example of template context.'
        return render('test')

    def hello(self):
        return render('hello')