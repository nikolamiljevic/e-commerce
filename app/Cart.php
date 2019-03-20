<?php

namespace App;


class Cart
{
    public $items; //['id'=>['quantity'=>,'price'=>,'data'=>],...]
    public $totalQuantity;
    public $totalPrice;

    public function __construct($prevCart){
        if ($prevCart != null) {
            //if there is items from previous add
            $this->items = $prevCart->items;
            $this->totalQuantity = $prevCart->totalQuantity;
            $this->price = $prevCart->totalPrice;

        }else{
            //if there is not any items in basket
            $this->items = [];
            $this->totalQuantity = 0;
            $this->totalPrice = 0;
        }
    }

    public function addItem($id, $product){

        $price = (int) str_replace("$","",$product->price);

        //the item already exists
        if(array_key_exists($id,$this->items)){
            $productToAdd = $this->items[$id];
            $productToAdd['quantity']++;

        }else{
            $productToAdd = ['quantity'=>1, 'price'=>$price,'data'=>$product];
        }

        $this->items[$id] = $productToAdd;
        $this->totalQuantity++;
        $this->totalPrice = $this->totalPrice + $price;
    }
    
}