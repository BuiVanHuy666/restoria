<?php

Route::prefix('/tai-khoan')
     ->middleware(['verified', 'auth', 'role:customer'])
     ->group(function () {
         Route::livewire('', 'customer::profile')->name('customer.profile');
         Route::livewire('/don-hang', 'customer::order')->name('customer.order');
         Route::livewire('/doi-mat-khau', 'customer::change-password')->name('customer.change-password');
         Route::livewire('/dia-chi', 'customer::addresses')->name('customer.address');
         Route::livewire('gio-hang', 'customer::cart')->name('customer.cart');
     });
