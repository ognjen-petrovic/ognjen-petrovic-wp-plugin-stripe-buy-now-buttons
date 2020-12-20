# Simple Stripe checkout buttons

An easy to accept payements using Stripe-hosted payment page that lets you collect payments quickly.


## Description

This plugin allows you to accept Stripe payments on your WordPress pages.

It uses a simple shortcode that creates one or more Stripe checkout buttons.


## Stripe account prerequests

* [Enable Stripe Checkoout and client-only integration](https://stripe.com/docs/payments/checkout/client#enable-checkout)
* [Create products and prices](https://stripe.com/docs/payments/checkout/client#create-products-and-prices)


## Configuration

After the plugin is installed and activated, you need to configure settings. Settings are located under "Settings->Simple Stripe buttons"


## Usage

Create buttons using a simple shortcode.

Shortcode parameters:

* price-id - Price ID from Stripe dashboard. Required
* text - Button text. If ommited it default "Buy now" is used

### Examples

```
[simple-stripe-button price-id="price_1HvOLlDMgdjb9VY8UYqYhCQk" /]

[simple-stripe-button price-id="price_1HwvyyDMgdjb9VY8RSyKuNfG" text="buy me"/]
```
