<?php

class ExperimentsController extends AppController {
    //var $scaffold;

    function start($experiment_id){
        $this->Experiment->read(null, $experiment_id);
        $this->createNewParticipant();
        $this->redirect(array('action' => 'household_income'));
    }

    function selection(){
        $this->Experiment->recursive = 3;
        $this->init();
        $this->redirectIfNotInExperiment();
        $total_tax = $this->Experiment->Participant->field('tax_paid');
        $items =  $this->Experiment->data['ShopItem'];
        $spend_on_environment = 0;
        foreach($items as $item){
            $spend_on_environment +=  $item['default_spend'];
        }
        $i = 0;
        foreach($items as $item){
            $normalisation_ratio =   $total_tax / $spend_on_environment;
            $normalised_amount = round($normalisation_ratio * $item['default_spend'], 2);
            $items[$i]['normalised_value'] = $normalised_amount;
            $j=0;
            $max =  $normalised_amount;
            foreach ($item['ItemState'] as $state) {
                $state_min = round($state['valid_at_min'] * $normalisation_ratio,2);
                $items[$i]['ItemState'][$j]['normalised_min'] = $state_min ;
                $state_max = round($state['valid_at_max'] * $normalisation_ratio,2);
                $items[$i]['ItemState'][$j]['normalised_max'] = $state_max;
                $max = ($state_min*1.05 > $max) ? $state_min*1.05 : $max;
                $j++;
            }
            $items[$i]['max_normalised_spend'] = $max;
            $i++;
        }
        $this->set("total_tax", $total_tax);
        $this->set("items", $items);
    }

    function household_income(){
        $this->init();
        $this->redirectIfNotInExperiment();
        if(!empty($this->data['Participant']['household_income'])){
            $this->request->data['Participant']['tax_paid'] = $this->calculate_tax($this->data['Participant']['income']);
            $saved = $this->Experiment->Participant->save($this->request->data);
            if($saved) {
                $this->redirect(array('action'=>'selection'));
            }
        }  else {
            $this->data = array('Participant' => array('id' => $this->Experiment->Participant->id));
        }
    }

    function save_selection(){
        foreach($this->data['ParticipantResult'] as $result){
            $this->Experiment->ParticipantResult->create();
            $this->Experiment->ParticipantResult->save( array('ParticipantResult'=>$result));
        }

    }

    private function calculate_tax($income){
        return 2762 + 0.31 * $income;
    }

    private function init(){
        $this->layout = "experiment";
        $participant_id = $this->getCurrentParticipantId();
        $this->set('participant_id', $participant_id);
        $this->Experiment->Participant->read(null, $participant_id);
        $this->Experiment->read(null, $this->Experiment->Participant->field('experiment_id'));
    }

    private function redirectIfNotInExperiment() {
        if(!$this->getCurrentParticipantId()){
            $this->redirect(array('action' => 'select_experiment'));
        }
    }

    private function getCurrentParticipantId(){
        return $this->Session->read("Participant.id");
    }

    private function createNewParticipant() {
        $this->Experiment->Participant->set('experiment_id', $this->Experiment->id);
        $this->Experiment->Participant->save();

        $participantId = $this->Experiment->Participant->id;

        $this->Session->write("Participant.id", $participantId);
        return $participantId;
    }

    private function getCurrentOrNewParticipantId(){
        $participantId = $this->getCurrentParticipantId();
        if(empty($participantId)){
            $participantId = $this->createNewParticipant();
        }
        return $participantId;
    }

}
