<?php

class User_model extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
    }
    
    /**
     * Creates a user.
     *
     * @param   string
     * @param   int     the user's id with service
     * @param   string  the user's token for future authenticated requests
     * @param   string  the user's token secret for future authenticated
                        requests
     * @param   string  the URL to the user's profile image
     * @param   string  the user's name
     * @return  array
     */
    public function create($username, $oauth_id = NULL, $oauth_token = NULL,
                           $oauth_token_secret = NULL,
                           $profile_image_url = NULL, $name)
    {
        $this->db->insert('oak_user', array(
            'active' => 1,
            'date_registered' => time(),
            'date_signin' => time(),
            'name' => $name,
            'oauth_id' => $oauth_id,
            'oauth_token' => $oauth_token,
            'oauth_token_secret' => $oauth_token_secret,
            'profile_image_url' => $profile_image_url,
            'username' => $username
        ));
        
        return $this->get_user($this->db->insert_id());
    }
    
    /**
     * Returns a user by id.
     *
     * @param   int     the user's id
     * @return  array
     */
    public function get_user($id)
    {
        $this->db->select('id, name, profile_image_url, username');
        $query = $this->db->get_where('oak_user', array('id' => $id));
        
        return ($query->num_rows() > 0) ? $query->row_array() : FALSE;
    }
    
    /**
     * Returns a user by name.
     *
     * @param   string the user's username
     * @return  array
     */
    public function get_user_by_name($name)
    {
        $this->db->select('id, name, profile_image_url, username');
        $query = $this->db->get_where('oak_user', array('username' => $name));
        
        return ($query->num_rows() > 0) ? $query->row_array() : FALSE;
    }
    
    /**
     * Returns true if the user is authorized, false otherwise.
     *
     * @return  bool
     */
    public function is_authorized()
    {
        if ($this->session->userdata('authorized'))
        {
            return TRUE;
        }
        if ($this->input->cookie('authentication'))
        {
            $cookie_value = explode(':',
                $this->input->cookie('authentication'));
            $username = $cookie_value[0];
            $token = $cookie_value[1];
            
            // Check if random number and username are associated
            $query = $this->db->get_where('oak_cookie', array(
                'token' => $token,
                'username' => $username
            ));
            
            if ($query->num_rows > 0)
            {
                $user = $this->get_user_by_name($username);
                
                $this->session->set_userdata(array(
                    'authorized' => TRUE,
                    'user_id' => $user['id']
                ));
                
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * Sets authentication cookie.
     *
     * @param   array the user
     * @return  void
     */
    public function set_auth_cookie($user)
    {
        $this->load->helper('string');
        
        $token = random_string('alnum', 32);
        $cookie_value = $user['username'] . ':' . $token;
        $this->input->set_cookie('authentication', $cookie_value, '31536000');
        
        // Record random number and username association
        $this->db->insert('oak_cookie', array(
            'date_created' => time(),
            'token' => $token,
            'username' => $user['username']
        ));
        
        $this->session->set_userdata(array(
            'authorized' => TRUE,
            'user_id' => $user['id']
        ));
    }
    
    /**
     * Unsets authentication cookie.
     *
     * @return  void
     */
    public function unset_auth_cookie()
    {
        if ($this->input->cookie('authentication'))
        {
            $cookie_value = explode(':',
                $this->input->cookie('authentication'));
            $username = $cookie_value[0];
            $token = $cookie_value[1];
            $this->input->set_cookie('authentication', '', '');
            
            // Remove random number and username association
            $this->db->delete('oak_cookie', array(
                'token' => $token,
                'username' => $username
            ));
        }
    }
}

?>