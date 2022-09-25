<?php
class PDOManager {
    public static $PDOConfig;
    public static $pdo;

    public static function Setup() {
        self::$PDOConfig = require(".config/config.inc.php");
        self::$pdo = new PDO('mysql:host='.self::$PDOConfig["host"].';dbname='.self::$PDOConfig["db"].';charset=utf8', self::$PDOConfig["user"], self::$PDOConfig["pwd"]);
    
        $statement = self::$pdo->prepare("SHOW TABLES LIKE 'users'");
        $statement->execute();
    
        if (!$statement->fetch()) {
            header("Location: plugins/PDO/setup.php");
        }
    }
}
?>