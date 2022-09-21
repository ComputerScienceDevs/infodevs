<?php
return array(
// Basic plugin info
// --------------------------------------
    "permission"    => 10,
    "moduleName"    => "NNData",
    "menu"          => array(
        "url"       => "?module=NNData&page=home",
        "label"     => "NNData"
    ),
    "pages"         => array(
        "home"      => function() {
            echo "Hallo Welt";
        },
        "GLOBHOME"  => function() {
            echo "GlobHome";
        }
    )
);
?>