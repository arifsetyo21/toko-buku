<?php

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

// Menjadikan halaman login sebagai index
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Override route register
Route::match(["GET", "POST"], "/register", function(){
    return redirect("/login");
})->name("register");

// Menambahkan route ke Controller UserController yang bersifat resource (CRUD)
// Menambah middleware('auth') untuk mengalihkan user yang belum login ke halaman login
// https://laravel.com/docs/5.8/authentication#protecting-routes
Route::resource("users", "UserController");

// Membuat route untuk trash
// Kode ini perlu diletakkan sebelum resource categories, agar terpanggil terlebih dahulu
// Menggunakan name("categories.trash") agar dapat menggunakan helper untuk menggenerate linknya
// Route::get("/categories/trash", "CategoryController@trash")->name("categories.trash");

// // Membuar route untuk restore
// // Menggunakan name("categories.restore") agar dapat menggunakan helper untuk menggenerate linknya
// Route::get("/categories/{id}/restore", "CategoryController@restore")->name("categories.restore");

// Route::delete("/categories/{id}/delete-permanent", "CategoryController@deletePermanent")->name("categories.delete-permanent");

// Buat route group
Route::group(["prefix" => "/categories"], function() {
    Route::get("/trash", "CategoryController@trash")->name("categories.trash");
    Route::get("/{id}/restore", "CategoryController@restore")->name("categories.restore");
    Route::delete("/{id}/delete-permanent", "CategoryController@deletePermanent")->name("categories.delete-permanent");
});

// Menambahkan route ke Controller CategoryController yang bersifat resource (CRUD)
Route::resource("categories", "CategoryController");

// Menambahkan route ke Controller BookController yang bersifat resource (CRUD)
Route::group(["prefix" => "/books"], function(){
    Route::get("/trash", "BookController@trash")->name("books.trash");
    Route::post("/{id}/restore", "BookController@restore")->name("books.restore");
    Route::delete("/{id}/delete-permanent", "BookController@deletePermanent")->name("books.delete-permanent");
});

// Menambahkan resource route
Route::resource("books", "BookController");

// Menambahkan route ke list category
Route::get("/ajax/categories/search", "CategoryController@ajaxSearch");

// Menambahkan route ke controller CategoryController yang bersifat resource
Route::resource("orders", "OrderController");
