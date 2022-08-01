import dynamicfilegeneration, os


#        +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
#            Crucial settings are specified in globalvariables.py! 
#                        Comment out steps as needed.
#        +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


# ------------------------------ Remake Database --------------------------------
''' 
Drop the existing `mp` database and create and new empty `mp` database.

Dynamically generate all the SQL files in the entire project (reads from
		static-sql.sql, but this file is never executed; it's contents
		are added to create-tables.sql).

Create all the tables in the new schema via executing the SQL we just 
       generated.
'''

dynamicfilegeneration.generateCreateTablesFile(file_extension='.sql', what_to_do=['generate file', 'execute file'])
# -------------------------------------------------------------------------------

# ------------------------------ Download Sample Data ---------------------------
'''
Generate and execute the curl requests to download the sample data in CSV format from Mockaroo
'''

dynamicfilegeneration.generateSampleDataCurlRequestsFile(file_extension='.bat', what_to_do=['generate file', 'execute file'])
dynamicfilegeneration.generateImportSampleDataFile(file_extension='.sql', what_to_do=['generate file', 'execute file'])
# -------------------------------------------------------------------------------

# ------------------------------ Run Matching Algorithm -------------------------
'''
Run the matching algorithm, adding the results to the `mentorship` table 
(assuming 'globalvariables.test_mode = False' in globalvariables.py)
'''

os.system('py main.py')
# -------------------------------------------------------------------------------
