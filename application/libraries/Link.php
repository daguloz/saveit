<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Link
{
    // CodeIgniter instance
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function add($form_data)
    {

        $form_data['user_id'] = $this->CI->session->userdata['user_id'];
        $link_id = $this->CI->dbsaveit->add_link($form_data);
        if ($link_id != NULL)
        {
            return true;
        }
        else
        {
            throw new Exception('Error in database');
        }
    }

    public function get()
    {
        $user_id = $this->CI->session->userdata['user_id'];
        $links = $this->CI->dbsaveit->get_links('user_id', $user_id);
        if ($links !== NULL)
        {
            return $links;
        }
        else
        {
            throw new Exception('Error in database');
        }
    }

}
