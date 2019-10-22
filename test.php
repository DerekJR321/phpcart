<?php


    $products = file_get_contents("bikerentals.json");
    $arr = json_decode($products, true);
    $productLen = count($arr['products']);

$tmp = '2';

for($i = 0; $i < $productLen; $i++) {
    if($arr['products'][$i]['id'] == $tmp) {
        echo 'sheeeit. where in the if statement<br>';
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

print_r($itemData);
echo '<br><br>';
echo 'ID: ' . $itemData['id'];
echo '<br>';
echo 'NAME: ' . $itemData['name'];
echo '<br>';

