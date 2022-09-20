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

    function addEventListener($event, $handle) {
        global $events;

        if(isset($events[$event])) {
            array_push($events[$event], $handle);
        }
        else {
            createEvent($event, $handle);
        }
    }

    // Creates an event
    function createEvent(
        string $event,
        callable $handle
    ) {
        global $events;
        if(isset($events[$event])) {
            array_unshift($events[$event], $handle);
        }
        else {
            $events[$event] = array($handle);
        }
    }

    class Tag {
        private string  $tag;
        private array   $opts;
        private string  $tagEv;
        private bool    $auto;

        public array    $childs;
    
        public function __construct(
            string $tagname,
            array $options = array(),
            array $childs = array(),
            bool $auto = false,
            callable $content = NULL
        ) {
            // Global vars
            global $events;

            // Assign values to properties
            $this->auto = $auto;
            $this->tag = strtolower($tagname);
            $this->opts = $options;
            $this->childs = $childs;
    
            // Generate event base name, result should be div#content for example
            $this->tagEv = $this->tag;
            if(isset($options["id"])) {
                $this->tagEv = $this->tag."#".$options["id"];
            }
    
            // Event TagEvBegin
            createEvent(
                $this->tagEv."Begin",
                function () {
                    $this->Send(BEGIN);
                }
            );
    
            // Event tagEv
            createEvent(
                $this->tagEv,
                function() {
                    $this->Send(CHILDS);
                }
            );

            if($content != NULL) {
                addEventListener(
                    $this->tagEv,
                    $content
                );
            }

            // Event TagEvEnd
            createEvent(
                $this->tagEv."End",
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

            foreach ($events[$EventToCall] as $handle) {
                $handle();
            }

            return count($events[$EventToCall]);
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
        public static function setup() {
            new Tag(
                "HTML",
                array(
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