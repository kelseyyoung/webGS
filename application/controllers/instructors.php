<?php

  class Instructors extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('instructor_model');
      $this->load->model('class_model');
      $this->load->model('assignment_model');
      $this->load->model('score_model');
      $this->load->model('student_model');
      $this->load->model('section_model');
      $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
      $this->output->set_header("Pragma: no-cache");
    }

    public function index() {
      //TODO: only admin should be able to get here
      $data['instructors'] = $this->instructor_model->get_instructors();
      $data['title'] = "Instructors";

      $this->load->view('templates/header', $data);
      $this->load->view('instructors/index', $data);
      $this->load->view('templates/footer');
    }

    public function view() {
      $user = $this->session->userdata("user_id");
      if ($user && $this->session->userdata("type") == "instructor") {
        $data['instructor'] = $this->instructor_model->get_instructors($user);
        $data['title'] = "Instructors";
        $data['classes'] = $this->class_model->get_classes_by_instructor($user);

        $this->load->view('templates/header', $data);
        $this->load->view('instructors/view', $data);
        $this->load->view('templates/footer');

      } else {
        //Redirect to unauthorized page
        redirect(site_url('unauthorized'));
      }
    }

    public function create() {

      $this->load->helper('form');
      $this->load->library('form_validation');

      $data['title'] = 'Create an instructor';

      $this->form_validation->set_rules('name', 'Name', 'required');
      $this->form_validation->set_rules('username', 'Username', 'required|callback_username_unique');
      $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]|md5');
      $this->form_validation->set_rules('password-confirm', 'Confirm Password', 'required|matches[password]');

      if ($this->form_validation->run() === FALSE) {
        //invalid form or get
        $this->load->view('templates/header', $data);
        $this->load->view('instructors/create');
        $this->load->view('templates/footer');
      } else {
        //form valid
        $success = $this->instructor_model->create_instructor();
        if ($success) {
          $query = $this->instructor_model->validate_instructor();
          if (!empty($query)) {
            $this->session->set_userdata(array('user_id' => $query['id'], 'type' => 'instructor'));
            redirect(site_url('instructors/view/'.$this->session->userdata('user_id')));
          }
        }
        redirect(site_url(''));
      }  
    
    }

    public function view_grades($class_id, $id) {
      //View grades of a student per class
      $user = $this->session->userdata('type');
      if (!$user || $user != "instructor") {
	redirect(site_url('unauthorized'));
      }
      $class = $this->class_model->get_classes($class_id);
      $assignments = $this->assignment_model->get_assignments_by_class($class_id);
      $scores = $this->score_model->get_scores_by_student($class_id, $id);
      $student = $this->student_model->get_students($id);
      $section = $this->section_model->get_section_for_student($id, $class_id); 
      $submissions = array();
      foreach ($assignments as $a) {
	$submissions[$a['id']] = array();
	$path = upload_path().str_replace(" ", "_", $class['name']).'/'.str_replace(" ", "_", $section['name']).'/'.str_replace(" ", "_", $a['name']).'/'.$student['username'];
	foreach (glob($path.'/current/*') as $file) {
	  if (is_file($file)) {
	    $filename = substr($file, strrpos($file, "/") + 1);
	    array_push($submissions[$a['id']], array("file" => $filename, "path" => $path.'/current/'));
	  }
	}
	foreach (glob($path.'/old/*') as $file) {
	  if (is_file($file)) {
	    $filename = substr($file, strrpos($file, "/") + 1);
	    array_push($submissions[$a['id']], array("file" => $filename, "path" => $path .'/old/'));
	  }
	}
      }
      $data['submissions'] = $submissions;
      $data['title'] = "View Grades";
      $data['class'] = $class;
      $data['assignments'] = $assignments;
      $data['scores'] = $scores;
      $data['student'] = $student;
      $this->load->view('templates/header', $data);
      $this->load->view('instructors/view_grades', $data);
      $this->load->view('templates/footer');
    }

    public function get_file_contents() {
      $path = $_GET['path'];
      $file = $_GET['file'];
      $filestr = file_get_contents($path.$file);
      echo $filestr;
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
