# HK Housing Analysis

## What is this
Displaying data to show the housing statistics in one of the world's most expensive housing markets.

## About
I found that the HK housing problem is very evident and the income gap is always enormous.

## Built With
- PHP
- MySQL
- Python
- Apache
- [Mini.css](https://minicss.org/)
- [Pandas](https://pandas.pydata.org/)

- Trello



Financial and Housing Data Provided By: [Government of Hong Kong](https://data.gov.hk/en/)

# Raw Data Sources
- CenStats
    - cpi_data.xls (Data Source Update frequency: Monthly)
        - Webpage: https://www.censtatd.gov.hk/hkstat/sub/sp270.jsp
        - Data Download: https://www.censtatd.gov.hk/hkstat/sub/sp270.jsp?tableID=052&ID=0&productType=8 (Bottom of page)
    - monthlyPay_data.xlsx (Data Source Update frequency: Yearly)
        - Webpage: https://www.censtatd.gov.hk/hkstat/sub/sp270.jsp
        - Data Download: https://www.censtatd.gov.hk/hkstat/sub/sp210.jsp?productCode=D5250017
- HKMA
    - capitalmarkets_stats.json (Data Source Update frequency: Monthly)
        - Webpage: https://apidocs.hkma.gov.hk/documentation/market-data-and-statistics/monthly-statistical-bulletin/financial/capital-market-statistics/
        - API URL: https://api.hkma.gov.hk/public/market-data-and-statistics/monthly-statistical-bulletin/financial/capital-market-statistics
    - econ_stats.json (Data Source Update frequency: Monthly)
        - Webpage: https://apidocs.hkma.gov.hk/documentation/market-data-and-statistics/monthly-statistical-bulletin/financial/economic-statistics/
        - API URL: https://api.hkma.gov.hk/public/market-data-and-statistics/monthly-statistical-bulletin/financial/economic-statistics
    - monetary_stats.json (Data Source Update frequency: Monthly)
        - Webpage: https://apidocs.hkma.gov.hk/documentation/market-data-and-statistics/monthly-statistical-bulletin/financial/monetary-statistics/
        - API URL: https://api.hkma.gov.hk/public/market-data-and-statistics/monthly-statistical-bulletin/financial/monetary-statistics
- RVD
    - domestic_sales_from-2002.csv (Data Source Update frequency: Monthly)
        - Webpage: https://www.rvd.gov.hk/en/property_market_statistics/index.html
        - Data Download: https://data.gov.hk/en-data/dataset/hk-rvd-tsinfo_rvd-property-market-statistics/resource/098ccac0-3332-48e1-9f0d-1d120e05f924
    - price_by_class_annual_86-98.csv (Data Source Update frequency: Monthly)
        - Webpage: https://www.rvd.gov.hk/en/property_market_statistics/index.html
        - Data Download: https://data.gov.hk/en-data/dataset/hk-rvd-tsinfo_rvd-property-market-statistics/resource/ede01470-783d-4469-bfa8-7dfa0d9b9570
    - price_by_class_annual_99-now.csv (Data Source Update frequency: Monthly)
        - Webpage: https://www.rvd.gov.hk/en/property_market_statistics/index.html
        - Data Download: https://data.gov.hk/en-data/dataset/hk-rvd-tsinfo_rvd-property-market-statistics/resource/167f48fe-ae05-4944-ad30-3853172dcf92
    - price_indices_annual_from-80.csv (Data Source Update frequency: Monthly)
        - Webpage: https://www.rvd.gov.hk/en/property_market_statistics/index.html
        - Data Download: https://data.gov.hk/en-data/dataset/hk-rvd-tsinfo_rvd-property-market-statistics/resource/b6d56ba7-7932-49a4-ab3c-c1858615325f
    - rent_indices_annual_from-80.csv (Data Source Update frequency: Monthly)
        - Webpage: https://www.rvd.gov.hk/en/property_market_statistics/index.html
        - Data Download: https://data.gov.hk/en-data/dataset/hk-rvd-tsinfo_rvd-property-market-statistics/resource/b7d58225-7e8c-4981-9484-20c6a95b1eb9
    - rent_by_class_annual_86-98.csv (Data Source Update frequency: Monthly)
        - Webpage: https://www.rvd.gov.hk/en/property_market_statistics/index.html
        - Data Download: https://data.gov.hk/en-data/dataset/hk-rvd-tsinfo_rvd-property-market-statistics/resource/5fc6153e-dfa8-47c3-88c6-9fe146e89288
    - rents_by_class_annual_99-now.csv (Data Source Update frequency: Monthly)
        - Webpage: https://www.rvd.gov.hk/en/property_market_statistics/index.html
        - Data Download: https://data.gov.hk/en-data/dataset/hk-rvd-tsinfo_rvd-property-market-statistics/resource/ad5da8b2-a38f-47a4-aff2-98ff30e4c398
