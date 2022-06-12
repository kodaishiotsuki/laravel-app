<?php

use App\Http\Controllers\ContactController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    echo "home page";
    // return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contacted', [ContactController::class, 'index'])->name('con');

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });



Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    //Userモデルから取得
    // $users = User::all();

    //クエリビルダ
    $users = DB::table('users')->get();

    return view('dashboard', compact('users'));
})->name('dashboard');
