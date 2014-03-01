<?php

class Habit extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('session');
        
        $this->load->model(array(
            'Comment_model',
            'Habit_model',
            'Lst_model',
            'User_model'
        ));
        
        $this->data['navigation_links'] = array(
            'Habit list' => 'list',
            'Create habit' => 'list/add',
            'Archive' => 'list/archive'
        );
    }
    
    /**
     * Deletes a comment.
     *
     * @param   int     the comment to delete
     * @param   string  the habit slug
     * @return  void
     */
    public function comment_delete($comment_id, $slug)
    {
        $comment = $this->Comment_model->get_comment($comment_id);
        $habit = $this->Habit_model->get_habit_by_name($slug);
        
        if ($comment && $habit &&
            $comment['habit_id'] == $habit['id'] &&
            $comment['user_id'] == $this->data['user']['id'])
        {
            $this->Comment_model->delete($comment['id']);
            $this->session->set_flashdata('message', 'Comment deleted.');
            redirect('habit/' . $slug);
        }
        
        return FALSE;
    }
    
    /**
     * Edits a comment.
     *
     * @param   int     the comment to edit
     * @param   string  the habit slug
     * @return  void
     */
    public function comment_edit($comment_id, $slug)
    {
        $this->data['comment'] = $this->Comment_model->get_comment($comment_id);
        $this->data['habit'] = $this->Habit_model->get_habit_by_name($slug);
        
        if ($this->data['comment'] && $this->data['habit'] &&
            $this->data['comment']['habit_id'] == $this->data['habit']['id'] &&
            $this->data['comment']['user_id'] == $this->data['user']['id'])
        {
            $this->load->helper('form');
            
            $this->load->library('form_validation');
            
            $this->data['title'] = 'Edit comment';
            
            $this->form_validation->set_rules('body', 'Comment', 'required');
            
            if ($this->form_validation->run() == FALSE)
            {
                $this->load->view('templates/header', $this->data);
                $this->load->view('comment/edit', $this->data);
                $this->load->view('templates/sidebar', $this->data);
                $this->load->view('templates/footer');
            }
            else
            {
                $this->Comment_model->
                    update(array('body' => $this->input->post('body')),
                           $this->data['comment']['id']);
                
                $this->session->set_flashdata('message', 'Comment edited.');
                redirect('habit/' . $this->data['habit']['slug'] . '#comment-' .
                         $this->data['comment']['id'], 'location', 303);
            }
        }
        
        return FALSE;
    }
    
    /**
     * Views a specific habit.
     *
     * @param   string  the habit to view
     * @return  void
     */
    public function view($slug)
    {
        $this->data['habit'] = $this->Habit_model->get_habit_by_name($slug);
        
        if (!$this->data['habit'])
        {
            show_404();
        }
        
        $this->load->helper('form');
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('body', 'Comment', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
            $this->data['comments'] = $this->Habit_model->
                get_comments($this->data['habit']['id']);
            
            $this->data['habit_author'] = $this->User_model->
                get_user($this->data['habit']['user_id']);
            
            $this->data['habit_listed'] = $this->Lst_model->
                is_habit_listed($this->data['habit']['id'],
                                $this->data['user']['id']);
            
            $this->data['habit_users'] = $this->Habit_model->
                get_users($this->data['habit']['id']);
            
            $this->data['page'] = 'habit_view';
            $this->data['title'] = $this->data['habit']['name'];
            
            $this->load->view('templates/header', $this->data);
            $this->load->view('habit/view', $this->data);
            $this->load->view('templates/sidebar', $this->data);
            $this->load->view('templates/footer');
        }
        else
        {
            $comment = $this->Comment_model->create(array(
                'body' => $this->input->post('body'),
                'habit_id' => $this->data['habit']['id'],
                'user_id' => $this->data['user']['id']
            ));
            $this->session->set_flashdata('message', 'Comment added.');
            redirect('habit/' . $this->data['habit']['slug'] . '#comment-' .
                     $comment['id'], 'location', 303);
        }
    }
}

?>