<?php

Route::prefix('quan-tri')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
       Route::livewire('/tong-quan', 'admin::overview')
           ->name('admin.overview');
       Route::livewire('/don-hang', 'admin::orders')
           ->name('admin.orders');
       Route::livewire('/thuc-don', 'admin::menu')
           ->name('admin.menu');
       Route::livewire('/quan-ly-khach-hang', 'admin::customer')
           ->name('admin.customer');
       Route::livewire('quan-ly-doanh-muc', 'admin::category')
           ->name('admin.category');
       Route::livewire('khuyen-mai', 'admin::promotion')
           ->name('admin.promotion');
    });
