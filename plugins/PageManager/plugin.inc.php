<?php
require_once "PageManager.inc.php";

use HTMLClient\TagPos;

return array(
// Basic plugin info
// --------------------------------------
    "permission"    => 1000,
    "moduleName"    => "PageManager",
    "args"          => array(
        "pages"     => function($value, $plugin) {
            PageManager::HandleArgs($value, $plugin);
        }
    ),
    "events"        => array(
        "body" => function() {
            PageManager::onBody();
        }
    ),
    "pages"         => array(
        "login"     => function() {
            if(isset($_GET["p"]) && $_GET["p"] == 1) {
                echo "ABC";
            }
            else {
                $LoginPage = require("login.inc.php");
                $LoginPage->Call(TagPos::END);
            }
        }
    )
)
?>