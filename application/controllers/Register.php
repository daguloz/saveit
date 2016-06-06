<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller
{
    // Variable data for views
    public $data = ['title' => 'Register'];

    /**
     * /register
     * Register form
     */
    public function index()
    {
        if ($this->user->is_logged())
            $this->render->redirect('/');
        $type = $this->input->post('type');
        if ($type)
            $this->index_post();
        else
        {
            $this->data['return'] = $this->session->flashdata('return');
            $this->render->load();
        }
    }

    /**
     * /register [POST]
     * Register form validation
     */
    public function index_post()
    {
        // Obtain form data
        $form_data = [
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'name' => $this->input->post('name'),
        ];
        $password_check = $this->input->post('password_check');

        $this->data['return'] = $this->input->post('return');

        // Check for entered email and password
        if (trim($form_data['email']) === '') $form_data['email'] = NULL;
        if (trim($form_data['password']) === '') $form_data['password'] = NULL;
        if (trim($password_check) === '') $password_check = NULL;
        if (trim($form_data['name']) === '') $form_data['name'] = NULL;
        
        if ( ($form_data['email'] !== NULL) && ($form_data['password'] !== NULL) && ($form_data['name'] !== NULL))
        {
            if ($form_data['password'] != $password_check)
            {
                $this->data['error']  = 'The passwords entered are not the same';
                $this->render->load('index');
            }
            else
            {
                $form_data['password'] = password_hash($form_data['password'], PASSWORD_BCRYPT);
                try
                {
                    $this->user->register($form_data);
                    // Redirect to previous page after registering
                    $this->render->redirect($return);
                }
                catch (Exception $e)
                {
                    switch ($e->getMessage()) {
                        case 'Invalid email':
                            $this->data['error']  = "Sorry, that email is already registered. Try to log in, or use another email.";
                            $this->render->load('index');
                            break;
                        
                        default:
                            show_error($e->getMessage());
                            break;
                    }
                }
            }

        }
        else
        {
            $this->data['error']  = 'Please fill the required fields.';
            $this->render->load('index');
        }
    }
}