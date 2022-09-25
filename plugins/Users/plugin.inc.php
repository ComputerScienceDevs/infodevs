<?php

return array(
    "moduleName"    => "Users",
    "permission"    => 2,
    "pages"         => array(
        "manage"    => function() {
            $page = require("Pages/manage.inc.php");
            $page->Call(HTMLClient\TagPos::END);
        }
    ),
    "menu"          => array(
        array(
            "label" => "<span class=\"icon\">&#xE80B;</span> Users",
            "url"   => "?module=Users&page=manage"
        )
    )
)

?>