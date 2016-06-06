<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User
{
    // CodeIgniter instance
    private $CI;

    // User ogged in status
    private $logged_in;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->model('dbsaveit');

        if ($this->CI->session->logged_in === true)
        {
            $user = $this->CI->dbsaveit->get_user('id', $this->CI->session->user_id);
            if (($user !== NULL) && ($user !== FALSE))
            {
                $this->logged_in = true;
            }
        } else
        {
            $this->logged_in = false;
        }

    }

    public function is_logged()
    {
        return $this->logged_in;
    }

    // Try to login an user
    public function login($user_data)
    {

        $email = $user_data['email'];

        // Check if user exists in database
        $user_db = $this->CI->dbsaveit->get_user('email', $email);
        $user_id = FALSE;

        if ($user_db === NULL)
        {
            // User does not exist in DB
            throw new Exception("Invalid login");
        }
        else if ($user_db === FALSE)
        {
            // Error in database
            throw new Exception('Error in database');
        }
        else
        {
            // User found in DB
            if (password_verify($user_data['password'], $user_db->password))
            {
                $user_id = $user_db->id;
                //$this->CI->db->update_login($user_id, $user_data);
            }
            else
            {
                throw new Exception("Invalid password");
            }
        }

        if ($user_id !== FALSE)
        {
            // Save userdata to session
            $this->CI->session->logged_in = true;
            $this->CI->session->login_time = time();
            $this->CI->session->login_save = $save;
            $this->CI->session->user_id = $user_id;
            $this->CI->session->name = $user_db->name;
            $this->CI->session->email = $user_data['email'];
        }

    }

    public function register($user_data)
    {

        // Check if user exists in database
        $user_db = $this->CI->dbsaveit->get_user('email', $user_data['email']);

        if ($user_db === NULL)
        {
            // User does not exist in DB

            if ($user_id = $this->CI->dbsaveit->register_user($user_data))
            {
                $this->CI->session->logged_in = true;
                $this->CI->session->login_time = time();
                $this->CI->session->user_id = $user_id;
                $this->CI->session->name = $user_data['name'];
                $this->CI->session->email = $user_data['email'];
            }
            else
            {
                throw new Exception('Error in database');
            }

        }
        else if ($user_db === FALSE)
        {
            // Error in database
            throw new Exception('Error in database');
        }
        else
        {
            // User found in DB
            throw new Exception("Invalid email");
        }

    }

    public function logout()
    {
        $this->CI->session->sess_destroy();
        $this->CI->render->redirect('/');
    }
}
