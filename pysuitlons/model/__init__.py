"""The application's model objects"""
import sqlalchemy as sa
from sqlalchemy import orm

from pysuitlons.model import meta

def init_model(engine):
    """Call me before using any of the tables or classes in the model"""
    ## Reflected tables must be defined and mapped here
    #global reflected_table
    #reflected_table = sa.Table("Reflected", meta.metadata, autoload=True,
    #                           autoload_with=engine)
    #orm.mapper(Reflected, reflected_table)
    #
    meta.Session.configure(bind=engine)
    meta.engine = engine


#foo_table = sa.Table('test', meta.metadata,
#    sa.Column('id', sa.types.Integer, primary_key=True),
#    sa.Column('name', sa.types.String(255), nullable=False),
#    )

#class Foo(object):
#    def get(self):
#        query = meta.Session.query(Foo).order_by(Foo.id)
#        return query.all()

#orm.mapper(Foo, foo_table)


## Classes for reflected tables may be defined here, but the table and
## mapping itself must be done in the init_model function
#reflected_table = None
#
#class Reflected(object):
#    pass
