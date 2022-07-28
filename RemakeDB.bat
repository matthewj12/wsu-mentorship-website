: Dynamically generate all the SQL files in the entire project. (Some non-dynamically-generated SQL code is stored in staticSql.sql.
: This file is never called directly by the mysql command, instead being read by generateSql.py and inserted into the final SQL files)
py generateSql.py

: Drop the existing database and create and new empty database
mysql -u root < "dropDbAndCreateEmptyDb.sql"

: Create all the tables in the new schema via executing the SQL we just generated
mysql -u root mp < "createTables.sql"
