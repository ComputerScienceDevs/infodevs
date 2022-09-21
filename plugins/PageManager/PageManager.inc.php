<?php
use HTMLClient\Tag;
use HTMLClient\TagPos;

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

    public static function onBody() {
        if(!isset(self::$current)) {
            echo "Seite nicht gefunden ...";
            return;
        }

        $page = new Tag(
            "DIV",
            options: array(
                "id" => "content"
            ),
            content: self::$current,
            flags: Tag::FLAG_AUTO
        );

        $page->Call(TagPos::END);
    }
}

?>