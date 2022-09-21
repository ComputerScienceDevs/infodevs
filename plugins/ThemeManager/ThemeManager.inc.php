<?php

namespace ThemeManager {
    use HTMLClient\Tag;

    enum Theme : int {
        case Dark = 0;
        case Light = 1;
    };

    class Manager {
        private static Theme $current;

        public static function UpdateCurrent() {
            self::$current = Theme::tryFrom($_COOKIE["theme"]) ?? Theme::Dark;
        }

        public static function Update(Theme $t) {
            \setcookie("theme", $t->value, time()+60*60*24*30, "/");
            
            self::UpdateCurrent();
        }

        public static function Init() {
            if(!isset($_COOKIE["theme"])) {
                \setcookie("theme", Theme::Dark->value, time()+60*60*24*30);
            }

            self::UpdateCurrent();
        }

        public static function onHead() {
            self::Init();

            $StaticPath = "plugins/".basename(__DIR__)."/static/";
            $Tpath = $StaticPath;

            switch(self::$current) {
                case Theme::Dark:
                    $Tpath .= "dark.css";
                    break;
                case Theme::Light:
                    $Tpath .= "light.css";
                    break;
            }

            $themeCSS = new Tag(
                "link",
                options: array(
                    "rel"   => "stylesheet",
                    "href"  => $Tpath
                ),
                flags: Tag::FLAG_AUTO | Tag::FLAG_SINGLE
            );

            $baseCSS = new Tag(
                "link",
                options: array(
                    "rel"   => "stylesheet",
                    "href"  => $StaticPath."base.css"
                ),
                flags: Tag::FLAG_AUTO | Tag::FLAG_SINGLE
            );
        }
    }   
}

?>