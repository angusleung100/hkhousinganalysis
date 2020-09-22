#Prerequisites
import pandas as panda

import pymysql

#Create database connection
host = "localhost";
database = "hkhousinganalysis";
username = "root";
password = "";


connection = pymysql.connect(host=host, user=username, password=password, db=database)

cursor = connection.cursor()


def getClassPricesForTerritory(territory, fileLoc):
    dataSheet = panda.read_csv(fileLoc)

    datasheetColumnNames = list(dataSheet.columns)

    yearColumn = dataSheet[datasheetColumnNames[0]]

    counter = 0

    filterTerritory = ""

    """
    3 present-day Territories -> Hong Kong, Kowloon, and New Territories

    Filtering shortened names (To minimize parsing a human error in typing a territory name):
    ---------------------------
    Hon -> Hong Kong
    Kow -> Kowloon
    New -> New Territories
    New K -> New Kowloon (For LEGACY sheets before 1999)

    Why?
    There are columns where "Kowloon" is written as "Kowloom" which will be filtered correctly. With minimum letters only, the probability of filtering a mess up incorrectly is reduced.

    """
    if territory == "Hong Kong":
        filterTerritory = "Hon"
    elif territory == "Kowloon":
        filterTerritory = "Kow"
    elif territory == "New Kowloon":
        filterTerritory = "New K"
    elif territory == "New Territories":
        filterTerritory = "New"
    else:
        pass 

    print("\n\n"+territory+"\n====")
    for year in yearColumn:
        if(year != "Year"):
            print("\n\n"+year+"\n----")
            #for pricePerSqrM in priceColumns:
            for columnName in datasheetColumnNames:
                
                column = dataSheet[columnName]
                if "Remarks" not in column[0]:
                    if territory in column[0]:
                        classification = column[0][0:7]
                        classification = classification.replace(" ","_")

                            
                        print(classification)
            





#Import data from housing price by class sheet
fileName = "../raw_data/rv_data/price_by_class_annual_86-98.csv"

territoryList = ["Hong Kong", "Kowloon", "New Kowloon", "New Territories"]

for territorySelect in territoryList:
    print(getClassPricesForTerritory(territorySelect, fileName))
