<?php

// start session
if(!session_id()) {
	session_start();
}

class Cart
{
    protected $cart_contents = array();

    public function __construct()
    {
        // get cart array from session data
        $this->cart_contents = !empty($_SESSION['cart_contents']) ? $_SESSION['cart_contents'] : NULL;
        if ($this->cart_contents === NULL) {
            // set base values
            $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        }
    }

    // return cart contents
    public function contents()
    {
        // put newest items first
        $cart = array_reverse($this->cart_contents);

        unset($cart['total_items'], $cart['cart_total']);

        return $cart;
    }

    // get cart item details
    public function get_item($row_id)
    {
        return (in_array($row_id, array('total_items', 'cart_total'), TRUE) OR !isset($this->cart_contents[$row_id]))
            ? FALSE
            : $this->cart_contents[$row_id];
    }


    // return total item count
    public function total_items() {
        return $this->cart_contents['total_items'];
    }

    // returns the total price
    public function total()
    {
        return $this->cart_contents['cart_total'];
    }

    // insert item into the cart and save to session
    public function insert($item = array())
    {
        if (!is_array($item) || count($item) === 0) {
            return FALSE;
        }

        if (!isset($item['id'], $item['name'], $item['price'], $item['qty'])) {
            return FALSE;
        }

        $item['qty'] = (float)$item['qty'];
        if ($item['qty'] === 0) {
            return FALSE;
        }
        $item['price'] = (float)$item['price'];
        // create unique id for item being put in cart
        $rowid = md5($item['id']);

        // get quantity if exists and add to it
        $old_qty = isset($this->cart_contents[$rowid]['qty']) ? (int)$this->cart_contents[$rowid]['qty'] : 0;
        // re-enter with unique id
        $item['rowid'] = $rowid;
        $item['qty'] += $old_qty;
        $this->cart_contents[$rowid] = $item;

        // save cart item
        if ($this->save_cart()) {
            return isset($rowid) ? $rowid : TRUE;
        }

        return FALSE;
    }


    // update cart
    public function update($item = array())
    {
        if (!is_array($item) || count($item) === 0) {
            return FALSE;
        }

        if (!isset($item['rowid'], $this->cart_contents[$item['rowid']])) {
            return FALSE;
        }

        if (isset($item['qty'])) {
            $item['qty'] = (float)$item['qty'];
            //remove the item from the cart if the quantity changes to 0
            if ($item['qty'] === 0) {
                unset($this->cart_contents[$item['rowid']]);
                return TRUE;
            }
        }

        // find updatable keys
        $keys = array_intersect(array_keys($this->cart_contents[$item['rowid']]), array_keys($item));
        // set price
        if (isset($item['price'])) {
            $item['price'] = (float)$item['price'];
        }

        foreach (array_diff($keys, array('id', 'name')) as $key) {
            $this->cart_contents[$item['rowid']][$key] = $item[$key];
        }

        // save cart data
        $this->save_cart();
        return TRUE;
    }

    // save the cart array to session
    protected  function save_cart() {
        $this->cart_contents['total_items'] = $this->cart_contents['cart_total'] = 0;
        foreach ($this->cart_contents as $key => $val) {
            // check array has proper indexes
            if(!is_array($val) || !isset($val['price'], $val['qty'])) {
                continue;
            }

            $this->cart_contents['cart_total'] += ($val['price'] * $val['qty']);
            $this->cart_contents['total_items'] += $val['qty'];
            $this->cart_contents[$key]['subtotal'] = ($this->cart_contents[$key]['price'] * $this->cart_contents[$key]['qty']);
        }

        // if the cart is empty, delete it from session
        if(count($this->cart_contents) <= 2) {
            unset($_SESSION['cart_contents']);
            return FALSE;
        } else {
            $_SESSION['cart_contents'] = $this->cart_contents;
            return TRUE;
        }
    }

    // remove item from cart
    public function remove($row_id) {
        unset($this->cart_contents[$row_id]);
        $this->save_cart();
        return TRUE;
    }

    // destroy cart
    public function destroy() {
        $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        unset($_SESSION['cart_contents']);
    }



}
