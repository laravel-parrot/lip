<?php

namespace LaravelParrot\Setup\Command;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command as Command;

 class SetupCommand extends Command
{

  protected $signature = 'laravel-parrot:setup';
  protected $description = 'laravel parrot setup';

  /**
   * Create a new controller creator command instance.
   *
   * @param  \Illuminate\Filesystem\Filesystem  $files
   * @return void
   */
  public function __construct(Filesystem $files)
  {
      parent::__construct();

      $this->files = $files;
  }

  /**
   * Execute the console command.
   *
   * @return bool|null
   */
  public function handle(){
    $path = base_path('composer.json');

    $this->files->put($path, $this->buildClass());

    $this->info($this->type.' created successfully.');
  }

  /**
   * Build the class with the given name.
   *
   * @param  string  $name
   * @return string
   */
  protected function buildClass()
  {
      $stub = $this->files->get(base_path('composer.json'));

      return $this->replaceNamespace($stub);
  }

  /**
   * Replace the namespace for the given stub.
   *
   * @param  string  $stub
   * @param  string  $name
   * @return $this
   */
  protected function replaceNamespace(&$stub)
  {
    if (! str_contains($stub,'merge-plugin')) {
      $stub = str_replace(
          [
            '"extra": {',
            '"App\\\\": "app/"',
          ],
          [
            '"extra": {
              "merge-plugin": {
                "include": [
                    "parrot/parrot/composer.json",
                    "pluginx/*/composer.json"
                    ],
                "recurse": true,
                "replace": false,
                "ignore-duplicates": false,
                "merge-dev": true,
                "merge-extra": false,
                "merge-extra-deep": false,
                "merge-scripts": false
              },',
              '"App\\\\": "app/",
          "Parrot\\\\Parrot\\\\": "parrot/parrot/"',
        ],
          $stub
      );
    }
      return $stub;
  }

}
 ?>