<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        if ($this->user->is_logged())
        {
            $this->load->library('link');

            $type = $this->input->post('type');
            if ($type)
                $this->index_post();
            else
            {
                $this->data['links'] = $this->link->get();
                $this->render->load();
            }
        }
        else
        {
            $this->render->load();
        }
    }

    public function index_post()
    {
        $type = $this->input->post('type');

        echo '<pre>';
        print_r($_POST);
        die();
        
        if ($type === 'add_link')
        {
            // Obtain form data
            $form_data = [
                'url' => $this->input->post('url'),
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'tags' => $this->input->post('tags')
            ];

            if (trim($form_data['url']) === '') $form_data['url'] = NULL;
        
            if ($form_data['url'] !== NULL)
            {
                $this->link->add($form_data);
            }
            else
            {
                $this->data['error'] = "URL is required to save a link";
            }
        }
        else
        {
            $this->data['error'] = "Unknown error";
        }
        $this->data['links'] = $this->link->get();
        $this->render->load();
    }
}
