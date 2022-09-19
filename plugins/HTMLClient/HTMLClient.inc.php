<?php
namespace HTMLClient {
    const BEGIN     = 0;
    const END       = 1;
    const MAIN      = 2;

    const CHILDS    = 3;

    const STR = array(
        BEGIN   => "Begin",
        END     => "End",
        MAIN    => ""
    );

    $events = array();

    class Tag {
        private string  $tag;
        private array   $opts;
        private string  $tagEv;
        private bool    $auto;

        public array    $childs;

        // Creates an event
        private function createEvent(
            &$EventArrayRef,
            callable $handle
        ) {
            if(isset($EventArrayRef)) {
                array_unshift($EventArrayRef, $handle);
            }
            else {
                $EventArrayRef = array($handle);
            }
        }
    
        public function __construct(
            string $tagname,
            array $options = array(),
            array $childs = array(),
            bool $Nauto = false,
        ) {
            // Global vars
            global $events;

            // Assign values to properties
            $this->auto = $Nauto;
            $this->tag = strtolower($tagname);
            $this->opts = $options;
            $this->childs = $childs;
    
            // Generate event base name, result should be div#content for example
            $this->tagEv = $this->tag;
            if(isset($options["id"])) {
                $this->tagEv = $this->tag."#".$options["id"];
            }
    
            // Event TagEvBegin
            $this->createEvent(
                $events[$this->tagEv."Begin"],
                function () {
                    $this->Send(BEGIN);
                }
            );
    
            // Event tagEv
            $this->createEvent(
                $events[$this->tagEv],
                function() {
                    $this->Send(CHILDS);
                }
            );

            // Event TagEvEnd
            $this->createEvent(
                $events[$this->tagEv."End"],
                function () {
                    $this->Send(END);
                }
            );
    
            if($this->auto) {
                $this->Call(BEGIN);
                $this->Call(MAIN);
            }
        }
    
        public function Call(int $what): ?int {
            global $events;
            $EventToCall = ($this->tagEv).STR[$what];
            $n = 0;
            foreach ($events[$EventToCall] as $handle) {
                $handle();
                $n++;
            }

            return $n;
        }

        public function __destruct() {
            if($this->auto) {
                $this->Call(END);
            }
        }
    
        public function Send($what) {
            $showOptions = false;
            
            switch($what) {
                case BEGIN:
                    echo "<";
                    $showOptions = true;
                    break;
                case END:
                    echo "</";
                    break;
                case CHILDS:
                    foreach(array_reverse($this->childs) as $child) {
                        $child->Call(BEGIN);
                        $child->Call(MAIN);
                        $child->Call(END);
                    }
                    return;
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
                array(
                    new Tag("HEAD"),
                    new Tag("BODY")
                ),
                true
            );
        }
    }

    function addEventListener($ev, $handle) {
        if(isset($events[$ev])) {
            array_push($events[$ev], $handle);
        }
        else {
            // Move createEvent in HTMLClient\ and call it would be maybe better
            $events[$ev] = array($handle);
        }
    }
}

namespace {
    ?>
    <!DOCTYPE html>
    <?php
    if(isset($_GET["test"])) {
        HTMLClient\Client::setup();
    }
}

?>