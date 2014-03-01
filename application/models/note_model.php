<?php

class Note_model extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
    }
    
    /**
     * Creates a note.
     *
     * @param   array  an associative array of insert values
     * @return  mixed  an array if the note was created, false otherwise
     */
    public function create($data)
    {
        $this->db->insert('oak_note', array(
            'active' => 1,
            'body' => $data['body'],
            'date_created' => time(),
            'task_id' => $data['task_id']
        ));
        
        return $this->get_note($this->db->insert_id());
    }
    
    /**
     * Deactivates a note.
     *
     * @param   int   the note to deactivate
     * @return  bool  true if the note was deactivated, false otherwise
     */
    public function delete($id)
    {
        $this->db->update('oak_note', array('active' => 0), array('id' => $id));
        
        return ($this->db->affected_rows() > 0);
    }
    
    /**
     * Returns a note by id.
     *
     * @param   int    the note id
     * @return  mixed  an array if the note was found, false otherwise
     */
    public function get_note($id)
    {
        $this->db->select('id, body, task_id');
        $query = $this->db->get_where('oak_note', array(
            'id' => $id,
            'active' => 1
        ));
        
        return ($query->num_rows() > 0) ? $query->row_array() : FALSE;
    }
    
    /**
     * Updates a note.
     *
     * @param   array  an associative array of new values
     * @param   int    the note to update
     * @return  bool   true if the note was updated, false otherwise
     */
    public function update($data, $id)
    {
        $this->db->update('oak_note', array('body' => $data['body']),
                          array('id' => $id));
        
        return ($this->db->affected_rows() > 0);
    }
}

?>