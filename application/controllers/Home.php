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
                $this->data['categories'] = $this->link->get_categories();
                $this->data['links'] = $this->link->get_links();
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

        if ($type === 'link-add')
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
                $this->link->add('link', $form_data);
            }
            else
            {
                $this->data['error'] = "URL is required to save a link";
            }
        }
        else if ($type === 'category-add')
        {
            // Obtain form data
            $form_data = [
                'name' => $this->input->post('name'),
                'parent_id' => $this->input->post('parent'),
                'description' => $this->input->post('description'),
                'tags' => $this->input->post('tags')
            ];

            if (trim($form_data['parent_id']) === '') $form_data['parent_id'] = NULL;
            if (trim($form_data['name']) === '') $form_data['name'] = NULL;
        
            if ($form_data['name'] !== NULL)
            {
                $this->link->add('category', $form_data);
            }
            else
            {
                $this->data['error'] = "URL is required to save a link";
            }
        }
        else if ($type === 'link-delete')
        {
            $delete_id = $this->input->post('id');
            if (trim($delete_id === '') $delete_id = NULL;

            if ($delete_id !== NULL)
            {
                $this->link->delete('link', $delete_id);
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
        $this->data['categories'] = $this->link->get_categories();
        $this->data['links'] = $this->link->get_links();
        $this->render->load();
    }
}


No me dará tiempo a completar todos los objetivos marcados inicialmente,