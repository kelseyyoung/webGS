<?php

  class Students extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('student_model');
      $this->load->model('class_model');
      $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
      $this->output->set_header("Pragma: no-cache");
    }

    /**
     * url: students/view
     * STUDENTS & INSTRUCTORS
     * Main page for students
     */
    public function view() {
      $user = $this->session->userdata("type");
      if (!$user) {
	redirect(site_url('unauthorized'));
      }
      $data['student'] = $this->student_model->get_students($this->session->userdata('user_id'));
      $data['classes']  = $this->class_model->get_classes_by_student($this->session->userdata('user_id'));
      $data['title'] = "Students";

      $this->load->view('templates/header', $data);
      $this->load->view('students/view', $data);
      $this->load->view('templates/footer');
    }

  }
?>
