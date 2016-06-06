<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    // Variable data for views
    public $data = ['title' => 'Login'];

    /**
     * /login
     * Login form
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
     * /login [POST]
     * Login form validation
     */
    public function index_post()
    {
        // Obtain form data
        $form_data = [
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'save' => $this->input->post('save'),
        ];

        $this->data['return'] = $this->input->post('return');

        // Check for entered email and password
        if (trim($form_data['email']) === '') $form_data['email'] = NULL;
        if (trim($form_data['password']) === '') $form_data['password'] = NULL;
        
        if ( ($form_data['email'] !== NULL) && ($form_data['password'] !== NULL) )
        {

            try
            {
                $this->user->login($form_data);
                // Redirect to previous page after login
                $this->render->redirect($return);
            }
            catch (Exception $e)
            {
                switch ($e->getMessage()) {
                    case 'Invalid login':
                    case 'Invalid password':
                        $this->data['error']  = "Sorry, the email and password entered are not valid";
                        $this->render->load('index');
                        break;
                    
                    default:
                        show_error($e->getMessage());
                        break;
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