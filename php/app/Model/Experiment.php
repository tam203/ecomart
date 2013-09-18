<?php
class Experiment extends AppModel {
    public $hasMany = array(
        'ShopItem' => array(
            'className' => 'ShopItem'
        ),

        'Participant' => array(
            'className' => 'Participant'
        ),

        "ParticipantResult" => array(
            'className' => 'ParticipantResult'
        )
    );
}
