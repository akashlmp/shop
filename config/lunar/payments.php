<?php

return [

    'default' => env('PAYMENTS_TYPE', 'cash-on-delivery'),

    'types' => [
        'cash-in-hand' => [
            'driver' => 'offline',
            'authorized' => 'payment-offline',
        ],
        'offline' => [
            'driver' => 'offline',
            'authorized' => 'payment-offline',
        ],
        'cash-on-delivery' => [
            'driver' => 'cash-on-delivery',
            'authorized' => 'processing',
        ],
        'razorpay' => [
            'driver' => 'razorpay',
            'authorized' => 'payment-received',
        ],
    ],

];
