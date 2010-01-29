from ez_setup import use_setuptools
use_setuptools()

from setuptools import setup, find_packages

version = '0.0.1'

setup(
    name='pysuitlons',
    version=version,
    author="Faltzer (Chris Santiago)",
    author_email="faltzermaster@aol.com",
    keywords='web wsgi framework sqlalchemy pylons paste template suit pysuit',
    description='Pylons template sporting PySUIT as the template system based on the default_project template.',
    long_description='',
    license='MIT License',
    url='http://faltzershq.com/pysuitlons',
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
    zip_safe=True,
    packages=find_packages(),
    install_requires=["PasteScript>=1.7.3"],
    include_package_data=True,
    entry_points="""
        [paste.paster_create_template]
        pysuit = pysuitlons.template:PySUITTemplate
    """)
