<?php
class ItemState extends AppModel {
    public $belongsTo = array(
        "ShopItem"  => array(
            'className' => "ShopItem"
        )
    );
}