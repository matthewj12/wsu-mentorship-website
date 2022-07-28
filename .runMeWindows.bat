: The default MySQL secure_file_privileges directory to import local files + our directory for this database's sample data (SampleData)
set sampleDataDir=C:\ProgramData\MySQL\MySQL Server 8.0\Uploads\SampleData

: Dynamically generate all the SQL files in the entire project.
: Drop the existing database and create and new empty database
: Create all the tables in the new schema via executing the SQL we just generated
remakeDb.bat

: Generate and execute the curl requests to download the sample data in CSV format from Mockaroo
@REM py downloadAndImportSampleData.py
@REM py downloadAndImportSampleData.py -download_only
py downloadAndImportSampleData.py -import_only

: Run the matching algorithm, adding the results to the `mentorship` table (assuming "test_mode = False" in globalVariables.py)
py main.py
