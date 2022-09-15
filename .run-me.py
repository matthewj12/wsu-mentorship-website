import sys, os
sys.path.insert(0, 'backend/static-files/python')
import dynfilegen


#        +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
#            Crucial settings are specified in globvars.py! 
#                     Comment out steps as needed.
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

# dynfilegen.genCreateTablesFile(generate_file=True, execute_file=True)
# -------------------------------------------------------------------------------

# ------------------------------ Download Sample Data ---------------------------
'''
Generate and execute the curl requests to download the sample data in CSV format from Mockaroo
'''

# dynfilegen.genSampleDataCurlRequestsFile(generate_file=True, execute_file=True)
# dynfilegen.genImportSampleDataFile(generate_file=False, execute_file=True)
# -------------------------------------------------------------------------------

# ------------------------------ Run Matching Algorithm -------------------------
'''
Run the matching algorithm, adding the results to the `mentorship` table 
'''

os.system('py backend/static-files/python/create-matches-auto.py 1-1-1 2-2-2')
# ------------------------------# -------------------------------------------------
