<?php

use HTMLClient\Tag;
use HTMLClient\TagPos;

class Menu {
    private static array $entrys;

    private static function initArrays() {
        if(!isset(self::$entrys)) {
            self::$entrys = array();
        }
    }

    public static function AddEntry(array $value, string $plugin) {
        self::initArrays();

        if(!(isset($value["label"]) && isset($value["url"]))) {
            die("Error: Cannot create menu entry for plugin $plugin. \$value array is invalid.");
        }

        array_push(self::$entrys, $value);
    }

    public static function onBody() {
        $EntryTags = array();

        foreach (self::$entrys as $entry) {
            $id = "MenuEntry".$entry["label"];

            array_push(
                $EntryTags,
                new Tag(
                    "A",
                    array(
                        "href" => $entry["url"],
                        "id"   => $id
                    ),
                    array(),
                    content: function() use ($entry) {
                        echo $entry["label"];
                    }
                )
            );
        }

        $menu = new Tag(
            "DIV",
            array(
                "id" => "menu"
            ),
            $EntryTags,
            flags: Tag::FLAG_AUTO
        );

        $menu->Call(TagPos::END);
    }
};

?>