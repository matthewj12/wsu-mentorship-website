load data local infile "c:/users/matthew/documents/coding/sql/SummerProj/ParticipantSampleData.csv"
into table `participant`
fields terminated by "," enclosed by "'"
lines terminated by "\r\n"
ignore 1 rows;

load data local infile "c:/users/matthew/documents/coding/sql/SummerProj/HasHobbySampleData.csv"
into table `has hobby`
fields terminated by "," enclosed by "'"
lines terminated by "\r\n"
ignore 1 rows;

load data local infile "c:/users/matthew/documents/coding/sql/SummerProj/SpeaksSecondLanguageSampleData.csv"
into table `speaks second language`
fields terminated by "," enclosed by "'"
lines terminated by "\r\n"
ignore 1 rows;

load data local infile "c:/users/matthew/documents/coding/sql/SummerProj/IsRaceSampleData.csv"
into table `is race`
fields terminated by "," enclosed by "'"
lines terminated by "\r\n"
ignore 1 rows;