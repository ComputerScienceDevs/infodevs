<?php
class PluginLoader {
    private static array $setupHandles;

    public static function LoadPlugins() {
        // Load plugin list
        $plugins = require("plugins.inc.php");

        // Load additional handeled args
        $additionalArgs = array();
        foreach($plugins as $name => $path) {
            $config = require($path."/plugin.inc.php");
            if(isset($config["args"])) {
                foreach($config["args"] as $arg => $handle) {
                    if(isset($additionalArgs[$arg])) {
                        if(!isset($config["useOtherPluginArgs"]) || !$config["useOtherPluginArgs"]) {
                            echo "Warning: Plugin ".$config["moduleName"]."uses other plugin args. Set \"useOtherPluginArgs\" in plugin.inc.php to true to supress this warning.";
                        }
                        array_push($additionalArgs[$arg], $handle);
                    }
                    else {
                        $additionalArgs[$arg] = array($handle);
                    }
                }
            }
        }

        // Init setupHandles to store handles
        self::$setupHandles = array();
        
        // Load setup handles
        foreach($plugins as $name => $path) {
            $config = require($path."/plugin.inc.php");
            foreach($additionalArgs as $arg => $handle) {
                if(isset($config[$arg])) {
                    $handle($config[$arg], $config["moduleName"]);
                }
            }
            if(isset($config["setup"])) {
                array_push(self::$setupHandles, $config["setup"]);
            }
        }
    }

    public static function Setup() {
        foreach(self::$setupHandles as $handle) {
            $handle();
        }
    }
}
?>