<?php
class ShopItem extends AppModel {
    public $belongsTo = array(
        'Experiment' => array(
            'className' => 'Experiment'
        )
    );

    public $hasMany = array(
        "ItemState" => array(
            "className" => "ItemState"
        )
    );
}