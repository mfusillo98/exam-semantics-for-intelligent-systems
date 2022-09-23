import advertools
import pandas as pd


def scraping_url_list(url_list, filename):
    advertools.crawl(url_list, 'tmp/scraping.jl')
    proximus_crawl = pd.read_json('tmp/scraping.jl', lines=True)
    proximus_crawl.filter(regex='jsonld').to_csv(filename)
