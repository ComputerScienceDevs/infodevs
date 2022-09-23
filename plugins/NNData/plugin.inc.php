<?php
return array(
// Basic plugin info
// --------------------------------------
    "permission"    => 10,
    "moduleName"    => "NNData",
    "menu"          => array(
        array(
            "url"       => "?module=NNData&page=GLOBHOME",
            "label"     => "<span class=\"icon\">&#xE800;</span> Home"
        ),
        array(
            "url"       => "?module=NNData&page=home",
            "label"     => "<span class=\"icon\">&#xF1C0;</span> NNData"
        )
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