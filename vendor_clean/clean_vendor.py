import os
import sys
import shutil
dir_list= [
	"doctorine/instantiator/tests",
    "mikey179/vfStream/examples",
    "mikey179/vfStream/src/test",

    "myclabs/deep-copy/doc",
    "myclabs/deep-copy/fixtures",

    "php-console/php-console/examples",
    "php-console/php-console/test",

    "phpspec/prophecy/CHANGES.md",
    "phpspec/prophecy/LICENSE",
    "phpspec/prophecy/README.md",

    "phpunit/php-code-coverage/.github",
    "phpunit/php-code-coverage/tests",
    "phpunit/php-timer/tests",
    "phpunit/php-token-stream/tests",
    "phpunit/phpunit/tests",
    "phpunit/phpunit/.github",
    "phpunit/phpunit-mock-objects/tests",

    "ramsey/uuid/docs",

    "sebastian/code-unit-reverse-lookup/tests",
    "sebastian/comparator/tests",
    "sebastian/diff/tests",
    "sebastian/environment/tests",
    "sebastian/exporter/tests",
    "sebastian/global-state/tests",
    "sebastian/object-enumerator/tests",
    "sebastian/recursion-context/tests",
    "sebastian/resource-operations/tests",
    "sebastian/version/tests",

    "yaml/Tests",
	
	"tightenco/collect/tests",
	"tightenco/collect/stubs/tests",
	
	"phpfastcache/phpfastcache/docs",
	"phpfastcache/phpfastcache/tests",
	"phpfastcache/phpfastcache/.github",
	
	"symfony/var-dumper/Tests"
];

dir_search_for_junk_files=[
    "myclabs/deep-copy/",
    "phpunit/php-code-coverage/",
    "phpunit/php-text-template/",
    "phpunit/php-timer/",
    "phpunit/php-token-stream/",
    "phpunit/php-token-stream/",
    "phpunit/phpunit/",
    "phpunit/phpunit-mock-objects/",
    "phpunit/php-file-iterator/",
    "ramsey/uuid/",
    "sebastian/code-unit-reverse-lookup/",
    "sebastian/comparator/",
    "sebastian/diff/",
    "sebastian/environment/",
    "sebastian/exporter/",
    "sebastian/global-state/",
    "sebastian/object-enumerator/",
    "sebastian/recursion-context/",
    "sebastian/resource-operations/",
    "sebastian/version/",
    "symfony/debug/",
    "symfony/polyfill-mbstring/",
    "symfony/polyfill-php72/",
    "symfony/var-dumper/",
    "symfony/yaml/",
    "webmozart/assert/",
    "php-console/php-console/",
    "phpdocumentor/reflection-docblock/",
    "phpdocumentor/reflection-common/",
    "phpdocumentor/type-resolver/",
    "phpspec/prophecy/",
    "mikey179/vfsStream/",
    "doctrine/instantiator/",
	"tightenco/collect/",
	"phpfastcache/phpfastcache/",
]

junk_file_names = [
	".gitignore", ".gitattributes", "README.md", "LICENSE", "ChangeLog.md", "phpunit.xml",
    ".travis.yml", "readmd.md", ".php_cs.dist", "ChangeLog-5.7.md", "phpunit.xsd",
    "CHANGELOG.md", "CODE_OF_CONDUCT.md", "CONTRIBUTING.md", ".php_cs", ".travis.install.sh", "ChangeLog-2.2.md",
    "ChangeLog-3.0.md", "ChangeLog-3.1.md", "ChangeLog-3.2.md", "ChangeLog-3.3.md", "ChangeLog-3.4.md",
    "ChangeLog-4.0.md", ".converalls.yml", ".CONTRIBUTING.md", ".scrutinizer.yml", "CHANGES.md", ".stickler.yml",
    "ChangeLog.md", ".coveralls.yml", "phpunit.xml.dist" , ".styleci.yml", ".composer-auth.json","collect.logo",
	"upgrade.sh","code_of_conduct.md","CODE_OF_CONDUCT.md",".codeclimate.yml",
	"CNAME","LICENCE","collect-logo.png"
]
def print_vendor_stats():
	vendor_dir = os.path.join(os.getcwd(),"vendor");
	count = 0
	if os.path.exists(vendor_dir):
		for root, dirs, files in os.walk(vendor_dir):
			for file in files:
				count += 1
	print "num files: " + str(count)
	
def normalize_path(dir):
	return dir.replace('\\',os.sep).replace('/',os.sep)

def delete_dir(dir):
	if os.path.exists(dir):
		print "deleting dir: " + dir
		shutil.rmtree(dir)

def delete_file(file):
	if os.path.exists(file):
		print "deleting file: " + file
		os.remove(file)
		
def delete_dirs():
	global dir_list
	for dir in dir_list:
		dir_path = os.path.join(os.getcwd(), "vendor",normalize_path(dir))	
		delete_dir(dir_path)
		
def delete_junk_files():
	global dir_search_for_junk_files
	for dir in dir_search_for_junk_files:
		dir_path = os.path.join(os.getcwd() ,"vendor",normalize_path(dir))
		for file in junk_file_names:
			file_path = os.path.join(dir_path,file);
			delete_file(file_path)
		
if __name__ == "__main__":
	# walk_vendor_dir()
	print_vendor_stats()
	delete_dirs()
	delete_junk_files()
	print_vendor_stats()
	raw_input("Press Enter to continue...")
	
	
