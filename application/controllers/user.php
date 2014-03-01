<?php

require_once APPPATH . 'libraries/scribe-php/src/test/bootstrap.php';

class User extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library(array(
            'email',
            'session'
        ));
        
        $this->load->model('user_model');
    }
    
    /**
     * Sign in with Twitter.
     *
     * @return  void
     */
    public function sign_in_twitter()
    {
        // Check if the user has authorized at Twitter
        
        if (!($this->input->get('oauth_token') ||
            $this->input->get('oauth_verifier')))
        {
            $builder = new ServiceBuilder();
            $service = $builder->provider(new TwitterApi())->
                apiKey('S6uQz5XvY3JMGlZuquy1Q')->
                apiSecret('CzcukKbhN7Em7neYoR3OAtSrLRNSPyyFoN3AATN8Nmg')->
                callback(site_url('user/sign_in_twitter/'))->
                build();
            $request_token = $service->getRequestToken();
            $this->session->set_userdata(array(
                'oauth_service' => $service,
                'request_token' => $request_token
            ));
            redirect($service->getAuthorizationUrl($request_token));
        }
        
        // Twitter passes authorization information for the user
        
        $service = $this->session->userdata('oauth_service');
        
        // Convert the request token to an access token
        $access_token = $service->getAccessToken(
            $this->session->userdata('request_token'),
            new Verifier($this->input->get('oauth_verifier')));
        $this->session->unset_userdata('request_token');
        
        // Request representation of user
        
        $request = new OAuthRequest(Verb::GET,
            'http://api.twitter.com/1/account/verify_credentials.json');
        $service->signRequest($access_token, $request);
        $response = $request->send();
        $authed_user = json_decode($response->getBody(), TRUE);
        
        $user = $this->user_model->
            get_user_by_name($authed_user['screen_name']);
        
        if (!$user)
        {
            $user = $this->user_model->create($authed_user['screen_name'],
                                              $authed_user['id'],
                                              $access_token->getToken(),
                                              $access_token->getSecret(),
                                            $authed_user['profile_image_url'],
                                            $authed_user['name']);
            
            $this->email->from('contact@oakdule.com', 'Oakdule');
            $this->email->to('contact@oakdule.com');
            
            $this->email->subject('Sign in with Twitter');
            $this->email->message('@' . $authed_user['screen_name'] .
                ' has signed in with Twitter.');
            
            $this->email->send();
        }
        
        $this->user_model->set_auth_cookie($user);
        redirect('list');
    }
    
    public function logout()
    {
        if ($this->session->userdata('authorized'))
        {
            $this->session->set_userdata('authorized', FALSE);
            $this->session->unset_userdata(array(
                'oauth_service' => '',
                'user_id' => ''
            ));
            
            if ($this->input->cookie('authentication'))
            {
                $this->user_model->unset_auth_cookie();
            }
        }
        redirect('/');
    }
}

?>