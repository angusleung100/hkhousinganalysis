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


def getClassPriceIndexAllTerritories(fileLoc, tableName):
    
    #Check if table exists an create if needed
    checkTableExistsQuery = cursor.execute("SHOW TABLES WHERE `Tables_in_hkhousinganalysis` = '" + tableName + "';")

    if checkTableExistsQuery > 0:
        print("Table exists")
    else:
        createRentByClassTableQuery = "CREATE TABLE `" + tableName + "` (`year` VARCHAR(255) NOT NULL, `Class_A` VARCHAR(255) NOT NULL,`Class_B` VARCHAR(255) NOT NULL,`Class_C` VARCHAR(255) NOT NULL,`Class_D` VARCHAR(255) NOT NULL,`Class_E` VARCHAR(255) NOT NULL,`All_Classes` VARCHAR(255) NOT NULL, PRIMARY KEY (`year`));"
        createRentByClassTable = cursor.execute(createRentByClassTableQuery)
        print("RV Price Idices By Class Table Created")

    dataSheet = panda.read_csv(fileLoc)

    datasheetColumnNames = list(dataSheet.columns)

    yearColumn = dataSheet[datasheetColumnNames[0]]

    counter = 0

    #Import query template
    importBulkPriceIndiceDataQuery = "INSERT IGNORE INTO `" + tableName + "` (`year`,`Class_A`,`Class_B`,`Class_C`,`Class_D`,`Class_E`,`All_Classes`) VALUES "

    yearRowTarget = 1   

    for year in yearColumn:

        if(year != "Year"):
            #print("\n\n"+year+"\n----")

            importBulkPriceIndiceDataQuery = importBulkPriceIndiceDataQuery + "(" 
            #for pricePerSqrM in priceColumns:
            for columnName in datasheetColumnNames:
                
                column = dataSheet[columnName]
                if "Remarks" not in column[0] and "&" not in column[0]:
                    
                    if "All" in column[0]:
                        classification = column[0]
                    else:
                        classification = column[0][0:7]
                    classification = classification.replace(" ","_")

                    print(classification)

                    if(column[yearRowTarget] == "-"):
                        importBulkPriceIndiceDataQuery = importBulkPriceIndiceDataQuery + "'',"
                    else:
                        importBulkPriceIndiceDataQuery = importBulkPriceIndiceDataQuery + "'" + str(column[yearRowTarget]) + "',"
                            
                    

            importBulkPriceIndiceDataQuery = importBulkPriceIndiceDataQuery[:-1] + "),"
            yearRowTarget += 1
    
    importBulkPriceIndiceDataQuery = importBulkPriceIndiceDataQuery[:-1] + ";"
    print(importBulkPriceIndiceDataQuery)
    importBulkHousingRentData = cursor.execute(importBulkPriceIndiceDataQuery)

    connection.commit()

    
    print("Price indices imported to database")

#Import data from housing price by class sheet
fileName = "../raw_data/rv_data/price_indices_annual_from-80.csv"

tableName = "rv_price_index_by_class_data"

#Import bulk data into database
 
getClassPriceIndexAllTerritories(fileName, tableName)

