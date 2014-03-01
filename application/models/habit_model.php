<?php

class Habit_model extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
    }
    
    /**
     * Creates a habit.
     *
     * @param   array  an associative array of insert values
     * @return  bool   true if the habit was created, false otherwise
     */
    public function create($data)
    {
        $slug = url_title($data['name']);
        
        $habit_created = $this->db->insert('oak_habit', array(
            'active' => 1,
            'date_created' => time(),
            'description' => $data['description'],
            'name' => $data['name'],
            'slug' => $slug,
            'user_id' => $data['user_id']
        ));
        
        $task_created = $this->db->insert('oak_task', array(
            'active' => 1,
            'date_started' => mktime(0, 0, 0),
            'habit_id' => $this->db->insert_id(),
            'user_id' => $data['user_id']
        ));
        
        return ($habit_created && $task_created);
    }
    
    public function delete($task_id)
    {
        // Retrieve the task entry
        
        $this->db->select('habit_id');
        $this->db->where('id', $task_id);
        $query = $this->db->get('oak_task');
        $task_entry = $query->row_array();
        
        // Deactivate the task entry
        
        $this->db->where('id', $task_id);
        $task_deactivated = $this->db->update('oak_task', array(
            'active' => 0,
            'date_archived' => time()
        ));
        
        /*
        
        // Check for other task entries for this habit, if there are none
        // deactivate the habit entry
        
        $this->db->where(array(
            'active' => 1,
            'habit_id' => $task_entry['habit_id']
        ));
        $query = $this->db->get('oak_task');
        
        if ($query->num_rows() == 0)
        {
            $this->db->where('id', $task_entry['habit_id']);
            $this->db->update('oak_habit', array('active' => 0));
        }
        
        */
        
        return $task_deactivated;
    }
    
    /**
     * Returns the comments for a habit.
     *
     * @param {integer} id the habit
     * @return {array|boolean}
     */
    public function get_comments($id)
    {
        $this->db->select('id, body, date_created, user_id');
        $query = $this->db->get_where('oak_comment', array(
            'active' => 1,
            'habit_id' => $id
        ));
        
        if ($query->num_rows() > 0)
        {
            $comments = $query->result_array();
            
            foreach ($comments as &$comment)
            {
                $this->db->select('name, profile_image_url, username');
                $query = $this->db->get_where('oak_user',
                    array('id' => $comment['user_id']));
                $comment['user'] = $query->row_array();
            }
            unset($comment);
            
            return $comments;
        }
        
        return FALSE;
    }
    
    /**
     * Returns all habits, or get a habit by its id, or get all habits for a
     * particular user.
     *
     * @param {integer} id the habit
     * @param {integer} user_id
     * @return {array|boolean}
     */
    public function get_habit($id)
    {
        // $this->db->select('id, description, name, slug');
        $query = $this->db->get_where('oak_habit', array(
            'id' => $id,
            'active' => 1
        ));
        
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        
        return FALSE;
    }
    
    public function get_habit_by_name($name)
    {
        $name = str_replace('-', ' ', $name);
        $this->db->select('id, date_created, description, name, slug, user_id');
        $query = $this->db->get_where('oak_habit', array('name' => $name));
        
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        
        return FALSE;
    }
    
    /**
     * Returns the users for a habit
     *
     * @param   int    the habit id
     * @return  mixed  an array if users were found, false otherwise
     */
    public function get_users($id)
    {
        $this->db->select('oak_user.name, oak_user.profile_image_url,
            oak_user.username');
        $this->db->from('oak_task');
        $this->db->join('oak_user', 'oak_task.user_id = oak_user.id');
        $this->db->where(array(
            'oak_task.active' => 1,
            'oak_task.habit_id' => $id
        ));
        $this->db->order_by('oak_task.date_started', 'ASC');
        $query = $this->db->get();
        
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }
}

?>