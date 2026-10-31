<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profil', function () {
    return view('welcome');
});
Route::get('/profil/{nama}', function ($nama) {
    return 'Hallo, ' . $nama;
});
