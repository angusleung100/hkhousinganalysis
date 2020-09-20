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

counter = 0

datasheetColumnNames = list(dataSheet.columns)

yearColumn = dataSheet[datasheetColumnNames[0]]
cpiColumn = dataSheet[datasheetColumnNames[2]]
cpiChangeColumn = dataSheet[datasheetColumnNames[3]]

#Create table if not exist

for row in yearColumn:
    try:
        if str(row) != "nan" and str(row) != "Year":
            print(str(yearColumn[counter])+" - "+str(cpiColumn[counter])+" - "+str(cpiChangeColumn[counter]))
            counter += 1
        
        if yearColumn[counter-1] > yearColumn[counter]:
            break;
    except:
        counter += 1
    
