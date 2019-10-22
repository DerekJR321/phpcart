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

            <!--carousel-->
            <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="slide 1">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="slide 2">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="slide 3">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <!--/carousel-->

            <!--products-->
            <div class="row" id="products">
                <?php
                // get products
                $result = $db->query("SELECT * FROM products ORDER BY id ASC LIMIT 10");
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