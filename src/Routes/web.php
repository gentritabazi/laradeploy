<?php

use Illuminate\Support\Facades\Route;
use GentritAbazi\Laradeploy\Controllers\LaradeployController;

Route::post('/deploy', [LaradeployController::class, 'deploy']);
