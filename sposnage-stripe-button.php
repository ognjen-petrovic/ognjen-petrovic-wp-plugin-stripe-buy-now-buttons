<?php
/**
 * Plugin Name: Sposnage - Stripe checkout button
 * Description: The easiest way to add Stripe payment button.
 * Version: 1.0.0
 */

//https://stripe.com/docs/testing
//https://stripe.com/docs/payments/checkout/client#enable-checkout
//https://stripe.com/docs/payments/checkout
//The Checkout client-only integration is not enabled. Enable it in the Dashboard at https://dashboard.stripe.com/account/checkout/settings.

function sposnage_do_we_have_all_reqired_settings()
{
    static $do_we_have = null;
    if($do_we_have === null)
    {
        $do_we_have = sposnage_stripe_get_env_option('publishable_key') && sposnage_stripe_get_env_option('secret_key') && sposnage_stripe_get_env_option('success_url');
    }

    return $do_we_have;
}

function sposnage_stripe_get_options()
{
    static $options = null;
    if ($options == null)
    {
        $options = get_option('sposnage_stripe_options');
    }
    return $options;
}

function sposnage_stripe_get_env_option($option_name)
{
    $options = sposnage_stripe_get_options();
    if (strtolower($options['environment']) == 'live')
    {
        $prefix = 'live_';
    }
    else
    {
        $prefix = 'test_';
    }

    return $options[$prefix . $option_name];
}

function sposnage_add_stripe_settings_page() {
    add_options_page('Simple Stripe button page', 'Simple Stripe button', 'manage_options', 'sposnage-stripe-plugin', 'sposnage_render_stripe_settings_page');
}
add_action('admin_menu', 'sposnage_add_stripe_settings_page');

function sposnage_render_stripe_settings_page() {
    ?>
    <h1>Simple Stripe button settings</h1>
    <form action="options.php" method="post">
        <?php 
        settings_fields('sposnage_stripe_options');
        do_settings_sections('sposnage_stripe_settings_section'); 
        ?>
        <input name="submit" class="button button-primary" type="submit" value="Save" />
    </form>
    <?php
}

function sposnage_stripe_register_settings() {
    register_setting('sposnage_stripe_options', 'sposnage_stripe_options'/*, 'sposnage_stripe_options_validate' */);
    add_settings_section('common', null, null, 'sposnage_stripe_settings_section');
    add_settings_section('test_api_settings', 'Test environment API settings', '', 'sposnage_stripe_settings_section');
    add_settings_section('live_api_settings', 'Live environment API settings', '', 'sposnage_stripe_settings_section');

    $id = 'environment';
    add_settings_field( $id, 'Active environment', 'sposnage_stripe_add_environment_select', 'sposnage_stripe_settings_section', 'common', ['id'=>$id] );

    $id = 'test_publishable_key';
    add_settings_field( $id, 'Test publishable key', 'sposnage_stripe_input_field', 'sposnage_stripe_settings_section', 'test_api_settings', ['id'=>$id] );

    $id = 'test_secret_key';
    add_settings_field( $id, 'Test secret key', 'sposnage_stripe_input_field', 'sposnage_stripe_settings_section', 'test_api_settings', ['id'=>$id, 'field_type'=>'password'] );

    $id = 'test_success_url';
    add_settings_field( $id, 'Test success URL', 'sposnage_stripe_input_field', 'sposnage_stripe_settings_section', 'test_api_settings', ['id'=>$id] );
    
    //$id = 'test_cancel_url';
    //add_settings_field( $id, 'Test cancel URL', 'sposnage_stripe_input_field', 'sposnage_stripe_settings_section', 'test_api_settings', ['id'=>$id] );
    
    $id = 'live_publishable_key';
    add_settings_field( $id, 'Live publishable key', 'sposnage_stripe_input_field', 'sposnage_stripe_settings_section', 'live_api_settings', ['id'=>$id] );
    
    $id = 'live_secret_key';
    add_settings_field( $id, 'Live secret key', 'sposnage_stripe_input_field', 'sposnage_stripe_settings_section', 'live_api_settings', ['id'=>$id, 'field_type'=>'password'] );

    $id = 'live_success_url';
    add_settings_field( $id, 'Test success URL', 'sposnage_stripe_input_field', 'sposnage_stripe_settings_section', 'live_api_settings', ['id'=>$id] );
    
    //$id = 'live_cancel_url';
    //add_settings_field( $id, 'Test cancel URL', 'sposnage_stripe_input_field', 'sposnage_stripe_settings_section', 'live_api_settings', ['id'=>$id] );
}
add_action('admin_init', 'sposnage_stripe_register_settings');

/*
function sposnage_stripe_options_validate( $input ) {
    $newinput['api_key'] = trim( $input['api_key'] );
    return $newinput;
}

function sposnage_stripe_section_text() {
    echo '<p>Here you can set all the options for using the API</p>';
}
*/

function sposnage_stripe_input_field($arguments) {
    $id = $arguments['id'];
    $options = sposnage_stripe_get_options();
    $field_type = (isset($arguments['field_type'])) ? $arguments['field_type'] : 'text';
    if (isset($options[$id]))
    {
        $value = esc_attr( $options[$id] );
    }
    else
    {
        $value = '';
    }
    echo "<input id='$id' name='sposnage_stripe_options[$id]' type='$field_type' value='$value' class='regular-text'/>";
}

function sposnage_stripe_add_environment_select($arguments)
{
    $envs = ['Test', 'Live'];
    $id = $arguments['id'];
    $options = sposnage_stripe_get_options();
    if (isset($options[$id]))
    {
        $value = $options[$id];
    }
    else
    {
        $value = '';
    }

    $field = "<select id='$id' name='sposnage_stripe_options[$id]'>";
    foreach ($envs as $env) {
        if($value == $env)
        {
            $selected = ' selected';
        }
        else
        {
            $selected = '';
        }
        $field .= "<option value='$env'$selected>$env</option>";
    }
    $field .= '</select>';

    echo $field;
}

include __DIR__ . '/inc/shortcode/stripe-button.php';
include __DIR__ . '/inc/stripe-proxy.php';