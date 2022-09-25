<?php

use HTMLClient\Tag;
use HTMLClient\TagPos;

return new Tag(
    "FORM",
    options: array(
        "method" => "post",
        "id"     => "loginform",
        "action" => "?module=PageManager&page=login&p=1"
    ),
    childs: array(
        new Tag(
            "H1",
            content: function() {
                echo "Einloggen";
                if(isset($_GET["wrongpwd"])) {
                    $infotext = new Tag(
                        "P",
                        options: array(
                            "id"    => "WrongPWDInfoTextBox"
                        ),
                        content: function() {
                            echo "Username and/or password is invalid. Please try again."; 
                        }
                    );

                    $infotext->Call(TagPos::END);
                }
            }
        ),
        new Tag(
            "INPUT",
            options: array(
                "type"          => "text",
                "name"          => "user",
                "placeholder"   => "Username",
                "id"            => "UsernameInput"
            )
        ),
        /*new Tag(
            "BR",
            options: array(
                "id"    => "LoginBR0"
            ),
            flags: Tag::FLAG_SINGLE
        ),*/
        new Tag(
            "INPUT",
            options: array(
                "type"          => "password",
                "name"          => "pwd",
                "placeholder"   => "Password",
                "id"            => "PasswordInput"
            )
        ),
        /*new Tag(
            "BR",
            options: array(
                "id"    => "LoginBR1"
            ),
            flags: Tag::FLAG_SINGLE
        ),*/
        new Tag(
            "BUTTON",
            options: array(
                "id"    => "LoginSubmitButton"
            ),
            content: function() {
                echo "Einloggen";
            }
        )
    ),
    flags: Tag::FLAG_AUTO
);