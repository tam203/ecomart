<?php
class Question extends AppModel {
    public $belongsTo = array(
        'Experiment' => array(
            'className' => 'Experiment'
        )
    );
}