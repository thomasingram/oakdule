<?php

class Lst_model extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
    }
    
    /**
     * Adds an existing habit to the list.
     *
     * @param   int     the habit's id
     * @param   int     the user's id
     * @return  bool
     */
    public function add_habit($habit_id, $user_id)
    {
        if ($this->Habit_model->get_habit($habit_id))
        {
            $query = $this->db->get_where('oak_task', array(
                'habit_id' => $habit_id,
                'user_id' => $user_id
            ));
            
            if ($query->num_rows() > 0)
            {
                $task = $query->row_array();
                
                if ($task['active'] == 0)
                {
                    return $this->db->update('oak_task', array('active' => 1),
                                             array('id' => $task['id']));
                }
                
                return FALSE;
            }
            
            $data = array(
                'active' => 1,
                'date_started' => mktime(0, 0, 0),
                'habit_id' => $habit_id,
                'user_id' => $user_id
            );
            
            return $this->db->insert('oak_task', $data);
        }
        
        return FALSE;
    }
    
    public function get_entries($user_id)
    {
        $this->db->select('id, date_started');
        $this->db->order_by('date_started', 'ASC');
        $query_tasks = $this->db->get_where('oak_task', array(
            'active' => 1,
            'user_id' => $user_id
        ));
        
        if ($query_tasks->num_rows() > 0)
        {
            $this->db->select('oak_entry.date_recorded, oak_entry.task_id');
            $this->db->from('oak_entry');
            $this->db->join('oak_task', 'oak_entry.task_id = oak_task.id');
            $this->db->where(array(
                'oak_task.active' => 1,
                'oak_task.user_id' => $user_id
            ));
            $this->db->order_by('oak_entry.date_recorded', 'DESC');
            $this->db->order_by('oak_entry.task_id', 'ASC');
            $query_entries = $this->db->get();
        
            $entries = array();
            $entry_date = new DateTime('00:00:00');
        
            // Start from the first habit
            
            $first_habit = $query_tasks->first_row('array');
            $start_date = $first_habit['date_started'];
        
            while ($entry_date->getTimestamp() >= $start_date)
            {
                $entry_tasks = array();
                foreach ($query_tasks->result_array() as $task)
                {
                    $entry_tasks[$task['id']] = FALSE;
                    foreach ($query_entries->result_array() as $entry)
                    {
                        if ($entry_date->getTimestamp() ==
                            $entry['date_recorded'] &&
                            $task['id'] == $entry['task_id'])
                        {
                            $entry_tasks[$task['id']] = TRUE;
                            break;
                        }
                    }
                }
                $entries[$entry_date->getTimestamp()] = $entry_tasks;
                $entry_date->sub(new DateInterval('P1D'));
            }
        
            return $entries;
        }
        
        return FALSE;
        
        // Array
        // (
        //  [1338332400] => Array
        //      (
        //          [1] => TRUE
        //          [3] => TRUE
        //          [5] => FALSE
        //      )
        //  [1338418800] => Array
        //      (
        //          [1] => TRUE
        //          [3] => FALSE
        //          [5] => FALSE
        //      )
        // )
    }
    
    /**
     * Returns habits for a user.
     *
     * @param   int    the user id
     * @param   bool   if set to TRUE, inactive habits are returned instead
     * @return  mixed  an array if habits were found, FALSE otherwise
     */
    public function get_habits($user_id, $inactive_habits = FALSE)
    {
        $this->db->select('oak_habit.id AS habit_id, oak_habit.description,
            oak_habit.name, oak_habit.slug, oak_task.id, oak_task.date_archived,
            oak_task.date_started');
        $this->db->from('oak_habit');
        $this->db->join('oak_task', 'oak_habit.id = oak_task.habit_id');
        $this->db->where(array(
            'oak_task.active' => ($inactive_habits) ? 0 : 1,
            'oak_task.user_id' => $user_id
        ));
        
        if ($inactive_habits)
        {
            $this->db->order_by('oak_task.date_archived', 'DESC');
        }
        else
        {
            $this->db->order_by('oak_task.date_started', 'ASC');
        }
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
        {
            $habits = $query->result_array();
            
            foreach ($habits as &$habit)
            {
                $query = $this->db->get_where('oak_entry',
                                              array('task_id' => $habit['id']));
                $habit['number_of_entries'] = $query->num_rows();
            }
            unset($habit);
            
            return $habits;
        }
        
        return FALSE;
    }
    
    /**
     * Returns the notes for a task.
     *
     * @param   int    the task id
     * @return  mixed  an array if notes were found, false otherwise
     */
    public function get_notes($task_id)
    {
        $this->db->select('id, body, date_created');
        $this->db->order_by('date_created', 'DESC');
        $query = $this->db->get_where('oak_note', array(
            'active' => 1,
            'task_id' => $task_id
        ));
        
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }
    
    /**
     * Returns a task by id.
     *
     * @param   int    the task id
     * @return  mixed  an array if the task was found, false otherwise
     */
    public function get_task($task_id)
    {
        $this->db->select('oak_habit.name, oak_task.id, oak_task.user_id');
        $this->db->from('oak_task');
        $this->db->join('oak_habit', 'oak_habit.id = oak_task.habit_id');
        $this->db->where(array('oak_task.id' => $task_id));
        $query = $this->db->get();
        
        return ($query->num_rows() > 0) ? $query->row_array() : FALSE;
    }
    
    /**
     * Returns TRUE if the habit is listed, FALSE otherwise.
     *
     * @param   int  the habit id
     * @param   int  the user id
     * @return  bool
     */
    public function is_habit_listed($habit_id, $user_id)
    {
        $query = $this->db->get_where('oak_task', array(
            'active' => 1,
            'habit_id' => $habit_id,
            'user_id' => $user_id
        ));
        
        return ($query->num_rows() > 0);
    }
    
    /**
     * Sets entries from today and yesterday.
     *
     * @param {integer} user_id
     * @return {boolean}
     */
    public function set_entries($user_id)
    {
        $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
        
        // Delete entries from today and yesterday
        
        $this->db->query('DELETE oak_entry FROM oak_entry
            JOIN oak_task ON oak_entry.task_id = oak_task.id
            WHERE oak_entry.date_recorded >= ' . $yesterday . '
            AND oak_task.active = 1
            AND oak_task.user_id = ' . $user_id);
        
        // Add entries passed via POST
        
        foreach ($this->input->post() as $key => $value)
        {
            if (substr($key, 0, 3) == 'cb-') // e.g. cb-1-2012-05-29
            {
                $entry_tokens = explode('-', $key);
                
                $data = array(
                    'date_recorded' => mktime(0, 0, 0, $entry_tokens[3],
                        $entry_tokens[4], $entry_tokens[2]),
                    'task_id' => $entry_tokens[1]
                );
                
                $this->db->insert('oak_entry', $data);
            }
        }
        
        return TRUE;
    }
}

?>