<?php include_once ('inc/header.php'); ?>

<div class="container">
    <div class="py-5 text-center">
        <i class="fa fa-credit-card fa-3x text-primary"></i>
        <h2 class="my-3">Checkout Form</h2>
    </div>
    <div class="row py-5">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-content-center mb3">
                <span class="text-muted">Your Cart</span>
                <span class="badge badge-secondary badge-pill">'qty'</span>
            </h4>
            <ul class="list-group mb-3">
                <!--product-->
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Product Name</h6>
                        <small class="text-muted">category</small>
                    </div>
                    <span class="text-muted">price</span>
                </li>
                <!--/product-->
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (USD)</span>
                    <strong>total price</strong>
                </li>
            </ul>
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
            <form action="cartAction.php" method="post">
                <div class="row">
                    <!--first name-->
                    <div class="col-md-6 mb-3">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" placeholder="" value="" required>
                    </div>
                    <!--last name-->
                    <div class="col-md-6 mb-3">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" placeholder="" value="" required">
                    </div>
                </div>

                <!--email-->
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="you@yourdomain.com" value="" required>
                </div>
                <!--address-->
                <div class="mb-3">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" placeholder="" value="" required>
                </div>
                <!--city/state/zip-->
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" placeholder="" value="" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="state">State</label>
                        <select class="custom-select d-block w-100" id="state" value="" required">
                        <?php
                            $result = $db->query("SELECT * FROM states ORDER BY name ASC");
                            echo '<option disabled selected>Please Select State</option>';
                            while($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . "</option>";
                            }
                        ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control" id="zip" placeholder="" value="" required>
                    </div>
                </div>
                <hr class="mb-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="save-info">
                    <label class="custom-control-label" for="save-info">Save this information for next time?</label>
                </div>
                <hr class="mb-4">

                <h4 class="mb-3">Payment</h4>

                <div class="d-block my-3">
                    <div class="custom-control custom-radio">
                        <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                        <label class="custom-control-label" for="credit">Credit Card</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input id="google" name="paymentMethod" type="radio" class="custom-control-input" required>
                        <label class="custom-control-label" for="google">Google Pay</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input id="paypal" name="paymentMethod" type="radio" class="custom-control-input" required>
                        <label class="custom-control-label" for="paypal">PayPal</label>
                    </div>
                </div>

                <div class="row">
                    have this switch based on payment methods
                </div>
                <hr class="mb-4">
                <button class="btn btn-success btn-lg btn-block" type="submit">Checkout</button>
            </form>
        </div>
    </div>
</div>
