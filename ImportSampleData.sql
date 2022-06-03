load data infile "c:/users/matth/documents/coding/sql/SummerProj/ParticipantSampleData.csv"
into table `participant`
fields terminated by "," enclosed by "'"
lines terminated by "\r\n"
ignore 1 rows;

load data infile "c:/users/matth/documents/coding/sql/SummerProj/HasHobbySampleData.csv"
into table `has hobby`
fields terminated by "," enclosed by "'"
lines terminated by "\r\n"
ignore 1 rows;