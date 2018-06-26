import os
import sys
import shutil

	
def normalize_path(dir):
	return dir.replace('\\',os.sep).replace('/',os.sep)

def delete_dir_if_is_dir(dir):
	if (os.path.isdir(dir)):
		delete_dir(dir)
		
def delete_dir(dir):
	if os.path.exists(dir):
		print "deleting dir: " + dir
		shutil.rmtree(dir)

def delete_file(file):
	if os.path.exists(file):
		print "deleting file: " + file
		os.remove(file)
					
def delete_crash_dumps():
	crash_dumps_dir = "C:\Users\moh\AppData\Local\CrashDumps"
	if os.path.exists(crash_dumps_dir):
		for root, dirs, files in os.walk(crash_dumps_dir):
			for file in files:
				filepath = os.path.join(root,file)
				delete_file(filepath)
		
if __name__ == "__main__":
	# walk_vendor_dir()
	delete_crash_dumps()
	raw_input("Press Enter to continue...")
	
	
