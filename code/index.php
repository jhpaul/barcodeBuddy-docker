<?php

/**
 * Barcode Buddy for Grocy
 *
 * PHP version 7
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU General
 * Public License v3.0 that is attached to this project.
 *
 * @author     Marc Ole Bulling
 * @copyright  2019 Marc Ole Bulling
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html  GNU GPL v3.0
 * @since      File available since Release 1.0
 */


/**
 * Index file that receives barcodes and displays web UI
 *
 * Make sure to modify API details. This script requires php-sqlite3 and php-curl
 * 
 * @author     Marc Ole Bulling
 * @copyright  2019 Marc Ole Bulling
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html  GNU GPL v3.0
 * @since      File available since Release 1.0
 *
 */



require_once __DIR__ . "/incl/config.php";
require_once __DIR__ . "/incl/api.inc.php";
require_once __DIR__ . "/incl/db.inc.php";
require_once __DIR__ . "/incl/internalChecking.inc.php";
require_once __DIR__ . "/incl/processing.inc.php";
require_once __DIR__ . "/incl/websocketconnection.inc.php";
require_once __DIR__ . "/incl/webui.inc.php";


if (checkExtensionsInstalled()["result"] == RESULT_REQ_MISSING || !isGrocyApiSet()) {
    header("Location: setup.php");
    die();
}

//If barcodes or parameters are passed through CLI or GET, process them and do not do anything else
if (isset($_GET["version"]) || (isset($argv[1]) && $argv[1]=="-v")) {
   die("BarcodeBuddy ".BB_VERSION);
}

if (isset($argv[1])) {
    processNewBarcode(sanitizeString($argv[1], true));
    die;
}
if (isset($_GET["mode"])) {
    processModeChangeGetParameter($_GET["mode"]);
    hideGetPostParameters();
}
if (isset($_GET["refreshbarcode"])) {
    processRefreshedBarcode(sanitizeString($_GET["refreshbarcode"]));
    hideGetPostParameters();
}

if (isset($_GET["add"])) {
    processNewBarcode(sanitizeString($_GET["add"], true));
    if (!isset($_GET["showui"])) {
        die("OK");
    }
    hideGetPostParameters();
}





// If a button was pressed, we are processing everything here.
// Only one row can be processed at a time
processButtons();

$barcodes = getStoredBarcodes();
		if (sizeof($barcodes['known']) > 0 || sizeof($barcodes['unknown']) > 0) {
		    $productinfo = getProductInfo();
		}

$webUi = new WebUiGenerator(MENU_MAIN);
$webUi->addHeader();
$webUi->addCard("New Barcodes",getHtmlMainMenuTableKnown($barcodes),"Delete all",$_SERVER['PHP_SELF'].'?delete=known');
$webUi->addCard("Unknown Barcodes",getHtmlMainMenuTableUnknown($barcodes),"Delete all",$_SERVER['PHP_SELF'].'?delete=unknown');
$webUi->addCard("Processed Barcodes",getHtmlLogTextArea(),"Clear log",$_SERVER['PHP_SELF'].'?delete=log');
$webUi->addFooter();
$webUi->printHtml();



?>
