<?php

add_shortcode('sposnage-stripe-button', function ($atts = array(), $content = null, $tag = '')
{
    if (!is_array($atts)) return '';
    $atts = array_change_key_case( $atts, CASE_LOWER );
    if  (array_key_exists('price-id', $atts) === false)
    {
        return '<b>Missing a price-id</b>';
    }
    
    static $num = 0;

    $html = '';
    if ($num == 0)
    {
        $publishable_key = sposnage_stripe_get_env_option('publishable_key');
        $js_url =  plugins_url( '/script.js', realpath(__DIR__ . '/../../script.js'));
        $success_url = sposnage_stripe_get_env_option('success_url');
        $html .= '<script src="https://js.stripe.com/v3/"></script>';
        $html .= "<script src=\"$js_url\" data-publishable-key=\"$publishable_key\" data-success-url=\"$success_url\"></script>";
        ++$num;
    }

    $html .= <<<EOT
        <button class="sposnage-stripe-button" data-price-id="${atts['price-id']}">Buy now</button>
    EOT;
    return $html;
});