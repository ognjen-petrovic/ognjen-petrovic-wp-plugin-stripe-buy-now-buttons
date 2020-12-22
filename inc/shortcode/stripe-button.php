<?php
namespace SimpleStripeButton;

add_shortcode('simple-stripe-button', function ($atts = array(), $content = null, $tag = '')
{
    if (sposnage_do_we_have_all_reqired_settings() == false)
    {
        return '<b>Missing parameters. Please check under the "Settings->Simple Stripe buttons" menu.</b>';
    }

    if (!is_array($atts)) return '';
    $atts = array_change_key_case( $atts, CASE_LOWER );
    if  (array_key_exists('price-id', $atts) === false)
    {
        return '<b>Missing a price-id</b>';
    }
    
    static $num = 0;
    if ($num == 0)
    {
        $publishable_key = sposnage_stripe_get_env_option('publishable_key');
        $js_url =  plugins_url( '/script.js', realpath(__DIR__ . '/../../script.js'));
        $success_url = sposnage_stripe_get_env_option('success_url');
        $nonce = wp_create_nonce('sposnage');
        wp_enqueue_script('simple-stripe-button-stripe-v3', 'https://js.stripe.com/v3/');
        wp_enqueue_script('simple-stripe-button', $js_url);
        $inline_script = <<<EOT
        var SIMPLE_STRIPE_BUTTON = {
            publishableKey: "$publishable_key",  
            successUrl: "$success_url",  
            nonce: "$nonce"  
        };
        EOT;
        wp_add_inline_script('simple-stripe-button', $inline_script, 'before');
        ++$num;
    }

    $text = array_key_exists('text', $atts) ? $atts['text'] : 'Buy now';

    $html = <<<EOT
        <button class="simple-stripe-button" data-price-id="${atts['price-id']}">$text</button>
    EOT;
    return $html;
});