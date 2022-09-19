<?php
namespace HTMLClient {
    const BEGIN = 0;
    const END   = 1;
    const MAIN  = 2;

    const STR = array(
        BEGIN   => "Begin",
        END     => "End",
        MAIN    => ""
    );

    $events = array();

    class Tag {
        public $tag;
        public $opts;
        public $tagEv;
        public $auto;
    
        public function __construct(
            $tagname,
            $options = array(),
            $main = NULL,
            $Nauto = true,
        ) {
            global $events;
            $this->auto = $Nauto;

            $this->tag = strtolower($tagname);
            $this->tagEv = $this->tag;
            $this->opts = $options;
    
            if(isset($options["id"])) {
                $this->tagEv = $this->tag."#".$options["id"];
            }
    
            $events[$this->tagEv."Begin"]   = array(
                function () {
                    $this->send(BEGIN);
                }
            );
    
            if($main == NULL) {
                $events[$this->tagEv]  = array();
            }
            else {
                $events[$this->tagEv]  = array($main);
            }
            
            $events[$this->tagEv."End"]     = array(
                function () {
                    $this->send(END);
                }
            );
    
            if($this->auto) {
                self::Call($this, BEGIN);
                self::Call($this, MAIN);
            }
        }
    
        public static function Call(Tag $Tagobj, int $what): ?int {
            global $events;
            $EventToCall = ($Tagobj->tagEv).STR[$what];
            $n = 0;
            foreach ($events[$EventToCall] as $handle) {
                $handle();
                $n++;
            }

            return $n;
        }

        public function __destruct() {
            if($this->auto) {
                self::Call($this, END);
            }
        }
    
        public function send($what) {
            $showOptions = false;
            
            switch($what) {
                case BEGIN:
                    echo "<";
                    $showOptions = true;
                    break;
                case END:
                    echo "</";
                    break;
            }
    
            echo $this->tag;
    
            if($showOptions) {
                foreach($this->opts as $name => $value) {
                    echo(" $name=\"$value\"");
                }
            }
    
            echo ">";
        }
    }
    
    class Client {
        public static function addEvent($plugin, $value) {
            global $events;
            foreach($value as $event => $handle) {
                $events[$event] = $handle;
            }
        }
    
        public static function callEvent($ev) {
            global $events;

            if(!isset($events[$ev])) {
                exit("plugins/HTMLClient/HTMLClient.inc.php at line 113: Unable to call HTML Event");
            }
    
            foreach ($events[$ev] as $handle) {
                $handle();
            }
        }
    
        public static function setup() {
            new Tag(
                "HTML", array(
                    "lang" => "en"
                ),
                (function() {
                    new Tag("HEAD");
                })
            );
        }
    }
}

namespace {
    ?>
    <!DOCTYPE html>
    <?php
    HTMLClient\Client::setup();
}

?>