<?php

  class Classes extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('class_model');
      $this->load->model('student_model');
      $this->load->model('assignment_model');
      $this->load->model('instructor_model');
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
	$data['title'] = "View Class";
	$data['class'] = $this->class_model->get_classes($id);
	$data['assignments'] = $this->assignment_model->get_assignments_by_class($id);
	$this->load->view('templates/header', $data);
	$this->load->view('classes/student_view', $data);
	$this->load->view('templates/footer');
      } else {
	redirect(site_url('unauthorized'));
      }
    }

    public function add_student($id) {
      //TODO: csrf 
      
      $this->load->helper('form');
      $this->load->library('form_validation');
      $this->form_validation->set_rules('student', 'Student', 'required|callback_unique_in_class[' .$id . ']');
      $username = $this->input->post('student');
      if ($this->form_validation->run() === TRUE) {
        $this->class_model->add_student($id);
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
      $this->class_model->remove_instructor($id, $iid);
      echo json_encode("");
    }

    public function create() {

      $user = $this->session->userdata("type");
      if ($user && $user == "instructor") {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Create a class';

        $this->form_validation->set_rules('name', 'Name', 'required|callback_name_unique');
        $this->form_validation->set_rules('num_sections', 'Number of Sections', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
          //invalid form or get
          $this->load->view('templates/header', $data);
          $this->load->view('classes/create');
          $this->load->view('templates/footer');
        } else {
          //form valid
          $this->class_model->create_class();
          redirect(site_url('instructors/view/'.$this->session->userdata('user_id')));
        }  
      } else {
        //Redirect to unauthorized
        redirect(site_url('unauthorized'));
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
