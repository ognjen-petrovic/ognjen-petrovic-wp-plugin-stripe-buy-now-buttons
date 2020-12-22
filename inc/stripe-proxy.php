<?php
namespace SimpleStripeButton;

function sposnage_stripe_proxy() {
    check_ajax_referer('sposnage');
    $options = get_option('sposnage_stripe_options');
    require_once(__DIR__ . '/stripe-php-7.67.0/init.php');
    \Stripe\Stripe::setApiKey(sposnage_stripe_get_env_option('secret_key'));
    try {
        $price_id = sanitize_text_field($_GET['price_id']);
        if (empty($price_id))
        {
            throw new \Exception('Missing a price id');
        }
        $price = \Stripe\Price::retrieve($price_id);
        wp_send_json_success([
            'price_id' => $price_id,
            'test_publishable_key' =>   $options['test_publishable_key'],
            'price' => $price
        ]);
    } catch (\Exception $th) {
        wp_send_json_error($th->getMessage());
    }
}
// register the ajax action for authenticated users
add_action('wp_ajax_simple_stripe_button_proxy', __NAMESPACE__ . '\sposnage_stripe_proxy');

// register the ajax action for unauthenticated users
add_action('wp_ajax_nopriv_simple_stripe_button_proxy', __NAMESPACE__ . '\sposnage_stripe_proxy');