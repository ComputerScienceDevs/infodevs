<?php
use HTMLClient\Tag;

class PageManager {
    private static $current;

    public static function HandleArgs($value, $plugin) {
        if(!(isset($_GET["module"]) && isset($_GET["page"]))) {
            if(isset($value["GLOBHOME"])) {
                self::$current = $value["GLOBHOME"];
                return;
            }
        }
        
        if($_GET["module"] == $plugin && isset($value[$_GET["page"]])) {
            self::$current = $value[$_GET["page"]];
        }
    }

    public static function Setup() {
        if(!isset(self::$current)) {
            echo "Seite nicht gefunden ...";
            return;
        }

        new Tag(
            "DIV",
            options: array(
                "id" => "content"
            ),
            content: self::$current,
            auto: true
        );
    }
}

?>