<?php

class Pages extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('user_model');
        
        if ($this->user_model->is_authorized())
        {
            $user_id = $this->session->userdata('user_id');
            $this->data['user'] = $this->user_model->get_user($user_id);
        }
    }
    
    public function view($page = 'index')
    {
        if (!file_exists(APPPATH . 'views/pages/' . $page . '.php'))
        {
            show_404();
        }
        
        $this->data['title'] = ucfirst($page); // Capitalise the first letter
        
        $this->load->view('templates/header', $this->data);
        $this->load->view('pages/' . $page, $this->data);
        $this->load->view('templates/footer');
    }
}

?>