<?php

  class Assignments extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('assignment_model');
      $this->load->model('class_model');
      $this->load->model('testcase_model');
      $this->load->model('section_model');
      $this->load->model('score_model');
      $this->load->model('student_model');
      $this->load->model('submission_model');
      $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
      $this->output->set_header("Pragma: no-cache");
    }

    public function index() {
      $data['assignments'] = $this->assignment_model->get_assignments();
      $data['title'] = "Assignments";
    
      $this->load->view('templates/header', $data);
      $this->load->view('assignments/index', $data);
      $this->load->view('templates/footer');

    }

    public function view($id) {
       $data['assignment'] = $this->assignment_model->get_assignments($id);
       $data['title'] = "Assignments";

       $this->load->view('templates/header', $data);
       $this->load->view('assignments/view', $data);
       $this->load->view('templates/footer');
    }

    public function create() {

      $user = $this->session->userdata('type');
      if ($user && $user == "instructor") {

        $config["upload_path"] = upload_path();
        $config["allowed_types"] = 'java';
        $this->load->library('upload', $config);
        
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Create an assignment';

        $this->form_validation->set_rules('name', 'Name', 'required|callback_name_unique');
        $this->form_validation->set_rules('class', 'Class', 'required');
        $this->form_validation->set_rules('due_date_start', 'Start Date', 'required|callback_compare_date');
        $this->form_validation->set_rules('due_date_end', 'End Date', 'required|callback_compare_date');
        $this->form_validation->set_rules('num_testcases', 'Number of Testcases', 'required|numeric');
        $this->form_validation->set_rules('points_per_testcase', 'Points per Testcase', 'required|numeric');
        $this->form_validation->set_rules('total_points', 'Total Points', 'required|numeric');
	$this->form_validation->set_rules('main_testcase_name', 'Main Testcase', 'required|callback_testcase_matches');

        if ($this->form_validation->run() === FALSE) {
          //invalid form or get
          $data['classes'] = $this->class_model->get_classes_by_instructor($this->session->userdata("user_id"));
          $this->load->view('templates/header', $data);
          $this->load->view('assignments/create', $data);
          $this->load->view('templates/footer');
        } else {
          //form valid
          //Upload java files
	  $canPass = true;
	  foreach ($_FILES as $key => $value) {
	    if (! $this->upload->do_upload($key)) {
	      $canPass = false;
	    }
	  }
	  if (!$canPass) {
	    $data['upload_errors'] = $this->upload->display_errors();
	    $this->load->view('templates/header', $data);
	    $this->load->view('assignments/create', $data);
	    $this->load->view('templates/footer');
	  } else {
	    //No errors happened uploading
	    $this->assignment_model->create_assignment();
	    $this->testcase_model->create_testcase();
	    //Create directory for all sections
	    $sections = $this->section_model->get_sections_by_class_name($this->input->post('class'));
	    $classDir = str_replace(" ", "_", $this->input->post('class'));
	    $aDir = str_replace(" ", "_", $this->input->post('name'));
	    foreach($sections as $s) {
	      mkdir(upload_path().'/'.$classDir.'/'.str_replace(" ", "_", $s['name']).'/'.$aDir);
	      //Make directory for testfile
	      mkdir(upload_path().'/'.$classDir.'/'.str_replace(" ", "_", $s['name']).'/'.$aDir.'/testcase');
	      foreach($_FILES as $key => $value) {
		//Copy file to testcase directory
		copy(upload_path().'/'.$value['name'], upload_path().'/'.$classDir.'/'.$s['name'].'/'.$aDir.'/testcase/'.$value['name']);
	      }
	    }
	    foreach($_FILES as $key => $value) {
	      //Remove test file from uploads directory
	      unlink(upload_path().'/'.$value['name']);
	    }
	    redirect(site_url('instructors/view/'.$this->session->userdata("user_id")));
	  }
        }  
      } else {
        redirect(site_url('unauthorized'));
      }
    
    }

    public function results($sid) {
      //Show results from running testcases
      $path = $this->session->flashdata('path');
      $file = $this->session->flashdata('filename');
      $id = $this->session->flashdata('assignment_id');
      //Get results from xml file
      $xml = new DOMDocument();
      $xml->load($path."/new/results.xml");
      $header = $xml->getElementsByTagName('testsuite');
      $h = $header->item(0);
      $errors = $h->getAttribute('errors');
      $num_failures = $h->getAttribute('failures');
      $tests = $h->getAttribute('tests');
      $data['errors'] = $errors;
      $data['failures'] = $num_failures;
      $data['tests'] = $tests;
      //Get potential failures
      $failureArray = array();
      $failures = $xml->getElementsByTagName('failure');
      foreach($failures as $f) {
	$message = $f->getAttribute('message');
	$i = strpos($message, "expected");
	if ($i !== false) {
	  $message = substr($message, 0, $i - 1); 
	}
	array_push($failureArray, $message);
      }

      //Scoring
      $assignment = $this->assignment_model->get_assignments($id);
      $data['total_points'] = $assignment['total_points'];
      $newScore = $assignment["points_per_testcase"] * ($tests - $num_failures);
      $data['score'] = $newScore;
      $score = $this->score_model->get_score($sid, $id);
      if (!empty($score)) {
	//Score exists, see if we got a higher one
	if ($score['score'] < $newScore) {
	  //Update
	  $this->score_model->update_score($sid, $id, $newScore);
	  //Move current file to old
	  rename($path.'/current/'.$file, $path.'/old/'.$file.'.'.date("Y-m-d-H:i:s"));
	  //Move new file to current
	  rename($path .'/new/'.$file, $path.'/current/'.$file);
	} else {
	  //Move file to old
	  rename($path.'/new/'.$file, $path.'/old/'.$file.'.'.date("Y-m-d-H:i:s"));
	}
      } else {
	//Score doesn't exist, create new one
	$this->score_model->submit_score($sid, $id, $newScore);
	//Move file to 'current'
	rename($path .'/new/'.$file, $path.'/current/'.$file);
      }
      //Delete all other files in new directory
      foreach(glob($path.'/new/*') as $fname) {
	if (is_file($fname)) {
	  unlink($fname);
	}
      }
      //Create submission record
      $this->submission_model->create_submission($newScore, $failureArray, $sid, $assignment['id']);

      $data['title'] = "Submission Results";
      $data['messages'] = $failureArray;

      $this->load->view('templates/header', $data);
      $this->load->view('assignments/results', $data);
      $this->load->view('templates/footer');
    }

    public function view_submissions($aid) {
      $user = $this->session->userdata('user_id');
      if (!$user) {
	redirect(site_url('unauthorized'));
      }
      $assignment = $this->assignment_model->get_assignments($aid);
      $submissions = $this->submission_model->get_submissions_by_student_and_assignment($user, $aid);

      $data['title'] = "View Submissions";
      $data['assignment'] = $assignment;
      $data['submissions'] = $submissions;
      $this->load->view('templates/header', $data);
      $this->load->view('assignments/view_submissions', $data);
      $this->load->view('templates/footer');
    }

    public function edit($assignment = FALSE, $class = FALSE) {
      $user = $this->session->userdata('type');
      if (!$user || $user != "instructor" || !$assignment || !$class) {
        redirect(site_url('unauthorized'));
      }
      $assignment = $this->assignment_model->get_assignments($assignment);
      $class = $this->class_model->get_classes($class);
      $query = $this->class_model->get_class_by_instructor($class['id'], $this->session->userdata('user_id'));
      if ($assignment && $class && $assignment["the_class_id"] == $class["id"] && !empty($query)) {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('class', 'Class', 'required');
        $this->form_validation->set_rules('due_date_start', 'Start Date', 'required|callback_compare_date');
        $this->form_validation->set_rules('due_date_end', 'End Date', 'required|callback_compare_date');
        $this->form_validation->set_rules('num_testcases', 'Number of Testcases', 'required|numeric');
        $this->form_validation->set_rules('points_per_testcase', 'Points per Testcase', 'required|numeric');
        $this->form_validation->set_rules('total_points', 'Total Points', 'required|numeric');
	$this->form_validation->set_rules('main_testcase_name', 'Main Testcase', 'required|callback_testcase_matches');

        $data['title'] = "Edit Assignment";
	$data['assignment'] = $assignment;
	$data['class'] = $class;
        $data['testcase'] = $this->testcase_model->get_testcases_by_assignment($data["assignment"]["id"]);

        if ($this->form_validation->run() === FALSE) {
          $this->load->view('templates/header', $data);
          $this->load->view('assignments/edit', $data);
          $this->load->view('templates/footer');
        } else {
          //form valid
          //Check if new file
          if (empty($_FILES['testcase_file']['name'])) {
            $this->assignment_model->update_assignment($data["assignment"]["id"]);
            redirect(site_url('instructors/view/'.$this->session->userdata("user_id")));
          } else {
            //Upload java file
            if (! $this->upload->do_upload('testcase_file')) {
              //Error uploading file
              $data['upload_errors'] = $this->upload->display_errors();
              $this->load->view('templates/header', $data);
              $this->load->view('assignments/edit', $data);
              $this->load->view('templates/footer');
            } else {
              $this->assignment_model->update_assignment($data["assignment"]["id"]);
              $this->testcase_model->update_testcase($data["assignment"]["id"]);
              redirect(site_url('instructors/view/'.$this->session->userdata("user_id")));
            }
          } 
        }
      } else {
        redirect(site_url('unauthorized'));
      }
    }

    public function view_grades($id, $class_id) {
      //View all grades per assignment
      $type = $this->session->userdata('type');
      if (!$type || $type != "instructor") {
	redirect(site_url('unauthorized'));
      }
      $scores = $this->score_model->get_scores_by_assignment($id);
      $assignment = $this->assignment_model->get_assignments($id);
      $students = $this->student_model->get_students_by_class($class_id);
      $data['assignment'] = $assignment;
      $data['scores'] = $scores;
      $data['students'] = $students;
      $data['all_sections'] = $this->section_model->get_sections_by_class($id);
      $data['student_sections'] = $this->section_model->get_students_by_class_per_sections($id);
      $data['title'] = "View Grades";
      $this->load->view('templates/header', $data);
      $this->load->view('assignments/view_grades', $data);
      $this->load->view('templates/footer');
    }

    //Make sure start date is less than end date
    public function compare_date() {
      $start = new DateTime($this->input->post("due_date_start"));
      $end = new DateTime($this->input->post("due_date_end"));
      if ($end >= $start) {
        return true;
      } else {
        $this->form_validation->set_message('compare_date', "The start date must be earlier then the end date.");
        return false;
      }
    }

    //Make sure assignment name is unique
    public function name_unique($name) {
      $query = $this->db->get_where("wgsDB_assignment", array("name" => $name))->row_array();
      if (empty($query)) {
        return true;
      } else {
        $this->form_validation->set_message("name_unique", "That assignment name is already taken.");
        return false;
      }
    }

    //Make sure testcase name is one of the files uploaded
    public function testcase_matches($name) {
      foreach ($_FILES as $key => $value) {
	$fName = $value['name'];
	if ($name == $fName) {
	  return true;
	}
      }
      $this->form_validation->set_message('testcase_matches', "No file was uploaded with that name.");
      return false;
    }
  }
