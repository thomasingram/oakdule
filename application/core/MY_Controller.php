<?php

class MY_Controller extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('user_model');
        
        if ($this->user_model->is_authorized())
        {
            $user_id = $this->session->userdata('user_id');
            $this->data['user'] = $this->user_model->get_user($user_id);
        }
        // else
        // {
        //     redirect('/');
        // }
    }
}

?>