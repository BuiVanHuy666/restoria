<?php

return [
    'main' => [
        [
            'label' => 'Giới thiệu',
            'route' => 'client.about-us'
        ],
        [
            'label' => 'Thực đơn',
            'route' => 'client.menu',
        ],
        [
            'label' => 'Đặt món online',
            'route' => 'client.order-online',
        ],
        [
            'label' => 'Đặt bàn',
            'route' => 'client.book-table',
        ],
        [
            'label' => 'Thư viện ảnh',
            'route' => 'client.gallery',
        ],
        [
            'label' => 'Liên hệ',
            'route' => 'client.contact',
        ],
    ],

    'customer' => [
        [
            'label' => 'Tài khoản',
            'route' => 'customer.profile',
        ],
        [
            'label' => 'Đơn hàng',
            'route' => 'customer.order',
        ],
        [
            'label' => 'Địa chỉ',
            'route' => 'customer.address',
        ],
        [
            'label' => 'Đổi mật khẩu',
            'route' => 'customer.change-password',
        ],
    ]

];
