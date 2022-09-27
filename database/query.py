import pymysql
import os


# Matteo Fusillo
def insert(cursor, table, record_dict, ignore=False):
    try:
        cols = '`,`'.join(record_dict.keys())
        placeholders = ', '.join(['%({})s'.format(key) for key in record_dict.keys()])
        query = "INSERT " + ("IGNORE" if ignore == True else '') + " INTO " + table + " (`{}`) VALUES ({})".format(cols,
                                                                                                                   placeholders)
        result = cursor.execute(query, record_dict)
        return result
    except:
        print(cursor._last_executed)
        raise


# Matteo Fusillo
def update(cursor, table, record_dict, pk_dict):
    try:
        query = "UPDATE " + table + " SET {}".format(', '.join('{}=%s'.format(k) for k in record_dict))
        query += " WHERE {}".format(' AND '.join('{}=%s'.format(k) for k in pk_dict))
        result = cursor.execute(query, [*record_dict.values(), *pk_dict.values()])
        return result
    except:
        print(cursor._last_executed)
        raise
