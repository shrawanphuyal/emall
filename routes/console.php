<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

// below commands are created by Ashish Dhamala
Artisan::command('make:view {name} {path?}', function ($name, $path = "") {
  $exploded = explode('/', $name);
  $dir      = resource_path('views');
  for ($i = 0; $i < count($exploded) - 1; $i++) {
    $dir .= '/' . $exploded[$i];
  }
  if (!File::exists($dir)) {
    File::makeDirectory($dir, 0775, true);
  }

  $filePath = $dir . "/" . "$exploded[$i].blade.php";
  File::put($filePath, '');

  $this->info("View created successfully.");
})->describe('Create a new view file');