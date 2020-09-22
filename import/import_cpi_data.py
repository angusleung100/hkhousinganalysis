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

#Import data from Monthly Salary HK Stats
dataSheet = panda.read_excel("../raw_data/censtats_data/cpi_data.xls")



datasheetColumnNames = list(dataSheet.columns)

yearColumn = dataSheet[datasheetColumnNames[0]]
cpiColumn = dataSheet[datasheetColumnNames[2]]
cpiChangeColumn = dataSheet[datasheetColumnNames[3]]



#Check if table exists
checkTableExistsQuery = cursor.execute("SHOW TABLES WHERE `Tables_in_hkhousinganalysis` = 'censtats_cpi_data';")

#Create table if not exist
if checkTableExistsQuery > 0:
    print("Table exists")
else:
    createCPITableQuery = "CREATE TABLE `censtats_cpi_data` (`year` VARCHAR(255) NOT NULL, `cpi` VARCHAR(255) NOT NULL, `cpi_change` VARCHAR(255) NOT NULL, PRIMARY KEY (`year`));"

    createCPITable = cursor.execute(createCPITableQuery)

    connection.commit()

    print("CPi data table created")

counter = 0
for row in yearColumn:
    try:
        if str(row) != "nan" and str(row) != "Year":
            print(str(yearColumn[counter])+" - "+str(cpiColumn[counter])+" - "+str(cpiChangeColumn[counter]))
            counter += 1
        
        if yearColumn[counter-1] > yearColumn[counter]:
            break;
    except:
        counter += 1
    
#Import data in bulk
counter = 0


#Import query template
importBulkCPIDataQuery = "INSERT IGNORE INTO `censtats_cpi_data` (`year`,`cpi`,`cpi_change`) VALUES "

for row in yearColumn:
    try:
        if str(row) != "nan" and str(row) != "Year":
            cpiValue = str(cpiColumn[counter])
            if cpiValue == "n.a.":
                cpiValue = ""
            
            cpiChangeValue = str(cpiChangeColumn[counter])
            if cpiChangeValue == "n.a.":
                cpiChangeValue = ""

            importBulkCPIDataQuery = importBulkCPIDataQuery + "('" + str(yearColumn[counter]) + "'," + "'" + cpiValue + "',"+ "'" + cpiChangeValue + "'),"
            counter += 1
        
        if yearColumn[counter-1] > yearColumn[counter]:

            
            importBulkCPIDataQuery = importBulkCPIDataQuery[:-1] + ";"
            print(importBulkCPIDataQuery)

            importBulkCPIData = cursor.execute(importBulkCPIDataQuery)

            connection.commit()
            print("CPI data imported")
            break;
    except:
        counter += 1
    