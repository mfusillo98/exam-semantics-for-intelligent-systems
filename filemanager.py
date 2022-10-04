import json
import os
import gzip as gz


def save_dict_to_file(obj, path):
    dirname = os.path.dirname(path)
    if not os.path.exists(dirname):
        os.makedirs(dirname)
    with open(path, 'w+') as outfile:
        json.dump(obj, outfile)


def get_abs_path(relative_path):
    return os.path.dirname(os.path.realpath(__file__)) + str(relative_path).replace("/", os.path.sep)


def gzip(path):
    with open(path, 'rb') as f_in, \
            gz.open(path + ".gz", 'wb') as f_out:
        f_out.writelines(f_in)
