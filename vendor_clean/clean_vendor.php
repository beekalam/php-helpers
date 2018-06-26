<?php
if (PHP_SAPI != "cli") {
    die("Only from command line");
}
$vendor_dir   = getcwd() . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR;
$ds           = DIRECTORY_SEPARATOR;
$create_batch = false;
if ($_SERVER["argc"] == 2 && $_SERVER["argv"]["1"] == 'create_batch')
    $create_batch = true;

if (!is_dir($vendor_dir)) {
    die("can not find vendor dir");
}
$f = fopen("batch_delete.bat", "w");
function write_to_file($item, $type)
{
    echo $item . PHP_EOL;
    global $f;
    if ($type == "dir")
        fwrite($f, "rmdir /S /Q " . $item . PHP_EOL);
    else
        fwrite($f, "del " . $item . PHP_EOL);
}

//////////////////////////////////////////
function writeln($line)
{
    echo $line . PHP_EOL;
}

function scan_dir($path)
{
    $ite = new RecursiveDirectoryIterator($path);

    $bytestotal = 0;
    $nbfiles    = 0;
    foreach (new RecursiveIteratorIterator($ite) as $filename => $cur) {
        $filesize   = $cur->getSize();
        $bytestotal += $filesize;
        $nbfiles++;
        $files[] = $filename;
    }

    $bytestotal = number_format($bytestotal);

    return array('total_files' => $nbfiles, 'total_size' => $bytestotal, 'files' => $files);
}

function print_stat()
{
    global $vendor_dir;
    $stat = scan_dir($vendor_dir);
    unset($stat["files"]);
    foreach ($stat as $k => $v)
        writeln("[ ] $k: " . $v);
}

if ($create_batch == false) {
    print_stat();
}

$dir = array(
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
	"dusank/knapsack/tests",
	"guzzle/guzzle/tests",
	"guzzle/guzzle/docs",
	"monolog/monolog/doc",
	"monolog/monolog/tests",
	
	"symfony/console/Tests",
	"symfony/event-dispatcher/Tests",
	"symfony/form/Tests",
	"symfony/inflector/Tests",
	"symfony/intl/Tests",
	"symfony/options-resolver/Tests",
	"symfony/property-access/Tests",
	"symfony/translation/Tests",
	"symfony/validator/Tests",
	"twig/twig/test",
	"twig/twig/doc",
);


foreach ($dir as $d) {
    $dir_path = $vendor_dir . str_replace("/", DIRECTORY_SEPARATOR, $d);

    if (is_dir($dir_path)) {
        if ($create_batch == false) {
            array_map('unlink', glob($dir_path . DIRECTORY_SEPARATOR . "*"));
            rmdir($dir_path);

            echo "[DEL] $dir_path" . PHP_EOL;
        } else {
            write_to_file($dir_path, "dir");
        }
    }
}


$ignore = array(".gitignore", ".gitattributes", "README.md", "LICENSE", "ChangeLog.md", "phpunit.xml",
    ".travis.yml", "readmd.md", ".php_cs.dist", "ChangeLog-5.7.md", "phpunit.xsd",
    "CHANGELOG.md", "CODE_OF_CONDUCT.md", "CONTRIBUTING.md", ".php_cs", ".travis.install.sh", "ChangeLog-2.2.md",
    "ChangeLog-3.0.md", "ChangeLog-3.1.md", "ChangeLog-3.2.md", "ChangeLog-3.3.md", "ChangeLog-3.4.md",
    "ChangeLog-4.0.md", ".converalls.yml", ".CONTRIBUTING.md", ".scrutinizer.yml", "CHANGES.md", ".stickler.yml",
    "ChangeLog.md", ".coveralls.yml", "phpunit.xml.dist" . ".styleci.yml", ".composer-auth.json","collect.logo","CHANGELOG",
	"COPYING","upgrade.sh",".scrutinizer.yml",".sensiolabs.yml","LICENSE.md","phpspec.yml","build.xml","UPGRADING.md",
	".editorconfig","readme.rst","phpunit.xml.dist"
);

$ignore = array_unique($ignore);


$dir_files = array(
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
	"bcosca/fatfree/lib/",
	"dusank/knapsack/",
	"guzzle/guzzle/",
	"symfony/console/",
	"symfony/event-dispatcher/",
	"symfony/form/",
	"symfony/inflector/",
	"symfony/intl/",
	"symfony/options-resolver/",
	"symfony/polyfill-intl-icu/",
	"symfony/property-access/",
	"symfony/translation/",
	"symfony/validator/",
	"twig/twig/",
);
foreach ($dir_files as $d) {
    foreach ($ignore as $i) {
        $fpath = $vendor_dir . str_replace("/", DIRECTORY_SEPARATOR, $d) . $i;
        if ($create_batch == false) {
            if (file_exists($fpath) && is_file($fpath)) {
                unlink($fpath);
            }
        } else {
            write_to_file($fpath, "file");
        }
    }
}

if ($create_batch == false)
    print_stat();
else
    fwrite($f, "pause");