<?php
require "HTMLClient.inc.php";

return array(
// Basic plugin info
// --------------------------------------
    "permission"    => 1000,
    "moduleName"    => "HTMLClient",
    "setup"         => function() {
        HTMLClient\Client::setup();
    },
    "args"          => array(
        "events"    => function($value, $plugin) {

        }
    )
);
?>