<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Store</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AaXutvOIzQWP0alLTyJkgk5AJ5zc9iq-OM5fLHZvcey0SDG2p-qZKL3y_-O9hJ6IlNHQVajKR-itvN0c&currency=PHP"></script>
</head>
<body>
    <form action="" method="post">
        <input type="text" id="amount" name="amount" placeholder="Enter Amount" /><br/><br/>
        <div id="paypal-button-container" style="width:250px"></div>
    </form>

    <script>
        var donate_amount = document.getElementById("amount").value;

        paypal.Buttons({

            createOrder: function(data, actions){
                
                return actions.order.create({
                    purchase_units: [{
                        
                        amount: {
                            currency_code: 'PHP',
                            value: donate_amount                          
                        }
                    }]
                })

            },
            onApprove: function(data, actions){
                console.log('Data :' + data);
                console.log('Action : '+actions);
                return actions.order.capture().then(function(details){
                    console.log(details.payer.name.given_name);
                    console.log(details);
                })
            }

        }).render('#paypal-button-container');
    </script>
</body>
</html>