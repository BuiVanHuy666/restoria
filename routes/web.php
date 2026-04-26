<?php

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $popularItems = MenuItem::with('categories')
                            ->where('is_popular', true)
                            ->where('status', 'available')
                            ->latest()
                            ->get();

    $categoriesWithItems = Category::with(['menuItems' => function($query) {
        $query->where('is_popular', true)
              ->where('status', 'available')
              ->get();
    }])->orderBy('sort_order')->get();

    return view('pages.home', compact('popularItems', 'categoriesWithItems'));
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
    $categories = Category::with(['menuItems' => function($query) {
        $query->available()->latest();
    }])->orderBy('sort_order')->get();

    return view('pages.menu', compact('categories'));
})->name('client.menu');

Route::get('/dat-mon-online', function () {
    $categories = Category::with(['menuItems' => function($query) {
        $query->available()->latest();
    }])->orderBy('sort_order')->get();

    return view('pages.order-online', compact('categories'));
})->name('client.order-online');

Route::get('/dat-ban', function () {
    return view('pages.book-table');
})->name('client.book-table');

require __DIR__ . '/auth.php';
require __DIR__ . '/customer.php';
require __DIR__ . '/admin.php';
