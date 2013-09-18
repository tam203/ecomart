<?php
class Participant extends AppModel {
    public $belongsTo = array(
        "Experiment" => array(
            "className" => "Experiment"
        )
    );

    public $hasMany = array(
        "ParticipantResult" => array(
            'className' => 'ParticipantResult'
        )
    );

}