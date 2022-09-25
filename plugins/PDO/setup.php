<?php
$PDOConfig = require(".config/config.inc.php");
$pdo = new PDO('mysql:host='.$PDOConfig["host"].';dbname='.$PDOConfig["db"].';charset=utf8', $PDOConfig["user"], $PDOConfig["pwd"]);

$statement = $pdo->prepare("SHOW TABLES LIKE 'users'");
$statement->execute();

if ($statement->fetch()) {
    header("Location: ../../");
}

if(!isset($_POST["vk"]) || $_POST["vk"] != $PDOConfig["auth-key"]) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Setup</title>
    </head>
    <body>
        <h1>Setup</h1>
        <p>Please enter a valid authentication key.</p>
        <form action="" method="post">
            <input type="password" name="vk" id="AuthKey" placeholedr="Key">
            <input type="submit" value="Next">
        </form>
    </body>
    </html>
    <?php
}
else if(
    !isset($_POST["setup-admin"]) ||
    !isset($_POST["user"]) ||
    !isset($_POST["pwd"]) ||
    !isset($_POST["pwdc"]) ||
    $_POST["pwd"] != $_POST["pwdc"]
){
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Setup - Authenthication was succesfull.</title>
    </head>
    <body>
        <h1>Create an admin account</h1>
        <form action="" method="post">
            <input type="hidden" name="setup-admin" value="1">
            <input type="hidden" name="vk" value="<?php echo $_POST["vk"]; ?>">
            <input type="text" name="user" placeholder="username">
            <input type="password" name="pwd" placeholder="password">
            <input type="password" name="pwdc" placeholder="check password">
            <input type="submit" value="Submit">
        </form>
    </body>
    </html>
    <?php
}
else {
    $statement = $pdo->prepare("CREATE TABLE `users` (`id` INT NOT NULL , `rights` INT NOT NULL , `name` VARCHAR(255) NOT NULL , `pwd` VARCHAR(255) NOT NULL , `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `autologin` VARCHAR(255) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB");
    $statement->execute();

    $statement = $pdo->prepare("INSERT INTO `users` (`id`, `rights`, `name`, `pwd`) VALUES (?, ?, ?, ?)");
    $statement->execute(array(0, 0, $_POST["user"], password_hash($_POST["pwd"], PASSWORD_DEFAULT)));

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Setup - Info</title>
    </head>
    <body>
        <h1>Setup completed</h1>
        <a href="../../">Homepage</a>
    </body>
    </html>
    <?php
}

?>