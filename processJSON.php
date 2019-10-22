<!-- these are processes for using external JSON file for products -->


<!-- this would replace the db call in cartAction.php-->
<?php

$productID = $_REQUEST['id'];

// process json file
$products = file_get_contents("bikerentals.json");
$arr = json_decode($products, true);
$productLen = count($arr['products']);

// process request based on action
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])) {

        // get specific item detail

        for ($i = 0; $i < $productLen; $i++) {
            if ($arr['products'][$i]['id'] == $productID) {
                $itemData = array(
                    'id' => $arr['products'][$i]['id'],
                    'name' => $arr['products'][$i]['name'],
                    'price' => $arr['products'][$i]['price'],
                    'product_type' => $arr['products'][$i]['product_type'],
                    'image' => $arr['products'][$i]['image'],
                    'qty' => 1
                );
                break;
            }
        }
    }
}
?>


<!-- this would replace the db call in index.php -->
<?php
// get products
$products = file_get_contents("bikerentals.json");
$arr = json_decode($products, true);
$productLen = count($arr['products']);
for($i = 0; $i < $productLen; $i++) {
    echo '<div class="col-lg-4 col-md-6 mb-4">';
    echo '<div class="card text-white bg-primary mb-3 product-image" style="max-width:20rem;" align="center">';
    echo '<img class="card-img-top" src="' . $arr['products'][$i]['image'] . '" alt="' . $arr['products'][$i]['name'] . '">';
    echo '<div class="card-body">';
    echo '<h4 class="card-title">' . $arr['products'][$i]['name'] . '</h4>';
    echo '<h5 class="card-subtitle mb-2 text-muted">Price: $' . number_format($arr['products'][$i]['price'], 2) . '</h5>';
    echo '<p class="card-text">Category: ' . $arr['products'][$i]['product_type'] . '</p>';
    echo '<a href="cartAction.php?action=addToCart&id=' . $arr['products'][$i]['id'] .'" class="btn btn-secondary">Add to Cart</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
} ?>
