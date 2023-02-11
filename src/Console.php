<?php

declare(strict_types=1);

require __DIR__ . "/Command.php";

function isWindows() {
    return substr(php_uname(), 0, 7) == "Windows";
}

class Console {
    /** @var array|Command[] $cmds */
    private array $cmds;
    private bool $success;

    public function __construct() {
        $this->cmds = [];
        $this->success = true;
    }

    public function addCmd(string $cmd) {
        $this->cmds[] = new Command($cmd);
        return $this;
    }

    public function run() {
        foreach ($this->cmds as $cmd) {
            exec($this->buildCmd($cmd), $cmd->output, $cmd->code);
            $cmd->done = true;

            if ($cmd->code !== 0) {
                $this->success = false;
                break;
            }
        }

        return $this;
    }

    public function printResults() {
        foreach ($this->cmds as $cmd) {
            if ($cmd->done) {
                echo $cmd . PHP_EOL;
            }
        }

        return $this;
    }

    public function isSuccess() {
        return $this->success;
    }

    private function buildCmd(Command $cmd) {
        return $cmd->cmd . " 2>&1";
    }
}
