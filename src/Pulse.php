<?php

declare(strict_types=1);

abstract class Pulse {
    protected static $instance;

    private array $finishedRecipes;

    private function __construct() {
        $this->finishedRecipes = [];
    }

    /**
     * @return self
     */
    static public function getInstace() {
        if (self::$instance === null) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public function doRouting() {
        $recipes = [];
        $routes = ltrim($_SERVER['REQUEST_URI'],'/' );
        $reflection = new ReflectionObject($this);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PROTECTED) as $method) {
            if ($method->class === self::class) {
                continue;
            }

            $recipes[] = $method->getName();
        }

        foreach (explode('/', $routes) as $recipe) {
            if (!in_array($recipe, $recipes)) {
                echo "Error: no recipe `$recipe` found." . PHP_EOL;
                die;
            }

            if (in_array($recipe, $this->finishedRecipes)) {
                continue;
            }

            $this->runRecipe($recipe);
        }
    }

    protected function runRecipe($recipe) {
        if (in_array($recipe, $this->finishedRecipes)) {
            return;
        }

        $result = $this->$recipe();
        $this->finishedRecipes[] = $recipe;

        if (!$result) {
            echo "Error: recipe `$recipe` failed.";
            die;
        }
    }
}
