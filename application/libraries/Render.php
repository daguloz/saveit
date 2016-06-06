<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Render
{

    // CodeIgniter instance
    private $CI;

    /**
     * Page controller (/links => links)
     */
    public $controller;

    private $data;

    private $date;

    /**
     * @var Current directory
     */
    public $dir;

    private $messages = [];

    private $pagination;

    /**
     * Requested page
     * /links => index
     * /links/edit => edit
     */
    public $page;

    /**
     * Array with css and javascript files to be loaded with the page
     */
    private $resources;


    public function __construct()
    {

        $this->CI =& get_instance();

        // Set locale if needed
        // setlocale(LC_ALL, 'es_ES');

        $this->controller = $this->CI->router->class;
        $this->page = $this->CI->router->method;
        $this->dir = str_replace(['\\', '/'], '', $this->CI->router->directory);

        // Redirect to login if the user is not logged in
        //if (($this->CI->user->is_logged() !== true) && ($this->controller !== 'login'))
            //$this->redirect('login');

        // Param ?page
        $pagination = $this->CI->input->get('page');
        if ($pagination === null)
            $pagination = 1;
        $this->pagination = $pagination;

        // Param ?date
        $date = $this->CI->input->get('date');
        if ($date === null)
            $date = time();
        else
            $date = strtotime($date);
        $this->date = $date;

        // Recover data from previous page
        if ($messages = $this->CI->session->flashdata('messages'))
            $this->messages = $messages;

        if (ENVIRONMENT != 'production')
            $this->add_msg('warning', 'Application in <strong>' . ENVIRONMENT . '</strong> mode');

        // Almacen de recursos
        $this->resources = [
            'css' => [],
            'js' => []
        ];

        // JavaCcript y CSS comunes
        $res = [
            'css' => [
                'bootstrap/css/bootstrap.min'
            ],
            'js' => [
                'jquery/jquery.min',
                'bootstrap/js/bootstrap.min'
            ]
        ];
        $this->add_resources($res);
    }

    /**
     * Add a message to show in the rendered view
     * @param [string] $type Message type. Valid values: 'danger', 'success', 'warning', 'info'
     * @param [string] $msg Message content
     */
    public function add_msg($type, $msg)
    {
        $found = false;
        $newMsg = ['type' => $type, 'content' => $msg];
        foreach ($this->messages as $key => $value) {
            if ($value === $newMsg) {
                $found = true;
                break;
            }
        }
        if (!$found)
            $this->messages[] = $newMsg;
    }

    /**
     * Add client-side resources to load in the rendered view
     * @param $files Array with files to load
     */
    public function add_resources($types)
    {
        foreach ($types as $type => $files)
        {
            foreach ($files as $file => $f)
            {
                if (!isset($this->resources[$type]))
                    $this->resources[$type] = [];
                if (!in_array($f, $this->resources[$type]))
                {
                    $this->resources[$type][] = $f;
                }
            }
        }
    }

    // Render a view as JSON
    public function api()
    {
        // Data for views
        if (isset($this->CI->data))
        {
            $data = $this->CI->data;
        } else
            $data = [];

        if (isset($data['status']))
            $this->CI->output->set_status_header($data['status']);

        //if (ENVIRONMENT === 'development')
            //$output['data'] = $this->_json_readable_encode($data);
        //else
        //print_r($data);
        $dataJson = json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR);

        $output['data'] = $dataJson;
        if ($dataJson === false) {
            $error = json_last_error();
            $dataError = ['status' => 500, 'message' => 'Error converting data to JSON format',
            'response' => ['result' => $error]];
            $output['data'] = json_encode($dataError, JSON_PARTIAL_OUTPUT_ON_ERROR);
        }

        $this->CI->load->view('api', $output);
    }

    // Load a page if the user is logged in, or show login if not
    public function load($page = false)
    {

        // Add default js and css
        $res = [
            'css' => ['app'],
            'js' => ['app']
        ];
        $this->add_resources($res);

        // Data for views
        if (isset($this->CI->data))
        {
            $data = $this->CI->data;
        } else
            $data = [];

        // View to load. Default is 'index'
        if (!$page)
        {
            if (isset($data['redirect']))
                $page = $data['redirect'];
            else
                $page = $this->page;
        }

        $data['messages'] = $this->messages;
        $data['controller'] = $this->controller;
        $data['page'] = $this->page;

        $data['date'] = $this->date;
        $data['pagination'] = $this->pagination;
        $data['resultsPerPage'] = 200;

        if (!isset($data['title']))
            $data['title'] = ucfirst($this->controller);

        $data['name'] = $this->CI->session->name;
        $data['email'] = $this->CI->session->email;
        $data['logged_in'] = $this->CI->user->is_logged();

        $this->data = $data;

        $this->CI->load->view('layout/header', $data);
        $this->CI->load->view($this->dir . '/' . $this->controller . '/' . $page, $data);
        $this->CI->load->view('layout/footer', $data);

    }

    public function error($template = false, $heading = false, $message = false, $status_code = false, $return = false)
    {
        $title = 'Error';
        
        if (!$template)
        {
            $template = 'html/error_general';
        }
        else if ($template === 'error_404')
        {
            $template = 'html/' . $template;
            $status_code = 404;
            $heading = "Error 404: Página no encontrada";
            $message = "No se ha encontrado la página solicitada.";
            $title = 'Error 404';
        }

        $data['heading'] = $heading;
        $data['message'] = $message;
        $data['title'] = $title;

        if ($status_code)
            $this->CI->output->set_status_header($status_code);

        $data['messages'] = $this->messages;
        $data['name'] = $this->CI->session->name;
        $data['email'] = $this->CI->session->email;
        if (isset($this->CI->user))
            $data['logged_in'] = $this->CI->user->is_logged();
        else
            $data['logged_in'] = false;

        $this->data = $data;

        if ($return)
        {
            $ret = $this->CI->load->view('/layout/header', $data, true);
            $ret .= $this->CI->load->view('/errors/' . $template, $data, true);
            $ret .= $this->CI->load->view('layout/footer', $data, true);
            return $ret;
        }
        else
        {
            $this->CI->load->view('/layout/header', $data);
            $this->CI->load->view('/errors/' . $template, $data);
            $this->CI->load->view('layout/footer', $data);
        }

    }

    public function get_resources($type)
    {

        if ($type === 'css')
        {
            if (isset($this->resources[$type]))
            {
                foreach ($this->resources[$type] as $file)
                    echo '<link rel="stylesheet" href="/res/' . $file . '.css">' . "\n";
            }
        }
        else if ($type === 'js')
        {
            if (isset($this->resources[$type]))
            {
                foreach ($this->resources[$type] as $file)
                    echo '<script src="/res/' . $file . '.js"></script>' . "\n";
            }
        }
    }

    // Redirects the page to the specified route
    public function redirect($controller = false, $page = false)
    {
        $this->CI->session->set_flashdata('messages', $this->messages);
        $this->CI->session->set_flashdata('return', $this->CI->uri->uri_string);

        if (!$controller)
            $controller = $this->controller;

        if ($page)
            $redir = $controller . '/' . $page;
        else
            $redir = $controller;

        redirect($redir);
    }


}