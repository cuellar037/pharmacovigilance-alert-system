<?php

use Illuminate\Support\Facades\Route;

// Root path
Route::view('/', 'app');

//It captures the remaining routes and sends them to the SPA.
Route::fallback(function () {
  return view('app');
});
