<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CSVImportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('/import-csv', [CSVImportController::class, 'import'])->name('import.csv');
Route::get('/generate-csv', [CSVImportController::class, 'generateCSV'])->name('generate.csv');

