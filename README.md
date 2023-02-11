# PULSE

**PULSE** is **P**HP B**u**i**l**d **S**yst**e**m, which recipes are triggerd
with HTTP request on corresponding endpoints.

## Quick Start

### How to create your own build script

First, you need to create a php script. \
You need to point your http server to it. It will manage routing by itself
automatically.

```console
$ php -S localhost:1337 build.php
```

In this script you need to create a class that will be extended from `Pulse`
base class. \
Get class exeplar and run `doRouting()` method

```php
class Example extended Pulse {
    protected function recipe() {
        // Here yours code
        echo "Hello, Pulse!";
        return true;
    }
}

header('Content-type: text/plain; charset=utf-8');
$example = Example::getInstance();
$example->doRouting();
```

Every `protected` method will be treated as recipe. \
Recipe must return bool to indicate if its failed or not.

Automatically **PULSE** will create a route with name of your recipe as endpoint
and it will call your recipe on HTTP request on this endpoint.

```console
$ curl localhost:1337/recipe
Hello, Pulse!
```

Also you can run several recipes with this sort of request:

```console
$ curl localhost:1337/build/run/test/foo/bar/baz
```

Or run every single one with `/` endpoint:

```console
$ curl localhost:1337/
```

If you want to make sure that one recipe runs **before** another, you should add
`$this->runRecipe('firstRecipe');` to your recipe:

```php
class Example extended Pulse {
    protected function build() {
        // Building binary
    }

    protected function install() {
        $this->runRecipe('build');
        // Installing binary
    }
}
```

```console
$ curl localhost:1337/install       # Will call `build` first
$ curl localhost:1337/build/install # This works too, `build` will not be called twice
```

### Console

`Console` - is a class-wrapper for `exec()` to make creation of recipies easier.

Example:

```
class Example extended Pulse {
    protected function build() {
        $recipe = (new Console())
            ->addCmd("gcc test.c -o test")
            ->run()
            ->printResults();

        return !$recipe->isFailed();
    }
}
```

You can chain command to not execute next commands if one fails:

```
class Example extended Pulse {
    protected function build() {
        $recipe = (new Console())
            ->addCmd("mkdir out/")
            ->addCmd("gcc test.c -o test")
            ->addCmd("mv test out/")
            ->run()
            ->printResults();

        return $recipe->isSuccess();
    }
}
```

Check (build.php)[./build.php] for simple working example of using PULSE with
Console.

## TODO

 - [X] Creating recipes
 - [X] Starting recipes with HTTP request
 - [X] Assuring that some recipes will be called before others
 - [X] Handy-ish way to call cosonle comands
 - [X] Run all recipes on `/` endpoint
 - [ ] ...
