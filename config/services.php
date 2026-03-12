<?php
// ============================================================
// FILE: config/services.php — Add this to your existing file
// ============================================================

return [

    // ... other services ...

    'razorpay' => [
        'key'            => env('RAZORPAY_KEY'),
        'secret'         => env('RAZORPAY_SECRET'),
        'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
    ],

];
