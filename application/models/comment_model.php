<?php

class Comment_model extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
    }
    
    /**
     * Creates a comment.
     *
     * @param   array  an associative array of insert values
     * @return  mixed  an array if the comment was created, false otherwise
     */
    public function create($data)
    {
        $this->db->insert('oak_comment', array(
            'active' => 1,
            'body' => $data['body'],
            'date_created' => time(),
            'habit_id' => $data['habit_id'],
            'user_id' => $data['user_id']
        ));
        
        return $this->get_comment($this->db->insert_id());
    }
    
    /**
     * Deactivates a comment.
     *
     * @param   int   the comment to deactivate
     * @return  bool  true if the comment was deactivated, false otherwise
     */
    public function delete($id)
    {
        $this->db->update('oak_comment', array('active' => 0),
                          array('id' => $id));
        
        return ($this->db->affected_rows() > 0);
    }
    
    /**
     * Returns a comment by id.
     *
     * @param   int    the comment id
     * @return  mixed  an array if the comment was found, false otherwise
     */
    public function get_comment($id)
    {
        $this->db->select('id, body, habit_id, user_id');
        $query = $this->db->get_where('oak_comment', array(
            'id' => $id,
            'active' => 1
        ));
        
        return ($query->num_rows() > 0) ? $query->row_array() : FALSE;
    }
    
    /**
     * Updates a comment.
     *
     * @param   array  an associative array of new values
     * @param   int    the comment to update
     * @return  bool   true if the comment was updated, false otherwise
     */
    public function update($data, $id)
    {
        $this->db->update('oak_comment', array('body' => $data['body']),
                          array('id' => $id));
        
        return ($this->db->affected_rows() > 0);
    }
}

?>