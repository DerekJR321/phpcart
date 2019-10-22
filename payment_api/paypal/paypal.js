paypal_sdk.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units : [{
                amount : {
                    value : amount_to_pay
                }
            }],
            application_context: {
                shipping_preference: "NO_SHIPPING",
            },
            country_code : "US"
        })
    },
    style: {
        color: 'blue',
        shape: 'rect',
        label: 'checkout',
        size: 'responsive',
        branding: true
    },
    onApprove: function(data, actions) {
        // capture funds from transaction
        return actions.order.capture().then(function(details) {

            return fetch('/pay-with-pp', {
                method: 'post',
                headers: {
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    orderID: data.orderID,
                    product_id : product_id,
                    _token : token
                })
            }).then(function(res) {
                alert('Payment has been made. Please see the delivery status on orders page');
                window.location.href = redirect_url
            });
        });
    },
}).render('#pp-buttons');