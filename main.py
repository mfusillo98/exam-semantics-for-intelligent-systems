import advertools
import json
import pandas as pd
from IPython.display import display


# This is a sample Python script.
# Press ⌃R to execute it or replace it with your code.
# Press Double ⇧ to search everywhere for classes, files, tool windows, actions, and settings.

def scraping_url_list(url_list, filename):
    advertools.crawl(url_list, 'tmp/scraping.jl')
    proximus_crawl = pd.read_json('tmp/scraping.jl', lines=True)
    proximus_crawl.filter(regex='jsonld').to_csv(filename)


def main():
    with open('scraping/giallozafferano.com-sitemap.json') as f:
        data = json.load(f)
        scraping_url_list(data, 'scraping/giallozafferano.com-JSONLD.csv')


# Press the green button in the gutter to run the script.
if __name__ == '__main__':
    main()

# See PyCharm help at https://www.jetbrains.com/help/pycharm/
