<?php

class menuItem {

  private $itemName;
  private $description;
  private $price;

  function __construct($itemName, $description, $price){
    $this->setItemName($itemName);
    $this->setDescription($description);
    $this->setPrice($price);
  }

  public function getItemName(){
    return $this->itemName;
  }

  public function getDescription(){
    return $this->description;
  }

  public function getPrice(){
    return $this->price;
  }

  public function setItemName($itemName){
    $this->itemName = $itemName;
  }

  public function setDescription($description){
    $this->description = $description;
  }
  public function setPrice($price){
    $this->price = $price;
  }



}
?>
