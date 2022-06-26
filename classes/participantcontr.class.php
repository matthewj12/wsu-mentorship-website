<?php

//This is the middle man which interacts with client directly
//So, this will call all the methods from participant and participantview
class ParticipantContr extends Participant {

    public function registerUser()
    {
        $this->registerUser();
    }

    public function showView()
    {
        include 'classes/participantview.class.php';
    }
}