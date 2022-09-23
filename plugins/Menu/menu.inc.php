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
        $EntryTags = array(
            new Tag(
                "DIV",
                options: array(
                    "id"    => "MainIcon",
                    "class" => "icon",
                ),
                content: function() {
                    echo "&#xF0EF";
                }
            )
        );

        $index = 0;
        foreach (self::$entrys as $entry) {
            $id = "MenuEntry".strval($index);

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

            $index++;
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