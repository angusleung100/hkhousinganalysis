#Prerequisites
import pandas as panda

import pymysql

def removeFootnoteTags(textSample):
    newText = ""

    cursor = 0
    ignoreChar = False

    while cursor < len(textSample):

        if "(" in textSample[cursor]:
            ignoreChar = True
        if ")" in textSample[cursor]:
            ignoreChar = False


        if ignoreChar is not True:
            newText = newText + textSample[cursor]
        cursor += 1
    return newText

def shortenColumn(textSample):

    columnNameLength = 25
    newText = ""
    cursor = 0
    while cursor < columnNameLength and cursor < len(textSample):
        newText = newText + textSample[cursor]
        cursor += 1
    
    return newText
        

#Create database connection
host = "localhost";
database = "hkhousinganalysis";
username = "root";
password = "";


connection = pymysql.connect(host=host, user=username, password=password, db=database)

#Import data from Monthly Salary HK Stats
workbookSheets = panda.ExcelFile("../raw_data/censtats_data/monthlyPay_data.xlsx")


cursor = connection.cursor()

#Check if table exists
checkTableExistsQuery = cursor.execute("SHOW TABLES WHERE `Tables_in_hkhousinganalysis` = 'censtats_monthlypay_amount_data';")

if checkTableExistsQuery > 0:
    print("Table exists")
else:

    createAmountTableQuery = "CREATE TABLE `censtats_monthlypay_amount_data` ("
    createChangeTableQuery = "CREATE TABLE `censtats_monthlypay_change_data` ("

    #Get data for each sheet in workbook
    for sheet in workbookSheets.sheet_names:
        if(sheet != "Index"):

            

            monthlySalariesDataFeed = panda.read_excel("../raw_data/censtats_data/monthlyPay_data.xlsx", sheet_name=sheet)



            createAmountTableQuery = createAmountTableQuery + "`year`" + " VARCHAR(255) NULL,"
            createChangeTableQuery = createChangeTableQuery + "`year`" + " VARCHAR(255) NULL,"
            #Get list of column names
            columns = list(monthlySalariesDataFeed)


            #Make table fields from catagories and subcategories

            categoryIndexTarget = 0

            earnerAllCategories = list(monthlySalariesDataFeed[columns[0]])

            earnerMonthlySalaries = list(monthlySalariesDataFeed[columns[1]])

            earnerMonthlySalariesYoYPercentChange = list(monthlySalariesDataFeed[columns[2]])

            #Interate through all rows
            while(categoryIndexTarget < len(list(earnerAllCategories))):
                if("By " in str(earnerAllCategories[categoryIndexTarget])):
                    
                    #Create category name for table column
                    categoryName = str(earnerAllCategories[categoryIndexTarget])
                    categoryName = categoryName.replace("By ", "")

                    #Check for numbers in format "( number )" and remove
                    categoryName = removeFootnoteTags(categoryName)

                    categoryName = categoryName.replace(")", "")

                    categoryName = categoryName.replace(" ", "_")

                    categoryName = shortenColumn(categoryName)

                    
                    #Find how many subcategories to count before reaching next category
                    subcategoryIndexTarget = categoryIndexTarget + 1    #Start off on next line after the By line

                    
                    #Get subcategory from category
                    for x in range(len(earnerAllCategories)):
                        if(earnerAllCategories[subcategoryIndexTarget] == "Notes : "):
                            break;
                        elif("By " in str(earnerAllCategories[subcategoryIndexTarget])):    #End of category
                            break;
                        else:
                            subcategoryName = str(earnerAllCategories[subcategoryIndexTarget])
                            
                           

                            #Check for numbers in format "( number )" and remove
                            subcategoryName = removeFootnoteTags(subcategoryName)

                            #Check and strip punctuation
                            punctuationBlacklist = "!@#$%^&*_+-=/\|.,;:≥)";
                            for punctuationCursor in subcategoryName:
                                if punctuationCursor in punctuationBlacklist:
                                    subcategoryName = subcategoryName.replace(punctuationCursor, "")

                            

                            subcategoryName = subcategoryName.replace(" ", "_")
                            
                            subcategoryName = subcategoryName.lower()
                            
                            subcategoryName = shortenColumn(subcategoryName)

                            subcategoryMonthlySalary = earnerMonthlySalaries[subcategoryIndexTarget];

                            subcategoryMonthlySalaryYoYPercentChange = earnerMonthlySalariesYoYPercentChange[subcategoryIndexTarget]



                            #Remove empty rows that include nan
                            if(subcategoryName != "nan"):
                                #Display output
                                
                                createAmountTableQuery = createAmountTableQuery + "`" + categoryName + "-" + subcategoryName + "`" + " VARCHAR(255) NULL,"
                                createChangeTableQuery = createChangeTableQuery + "`" + categoryName + "-" + subcategoryName + "`" + " VARCHAR(255) NULL,"
                                #print(categoryName+"-"+subcategoryName)
                            
                            subcategoryIndexTarget += 1
                            
                    categoryIndexTarget += 1  
                elif(str(earnerAllCategories[categoryIndexTarget]) == "Notes : "):
                    createAmountTableQuery = createAmountTableQuery[:-1]
                    createAmountTableQuery = createAmountTableQuery + ", PRIMARY KEY (`year`)" + ");"

                    createChangeTableQuery = createChangeTableQuery[:-1]
                    createChangeTableQuery = createChangeTableQuery + ", PRIMARY KEY (`year`)" + ");"

                    #print(createTableQuery)
                    createAmountTable = cursor.execute(createAmountTableQuery)

                    connection.commit()
                    
                    createChangeTable = cursor.execute(createChangeTableQuery)
                    connection.commit()

                    print("Monthly salary tables created")

                    break;
                else:
                    categoryIndexTarget +=1
            break;


#Check for non-existing columns and add columns to database table


for sheet in workbookSheets.sheet_names:
    if(sheet != "Index"):
        monthlySalariesDataFeed = panda.read_excel("../raw_data/censtats_data/monthlyPay_data.xlsx", sheet_name=sheet)


        print("Checking sheet"+ sheet +"\n\n")
        #Get list of column names
        columns = list(monthlySalariesDataFeed)

        sheetKeys = []

        #Make table fields from catagories and subcategories

        categoryIndexTarget = 0

        earnerAllCategories = list(monthlySalariesDataFeed[columns[0]])

        #Interate through all rows
        while(categoryIndexTarget < len(list(earnerAllCategories))):
            if("By " in str(earnerAllCategories[categoryIndexTarget])):
                
                #Create category name for table column
                categoryName = str(earnerAllCategories[categoryIndexTarget])
                categoryName = categoryName.replace("By ", "")

                #Check for numbers in format "( number )" and remove
                categoryName = removeFootnoteTags(categoryName)

                categoryName = categoryName.replace(")", "")

                categoryName = categoryName.replace(" ", "_")

                categoryName = shortenColumn(categoryName)

                
                #Find how many subcategories to count before reaching next category
                subcategoryIndexTarget = categoryIndexTarget + 1    #Start off on next line after the By line

                
                #Get subcategory from category
                for x in range(len(earnerAllCategories)):
                    if(earnerAllCategories[subcategoryIndexTarget] == "Notes : "):
                        break;
                    elif("By " in str(earnerAllCategories[subcategoryIndexTarget])):    #End of category
                        break;
                    else:
                        subcategoryName = str(earnerAllCategories[subcategoryIndexTarget])
                        
                        

                        #Check for numbers in format "( number )" and remove
                        subcategoryName = removeFootnoteTags(subcategoryName)

                        #Check and strip punctuation
                        punctuationBlacklist = "!@#$%^&*_+-=/\|.,;:≥)";
                        for punctuationCursor in subcategoryName:
                            if punctuationCursor in punctuationBlacklist:
                                subcategoryName = subcategoryName.replace(punctuationCursor, "")

                        

                        subcategoryName = subcategoryName.replace(" ", "_")
                        
                        subcategoryName = subcategoryName.lower()
                        
                        subcategoryName = shortenColumn(subcategoryName)

                        #Remove empty rows that include nan
                        if(subcategoryName != "nan"):
                            #Display output
                            
                            sheetKeys.append(categoryName+"-"+subcategoryName)
                            #print(categoryName+"-"+subcategoryName)
                        
                        subcategoryIndexTarget += 1
                        
                categoryIndexTarget += 1  
            elif(str(earnerAllCategories[categoryIndexTarget]) == "Notes : "):

                tempComparisonStorage = []
                
                #Check monthly amount table
                compareMonthlyPayAmountColumnNamesQuery = cursor.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'censtats_monthlypay_amount_data';")


                compareMonthlyPayAmountColumnNames = cursor.fetchall()
                    

                
                for column in compareMonthlyPayAmountColumnNames:
                    tempComparisonStorage.append(column[0])

                
                del tempComparisonStorage[0]

                if sheetKeys != tempComparisonStorage:
                    addMissingAmountColumnsQuery = "ALTER TABLE `censtats_monthlypay_amount_data` "
                    for sheetColumn in sheetKeys:
                        if sheetColumn not in tempComparisonStorage:
                            addMissingAmountColumnsQuery = addMissingAmountColumnsQuery + "ADD `" + sheetColumn + "`" + " VARCHAR(255) NULL,"
                    
                    
                    addMissingAmountColumnsQuery = addMissingAmountColumnsQuery[:-1]
                    addMissingAmountColumnsQuery = addMissingAmountColumnsQuery + ";"
                    

                    addMissingAmountColumns = cursor.execute(addMissingAmountColumnsQuery)
                    connection.commit()
                    print("New monthly pay amount column(s) added")

                #Check monthly change table
                tempComparisonStorage = []
                compareMonthlyPayChangeColumnNamesQuery = cursor.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'censtats_monthlypay_change_data';")


                compareMonthlyPayChangeColumnNames = cursor.fetchall()
                    
                for column in compareMonthlyPayChangeColumnNames:
                    tempComparisonStorage.append(column[0])

                del tempComparisonStorage[0]

                if sheetKeys != tempComparisonStorage:
                    addMissingChangeColumnsQuery = "ALTER TABLE `censtats_monthlypay_change_data` "
                    for sheetColumn in sheetKeys:
                        if sheetColumn not in tempComparisonStorage:
                            addMissingChangeColumnsQuery = addMissingChangeColumnsQuery + "ADD `" + sheetColumn + "`" + " VARCHAR(255) NULL,"
                    
                    
                    addMissingChangeColumnsQuery = addMissingChangeColumnsQuery[:-1]
                    addMissingChangeColumnsQuery = addMissingChangeColumnsQuery + ";"
                    

                    addMissingChangeColumns = cursor.execute(addMissingChangeColumnsQuery)
                    connection.commit()
                    print("New monthly pay change column(s) added")

                break;
            else:
                categoryIndexTarget +=1
 
#Import bulk data

for sheet in workbookSheets.sheet_names:
    if(sheet != "Index"):

        monthlySalariesDataFeed = panda.read_excel("../raw_data/censtats_data/monthlyPay_data.xlsx", sheet_name=sheet)
        
        sheetKeys = []
        
        #Get sheet year
        sheetName = sheet
        sheetName = sheetName.replace("E", "")


        #Get list of column names
        columns = list(monthlySalariesDataFeed)


        #Import query template
        importBulkMonthlyPayAmountDataQuery = "INSERT IGNORE INTO `censtats_monthlypay_amount_data` (`year`,"

        importBulkMonthlyPayChangeDataQuery = "INSERT IGNORE INTO `censtats_monthlypay_change_data` (`year`,"


        #Store selected data and columns
        tempBulkMonthlyPayColumnsSelect = ""

        tempBulkMonthlyPayAmountDataSelect = "'" + sheetName + "',"
        tempBulkMonthlyPayChangeDataSelect = "'" + sheetName + "',"
        
        #Make table fields from catagories and subcategories

        categoryIndexTarget = 0

        earnerAllCategories = list(monthlySalariesDataFeed[columns[0]])
        earnerAmount = list(monthlySalariesDataFeed[columns[1]])
        earnerChange = list(monthlySalariesDataFeed[columns[2]])

        #print(earnerAmount)
        #Interate through all rows
        while(categoryIndexTarget < len(list(earnerAllCategories))):
            if("By " in str(earnerAllCategories[categoryIndexTarget])):
                
                #Create category name for table column
                categoryName = str(earnerAllCategories[categoryIndexTarget])
                categoryName = categoryName.replace("By ", "")

                #Check for numbers in format "( number )" and remove
                categoryName = removeFootnoteTags(categoryName)

                categoryName = categoryName.replace(")", "")

                categoryName = categoryName.replace(" ", "_")

                categoryName = shortenColumn(categoryName)

                
                #Find how many subcategories to count before reaching next category
                subcategoryIndexTarget = categoryIndexTarget + 1    #Start off on next line after the By line



                #Get subcategory from category
                for x in range(len(earnerAllCategories)):
                    if(earnerAllCategories[subcategoryIndexTarget] == "Notes : "):
                        break;
                    elif("By " in str(earnerAllCategories[subcategoryIndexTarget])):    #End of category
                        break;
                    else:
                        subcategoryName = str(earnerAllCategories[subcategoryIndexTarget])
                        
                        

                        #Check for numbers in format "( number )" and remove
                        subcategoryName = removeFootnoteTags(subcategoryName)

                        #Check and strip punctuation
                        punctuationBlacklist = "!@#$%^&*_+-=/\|.,;:≥)";
                        for punctuationCursor in subcategoryName:
                            if punctuationCursor in punctuationBlacklist:
                                subcategoryName = subcategoryName.replace(punctuationCursor, "")

                        

                        subcategoryName = subcategoryName.replace(" ", "_")
                        
                        subcategoryName = subcategoryName.lower()
                        
                        subcategoryName = shortenColumn(subcategoryName)

                
                        #Remove empty rows that include nan
                        if(subcategoryName != "nan"):
                            
                            sheetKeys.append(categoryName+"-"+subcategoryName)

                            
                            #print(categoryName+"-"+subcategoryName)
                        
                        

                        subcategoryIndexTarget += 1
                        
                categoryIndexTarget += 1  
            elif(str(earnerAllCategories[categoryIndexTarget]) == "Notes : "):

                for column in sheetKeys:
                    tempBulkMonthlyPayColumnsSelect = tempBulkMonthlyPayColumnsSelect + "`" + column + "`,"
                for x in range(len(earnerAllCategories)):
                    

                    if(str(earnerAmount[x]) != "nan" and str(earnerAmount[x]) != "Median monthly wage (HK$)"):
                        tempBulkMonthlyPayAmountDataSelect = tempBulkMonthlyPayAmountDataSelect + "'" + str(earnerAmount[x]) + "',"
                    if(str(earnerChange[x]) != "nan" and str(earnerChange[x]) != "Year-on-year % change"):
                        tempBulkMonthlyPayChangeDataSelect = tempBulkMonthlyPayChangeDataSelect + "'" + str(earnerChange[x]) + "',"

                tempBulkMonthlyPayAmountDataSelect = tempBulkMonthlyPayAmountDataSelect[:-1]

                tempBulkMonthlyPayChangeDataSelect = tempBulkMonthlyPayChangeDataSelect[:-1]
                
                tempBulkMonthlyPayColumnsSelect = tempBulkMonthlyPayColumnsSelect[:-1]



                #Compile queries together
                importBulkMonthlyPayAmountDataQuery = importBulkMonthlyPayAmountDataQuery + tempBulkMonthlyPayColumnsSelect + ") VALUES (" + tempBulkMonthlyPayAmountDataSelect + ");"

                importBulkMonthlyPayChangeDataQuery = importBulkMonthlyPayChangeDataQuery + tempBulkMonthlyPayColumnsSelect + ") VALUES (" + tempBulkMonthlyPayChangeDataSelect + ");"

                importBulkMonthlyPayAmountData = cursor.execute(importBulkMonthlyPayAmountDataQuery)

                connection.commit()

                importBulkMonthlyPayChangeData = cursor.execute(importBulkMonthlyPayChangeDataQuery)

                connection.commit()

                print("Monthly salary amounts for "+sheetName+" and changes updated")
                break;
                
            else:
                categoryIndexTarget +=1 