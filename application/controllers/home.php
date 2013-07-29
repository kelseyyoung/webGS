<?php

  class Home extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('instructor_model');
      $this->load->model('student_model');
    }

    public function index() {
      $user = $this->session->userdata('type');
      if (!$type) {
	redirect(site_url('unauthorized'));
      }
      if ($type == "student") {
	//Student
	$data['student'] = $this->student_model->get_students($this->session->userdata('user_id'));
	$data['title'] = "Students";
	$this->load->view('templates/header', $data);
	$this->load->view('students/view', $data);
	$this->load->view('templates/footer');
      } else {
	//Instructor
	$data['instructor'] = $this->instructor_model->get_instructors($this->session->userdata('user_id'));
	$data['title'] = "Instructors";
	$data['classes'] = $this->class_model->get_classes_by_instructor($this->session->userdata('user_id'));

	$this->load->view('templates/header', $data);
	$this->load->view('instructors/view', $data);
	$this->load->view('templates/footer');
      }
    }

  }

?>
