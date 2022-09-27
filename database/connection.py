import pymysql
import os

_connection = {}


def _get_connection(host, port, database, user, password):
    connection = pymysql.connect(host=host,
                                 port=int(port),
                                 database=database,
                                 user=user,
                                 password=password,
                                 charset='utf8',
                                 cursorclass=pymysql.cursors.DictCursor)
    return connection


def get_connection(database):
    global _connection
    if database not in _connection:
        _connection[database] = _get_connection(
            host=os.environ.get("DB_HOST", ''),
            port=os.environ.get("DB_PORT", ''),
            user=os.environ.get("DB_USER", ''),
            password=os.environ.get("DB_PASSWORD", ''),
            database=database,
        )
    return _connection[database]


def new_connection(database):
    return _get_connection(
        host=os.environ.get("DB_HOST", ''),
        port=os.environ.get("DB_PORT", ''),
        user=os.environ.get("DB_USER", ''),
        password=os.environ.get("DB_PASSWORD", ''),
        database=database,
    )
