<?php
class Participant extends AppModel {
    public $belongsTo = array(
        "Experiment" => array(
            "className" => "Experiment"
        )
    );
}