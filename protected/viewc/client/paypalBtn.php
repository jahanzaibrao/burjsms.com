<?php if($data['paid']==0){ ?>

<?php if($data['userpg']['channel']=='paypal'){ ?>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
<?php } ?>

<?php if($data['userpg']['channel']=='stripe'){ ?>
    <script src="https://js.stripe.com/v3/"></script>
<?php } ?>

<?php if($data['userpg']['channel']=='paystack'){ ?>
    <script src="https://js.paystack.co/v1/inline.js"></script>
<?php } ?>

<script>
    $(document).ready(function(){

    <?php if($data['userpg']['channel']=='paystack'){ ?>
        const paymentForm = document.getElementById('paymentForm');paymentForm.addEventListener("submit", payWithPaystack, false);

        function payWithPaystack(e) {
            e.preventDefault();
            let handler = PaystackPop.setup({
                    key: '<?php echo $data['userpg']['public_key'] ?>',
                    email: '<?php echo $_SESSION['user']['email'] ?>',
                    amount: <?php echo floatval($data['invdata']['grand_total'])+30 ?> * 100,
                    ref: ''+Math.floor((Math.random() * 1000000000) + 1),
                    //label: "Optional string that replaces customer email"

            onClose: function(){
                bootbox.alert('Payment was cancelled successfully. Try again later.');
            },

            callback: function(response){
                window.location = app_url+"scOrderProcess/index?channel=paystack&paymentRef="+response.reference +"&invid=<?php echo $data['docdata']->id ?>&wflag=<?php echo $data['walletflag'] ?>&rtoi=<?php echo intval($data['returntoinvoice']) ?>";

            }

        });

        handler.openIframe();

        }
    <?php } ?>

    <?php if($data['userpg']['channel']=='stripe'){ ?>

        //-- Stripe money payment
        var stripe = Stripe("<?php echo $data['userpg']['publishable_key'] ?>");
        var elements = stripe.elements();
        var style = {
                        base: {
                            color: "#32325d",
                            fontFamily: 'Rawline, "Helvetica Neue", Helvetica, Arial, sans-serif',
                            fontSmoothing: "antialiased",
                            fontSize: "14px",
                            "::placeholder": {
                            color: "#32325d"
                            }
                        },
                        invalid: {
                            fontFamily: 'Rawline, "Helvetica Neue", Helvetica, Arial, sans-serif',
                            color: "#fa755a",
                            iconColor: "#fa755a"
                        }
                    };
        var card = elements.create("card", { style: style });
        // Stripe injects an iframe into the DOM
        card.mount("#card-element");

        card.on("change", function (event) {
            // Disable the Pay button if there are no card details in the Element
            document.querySelector("button").disabled = event.empty;
            document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
        });
        $(document).on("click","#stripebtn", function(){
            //validate email and name
            if(echeck($("#cardemail").val())==false){
                $("#card-error").html('Please enter a valid email.');
                return false;
            }
            if($("#cardname").val()==""){
                $("#card-error").html('Please enter the name on the card.');
                return false;
            }
            let customer = {
                name: $("#cardname").val(),
                email: $("#cardemail").val()
            }
            payWithCard(stripe, card, '<?php echo $data['userpg']['client_secret'] ?>', customer);
        })

        var paymentRequest = stripe.paymentRequest({
            country: 'IN',
            currency: '<?php echo strtolower(Doo::conf()->currency_name) ?>',
            total: {
                label: 'SMS CREDITS PURCHASE',
                amount: <?php echo floatval($data['invdata']['grand_total']) ?>,
            },
            requestPayerName: true,
            requestPayerEmail: true,
        });

        var prButton = elements.create('paymentRequestButton', {
            paymentRequest: paymentRequest,
        });

        // Check the availability of the Payment Request API first.
        // paymentRequest.canMakePayment().then(function(result) {
        // if (result) {
        //     prButton.mount('#payment-request-button');
        // } else {
        //     document.getElementById('payment-request-button').style.display = 'none';
        // }
        // });


        // Calls stripe.confirmCardPayment
        // If the card requires authentication Stripe shows a pop-up modal to
        // prompt the user to enter authentication details without leaving your page.
        var payWithCard = function(stripe, card, clientSecret, customer) {
            $("#stripebox").addClass("disabledBox");
            $("#paymentbox").css({"cursor": "progress"});
            $("#button-text").text("Verifying. Please Wait...");
            stripe
                .confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: customer.name,
                        email: customer.email
                    }
                }
                })
                .then(function(result) {
                if (result.error) {
                    // Show error to your customer
                    let errorMsg = document.querySelector("#card-error");
                    errorMsg.textContent = result.error.message;
                    $("#paymentbox").css({"cursor": "default"});
                    $("#button-text").text("Proceed to Pay");
                    $("#stripebox").removeClass("disabledBox");
                } else {
                    // The payment succeeded!
                    $.ajax({
                        type: 'post',
                        url: `${app_url}scOrderProcess/index`,
                        data: {
                            channel: 'stripe',
                            invData: JSON.stringify({
                                invoiceid: <?php echo intval($data['docdata']->id) ?>,
                                invTotal: <?php echo floatval($data['invdata']['grand_total']) ?>,
                                name: customer.name,
                                email: customer.email
                            }),
                            hash: '<?php echo $data['userpg']['hash'] ?>',
                            paymentData: JSON.stringify(result)
                        },
                        success: function(res){
                            str = `<h4 class="m-b-md"><i class="fa fa-check-circle fa-3x text-success"></i></h4>Thank you for your payment. Credits have been added to your account.`;
                            $("#paymentbox").html(str);
                            $("#paymentbox").css({"cursor": "default"});

                        }
                    })

                }
                });
        };



    <?php } ?>


    <?php if($data['userpg']['channel']=='paypal'){ ?>
        //-- paypal button
        if($("#paypal-button").length>0){
            paypal.Button.render({
                // Configure environment
                env: '<?php echo $data['userpg']['env'] ?>',
                client: {
                    sandbox: '<?php echo $data['userpg']['clientid'] ?>',
                    production: '<?php echo $data['userpg']['clientid'] ?>'
                },
                locale: 'en_US',
                style: {
                size: 'medium',
                color: 'blue',
                shape: 'rect',
                label: 'checkout',
                tagline: 'true'
                },
                // Set up a payment
                payment: function (data, actions) {
                    return actions.payment.create({
                        transactions: [{
                            amount: {
                                total: '<?php echo number_format($data['invdata']['grand_total'],2) ?>',
                                currency: `<?php echo trim(Doo::conf()->currency_name) ?>` //currency from conf
                            }
                        }]
                });
                },
                // Execute the payment:
                onAuthorize: function (data, actions) {
                    return actions.payment.execute()
                    .then(function () {
                        // Show a confirmation message to the buyer
                        //window.alert('Thank you for your purchase!');

                        // Redirect to the payment process page
                        window.location = app_url+"scOrderProcess/index?channel=paypal&paymentID="+data.paymentID+"&token="+data.paymentToken+"&payerID="+data.payerID+"&invid=<?php echo $data['docdata']->id ?>&wflag=<?php echo $data['walletflag'] ?>&rtoi=<?php echo intval($data['returntoinvoice']) ?>";
                    });
                }
            }, '#paypal-button');
        }

    <?php } ?>

    })
</script>

<?php } ?>
