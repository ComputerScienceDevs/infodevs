<?php

use HTMLClient\Tag;

function getUserList() : ?array {
    $result = array(
        new Tag(
            "TR",
            options: array(
                "id" => "userManagementViewHead"
            ),
            childs: array(
                new Tag(
                    "TH",
                    options: array(
                        "id" => "userManagementViewHeadEntry0"
                    ),
                    content: function() {
                        echo "Id";
                    }
                ),
                new Tag(
                    "TH",
                    options: array(
                        "id" => "userManagementViewHeadEntry1"
                    ),
                    content: function() {
                        echo "User";
                    }
                ),
                new Tag(
                    "TH",
                    options: array(
                        "id" => "userManagementViewHeadEntry2"
                    ),
                    content: function() {
                        echo "Created";
                    }
                ),
                new Tag(
                    "TH",
                    options: array(
                        "id" => "userManagementViewHeadEntry3"
                    ),
                    content: function() {
                        echo "Updated";
                    }
                ),
                new Tag(
                    "TH",
                    options: array(
                        "id" => "userManagementViewHeadEntry4"
                    ),
                    content: function() {
                        echo "Rights";
                    }
                ),
                new Tag(
                    "TH",
                    options: array(
                        "id" => "userManagementViewHeadEntry5"
                    ),
                    content: function() {
                        echo "Options";
                    }
                )
            )
        )
    );

    $statement = PDOManager::$pdo->prepare("SELECT `name`, `id`, `rights`, `created`, `updated` FROM `users`");
    $statement->execute();

    while($row = $statement->fetch()) {
        array_push(
            $result,
            new Tag(
                "TR",
                options: array(
                    "id"    => "usermanagementViewEntryFor".$row["name"]
                ),
                childs: array(
                    new Tag(
                        "TD",
                        options: array(
                            "id"    => "userManagementViewEntryValue".$row["name"]."Id"
                        ),
                        content: function() use($row) {
                            echo $row["id"];
                        }
                    ),
                    new Tag(
                        "TD",
                        options: array(
                            "id"    => "userManagementViewEntryValue".$row["name"]."Name"
                        ),
                        content: function() use($row) {
                            echo $row["name"];
                        }
                    ),
                    new Tag(
                        "TD",
                        options: array(
                            "id"    => "userManagementViewEntryValue".$row["name"]."TimeCreated"
                        ),
                        content: function() use($row) {
                            echo $row["created"];
                        }
                    ),
                    new Tag(
                        "TD",
                        options: array(
                            "id"    => "userManagementViewEntryValue".$row["name"]."TimeUpdated"
                        ),
                        content: function() use($row) {
                            echo $row["updated"];
                        }
                    ),
                    new Tag(
                        "TD",
                        options: array(
                            "id"    => "userManagementViewEntryValue".$row["name"]."Rights"
                        ),
                        content: function() use($row) {
                            echo $row["rights"];
                        }
                    ),
                    new Tag(
                        "TD",
                        options: array(
                            "id"    => "userManagementViewEntryOptions".$row["name"]
                        ),
                        childs: array(
                            // ...
                        ),
                        content: function() {
                            echo "<span style=\"opacity: 0.7;\">Still in developing ...</span>";
                        }
                    )
                )
            )
        );
    }

    return $result;
}

return new Tag(
    "DIV",
    options: array(
        "id" => "UserManagementMainBox"
    ),
    childs: array(
        new Tag(
            "H1",
            options: array(
                "id" => "userManagementMainTitle"
            ),
            content: function() {
                echo "Management";
            }
        ),
        new Tag(
            "TABLE",
            options: array(
                "id" => "userManagementListView"
            ),
            childs: getUserList()
        )
    ),
    flags: Tag::FLAG_AUTO
)
?>