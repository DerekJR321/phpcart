<?php

// init cart
require_once 'Cart.class.php';
$cart = new Cart;

// include db
require_once 'dbConfig.php';

// default page
$redirectLoc = 'index.php';

//process request based on action
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
    if($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])) {
        $productID = $_REQUEST['id'];

        //get product info
        $query = $db->query("SELECT * FROM products WHERE id = ". $productID);
        $row = $query->fetch_assoc();
        $itemData = array(
            'id'            =>  $row['id'],
            'name'          =>  $row['name'],
            'product_type'  =>  $row['product_type'],
            'image'         =>  $row['image'],
            'price'         =>  $row['price'],
            'qty'           =>  1
        );

        // insert item into cart
        $insertItem = $cart->insert($itemData);

        // redirect to cart page
        $redirectLoc = $insertItem ? 'viewCart.php' : 'index.php';
    } elseif ($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])) {
        //update item in cart
        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData);

        // return status
        echo $updateItem ? 'ok' : 'err';
        die;
    } elseif ($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])) {
        // remove item from cart
        $deleteItem = $cart->remove($_REQUEST['id']);

        // redirect to cart page
        $redirectLoc = 'viewCart.php';
    } elseif ($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0) {
        $redirectLoc = 'checkout.php';

        // store post data
        $_SESSION['postData'] = $_POST;

        $first_name = strip_tags($_POST['first_name']);
        $last_name = strip_tags($_POST['last_name']);
        $email = strip_tags($_POST['email']);
        $phone = strip_tags($_POST['phone']);
        $address = strip_tags($_POST['address']);
        $city = strip_tags($_POST['city']);
        $state = strip_tags($_POST['state']);
        $zip = strip_tags($_POST['zip']);

        $errorMsg = '';
        if (empty($first_name)) {
            $errorMsg .= 'Please enter your first name.<br />';
        }
        if (empty($last_name)) {
            $errorMsg .= 'Please enter your last name.<br />';
        }
        if (empty($email)) {
            $errorMsg .= 'Please enter your email address.<br />';
        }
        if (empty($phone)) {
            $errorMsg .= 'Please enter your phone number.<br />';
        }
        if (empty($address)) {
            $errorMsg .= 'Please enter your address.<br />';
        }
        if (empty($city)) {
            $errorMsg .= 'Please enter your city.<br />';
        }
        if (empty($state)) {
            $errorMsg .= 'Please select your state.<br />';
        }
        if (empty($zip)) {
            $errorMsg .= 'Please enter your zip code.<br />';
        }

        if (empty($errorMsg)) {
            // insert customer data into the database
            $insertCust = $db->query("INSERT INTO customers(first_name, last_name, email, phone, address, city, state, zip) VALUES ('" . $first_name . "', '" . $last_name . "', '" . $email . "', '" . $phone . "', '" . $address . "', '" . $city . "', '" . $state . "', '" . $zip . "')");
            if ($insertCust) {
                $custID = $db->insert_id;

                // insert order info into the db
                $insertOrder = $db->query("INSERT INTO orders(customer_id, grand_total, created, status) VALUES ($custID, '" . $cart->total() . "', NOW(), 'Pending')");
                if ($insertOrder) {
                    $orderID = $db->insert_id;

                    // get cart items
                    $cartItems = $cart->contents();

                    // set sql to insert ordered items
                    $sql = '';
                    foreach ($cartItems as $item) {
                        $sql .= "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('" . $orderID . "', '" . $item['id'] . "', '" . $item['qty'] . "');";
                    }

                    // insert ordered items into the database
                    $insertOrderItems = $db->multi_query($sql);

                    if ($insertOrderItems) {
                        // clear cart
                        $cart->destroy();

                        // redirect to orderSuccess
                        $redirectLoc = 'orderSuccess.php?id=' . $orderID;
                    } else {
                        $sessData['status']['type'] = 'error';
                        $sessData['status']['msg'] = 'Something went wrong. Please try again';
                    }
                } else {
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg'] = 'Something went wrong. Please try again.';
                }
            } else {
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'Something went wrong. Please try again.';
            }
        } else {
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Please fill in all of the required fields.<br />' . $errorMsg;
        }
        $_SESSION['sessData'] = $sessData;
    }
}
// redirect to the page needed
header("Location: $redirectLoc");
exit();