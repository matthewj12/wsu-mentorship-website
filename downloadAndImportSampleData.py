import os
import sys

if len(sys.argv):
	if sys.argv[1] not in ['-import_only', '-download_only']:
		print("Error in your argument to 'downloadAndImportSampleData.py'; valid arguments are '-import_only' and '-disable_imoprt'.")

	else:
		if sys.argv[1] == '-download_only':
			os.system('py generateCurlRequests.py')
			os.system('sampleDataCurlRequests.bat')
		elif sys.argv[1] == '-import_only':
			os.system('mysql -u root --local_infile mp < "importSampleData.sql"')
