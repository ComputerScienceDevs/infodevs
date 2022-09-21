<?php
namespace HTMLClient {
    enum TagPos : string {
        case BEGIN  = "Begin";
        case END    = "End";
        case MAIN   = "";
    }

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
        // Properties
        private string  $tag;
        private array   $opts;
        private string  $tagEv;
        private bool    $flags;

        // All childs as array
        public array    $childs;

        /* 
        Flags
        =====

        Note for BaseFlags: (BaseFlag0 | Baseflag1 | BaseFlag2 | ...) should be the same as the main flag.
        */

        //           Flag             HexValue     Binary           BaseFlags
        //           ----             --------     ------           ---------
        public const FLAG_NONE      = 0x00;     // 0000 0000        [None]
        public const FLAG_AUTO      = 0x01;     // 0000 0001        [None]
        public const FLAG_SINGLE    = 0x02;     // 0000 0010        [None]
        
        private function isFlagSet($flag) : ?bool {
            return(($flag & $this->flags) == $flag);
        }

        public function __construct(
            string $tagname,
            array $options = array(),
            array $childs = array(),
            $flags = self::FLAG_NONE,
            callable $content = NULL
        ) {
            // Global vars
            global $events;

            // Assign values to properties
            $this->flags = $flags;
            $this->tag = strtolower($tagname);
            $this->opts = $options;
            $this->childs = $childs;
    
            // Generate event base name, result should be div#content for example
            $this->tagEv = $this->tag;
            if(isset($options["id"])) {
                $this->tagEv = $this->tag."#".$this->opts["id"];
            }
    
            // Event TagEvBegin
            createEvent(
                $this->tagEv.TagPos::BEGIN->value,
                function () {
                    $this->Send(TagPos::BEGIN);
                }
            );
            
            if(!$this->isFlagSet(self::FLAG_SINGLE)) {
                // Event tagEv
                createEvent(
                    $this->tagEv,
                    function() {
                        $this->Send(TagPos::MAIN);
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
                    $this->tagEv.TagPos::END->value,
                    function () {
                        $this->Send(TagPos::END);
                    }
                );
            }
    
            if($this->isFlagSet(self::FLAG_AUTO)) {
                $this->Call(TagPos::BEGIN);
                if(!$this->isFlagSet(self::FLAG_SINGLE)) {
                    $this->Call(TagPos::MAIN);
                }
            }
        }
    
        public function Call(TagPos $what): ?int {
            global $events;
            $EventToCall = ($this->tagEv).$what->value;

            foreach ($events[$EventToCall] as $handle) {
                $handle();
            }

            return count($events[$EventToCall]);
        }
    
        public function Send(TagPos $what) {
            $showOptions = false;
            
            switch($what) {
                case TagPos::BEGIN:
                    echo "<";
                    $showOptions = true;
                    break;
                case TagPos::END:
                    echo "</";
                    break;
                case TagPos::MAIN:
                    foreach($this->childs as $child) {
                        $child->Call(TagPos::BEGIN);
                        $child->Call(TagPos::MAIN);
                        $child->Call(TagPos::END);
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
            $html = new Tag(
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

            $html->Call(TagPos::END);
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