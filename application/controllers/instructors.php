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

    /**
      * url: instructors/view
      * INSTRUCTORS ONLY
      * Main page for instructors
      */
    public function view() {
      $user = $this->session->userdata("type");
      if (!$user || $user != "instructor") {
	redirect(site_url('unauthorized'));
      }
      $data['instructor'] = $this->instructor_model->get_instructors($this->session->userdata('user_id'));
      $data['title'] = "Instructors";
      $data['classes'] = $this->class_model->get_classes_by_instructor($this->session->userdata('user_id'));

      $this->load->view('templates/header', $data);
      $this->load->view('instructors/view', $data);
      $this->load->view('templates/footer');
    }

    /**
      * url: instructors/view_grades/[class id]/[student id]
      * INSTRUCTORS ONLY
      * View grades for a student in a class
      */
    public function view_grades($class_id, $id) {
      //View grades of a student per class
      $user = $this->session->userdata('type');
      if (!$user || $user != "instructor") {
	redirect(site_url('unauthorized'));
      }
      $this->load->helper('form');
      $this->load->library('form_validation');
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

    /**
      * url: instructors/get_file_contents
      * INSTRUCTORS ONLY
      * Returns contents of requested file via ajax
      */
    public function get_file_contents() {
      $type = $this->session->userdata('type');
      if (!$type || $type != "instructor") {
	redirect(site_url('unauthorized'));
      }
      $path = $_GET['path'];
      $file = $_GET['file'];
      $filestr = file_get_contents($path.$file);
      echo $filestr;
    }

  }

?>
