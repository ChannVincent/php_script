<?php
/*
 * Run : /usr/local/php5/bin/php -f /Users/vchann/Documents/Git/php_script/index.php
 *
 */



/*
 * Import
 */
include "autoload.php";

/*
 * Coding facilitator
 */
define("DS", DIRECTORY_SEPARATOR);

/*
 * Global Variables
 */
$base_directory = dirname(__FILE__);
$input_directory = $base_directory.DS."input";
$output_directory = $base_directory.DS."output";
$default_directory = $base_directory.DS."default";

/*
 * Script
 */

$worker = new StructureAndroidGenerator($base_directory, $input_directory, $output_directory);
$worker->run();

/*
$worker = new ZipDividerWorker($base_directory, $input_directory, $output_directory);
$worker->run();
*/

/*
$worker = new FileAdapterWorker($base_directory, $input_directory, $output_directory, $default_directory);
$worker->run();
*/

/*
$worker = new JsonAdapterWorker($base_directory, $input_directory, $output_directory, $default_directory);
$worker->run();
*/

/*
$worker = new CropResizeImage($base_directory, $input_directory, $output_directory, $default_directory);
$worker->run();
*/

