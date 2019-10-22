<?php include_once ('inc/header.php'); ?>
<div class="container">
    <div class="row" style="margin-top: 20px;">

        <!--sidebar-->
        <?php include_once ('inc/sidebar.php');?>

        <!--main content-->
        <div class="col-lg-9">
            <!--cart info-->
            <div class="cart-view">
                <a href="viewCart.php" title="View Cart"><i class="fas fa-shopping-cart"></i>
                    <?php echo ($cart->total_items() > 0) ? $cart->total_items().' Item(s)':' Cart is Empty';?></a>
            </div>
            <br />
            <div class="row" id="products">
                <?php
                // get products
                $cat = $_GET['product_type'];

                $result = $db->query("SELECT * FROM products WHERE product_type = '$cat' ORDER BY id ASC");
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card text-white bg-primary mb-3 product-image align-content-center" style="max-width:20rem;">
                                <img class="card-img-top" src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                                <div class="card-body">
                                    <h4 class="card-title text-center"><?php echo $row['name']; ?></h4>
                                    <h5 class="card-subtitle mb-2 text-muted text-center">Price: $ <?php echo number_format($row['price'], 2); ?></h5>
                                    <p class="card-text text-muted text-center"><small>Category: <?php echo $row['product_type']; ?></small></p>
                                    <p class="text-center"><a href="cartAction.php?action=addToCart&id=<?php echo $row['id'];?>" class="btn btn-secondary">Add to Cart</a></p>
                                </div>
                            </div>
                        </div>
                    <?php }} else { ?>
                    <p>Product(s) not found...</p>
                <?php } ?>
            </div>
            <!--closing divs from header-->
        </div>
    </div>
</div>
<!--/-->

<?php include_once ('inc/footer.php'); ?>
