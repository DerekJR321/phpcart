<?php include_once ('inc/header.php'); ?>

<!--check if the cart is empty. If so, redirect to index.php-->
<?php
    if($cart->total_items() <= 0) {
        header("Location: index.php");
    }

    // get data from SESSION
    $postData = !empty($_SESSION['postData']) ? $_SESSION['postData']:array();
    unset($_SESSION['postData']);

    // get status message from SESSION
    $sessData = !empty($_SESSION['sessData']) ? $_SESSION['sessData']:'';
    if(!empty($sessData['status']['msg'])) {
        $statusMsg = $sessData['status']['msg'];
        $statusMsgType = $sessData['status']['type'];
        unset($_SESSION['sessData']['status']);
    }

?>
<script>
    $(document).ready(function() {
        $('input[type="radio"]').click(function() {
            let inputValue = $(this).attr("value");
            let targetBox = $("." + inputValue);
            $(".box").not(targetBox).hide();
            $(targetBox).show();
        });
    });
</script>

<div class="container">
    <div class="py-5 text-center">
        <i class="fa fa-credit-card fa-3x text-primary"></i>
        <h2 class="my-3">Checkout</h2>
    </div>
    <?php if(!empty($statusMsg) && ($statusMsgType == 'success')) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success"><?php echo $statusMsg; ?></div>
            </div>
        </div>
    <?php } elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
            </div>
        </div>
    <?php } ?>
    <div class="row py-5">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-content-center mb-3">
                <span class="text-muted">Your Cart</span>
                <span class="badge badge-secondary badge-pill"><?php echo $cart->total_items();?></span>
            </h4>
            <ul class="list-group mb-3">
                <?php
                if($cart->total_items() > 0) {
                    // get cart items from SESSION
                    $cartItems = $cart->contents();
                    foreach($cartItems as $item)  {
                ?>
                <!--products-->
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0"><?php echo $item['name'];?></h6>
                        <small class="text-muted">Category: <?php echo $item['product_type'];?></small>
                    </div>
                    <span class="text-muted"><?php echo '$' . number_format($item['price'], 2); ?></span>
                </li>
                <!--/products-->
                <?php } ?>
                <li class="list-group-item d-flex justify-content-between bg-light">
                    <div class="text-white">
                        <h6 class="my-0">Promo Code</h6>
                        <small>-example code--</small>
                    </div>
                    <span class="text-white">-$5.00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (USD)</span>
                    <strong><?php echo formatDollars($cart->total()); ?></strong>
                </li>
            </ul>
            <?php } ?>
            <form class="card p-2">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Promo Code">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary">Redeem</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Billing Address</h4>
            <form action="cartAction.php" method="POST">
                <div class="row">
                    <!--first name-->
                    <div class="col-md-6 mb-3">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" placeholder="" value="<?php echo !empty($postData['first_name'])?$postData['first_name']:'';?>" required>
                    </div>
                    <!--last name-->
                    <div class="col-md-6 mb-3">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" placeholder="" value="<?php echo !empty($postData['last_name'])?$postData['last_name']:'';?>" required>
                    </div>
                </div>

                <!--email-->
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="you@yourdomain.com" value="<?php echo !empty($postData['email'])?$postData['email']:'';?>"
                </div>
                <!--address-->
                <div class="mb-3">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" placeholder="" value="<?php echo !empty($postData['address'])?$postData['address']:'';?>" required>
                </div>

                <!--city/state/zip-->
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" placeholder="" value="<?php echo !empty($postData['city'])?$postData['city']:'';?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="state">State</label>
                        <select class="custom-select d-block w-100" id="state" value="<?php echo !empty($postData['state'])?$postData['state']:'';?>" required>
                            <?php
                                $result = $db->query("SELECT * FROM states ORDER BY name ASC");
                                echo '<option disabled selected>Please Select a State</option>';
                                while($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control" id="zip" placeholder="" value="<?php echo !empty($postData['zip'])?$postData['zip']:'';?>" required>
                    </div>
                </div>
                <hr class="mb-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="save-info">
                    <label class="custom-control-label" for="save-info">Save this information for next time?</label>
                </div>
                <hr class="mb-4">

                <h2 class="mb-3">Payment Information</h2>
                <div class="d-block my-3">
                    <ul class="list-inline text-left payment-methods">
                        <li><i class="pf pf-visa hvr-grow"></i></li>
                        <li><i class="pf pf-mastercard-alt hvr-grow"></i></li>
                        <li><i class="pf pf-american-express hvr-grow"></i></li>
                        <li><i class="pf pf-discover hvr-grow"></i></li>
                        <li><i class="pf pf-paypal hvr-grow"></i></li>
                        <li><i class="pf pf-amazon-pay hvr-grow"></i></li>
                    </ul>
                </div>


                <div class="d-block my-3">
                    <div class="custom-control custom-radio">
                        <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" value="cc" required>
                        <label class="custom-control-label" for="credit">Credit Card</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input id="paypal" name="paymentMethod" type="radio" class="custom-control-input" value="pp" required>
                        <label class="custom-control-label" for="paypal">PayPal</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input id="amazon" name="paymentMethod" type="radio" class="custom-control-input" value="amazonpay" required>
                        <label class="custom-control-label" for="amazon">Amazon Pay</label>
                    </div>
                </div>

                <!--credit card-->
                <div class="cc box">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cc-name">Name on Card</label>
                            <input id="cc-name" class="form-control" type="text" placeholder="" required />
                            <small class="text-muted">Name as displayed on card</small>
                            <div class="invalid-feedback">
                                Name on card is required
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cc-number">Credit Card Number</label>
                            <input id="cc-number" class="form-control" type="tel" placeholder="" required />
                            <div class="invalid-feedback">
                                Credit card number is required
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="cc-expiration">Expiration</label>
                            <input id="cc-expiration" class="form-control" type="date" required />
                            <div class="invalid-feedback">
                                Expiration date is required
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="cc-cvv">CVV</label>
                            <input id="cc-cvv" class="form-control" type="text" placeholder="" required />
                            <div class="invalid-feedback">
                                Security code is required
                            </div>
                        </div>
                    </div>
                </div>
                <!--/credit card-->


                <!--paypal-->
                <div class="pp box">
                    <script src="https://www.paypal.com/sdk/js?client-id=AaJ1_B9SSycAsiTGEdxIewn7C0c80TrCE0uM37L6zM5-9gg2atVb27FmTrMij08w8jzGXHc6aznbIhA3" data-namespace="paypal_sdk"></script>
                    <div id="pp-buttons"></div>

                    <script src="payment_api/paypal/paypal.js"></script>

                </div>
                <!--/paypal-->

                <!--amazon pay-->
                <div class="amazonpay box">
                    <div class="row">
                        <img src="images/payment/amazon-payment-icon-256.png" alt="Amazon Pay" />
                    </div>
                </div>
                <!--/amazon pay-->


                <hr class="mb-4">
                <button class="btn btn-success btn-block" type="submit">Checkout</button>
            </form>
        </div>
    </div>
</div>
    <!--closing divs from header-->
    </div>
    </div>
    </div>
    <!--/-->
<?php include_once ('inc/footer.php'); ?>