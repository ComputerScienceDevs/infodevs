<?php
use HTMLClient\Tag;

return new Tag(
    "DIV",
    options: array(
        "id" => "GlobHomeContainer"
    ),
    childs: array(
        new Tag(
            "H1",
            options: array(
                "id" => "GlobHomeTitle"
            ),
            content: function() {
                echo "Main page";
            }
        ),
        new Tag(
            "P",
            options: array(
                "id" => "GlobHomeMainText"
            ),
            content: function() {
                echo "Welcome on the NNetwork management server.";
            }
        )
    ),
    flags: Tag::FLAG_AUTO
);
?>