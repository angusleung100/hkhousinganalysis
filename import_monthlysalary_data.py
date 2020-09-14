#Prerequisites
import pandas as panda

import pymysql

def removeFootnoteTags(textSample):
    newText = "";

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

#Create database connection
host = "localhost";
database = "hkhousinganalysis";
username = "root";
password = "";



#Import data from Monthly Salary HK Stats
workbookSheets = panda.ExcelFile("censtats_data/monthlyPay_data.xlsx")



#Get data for each sheet in workbook
for sheet in workbookSheets.sheet_names:
    if(sheet != "Index"):

        

        monthlySalariesDataFeed = panda.read_excel("censtats_data/monthlyPay_data.xlsx", sheet_name=sheet)

        #Get sheet year
        sheetName = sheet
        sheetName = sheetName.replace("E", "")


        print("\n\n", sheetName,"\n","==============")

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



                categoryName = categoryName.replace(" ", "_")
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
                        
                        #Check and strip punctuation
                        punctuationBlacklist = "!@#$%^&*_+-=/\|.,;:≥)";
                        for punctuationCursor in subcategoryName:
                            if punctuationCursor in punctuationBlacklist:
                                subcategoryName = subcategoryName.replace(punctuationCursor, "")

                        #Check for numbers in format "( number )" and remove
                        subcategoryName = removeFootnoteTags(subcategoryName)

                        subcategoryName = subcategoryName.replace(" ", "_")
                        
                        subcategoryName = subcategoryName.lower()
                        
                        

                        subcategoryMonthlySalary = earnerMonthlySalaries[subcategoryIndexTarget];

                        subcategoryMonthlySalaryYoYPercentChange = earnerMonthlySalariesYoYPercentChange[subcategoryIndexTarget]



                        #Remove empty rows that include nan
                        if(subcategoryName != "nan"):
                            #Display output
                            print(categoryName,"-",subcategoryName, "|" ,subcategoryMonthlySalary , "|", subcategoryMonthlySalaryYoYPercentChange)
                        
                        subcategoryIndexTarget += 1
                        
                categoryIndexTarget += 1  
            elif(str(earnerAllCategories[categoryIndexTarget]) == "Notes : "):
                break;
            else:
                categoryIndexTarget +=1

