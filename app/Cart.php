<?php

namespace App;


class Cart
{
    public $items; //['id'=>['quantity'=>,'price'=>,'data'=>],...]
    public $totalQuantity;
    public $totalPrice;


    public function __construct($prevCart){
        if ($prevCart != null) {

            $this->items = $prevCart->items;
            $this->totalQuantity = $prevCart->totalQuantity;
            $this->items = $prevCart->totalPrice;

        }else{
            $this->items = [];
            $this->totalQuantity = 0;
            $this->totalPrice = 0;
        }
    }
    
}