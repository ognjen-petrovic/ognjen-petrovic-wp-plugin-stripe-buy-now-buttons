<?php

function sposnage_stripe_proxy() {
    $options = get_option('sposnage_stripe_options');
    require_once(__DIR__ . '/stripe-php-7.67.0/init.php');
    \Stripe\Stripe::setApiKey(sposnage_stripe_get_env_option('secret_key'));
    try {
        $price = \Stripe\Price::retrieve($_GET['price_id']);
        wp_send_json_success([
            'price_id' => $_GET['price_id'],
            'test_publishable_key' =>   $options['test_publishable_key'],
            'price' => $price
        ]);
    } catch (\Throwable $th) {
        wp_send_json_error($th);
    }
}
// register the ajax action for authenticated users
add_action('wp_ajax_sposnage_stripe_proxy', 'sposnage_stripe_proxy');

// register the ajax action for unauthenticated users
add_action('wp_ajax_nopriv_sposnage_stripe_proxy', 'sposnage_stripe_proxy');