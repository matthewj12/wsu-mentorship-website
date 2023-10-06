-- Active: 1667682089126@@127.0.0.1@3306@mp
load data infile "some_directory_on_server/sample-data/participant.csv"
into table `participant`
fields terminated by "," enclosed by "'"
lines terminated by "\n"
ignore 1 rows;

load data infile "some_directory_on_server/sample-data/max-matches-assoc-tbl.csv"
into table `max matches assoc tbl`
fields terminated by "," enclosed by "'"
lines terminated by "\n"
ignore 1 rows;

load data infile "some_directory_on_server/sample-data/gender-assoc-tbl.csv"
into table `gender assoc tbl`
fields terminated by "," enclosed by "'"
lines terminated by "\n"
ignore 1 rows;

load data infile "some_directory_on_server/sample-data/religious-affiliation-assoc-tbl.csv"
into table `religious affiliation assoc tbl`
fields terminated by "," enclosed by "'"
lines terminated by "\n"
ignore 1 rows;

load data infile "some_directory_on_server/sample-data/important-quality-assoc-tbl.csv"
into table `important quality assoc tbl`
fields terminated by "," enclosed by "'"
lines terminated by "\n"
ignore 1 rows;

load data infile "some_directory_on_server/sample-data/hobby-assoc-tbl.csv"
into table `hobby assoc tbl`
fields terminated by "," enclosed by "'"
lines terminated by "\n"
ignore 1 rows;

load data infile "some_directory_on_server/sample-data/major-assoc-tbl.csv"
into table `major assoc tbl`
fields terminated by "," enclosed by "'"
lines terminated by "\n"
ignore 1 rows;

load data infile "some_directory_on_server/sample-data/pre-program-assoc-tbl.csv"
into table `pre program assoc tbl`
fields terminated by "," enclosed by "'"
lines terminated by "\n"
ignore 1 rows;

load data infile "some_directory_on_server/sample-data/race-assoc-tbl.csv"
into table `race assoc tbl`
fields terminated by "," enclosed by "'"
lines terminated by "\n"
ignore 1 rows;

load data infile "some_directory_on_server/sample-data/second-language-assoc-tbl.csv"
into table `second language assoc tbl`
fields terminated by "," enclosed by "'"
lines terminated by "\n"
ignore 1 rows;
