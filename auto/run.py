import os
for dirpath, dirnames, files in os.walk('.'):
    if dirpath == "./pages" :
    	for file_name in files:
    		print(file_name)