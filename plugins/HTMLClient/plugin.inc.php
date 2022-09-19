<?php
require "HTMLClient.inc.php";

return array(
// Basic plugin info
// --------------------------------------
    "permission"    => 1000,
    "moduleName"    => "HTMLClient",
    "setup"         => function($value, $plugin) {
        HTMLClient\Client::setup();
    }
);
?>