mysql --local_infile=1 -u root mp < "CreateSchema.sql" && ^
mysql --local_infile=1 -u root mp < "ImportSampleData.sql"