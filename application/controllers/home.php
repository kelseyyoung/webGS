<?php

  class Home extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('instructor_model');
      $this->load->model('student_model');
    }

    public function index($page = 'index') {
      //Home page view
      if (! file_exists('application/views/home/'.$page.'.php')) {
        show_404();
      }
      $user = $this->session->userdata("user_id");
      if ($user) {
        //Figure out whether they're an instructor or student
        //Go to that page
        if( $this->session->userdata["type"] == "instructor" ) {
          //Redirect to instructor/view
          redirect(site_url('instructors/view/'.$this->session->userdata("user_id")));
        } else {
          //Redirect to student/view
          redirect(site_url('students/view/'.$this->session->userdata("user_id")));
        }
      } else {
        //Not logged in, take them to home page
        $data['title'] = "Home";

        $this->load->helper('form');
        $this->load->library('form_validation');
      
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
          //Invalid or get
          $this->load->view('templates/header', $data);
          $this->load->view('home/'.$page);
          $this->load->view('templates/footer');
        } else {
          //Log user in based on input
          //Redirect to correct page
          $iquery = $this->instructor_model->validate_instructor();
          if (empty($iquery)) {
            //Not an instructor, try student
            $squery = $this->student_model->validate_student();
            if (empty($squery)) {
              //Invalid user, show form errors
              $data["errors"] = "That username or password is invalid";
              $this->load->view('templates/header', $data);
              $this->load->view('home/'.$page);
              $this->load->view('templates/footer');
            } else {
              //Is student, set session and redirect
              $this->session->set_userdata(array('user_id' => $squery["id"], 'type' => "student"));
              redirect(site_url('students/view/'.$this->session->userdata("user_id")));
            }
          } else {
            //Is instructor, set session and redirect
            $this->session->set_userdata(array('user_id' => $iquery["id"], 'type' => 'instructor'));
            redirect(site_url('instructors/view/'.$this->session->userdata("user_id")));
          }
        }
      }
    }

  }

?>
