<?php
session_start();

use HTMLClient\Tag;
use HTMLClient\TagPos;

class PageManager {
    private static $current;
    private static $allowed;

    public static function onHTMLBegin() {
        if(
            !isset($_SESSION["user"]) ||
            $_SESSION["user"] == NULL
        ) {
            $_SESSION["user"] = NULL;

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

    public static function HandleArgPermission($value, $plugin) {
        $rights = isset($_SESSION["user"]) ? $_SESSION["user"]["rights"] : 999;

        if(!isset(self::$allowed)) {
            self::$allowed = array();
        }

        if($rights < $value) {
            array_push(self::$allowed, $plugin);
        }
    }

    public static function HandleArgs($value, $plugin) {
        if(!in_array($plugin, self::$allowed)) {
            return;
        }

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