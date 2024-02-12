<?php

namespace App\Service;

use App\Entity\Drink;
use SimpleXMLElement;

class DrinkService
{
    public function __construct() {}

    //Create a new Drink entity and insert our data into it
    public function xmlToDrink(SimpleXMLElement $row){
        return new Drink(
            (int)$row->entity_id,
            $row->CategoryName,
            $row->sku,
            $row->name,
            $row->description,
            (float)$row->price,
            $row->link,
            $row->image,
            $row->Brand,
            (int)$row->Rating,
            $row->CaffeineType,
            (int)$row->Count,
            $row->Flavored,
            $row->Seasonal,
            $row->Instock,
            (int)$row->Facebook,
            (int)$row->IsKCup,
            $row->shortdesc
        );
    }
}