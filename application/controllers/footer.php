<?php

  class Footer extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('class_model');
    }

    public function about() {
      $data['title'] = "About";
      $this->load->view('templates/header', $data);
      $this->load->view('static/about');
      $this->load->view('templates/footer');
    }

    public function help() {
      $data['title'] = "Help";
      $this->load->view('templates/header', $data);
      $this->load->view('static/help');
      $this->load->view('templates/footer');
    }

    public function contact() {
      $data['title'] = "Contact"; 
      $this->load->view('templates/header', $data);
      $this->load->view('static/contact');
      $this->load->view('templates/footer');
    }

  }
?>
