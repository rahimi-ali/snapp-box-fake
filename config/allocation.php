<?php

return [
    'fee_per_meter' => (int)env('FEE_PER_METER', 25),
    'allocation_timeout' => (int)env('ALLOCATION_TIMEOUT', 300),
    'auto_accept_time' => (int)env('AUTO_ACCEPT_TIME', 180),
];
