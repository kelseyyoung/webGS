<?php

  class Students extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('student_model');
      $this->load->model('class_model');
      $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
      $this->output->set_header("Pragma: no-cache");
    }

    public function index() {
      //TODO: only admin should be able to get here
      $data['students'] = $this->student_model->get_students();
      $data['title'] = "Students";
    
      $this->load->view('templates/header', $data);
      $this->load->view('students/index', $data);
      $this->load->view('templates/footer');
    }

    public function view() {
      $user = $this->session->userdata("user_id");
      if ($user && $this->session->userdata("type") == "student") {
        $data['student'] = $this->student_model->get_students($user);
	$data['classes']  = $this->class_model->get_classes_by_student($user);
        $data['title'] = "Students";

        $this->load->view('templates/header', $data);
        $this->load->view('students/view', $data);
        $this->load->view('templates/footer');
      } else {
        redirect(site_url('unauthorized'));
      }
    }

    public function create() {

      $this->load->helper('form');
      $this->load->library('form_validation');

      $data['title'] = 'Create a student';

      $this->form_validation->set_rules('name', 'Name', 'required');
      $this->form_validation->set_rules('username', 'Username', 'required|callback_username_unique');
      $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]|md5');
      $this->form_validation->set_rules('password-confirm', 'Confirm Password', 'required|matches[password]');

      if ($this->form_validation->run() === FALSE) {
        //invalid form or get
        $this->load->view('templates/header', $data);
        $this->load->view('students/create');
        $this->load->view('templates/footer');
      } else {
        //form valid
        $success = $this->student_model->create_student();
        if ($success) {
          $query = $this->student_model->validate_student();
          if (!empty($query)) {
            $this->session->set_userdata(array('user_id' => $query['id'], 'type' => 'student'));
            redirect(site_url('students/view/'.$this->session->userdata('user_id')));
          }
        }
        redirect(site_url(''));
      }    
    }
    
    //Make sure the username is unique
    public function username_unique($username) {
      $squery = $this->db->get_where('wgsDB_student', array('username' => $username))->row_array();
      $iquery = $this->db->get_where('wgsDB_instructor', array('username' => $username))->row_array();
      if (empty($squery) && empty($iquery)) {
        return true;
      } else {
        $this->form_validation->set_message('username_unique', "That username is already taken.");
        return false;
      }
    }


  }
