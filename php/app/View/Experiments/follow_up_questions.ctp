<div class="questions">
    <?php echo $this->Form->create('QuestionAnswer', array('url'=>Router::normalize(array('controller'=>'Experiments','action'=>'thank_you'))));  ?>
    <?php foreach($questions as $question):?>
        <?php
        $options = array("");
        foreach(explode("\n", $question['Question']['answers']) as $answer){
            if(trim($answer)){
                $options[] = $answer;
            }
        }
        echo $this->Form->input('answer', array(
            'label' => $question['Question']['question'] . ':',
            'options' => $options
        ));
        ?>
    <?php endforeach;?>
    <?php echo $this->Form->end("submit");?>
</div>