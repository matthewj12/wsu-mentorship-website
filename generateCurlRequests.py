from miscellaneousFunctions import *
from sampleDataUrls import *


out_file = open('sampleDataCurlRequests.bat', 'w')
out_file.write('pushd .\n')
out_file.write('cd %sampleDataDir%\n')

for distinct in urls.keys():
	suffix = 'AssocTbl' if distinct != 'participant' else ''
	curl = f"curl \"{mockaroo_base_url}{urls[distinct]}&key={mockaroo_key}\" > \"{toCamelCase(distinct)}{suffix}.csv\"\n"

	out_file.write(curl)

out_file.write('popd\n')
# out_file.write('exit')
out_file.close()