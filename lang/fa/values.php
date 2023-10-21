<?php

return [
    'delivery_statuses' => [
        'PENDING' => 'در جستجوی راننده',
        'ACCEPTED' => 'قبول توسط راننده',
        'ARRIVED_AT_PICKUP' => 'در مبدا',
        'PICKED_UP' => 'دریافت در مبدا',
        'ARRIVED_AT_DROP_OFF' => 'در مقصد',
        'DELIVERED' => 'تحویل شده',
        'CANCELLED' => 'لغو شده',
    ],

    'vehicle_types' => [
        'bike-without-box' => 'موتور بدون جعبه',
        'bike' => 'موتور',
        'van' => 'وانت',
        'van-heavy' => 'وانت سنگین',
    ],

    'payment_types' => [
        'prepaid' => 'پیش پرداخت',
        'cod' => 'پرداخت در محل',
    ],
];
