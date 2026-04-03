<?php

return [
    'order_confirmation' => [
        'subject' => 'Order Confirmation - #:order_number',
        'hello' => 'Hello :name,',
        'thank_you' => 'Thank you for your order at ' . config('app.name') . '. We are processing your order and will notify you once it\'s ready.',
        'order_number' => 'Order Number: #:number',
        'order_summary' => 'Order Summary',
        'product' => 'Product',
        'price' => 'Price',
        'quantity' => 'Qty',
        'subtotal' => 'Subtotal',
        'total' => 'Total',
        'payment_method' => 'Payment Method: :method',
        'payment_proof_received' => 'We have received your payment proof and will verify it shortly.',
        'status_update' => 'Current Status: :status',
        'view_order' => 'View Order',
        'contact_support' => 'If you have any questions about your order, please contact our support team.',
        'thanks' => 'Thank you for shopping with us!',
    ],
    'all_rights_reserved' => 'All rights reserved.',
];
