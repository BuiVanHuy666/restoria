<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('client.home');

Route::get('/gioi-thieu-nha-hang', function () {
    return view('pages.about-us');
})->name('client.about-us');

Route::get('/lien-he', function () {
    return view('pages.contact');
})->name('client.contact');

Route::get('/thu-vien-anh', function () {
    return view('pages.gallery');
})->name('client.gallery');

Route::get('/thuc-don-nha-hang', function () {
    return view('pages.menu');
})->name('client.menu');

Route::get('/dat-mon-online', function () {
    return view('pages.order-online');
})->name('client.order-online');

Route::get('/dat-ban', function () {
    return view('pages.book-table');
})->name('client.book-table');

require __DIR__ . '/auth.php';
