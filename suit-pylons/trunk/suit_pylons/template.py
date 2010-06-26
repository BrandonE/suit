from paste.deploy.converters import asbool
from paste.script.templates import Template, var

from tempita import paste_script_template_renderer

class PySUITTemplate(Template):
    """Based on ``pylons.util.PylonsTemplate``."""
    _template_dir = 'templates/suit'
    summary = 'Pylons default_project with SUIT as the templating engine.'
    template_renderer = staticmethod(paste_script_template_renderer)
    vars = [
        var('sqlalchemy', 'True/False: Include SQLAlchemy 0.5 configuration',
                default=False)
    ]
    ensure_names = ['description', 'author', 'author_email', 'url']

    def pre(self, command, output_dir, vars):
        """Called before template is applied."""
        package_logger = vars['package']
        if package_logger == 'root':
            # Rename the app logger in the rare case a project is named 'root'
            package_logger = 'app'
        vars['package_logger'] = package_logger
        vars['babel_templates_extractor'] = '' # Not yet
        # Ensure these exist in the namespace
        for name in self.ensure_names:
            vars.setdefault(name, '')

        vars['version'] = vars.get('version', '0.1')
        vars['zip_safe'] = asbool(vars.get('zip_safe', 'false'))
        vars['sqlalchemy'] = asbool(vars.get('sqlalchemy', 'false'))