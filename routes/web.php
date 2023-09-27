<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Models\Item;
use App\Models\ItemImagePivot;

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

Route::resource('item', ItemController::class); 
Route::post('uploads', [ItemController::class,'uploads'])->name('uploads');
Route::post('image/delete',[ItemController::class,'fileDestroy']);
Route::delete('/items/{image}', [ItemController::class,'delete'])->name('image.delete');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
