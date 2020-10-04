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
        filterTerritory = "New T"
    else:
        pass 


    #Import query template
    importBulkMonthlyPayAmountDataQuery = "INSERT IGNORE INTO `rv_price_by_class_data` (`year`,`Class_A`,`Class_B`,`Class_C`,`Class_D`,`Class_E`) VALUES "

    print("\n\n"+territory+"\n====")
    for year in yearColumn:
        if(year != "Year"):
            print("\n\n"+year+"\n----")
            #for pricePerSqrM in priceColumns:
            for columnName in datasheetColumnNames:
                
                column = dataSheet[columnName]
                if "Remarks" not in column[0]:
                    if filterTerritory in column[0] and filterTerritory != "Kow":   #Filter and target HK, NT, and NK
                        classification = column[0][0:7]
                        classification = classification.replace(" ","_")

                        print(classification)
            
                    if filterTerritory in column[0] and filterTerritory == "Kow" and "New K" not in column[0]:  #Filter and target Kowloon territory
                        classification = column[0][0:7]
                        classification = classification.replace(" ","_")
                            
                        print(classification)




#Import data from housing price by class sheet
fileNameOne = "../raw_data/rv_data/price_by_class_annual_86-98.csv"
fileNameTwo = "../raw_data/rv_data/price_by_class_annual_99-now.csv"

territoryList = ["Hong Kong", "Kowloon", "New Kowloon", "New Territories"]

#Check if table exists an create if needed
checkTableExistsQuery = cursor.execute("SHOW TABLES WHERE `Tables_in_hkhousinganalysis` = 'rv_price_by_class_data';")

if checkTableExistsQuery > 0:
    print("Table exists")
else:
    createPriceByClassTableQuery = "CREATE TABLE `rv_price_by_class_data` (`year` VARCHAR(255) NOT NULL, `Class_A` VARCHAR(255) NOT NULL,`Class_B` VARCHAR(255) NOT NULL,`Class_C` VARCHAR(255) NOT NULL,`Class_D` VARCHAR(255) NOT NULL,`Class_E` VARCHAR(255) NOT NULL, PRIMARY KEY (`year`));"
    createPriceByClassTable = cursor.execute(createPriceByClassTableQuery)
    print("RV Price By Class Table Created")

#Import bulk data into database


for territorySelect in territoryList:   #Pre-1998 data
    getClassPricesForTerritory(territorySelect, fileNameOne)

""" for territorySelect in territoryList:   #1999 onward data
    print(getClassPricesForTerritory(territorySelect, fileNameTwo)) """