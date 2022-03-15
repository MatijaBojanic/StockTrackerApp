<?php

use App\Models\Stock;
use App\Notifications\ImportantStockUpdate;
use Illuminate\Support\Facades\Route;

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

Route::get('/mail-preview', function () {
    $user = \App\Models\User::factory()->create(['email' =>'matija2@test.com']);
    return (new ImportantStockUpdate(Stock::first()))->toMail($user);
});
