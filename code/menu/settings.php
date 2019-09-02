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
 * @since      File available since Release 1.2
 */


/**
 * General settings
 *
 * @author     Marc Ole Bulling
 * @copyright  2019 Marc Ole Bulling
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html  GNU GPL v3.0
 * @since      File available since Release 1.2
 *
 */



require_once __DIR__ . "/../incl/config.php";
require_once __DIR__ . "/../incl/api.inc.php";
require_once __DIR__ . "/../incl/db.inc.php";
require_once __DIR__ . "/../incl/processing.inc.php";
require_once __DIR__ . "/../incl/websocketconnection.inc.php";
require_once __DIR__ . "/../incl/webui.inc.php";

if (isset($_POST["BARCODE_C"])) {
        saveSettings(); 
        //Hide POST, so we can refresh
        header("Location: " . $_SERVER["PHP_SELF"]);
        die();
    }



$webUi = new WebUiGenerator(MENU_SETTINGS);
$webUi->addHeader();
$webUi->addHtml('<form name="settingsform" id="settingsform" method="post" action="' . $_SERVER['PHP_SELF'] . '" >');
$webUi->addCard("General Settings",getHtmlSettingsGeneral());
$webUi->addCard("Grocy API",getHtmlSettingsGrocyApi());
$webUi->addCard("Websockets",getHtmlSettingsWebsockets());
$webUi->addHtml(getHtmlSettingsHiddenValues());
$webUi->addFooter();
$webUi->printHtml();

?>
