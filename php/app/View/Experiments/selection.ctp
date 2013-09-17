<div class="selection_view">

    <?php echo $this->Form->create('ParticipantResult', array('url'=>Router::normalize(array('controller'=>'Experiments','action'=>'save_selection'))));  ?>
    <?php echo $this->Form->input('participant_id',array('type'=>'hidden'));  ?>
    <?php $count = 0;?>
    <fieldset>
        <legend>You tax spend</legend>
        <em>Your current tax spend:</em><span>£<?php echo $total_tax;?></span><br/>
        <em>The tax you are willing to spend:</em>£<span id="calc_total"><?php echo $total_tax;?></span>
    </fieldset>
    <?php foreach($items as $item):?>
        <fieldset class="item">
            <legend><?php echo $item['name']?></legend>
            <?php $id = "amount_for_".$item['id'];?>
            <div id="slider-<?php echo $id;?>" class="slider" style="display:none;" data-max="<?php echo $item['max_normalised_spend'];?>"></div>
            <?php echo $this->Form->input('ParticipantResult['.$count.'][shop_item_id]',array('type'=>'hidden', 'value'=>$item['id']));  ?>
            <?php echo $this->Form->input('ParticipantResult['.$count.'][amount]',array('id'=>$id, 'class'=>'itemSpend money', 'value'=>$item['normalised_value']));  ?>
            <?php foreach($item['ItemState'] as $state):?>
                <div class="itemstate state-<?php echo $id;?>" data-min="<?php echo $state['normalised_min'];?>" data-max="<?php echo $state['normalised_max'];?>">
                    <h3><?php echo $state['name'];?></h3>
                    <p>
                        <?php echo $state['description'];?>
                    </p>
                    <p class="valid_statment">Valid between £<?php echo $state['normalised_min'];?> and £<?php echo $state['normalised_max'];?></p>
                </div>
            <?php endforeach;?>
        </fieldset>
        <?php $count++;?>
    <?php endforeach;?>
    <?php echo $this->Form->end(); ?>

    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <?php echo $this->Html->script('autoNumeric'); ?>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script>
        $(function() {
            // hide stuff needed for non js only
            $('.valid_statment').hide();

            // display money nicely
            $('input.money').autoNumeric('init',{aSign: '£'});

            // set up the sliders
            $( ".slider" ).each(function(index, ele){
                var input = $('#'+ele.id.replace('slider-',''))
                var min = 0;
                var max = parseFloat($(ele).attr('data-max'));
                var step = (max-min)/100
                step = (step < 1) ? 0.1 : Math.floor(step);

                $(ele).show();
                $(ele).slider({
                    value: parseFloat($(input).autoNumeric('get')),
                    min:min,
                    max:max,
                    step:step,
                    slide: function( event, ui ) {
                        input.autoNumeric('set', ui.value );
                        updateTotal();
                        toggleStates(input);
                    }
                });

                input.change(function(event){
                    var slider = $('#slider-' + event.target.id);
                    $(slider).slider('value',  parseFloat($(event.target).val()));
                    toggleStates(input)
                });

                toggleStates(input)

            });

        });

        function updateTotal(){
            var total=0;
            $('.itemSpend').each(function(i, ele){
                total+= parseFloat($(ele).autoNumeric('get'));
            })
            $('#calc_total').html(total.toFixed(2))
        }

        function toggleStates(input){
            var value = parseFloat($(input).autoNumeric('get'));

            $('.state-'+$(input).attr('id')).each(function(i, ele){
                var min = parseFloat($(ele).attr('data-min'));
                var max = parseFloat($(ele).attr('data-max'));
                var show = (value >= min && value < max);
                if(show) {
                    $(ele).show();
                } else {
                    $(ele).hide();
                }
            })
        }
    </script>
</div>
