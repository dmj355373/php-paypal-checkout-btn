<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Paypal Checkout Button</title>
</head>
<body>
    
    <form action="" id="process_payment_paypal">
    <div id="smart-button-container">
        <div style="text-align: center"><label for="description"> </label>
        <input type="text" name="descriptionInput" id="description" maxlength="127" value=""></div>
            <p id="descriptionError" style="visibility: hidden; color:red; text-align: center;">Please enter a description</p>
        <div style="text-align: center"><label for="amount"> </label><input name="amountInput" type="number" id="amount" value="" ><span> PHP</span></div>
            <p id="priceLabelError" style="visibility: hidden; color:red; text-align: center;">Please enter a price</p>
        <div id="invoiceidDiv" style="text-align: center; display: none;"><label for="invoiceid"> </label><input name="invoiceid" maxlength="127" type="text" id="invoiceid" value="" ></div>
            <p id="invoiceidError" style="visibility: hidden; color:red; text-align: center;">Please enter an Invoice ID</p>
        <div style="text-align: center; margin-top: 0.625rem;" id="paypal-button-container"></div>
    </div>
    </form>
    
    <script src="https://www.paypal.com/sdk/js?client-id=ASTAwVlErw3CqzFvLVKO3gw3xgpvDUhavoj0EqRCuiAkdm5c_gAoUJ97-nHX1C7G_EWdEZII6hrz1mZg&currency=PHP" data-sdk-integration-source="button-factory"></script>
    <script>
        function initPayPalButton() {
            var description = document.querySelector('#smart-button-container #description');
            var amount = document.querySelector('#smart-button-container #amount');
            var descriptionError = document.querySelector('#smart-button-container #descriptionError');
            var priceError = document.querySelector('#smart-button-container #priceLabelError');
            var invoiceid = document.querySelector('#smart-button-container #invoiceid');
            var invoiceidError = document.querySelector('#smart-button-container #invoiceidError');
            var invoiceidDiv = document.querySelector('#smart-button-container #invoiceidDiv');

            var elArr = [description, amount];

            if (invoiceidDiv.firstChild.innerHTML.length > 1) {
            invoiceidDiv.style.display = "block";
            }

            var purchase_units = [{"amount":{"currency_code":"PHP","value":1,"breakdown":{"item_total":{"currency_code":"PHP","value":1}}},"items":[{"name":"item name","unit_amount":{"currency_code":"PHP","value":1},"quantity":"1","category":"DONATION"}]}]

            function validate(event) {
            return event.value.length > 0;
            }

            paypal.Buttons({
            // style: {
            //     color: 'silver',
            //     shape: 'rect',
            //     label: 'donate',
            //     layout: 'vertical',
                
            // },

            onInit: function (data, actions) {
                actions.disable();

                if(invoiceidDiv.style.display === "block") {
                elArr.push(invoiceid);
                }

                elArr.forEach(function (item) {
                item.addEventListener('keyup', function (event) {
                    var result = elArr.every(validate);
                    if (result) {
                    actions.enable();
                    } else {
                    actions.disable();
                    }
                });
                });
            },

            onClick: function () {
                if (description.value.length < 1) {
                descriptionError.style.visibility = "visible";
                } else {
                descriptionError.style.visibility = "hidden";
                }

                if (amount.value.length < 1) {
                priceError.style.visibility = "visible";
                } else {
                priceError.style.visibility = "hidden";
                }

                if (invoiceid.value.length < 1 && invoiceidDiv.style.display === "block") {
                invoiceidError.style.visibility = "visible";
                } else {
                invoiceidError.style.visibility = "hidden";
                }

                purchase_units[0].description = description.value;
                purchase_units[0].amount.value = amount.value;
                purchase_units[0].amount.currency_code = 'PHP';
                purchase_units[0].amount.breakdown.item_total.value = amount.value;
                purchase_units[0].items[0].unit_amount.value = amount.value;

                if(invoiceid.value !== '') {
                purchase_units[0].invoice_id = invoiceid.value;
                }
            },

            createOrder: function (data, actions) {
                return actions.order.create({
                purchase_units: purchase_units,
                });
            },

            onApprove: function (data, actions) {
                return actions.order.capture().then(function (orderData) {

                // Full available details
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));

                // Show a success message within this page, e.g.
                const element = document.getElementById('paypal-button-container');
                element.innerHTML = '';
                element.innerHTML = '<h3>Thank you for your payment!</h3>';

                // Or go to another URL:  actions.redirect('thank_you.html');
                
                });
            },

            onError: function (err) {
                console.log(err);
            }
            }).render('#paypal-button-container');
        }
        initPayPalButton();



        // Process Paypal Payment on Donate Page
        $('#process_payment_paypal').submit(function(e){
            e.preventDefault()
            // var acc_id = document.getElementById('id').value;
            // var customer_name = $('#account_name').val();
            // console.log(customer_name);
            $.ajax({
                url:'/ajax?action=donation_payment_paypal',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success:function(data){
                    response = JSON.parse(data);
                    var getdata = response.data;
                    console.log(getdata);
                    console.log(response);
                    // var checkout_url = getdata.checkouturl;
                    // var paynowlink = document.getElementById("paynow");
                    // paynowlink.setAttribute('href', checkout_url);
                    // var customername = getdata.customername;
                    // if(customername === ''){
                    //     customername = $("#account_name").attr('value');
                    // }else{
                    //     customername = 'Anonymous';
                    // }
                    // var amount = getdata.amount;
                    // var description = getdata.description;
                    // var code = getdata.code;
                    // var reqcode = getdata.code;
                    // console.log("code: "+ code);
                    // $('#reqcode').each(function () { this.setAttribute('value', reqcode); })
                    // var hash = getdata.hash;
                    // console.log("hash: "+ hash);                
                    // console.log("checkouturl: "+ checkouturl);
                }
            }).done(function(data) { 
                // console.log(data);
                response = JSON.parse(data);
                var getdata = response.data;
                // console.log(response);        
                var code = getdata.code;
                update_code(code);               
            });
        });

        function update_code($code) {
            $.ajax({
                url:'/ajax?action=update_code',
                data: {code: $code},
                method: 'POST',
                type: 'POST',
                success:function(){
                    // console.log(data);       
                }
            })
            
        }
    </script>

</body>
</html>