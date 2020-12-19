(function(){
    const publishableKey = document.currentScript.dataset.publishableKey
    const successUrl = document.currentScript.dataset.successUrl
    window.addEventListener('load', function(){
        let stripe = Stripe(publishableKey);
        let buttons = Array.from(document.getElementsByClassName('simple-stripe-button'))
        for (let button of buttons)
        {
            fetch('/wp-admin/admin-ajax.php?action=simple_stripe_button_proxy&price_id=' + button.dataset.priceId)
            .then(response => response.json())
            .then(json => {
                if (json.success == false)
                {
                  throw Error(json.data)
                }
                let numberFormat = new Intl.NumberFormat(navigator.language, {
                    style: "currency",
                    currencyDisplay: "symbol",
                    currency: json.data.price.currency,
                });
                let amount = json.data.price.unit_amount
                let parts = numberFormat.formatToParts(amount)
                let zeroDecimalCurrency = true
                for (let part of parts) {
                  if (part.type === "decimal") {
                    zeroDecimalCurrency = false;
                  }
                }
                amount = zeroDecimalCurrency ? amount : amount / 100;
                button.innerHTML += '&nbsp;' + numberFormat.format(amount)
                
                button.addEventListener('click', function(ev){
                    stripe
                    .redirectToCheckout({
                      lineItems: [
                        {price: ev.target.dataset.priceId, quantity: 1},
                      ],
                      mode: 'payment',
                      successUrl: successUrl,
                      cancelUrl: window.location.href,
                    })
                    .then(function(result) {
                      // If `redirectToCheckout` fails due to a browser or network
                      // error, display the localized error message to your customer
                      // using `result.error.message`.
                      console.error(result.error.message)
                    });
                })
            })
            .catch(err => console.error(err))
        }
    })
})()
