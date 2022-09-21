<?php
require_once "PageManager.inc.php";

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
    )
)
?>