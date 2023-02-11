<?php

declare(strict_types=1);

class Command {
    public $cmd;
    public $code;
    public $output;
    public $done;

    public function __construct($cmd) {
        $this->cmd = str_replace("'", '"',$cmd);
        $this->code = 0;
        $this->output = [];
        $this->done = false;
    }

    public function __toString() {
        $res = "";
        $res .= $this->code === 0 ? "[+] " : "[-] ";
        $res .= $this->cmd;

        if (count($this->output) > 0) {
            $res .= " -> ";
            $res .= implode(PHP_EOL, $this->output);
        }

        return $res;
    }
}
