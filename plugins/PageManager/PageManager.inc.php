<?php
use HTMLClient\Tag;
use HTMLClient\TagPos;

class PageManager {
    private static $current;

    public static function onHTMLBegin() {
        session_start();
        
        if(
            !isset($_SESSION["uid"]) ||
            $_SESSION["uid"] == NULL
        ) {
            $_SESSION["uid"] == NULL;

            if(
                !isset($_GET["module"]) ||
                !isset($_GET["page"]) ||
                $_GET["module"] != "PageManager" ||
                $_GET["page"]   != "login"
            ) {
                header("Location: ?module=PageManager&page=login");
                exit();
            }
        }
    }

    public static function HandleArgs($value, $plugin) {
        if(!(isset($_GET["module"]) && isset($_GET["page"]))) {
            if(isset($value["GLOBHOME"])) {
                header("Location: ?module=$plugin&page=GLOBHOME");
                exit();
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