<?php

return [
    'all_rights_reserved' => 'All rights reserved.',

    'common' => [
        'greeting' => 'Hello :name,',
        'product' => 'Product',
        'quantity' => 'Quantity',
        'subtotal' => 'Subtotal',
        'total' => 'Total',
        'payment_method' => 'Payment Method: :method',
        'view_order' => 'View Order',
        'access_delivery' => 'Access Delivery',
    ],

    'order_placed' => [
        'subject' => 'Order Placed - #:order_number',
        'title' => 'Your order has been received',
        'message' => 'Thank you for your order #:order_number. Our team will review it shortly.',
        'next_step' => 'We will notify you when your order status changes.',
    ],

    'order_approved' => [
        'subject' => 'Order Approved - #:order_number',
        'title' => 'Your order is approved',
        'message' => 'Great news! Your order #:order_number has been approved.',
        'delivery_note' => 'Delivery is now available or will be available shortly from your order page.',
    ],

    'order_rejected' => [
        'subject' => 'Order Update - #:order_number',
        'title' => 'Your order could not be approved',
        'message' => 'Your order #:order_number was reviewed and could not be approved.',
        'reason' => 'Reason',
    ],

    'order_cancelled' => [
        'subject' => 'Order Cancelled - #:order_number',
        'title' => 'Your order has been cancelled',
        'message' => 'Your order #:order_number has been cancelled successfully.',
    ],

    'delivery_ready' => [
        'subject' => 'Delivery Ready - #:order_number',
        'title' => 'Your delivery is ready',
        'message' => 'Delivery for order #:order_number is now available.',
        'delivery_type' => 'Delivery Type',
        'product' => 'Product',
    ],

    'admin_new_order' => [
        'subject' => 'New Order Received - #:order_number',
        'title' => 'New order placed',
        'message' => 'A new order #:order_number has been placed and is awaiting review.',
        'customer' => 'Customer',
        'total' => 'Order Total',
        'payment_method' => 'Payment Method',
        'review_order' => 'Review Order',
    ],

    // Backward compatibility for any legacy usage.
    'order_confirmation' => [
        'subject' => 'Order Confirmation - #:order_number',
        'hello' => 'Hello :name,',
        'thank_you' => 'Thank you for your order.',
        'order_number' => 'Order Number: #:number',
        'order_summary' => 'Order Summary',
        'product' => 'Product',
        'price' => 'Price',
        'quantity' => 'Qty',
        'subtotal' => 'Subtotal',
        'total' => 'Total',
        'payment_method' => 'Payment Method: :method',
        'payment_proof_received' => 'Payment proof was received.',
        'status_update' => 'Current Status: :status',
        'view_order' => 'View Order',
        'contact_support' => 'Please contact support if you need help.',
        'thanks' => 'Thank you for shopping with us!',
    ],
];

