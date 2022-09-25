<?php
require_once "PageManager.inc.php";

use HTMLClient\TagPos;

return array(
// Basic plugin info
// --------------------------------------
    "permission"    => 1000,
    "moduleName"    => "PageManager",
    "args"          => array(
        "permission" => function($value, $plugin) {
            PageManager::HandleArgPermission($value, $plugin);
        },
        "pages"     => function($value, $plugin) {
            PageManager::HandleArgs($value, $plugin);
        }
    ),
    "events"        => array(
        "body"      => function() {
            PageManager::onBody();
        },
        "htmlBegin" => function() {
            PageManager::onHTMLBegin();
        }
    ),
    "pages"         => array(
        "login"     => function() {
            if(isset($_GET["p"]) && $_GET["p"] == 1) {
                $statement = PDOManager::$pdo->prepare("SELECT * FROM `users` WHERE `name` = ?");
                $statement->execute(array($_POST["user"]));

                $row = $statement->fetch();
                
                if($row && password_verify($_POST["pwd"], $row["pwd"])) {
                    session_regenerate_id();
                    $_SESSION["user"] = $row;
                    header("Location: ?lerror=false");
                }
                else {
                    header("Location: ?module=PageManager&page=login&wrongpwd");
                }
            }
            else {
                $LoginPage = require("login.inc.php");
                $LoginPage->Call(TagPos::END);
            }
        },
        "logout"    => function() {
            $_SESSION["user"] = NULL;
            header("Location: ?lerror=0");
        }
    ),
    "menu"      => PageManager::is_logged_in() ? array(
        array(
            "url"   => "?module=PageManager&page=logout",
            "label" => "<span class=\"icon\">&#xE802</span> Logout",
            "class" => "menuitem-right"
        )
    ) : 
    array()
)
?>