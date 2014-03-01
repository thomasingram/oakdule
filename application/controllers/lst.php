<?php

class Lst extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('session');
        
        $this->load->model(array(
            'Habit_model',
            'Lst_model',
            'Note_model',
            'User_model'
        ));
        
        $this->data['navigation_links'] = array(
            'Habit list' => 'list',
            'Create habit' => 'list/add',
            'Archive' => 'list/archive'
        );
    }
    
    /**
     * Adds a habit to the list.
     *
     * @param   int   existing habit to add
     * @return  void
     */
    public function add($habit_id = NULL)
    {
        if ($habit_id)
        {
            if ($this->Lst_model->add_habit($habit_id,
                                            $this->data['user']['id']))
            {
                $this->session->set_flashdata('message', 'Habit adopted.');
                redirect('list');
            }
            
            return FALSE;
        }
        
        $this->load->helper('form');
        
        $this->load->library('form_validation');
        
        $this->data['title'] = 'Create new habit';
        
        $this->form_validation->set_rules('name', 'Name', 'required');
        
        if ($this->form_validation->run() === FALSE)
        {
            // $this->load->view('templates/header', $this->data);
            // $this->load->view('list/index_new2');
            // $this->load->view('templates/sidebar');
            // $this->load->view('templates/footer');
            
            redirect('list', 'location', 303);
        }
        else
        {
            $this->Habit_model->create(array(
                'description' => $this->input->post('description'),
                'name' => $this->input->post('name'),
                'user_id' => $this->data['user']['id']
            ));
            $this->session->set_flashdata('message', 'Habit added.');
            redirect('list', 'location', 303);
        }
    }
    
    /**
     * Views the archive.
     *
     * @param   string  
     * @return  void
     */
    public function archive($username = NULL)
    {
        $this->data['authorized'] = TRUE;
        $user_id = $this->data['user']['id'];
        
        if ($username)
        {
            $this->data['list_owner'] = $this->User_model->
                get_user_by_name($username);
            
            if (!$this->data['list_owner'])
            {
                return FALSE;
            }
            
            $user_id = $this->data['list_owner']['id'];
            
            $this->data['authorized'] = ($this->data['user']['id'] ==
                $this->data['list_owner']['id']);
            
            $this->data['navigation_links']['Habit list'] = 'list/' .
                $this->data['list_owner']['username'];
            $this->data['navigation_links']['Archive'] = 'list/' .
                $this->data['list_owner']['username'] . '/archive';
            unset($this->data['navigation_links']['Create habit']);
        }
        
        $this->data['habits'] = $this->Lst_model->get_habits($user_id, TRUE);
        $this->data['page'] = 'archive';
        $this->data['title'] = $this->data['authorized'] ? 'Your archive' :
            $this->data['list_owner']['name'] . '’s archive';
        
        $this->load->view('templates/header', $this->data);
        $this->load->view('list/archive', $this->data);
        $this->load->view('templates/sidebar', $this->data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Deletes a habit from the list.
     *
     * @param   int   the habit to delete
     * @return  void
     */
    public function delete($task_id)
    {
        $task = $this->Lst_model->get_task($task_id);
        
        if ($task && $task['user_id'] == $this->data['user']['id'])
        {
            $this->Habit_model->delete($task_id);
            $this->session->set_flashdata('message', 'Habit deleted.');
            redirect('list');
        }
        
        return FALSE;
    }
    
    /**
     * Views the list.
     *
     * @param   string  
     * @param   int     the page number of the list
     * @return  void
     */
    public function index($username = NULL, $page_number = 1)
    {
        if (isset($_POST['submit']))
        {
            $this->Lst_model->set_entries($this->data['user']['id']);
        }
        
        $this->data['authorized'] = TRUE;
        $user_id = isset($this->data['user']) ? $this->data['user']['id'] : 1;
        
        if ($username)
        {
            $this->data['list_owner'] = $this->User_model->
                get_user_by_name($username);
            
            if (!$this->data['list_owner'])
            {
                return FALSE;
            }
            
            $user_id = $this->data['list_owner']['id'];
            
            $this->data['authorized'] = ($this->data['user']['id'] ==
                $this->data['list_owner']['id']);
            
            $this->data['navigation_links']['Habit list'] = 'list/' .
                $this->data['list_owner']['username'];
            $this->data['navigation_links']['Archive'] = 'list/' .
                $this->data['list_owner']['username'] . '/archive';
            unset($this->data['navigation_links']['Create habit']);
        }
        
        $this->data['entries'] = $this->Lst_model->get_entries($user_id);
        $this->data['habits'] = $this->Lst_model->get_habits($user_id);
        $this->data['page'] = 'list';
        // $this->data['title'] = $this->data['authorized'] ? 'Your list' :
        //     $this->data['list_owner']['name'] . '’s list';
        $this->data['title'] = isset($this->data['user']) ? 'Your list' : '';
        
        // Pagination
        
        $config['base_url'] = isset($this->data['list_owner']) ?
            site_url('list/' . $this->data['list_owner']['username'] . '/') :
            site_url('list/');
        $config['display_pages'] = FALSE;
        $config['first_link'] = FALSE;
        $config['full_tag_open'] = '<div id="pagination">';
        $config['full_tag_close'] = '</div>';
        $config['last_link'] = FALSE;
        $config['next_link'] = 'Next';
        $config['per_page'] = 14;
        $config['prev_link'] = 'Previous';
        $config['total_rows'] = count($this->data['entries']);
        $config['uri_segment'] = isset($this->data['list_owner']) ? 3 : 2;
        $config['use_page_numbers'] = TRUE;
        
        if ($config['total_rows'] > $config['per_page'])
        {
            $this->load->library('pagination');
            
            $this->pagination->initialize($config);
            
            $this->data['pagination'] = $this->pagination->create_links();
            
            $offset = ($page_number - 1) * $config['per_page'];
            $this->data['entries'] = array_slice($this->data['entries'],
                $offset, $config['per_page'], TRUE);
        }
        
        $this->load->view('templates/header', $this->data);
        // $this->load->view('list/index_new2', $this->data);
        $this->load->view('list/index', $this->data);
        // $this->load->view('templates/sidebar', $this->data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Deletes a note.
     *
     * @param   int   the note to delete
     * @param   int   the task id
     * @return  void
     */
    public function note_delete($note_id, $task_id)
    {
        $note = $this->Note_model->get_note($note_id);
        $task = $this->Lst_model->get_task($task_id);
        
        if ($note && $task &&
            $note['task_id'] == $task['id'] &&
            $task['user_id'] == $this->data['user']['id'])
        {
            $this->Note_model->delete($note['id']);
            $this->session->set_flashdata('message', 'Note deleted.');
            redirect('list/notes/' . $task['id']);
        }
        
        return FALSE;
    }
    
    /**
     * Edits a note.
     *
     * @param   int    the note to edit
     * @param   int    the task id
     * @return  mixed
     */
    public function note_edit($note_id, $task_id)
    {
        $this->data['note'] = $this->Note_model->get_note($note_id);
        $this->data['task'] = $this->Lst_model->get_task($task_id);
        
        if ($this->data['note'] && $this->data['task'] &&
            $this->data['note']['task_id'] == $this->data['task']['id'] &&
            $this->data['task']['user_id'] == $this->data['user']['id'])
        {
            $this->load->helper('form');
            
            $this->load->library('form_validation');
            
            $this->data['title'] = 'Edit note';
            
            $this->form_validation->set_rules('body', 'Note', 'required');
            
            if ($this->form_validation->run() == FALSE)
            {
                $this->load->view('templates/header', $this->data);
                $this->load->view('note/edit', $this->data);
                $this->load->view('templates/sidebar', $this->data);
                $this->load->view('templates/footer');
            }
            else
            {
                $this->Note_model->
                    update(array('body' => $this->input->post('body')),
                           $this->data['note']['id']);
                
                $this->session->set_flashdata('message', 'Note edited.');
                redirect('list/notes/' . $this->data['task']['id'] . '#note-' .
                         $this->data['note']['id'], 'location', 303);
            }
        }
        
        return FALSE;
    }
    
    /**
     * Views notes for a habit.
     *
     * @param   int    the task id
     * @return  mixed
     */
    public function note_index($task_id)
    {
        $this->data['task'] = $this->Lst_model->get_task($task_id);
        
        if ($this->data['task'] &&
            $this->data['task']['user_id'] == $this->data['user']['id'])
        {
            $this->load->helper('form');
            
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('body', 'Note', 'required');
            
            if ($this->form_validation->run() == FALSE)
            {
                $this->data['notes'] = $this->Lst_model->
                    get_notes($this->data['task']['id']);
                
                $this->data['title'] = 'Notes for ' .
                    $this->data['task']['name'];
                
                $this->load->view('templates/header', $this->data);
                $this->load->view('note/index', $this->data);
                $this->load->view('templates/sidebar', $this->data);
                $this->load->view('templates/footer');
            }
            else
            {
                $note = $this->Note_model->create(array(
                    'body' => $this->input->post('body'),
                    'task_id' => $this->data['task']['id']
                ));
                $this->session->set_flashdata('message', 'Note added.');
                redirect('list/notes/' . $this->data['task']['id'] . '#note-' .
                         $note['id'], 'location', 303);
            }
        }
        
        return FALSE;
    }
}

?>