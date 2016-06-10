<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Link
{
    // CodeIgniter instance
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function add($type, $form_data)
    {
        if (($type !== 'link') && ($type !== 'category'))
            return;

        $user_id = $this->CI->session->userdata['user_id'];
        $form_data['user_id'] = $user_id;
        $tags = explode(',', $form_data['tags']);
        unset($form_data['tags']);

        if ($type === 'link')
        {
            $node_id = $this->CI->dbsaveit->add_link($form_data, $tags);
        }
        else if ($type === 'category')
        {
            $node_id = $this->CI->dbsaveit->add_category($form_data, $tags);
        }
        if ($node_id !== NULL)
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
                if ($type === 'link')
                {
                    $data = [
                        'link_id' => $node_id,
                        'tag_id' => $tag_id
                    ];
                    $this->CI->dbsaveit->add_link_tag($data);
                }
                else if ($type === 'category')
                {
                    $data = [
                        'category_id' => $node_id,
                        'tag_id' => $tag_id
                    ];
                    $this->CI->dbsaveit->add_category_tag($data);
                }
            }
            return true;
        }
        else
        {
            throw new Exception('Error in database');
        }
    }

    public function delete($id)
    {
        $user_id = $this->CI->session->userdata['user_id'];
        //$result = $this->CI->dbsaveit->delete($id, $user_id);
        if ($result !== NULL)
        {
            return $result;
        }
        else
        {
            throw new Exception('Error in database');
        }
    }

    public function get_categories()
    {
        $user_id = $this->CI->session->userdata['user_id'];
        $result = $this->CI->dbsaveit->get_categories('user_id', $user_id);
        if ($result !== NULL)
        {
            return $result;
        }
        else
        {
            throw new Exception('Error in database');
        }
    }

    public function get_links()
    {
        $user_id = $this->CI->session->userdata['user_id'];
        $result = $this->CI->dbsaveit->get_links('user_id', $user_id);
        if ($result !== NULL)
        {
            return $result;
        }
        else
        {
            throw new Exception('Error in database');
        }
    }

}
