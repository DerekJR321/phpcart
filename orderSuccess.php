<?php include_once ('inc/header.php'); ?>

<?php
    // get order details from db
    $result = $db->query("SELECT r.*, c.first_name, c.last_name, c.email, c.phone FROM orders as r LEFT JOIN customers as c ON c.id = r.customer_id WHERE r.id = ".$_REQUEST['id']);

    if($result->num_rows > 0) {
        $orderInfo = $result->fetch_assoc();
    } else {
        header("Location: index.php");
    }
?>

<div class="container">
    <h1>ORDER STATUS</h1>
    <div class="col-12">
        <?php if(!empty($orderInfo)) { ?>
        <div class="col-md-12">
            <div class="alert alert-success">Your order has been placed successfully.</div>
        </div>

        <!-- order status & shipping info -->
        <div class="row col-lg-12 ord-addr-info">
            <div class="hdr">Order Info</div>
            <p><b>Reference ID: </b> #<?php echo $orderInfo['id'];?></p>
            <p><b>Total: </b> <?php echo '$'.$orderInfo['grand_total']. 'USD'; ?></p>
            <p><b>Placed On: </b> <?php echo $orderInfo['created']; ?></p>
            <p><b>Name: </b> <?php echo $orderInfo['first_name'] . ' ' .$orderInfo['last_name'];?></p>
            <p><b>Email: </b> <?php echo $orderInfo['email']; ?></p>
            <p><b>Phone: </b> <?php echo $orderInfo['phone']; ?></p>
        </div>

        <!--order-->
        <div class="row col-lg-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>QTY</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // get order from the database
                    $result = $db->query("SELECT i.*, p.name, p.price FROM order_items as i LEFT JOIN products as p ON p.id = i.product_id WHERE i.order_id = ".$orderInfo['id']);
                    if($result->num_rows > 0) {
                        while ($item = $result->fetch_assoc()) {
                            $price = $item['price'];
                            $quantity = $item['quantity'];
                            $sub_total = ($price * $quantity);
                ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo '$'.$price. ' USD'; ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td><?php echo '$' . $sub_total . ' USD'; ?></td>
                </tr>
                <?php } } ?>
                </tbody>
            </table>
        </div>
        <?php } else { ?>
        <div class="col-md-12">
            <div class="alert alert-danger">Your order submission failed.</div>
        </div>
        <?php } ?>
    </div>
</div>
<?php include_once ('inc/footer.php'); ?>
