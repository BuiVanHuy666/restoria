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
    ],

    'admin' => [
        [
            'label' => 'Tổng quan',
            'icon' => 'home',
            'route' => 'admin.overview'
        ],
        [
            'label' => 'Đơn hàng',
            'icon' => 'shopping-cart',
            'route' => 'admin.orders'
        ],
        [
            'label' => 'Doanh mục',
            'icon' => 'list-bullet',
            'route' => 'admin.category'
        ],
        [
            'label' => 'Thực đơn',
            'icon' => 'book-open',
            'route' => 'admin.menu'
        ],
        [
            'label' => 'Khuyến mãi',
            'icon' => 'percent-badge',
            'route' => 'admin.promotion'
        ],
        [
            'label' => 'Khách hàng',
            'icon' => 'users',
            'route' => 'admin.customer'
        ]
    ]

];
