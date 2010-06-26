from ez_setup import use_setuptools
use_setuptools()

from setuptools import setup, find_packages

version = '1.0.0'

setup(
    name='suit-pylons',
    version=version,
    author="Faltzer (Chris Santiago)",
    author_email="faltzermaster@aol.com",
    keywords='web wsgi framework sqlalchemy pylons paste template suit pysuit',
    description='Pylons template sporting Python-SUIT as the template system based on the default_project template.',
    long_description='',
    license='MIT License',
    url='http://suitframework.com/docs/pylons/',
    classifiers=[
        "Development Status :: 5 - Production/Stable",
        "Framework :: Pylons",
        "Intended Audience :: Developers",
        "License :: OSI Approved :: MIT License",
        "Programming Language :: Python",
        "Topic :: Internet :: WWW/HTTP",
        "Topic :: Internet :: WWW/HTTP :: Dynamic Content",
        "Topic :: Internet :: WWW/HTTP :: WSGI",
        "Topic :: Software Development :: Libraries :: Python Modules",
    ],
    zip_safe=False,
    packages=find_packages(),
    include_package_data=True,
    install_requires=["suit>=2.0.0", "rulebox"],
    entry_points="""
        [paste.paster_create_template]
        suit = suit_pylons.template:PySUITTemplate
    """)
