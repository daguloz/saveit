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

        $user_id = $this->CI->session->userdata['user_id'];
        $form_data['user_id'] = $user_id;
        $tags = explode(',', $form_data['tags']);
        unset($form_data['tags']);

        $link_id = $this->CI->dbsaveit->add_link($form_data, $tags);
        if ($link_id !== NULL)
        {
            $user_tags = $this->CI->dbsaveit->get_tags('t.user_id', $user_id);

            foreach ($tags as $t) {
                $t = trim($t);

                $found = false;
                foreach ($user_tags as $key => $value) {
                    if ($value->name === $t)
                    {
                        $tag_id = $value->id;
                        $found = true;
                        echo "Found! ID: $tag_id\n";
                        break;
                    }
                }
                if ($found === false)
                {
                    $data = [
                        'user_id' => $user_id,
                        'name' => $t
                    ];
                    $tag_id = $this->CI->dbsaveit->add_tag($data);
                    
                }

                $data = [
                    'link_id' => $link_id,
                    'tag_id' => $tag_id
                ];
                $this->CI->dbsaveit->add_link_tag($data);
            }
            die();
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
