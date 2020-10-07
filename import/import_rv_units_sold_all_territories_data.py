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


def getUnitsSoldAllTerritories():
    
    #Check if table exists an create if needed
    
    tableName = "rv_units_sold_all_territories_data"
    
    checkTableExistsQuery = cursor.execute("SHOW TABLES WHERE `Tables_in_hkhousinganalysis` = '" + tableName + "';")

    if checkTableExistsQuery > 0:
        print("Table exists")
    else:
        createUnitsSoldAllTerritoriesTableQuery = "CREATE TABLE `" + tableName + "` (`date` VARCHAR(255) NOT NULL,`Primary_Sales` VARCHAR(255) NOT NULL,`Primary_Total_Value` VARCHAR(255) NOT NULL,`Secondary_Sales` VARCHAR(255) NOT NULL,`Secondary_Total_Value` VARCHAR(255) NOT NULL, PRIMARY KEY (`date`));"
        createUnitsSoldAllTerritoriesTable = cursor.execute(createUnitsSoldAllTerritoriesTableQuery)
        print("RV Rent Indices By Class Table Created")

    fileName = "../raw_data/rv_data/domestic_sales_from-2002.csv"


    dataSheet = panda.read_csv(fileName)

    datasheetColumnNames = list(dataSheet.columns)

    dateColumn = dataSheet[datasheetColumnNames[0]]

    counter = 0

    #Import query template
    importBulkUnitsSoldDataQuery = "INSERT IGNORE INTO `" + tableName + "` (`date`,`Primary_Sales`,`Primary_Total_Value`,`Secondary_Sales`,`Secondary_Total_Value`) VALUES "

    dateRowTarget = 1   

    for date in dateColumn:

        if(date != "Month"):
            #print("\n\n"+year+"\n----")

            importBulkUnitsSoldDataQuery = importBulkUnitsSoldDataQuery + "(" 
            #for pricePerSqrM in priceColumns:
            for columnName in datasheetColumnNames:
                
                column = dataSheet[columnName]
                    
                classification = column[0]
                classification = classification.replace(" ","_")

                print(classification)

                if classification == "Month":

                    originalMonthFormat = str(column[dateRowTarget])
                    newMonthFormat = originalMonthFormat[3:7] + "-" + originalMonthFormat[0:2]
                    importBulkUnitsSoldDataQuery = importBulkUnitsSoldDataQuery + "'" + newMonthFormat + "',"
                
                elif classification:

                    if(column[dateRowTarget] == "-"):
                        importBulkUnitsSoldDataQuery = importBulkUnitsSoldDataQuery + "'',"
                    else:
                        importBulkUnitsSoldDataQuery = importBulkUnitsSoldDataQuery + "'" + str(column[dateRowTarget]) + "',"
                            
                    

            importBulkUnitsSoldDataQuery = importBulkUnitsSoldDataQuery[:-1] + "),"
            dateRowTarget += 1
    
    importBulkUnitsSoldDataQuery = importBulkUnitsSoldDataQuery[:-1] + ";"
    print(importBulkUnitsSoldDataQuery)
    importBulkUnitsSoldData = cursor.execute(importBulkUnitsSoldDataQuery)

    connection.commit()

    
    print("Units sold imported to database")

#Import data from housing price by class sheet


#Import bulk data into database
 
getUnitsSoldAllTerritories()

