<?php

error_reporting(E_ALL);

require __DIR__ . "/src/Console.php";
require __DIR__ . "/src/Pulse.php";

class Build extends Pulse {
    private const COMMON_FLAGS = "-O2 -Wall -Wextra -pedantic";

    protected function build() {
        $recipe = (new Console())
            ->addCmd("gcc test.c " . self::COMMON_FLAGS . " -o test")
            ->run()
            ->printResults();

        return $recipe->isSuccess();
    }

    protected function run() {
        $this->runRecipe('build');

        $recipe = (new Console())
            ->addCmd("call test.exe")
            ->run()
            ->printResults();

        return $recipe->isSuccess();
    }
}

function main() {
    header('Content-type: text/plain; charset=utf-8');
    $build = Build::getInstace();
    $build->doRouting();
}

main();
