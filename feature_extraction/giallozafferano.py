import scraping.crawler
import json

def scraping_giallozafferano():
    with open('scraping-scripts/giallozafferano.com-sitemap.json') as f:
        data = json.load(f)
        scraping.crawler.scraping_url_list(data, 'scraping-scripts/giallozafferano.com-JSONLD.csv')
