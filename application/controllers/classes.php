<?php

  class Classes extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('class_model');
      $this->load->model('section_model');
      $this->load->model('student_model');
      $this->load->model('assignment_model');
      $this->load->model('instructor_model');
      $this->load->model('score_model');
      $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
      $this->output->set_header("Pragma: no-cache");
    }

    public function index() {
      $data['classes'] = $this->class_model->get_classes();
      $data['title'] = "Classes";
    
      $this->load->view('templates/header', $data);
      $this->load->view('classes/index', $data);
      $this->load->view('templates/footer');

    }

    public function view($id) {
      $user = $this->session->userdata('type');
      if (!$user || $user != "instructor") {
        redirect(site_url('unauthorized'));
      }
      $classes = $this->class_model->get_classes_by_instructor($this->session->userdata('user_id'));
      $auth = false;
      foreach($classes as $class) {
        if ($class['id'] == $id) {
          $auth = true;
        }
      }
      if ($auth) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('student', 'Student', 'required|callback_unique_in_class[' .$id . ']');
        $this->form_validation->set_rules('instructor', 'Instructor', 'required|callback_unique_instructor[' .$id. ']');
        $data['class'] = $this->class_model->get_classes($id);
        $data['assignments'] = $this->assignment_model->get_assignments_by_class($id);
        $data['students'] = $this->student_model->get_students_by_class($id);
        $data['all_students'] = $this->student_model->get_students();
        $data['title'] = "Classes";
        $data["instructors"] = $this->instructor_model->get_instructors_by_class($id);
	$data["all_sections"] = $this->section_model->get_sections_by_class($id);
	$data["student_sections"] = $this->section_model->get_students_by_class_per_sections($id);
        $data["all_instructors"] = $this->instructor_model->get_instructors();

        $this->load->view('templates/header', $data);
        $this->load->view('classes/view', $data);
        $this->load->view('templates/footer');
      } else {
        redirect(site_url('unauthorized'));
      }
    }

    public function student_view($id) {
      $user = $this->session->userdata('type');
      if (!$user || $user !="student") {
	redirect(site_url('unauthorized'));
      }
      $classes = $this->class_model->get_classes_by_student($this->session->userdata('user_id'));
      $auth = false;
      foreach($classes as $class) {
	if ($class['id'] == $id) {
	  $auth = true;
	}
      }
      if ($auth) {
	$this->load->helper('form');
	$this->load->library('form_validation');
	$data['title'] = "View Class";
	$data['class'] = $this->class_model->get_classes($id);
	$data['assignments'] = $this->assignment_model->get_assignments_by_class($id);
	$data['scores'] = $this->score_model->get_scores_by_student($id, $this->session->userdata('user_id'));
	$this->load->view('templates/header', $data);
	$this->load->view('classes/student_view', $data);
	$this->load->view('templates/footer');
      } else {
	redirect(site_url('unauthorized'));
      }
    }

    public function submit_assignment($id) {
      //Figure out upload path
      $class = str_replace(" ", "_", $this->input->post('class_name'));
      $assignment = str_replace(" ", "_", $this->input->post('assignment_name'));
      $sections = $this->section_model->get_sections_by_class_name($this->input->post('class_name'));
      $student = $this->student_model->get_students($id);
      foreach($sections as $s) {
	$ret = $this->section_model->match_section_to_student($this->session->userdata('user_id'), $s['id']);
	if (!empty($ret)) {
	  $path = upload_path().$class.'/'.str_replace(" ", "_", $s['name']).'/'.$assignment.'/'.$student["username"];
	  if (!file_exists($path)) {
	    //Create if it doesn't already exist
	    mkdir($path);
	    mkdir($path . '/new');
	    mkdir($path . '/current');
	    mkdir($path . '/old');
	  }
	  $config["upload_path"] = $path . '/new'; 
	}
      }
      $config["allowed_types"] = "*";
      $this->load->library('upload', $config);

      $this->load->helper('form');

      if (! $this->upload->do_upload('assignment_submission')) {
	//Error uploading file
      } else {
	//Valid upload, redirect to running test page
	$this->session->set_flashdata('path', $path);
	$this->session->set_flashdata('filename', $this->input->post('submission_name'));
	//Redirect to submit page
	redirect(site_url('assignments/submit'));
      }
    }

    public function add_student($id) {
      //TODO: csrf 
      
      $this->load->helper('form');
      $this->load->library('form_validation');
      $this->form_validation->set_rules('student', 'Student', 'required|callback_unique_in_class[' .$id . ']');
      $this->form_validation->set_rules('student-section', 'Student Section', 'required');
      if ($this->form_validation->run() === TRUE) {
        $this->class_model->add_student($id);
	$username = $this->input->post('student');
        echo json_encode($this->student_model->get_student_by_username($username));
      } else {
        echo json_encode("");
      }
    }

    public function add_instructor($id) {
      //TODO: csrf
      $this->load->helper('form');
      $this->load->library('form_validation');
      $this->form_validation->set_rules('instructor', 'Instructor', 'required|callback_unique_instructor[' .$id. ']');
      $username = $this->input->post('instructor');
      if ($this->form_validation->run() === TRUE) {
        $this->class_model->add_instructor($id);
        echo json_encode($this->instructor_model->get_instructor_by_username($username));
      } else {
        echo json_encode("");
      }
    }

    public function remove_student($id, $sid) { 
      $this->class_model->remove_student($id, $sid);
      echo json_encode("");
    }

    public function remove_instructor($id, $iid) {
      $instructors = $this->instructor_model->get_instructors_by_class($id);
      if (count($instructors) == 1) {
	echo json_encode(array("error" => "A class must have at least one instructor"));
      } else {
	$this->class_model->remove_instructor($id, $iid);
	echo json_encode("");
      }
    }

    public function create() {

      $user = $this->session->userdata("type");
      if ($user && $user == "instructor") {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Create a class';

        $this->form_validation->set_rules('name', 'Name', 'required|callback_name_unique');
        $this->form_validation->set_rules('num_sections', 'Number of Sections', 'required|numeric');
	$this->form_validation->set_rules('sections', 'Sections', 'required|callback_matches_num|callback_sections_unique');

        if ($this->form_validation->run() === FALSE) {
          //invalid form or get
          $this->load->view('templates/header', $data);
          $this->load->view('classes/create');
          $this->load->view('templates/footer');
        } else {
          //form valid
          $this->class_model->create_class();
	  //Create directory for class
	  $classDir = str_replace(" ", "_", $this->input->post('name'));
	  mkdir(upload_path().'/'.$classDir);
	  $sections = explode(",", $this->input->post('sections'));
	  foreach ($sections as $s) {
	    mkdir(upload_path().'/'.$classDir.'/'.$s);
	  }
          redirect(site_url('instructors/view/'.$this->session->userdata('user_id')));
        }  
      } else {
        //Redirect to unauthorized
        redirect(site_url('unauthorized'));
      }
    
    }

    public function sections_unique($list) {
      $sections = explode(",", $list);
      $is_unique = count($sections) == count(array_unique($sections));
      if ($is_unique) {
	return true;
      } else {
	$this->form_validation->set_message('sections_unique', "The section names are not unique");
	return false;
      }
    }

    public function matches_num($list) {
      $num_sections = $this->input->post('num_sections');
      $sections = explode(",", $list);
      if (count($sections) != $num_sections) {
	$this->form_validation->set_message('matches_num', "- The number of sections and the listed sections do not match");
	return false;
      } else {
	return true;
      }
    }

    public function unique_in_class($student, $id) {
      //get id of student
      $student_row = $this->db->get_where('wgsDB_student', array('username' => $student))->row_array();
      $query = $this->db->get_where('wgsDB_student_classes', array("student_id" => $student_row['id'], "class_id" => $id))->row_array();
      if (empty($query)) {
        return true;
      } else {
        $this->form_validation->set_message("unique_in_class", "That student already belongs to this class");
        return false;
      }
    }

    public function unique_instructor($instructor, $id) {
      //Make sure instructor isn't already apart of class
      $instructor_row = $this->db->get_where('wgsDB_instructor', array('username' => $instructor))->row_array();
      $query = $this->db->get_where('wgsDB_class_instructors', array('class_id' => $id, 'instructor_id' => $instructor_row['id']))->row_array();
      if (empty($query)) {
        return true;
      } else {
        $this->form_validation->set_message('unique_instructor', "That instructor already belongs to this class");
        return false;
      }
    }


    //Make sure class name is unique
    public function name_unique($name) {
      $query = $this->db->get_where("wgsDB_class", array("name" => $name))->row_array();
      if (empty($query)) {
        return true;
      } else {
        $this->form_validation->set_message("name_unique", "That class name is already taken");
        return false;
      }
    }
  }
