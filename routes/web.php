<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchMapController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profil', function () {
    return view('welcome');
});
Route::get('/profil/{nama}', function ($nama) {
    return 'Hallo, ' . $nama;
});
Route::get('/peta', [BranchMapController::class, 'index']);
