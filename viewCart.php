<?php include_once('inc/header.php'); ?>

<script>
    function updateCartItem(obj, id) {
        $.get("cartAction.php", {action:"updateCartItem", id:id, qty:obj.value}, function(data) {
            if(data == 'ok') {
                location.reload();
            } else {
                alert('Cart update failed. Please try again.');
            }
        });
    }
</script>

<div class="container">
    <div class="card shopping-cart" style="margin-top: 80px;">
        <div class="card-header bg-primary text-light">
            <i class="fas fa-shopping-cart" aria-hidden="true"></i> Shopping Cart
            <a href="index.php" class="btn btn-secondary btn-sm float-right">Continue Shopping</a>
            <div class="clearfix"></div>
        </div>
        <div class="card-body">

            <?php
                if($cart->total_items() > 0) {
                    // get cart items from session
                    $cartItems = $cart->contents();
                    foreach($cartItems as $item) {
            ?>
            <!--product-->
            <div class="row">
                <div class="col-12 col-sm-12 col-md-2 text-center">
                    <img class="img-fluid" src="<?php echo $item['image'];?>" alt="<?php echo $item['name'];?>" width="120" height="80" />
                </div>
                <div class="col-12 text-sm-center col-sm-12 text-md-left col-md-6">
                    <h4 class="product-name"><strong><?php echo $item['name'];?></strong></h4>
                    <h6>
                        <small>Category: <?php echo $item['product_type'];?></small>
                    </h6>
                    <br />
                    <h5><strong>$<?php echo number_format($item['price'], 2);?></strong></h5>

                </div>
                <div class="col-12 col-sm-12 text-sm-center col-md-4 text-md-right row">
                    <div class="col-4 col-sm-4 col-md-6 text-md-right" style="padding-top: 5px;">
                        <h5><strong>Sub: $<?php echo number_format($item["subtotal"], 2); ?></strong></h5>
                    </div>
                    <div class="col-4 col-sm-4 col-md-4">
                        <div class="quantity">
                            <input class="form-control" type="number" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')"/>
                        </div>
                    </div>
                    <div class="col-2 col-sm-2 col-md-2 text-right">
                        <button type="button" class="btn btn-outline-danger btn-xs" onclick="return confirm('Are you sure you want to remove this item?')?window.location.href='cartAction.php?action=removeCartItem&id=<?php echo $item['rowid']; ?>':false;">
                            <i class="far fa-trash-alt" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <hr />
            <!--/product-->
            <?php } } else { ?>
            <div class="row">
                Your cart is empty...
            </div>
            <?php } ?>
        </div>
        <?php if($cart->total_items() > 0) { ?>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12">
                    <div class="float-right" style="margin:10px;">
                        <h4><strong>Total: <?php echo '$' . number_format($cart->total(), 2); ?></strong></h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="float-right" style="margin:10px;">
                        <a href="checkout.php" class="btn btn-primary">Checkout</a>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
</div>
<div style="margin-top: 30px;">&nbsp;</div>
<?php include_once ('inc/footer.php'); ?>