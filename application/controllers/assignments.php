<?php

  class Assignments extends CI_Controller {

    public function __construct() {
      parent::__construct();
      $this->load->model('assignment_model');
      $this->load->model('class_model');
      $this->load->model('testcase_model');
      $this->load->model('section_model');
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
        $config["allowed_types"] = '*';
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

        if ($this->form_validation->run() === FALSE) {
          //invalid form or get
          $data['classes'] = $this->class_model->get_classes_by_instructor($this->session->userdata("user_id"));
          $this->load->view('templates/header', $data);
          $this->load->view('assignments/create', $data);
          $this->load->view('templates/footer');
        } else {
          //form valid
          //Upload java file
          if (! $this->upload->do_upload('testcase_file')) {
            //Error uploading file
	    //echo upload_path();
            $data['upload_errors'] = $this->upload->display_errors();
            $this->load->view('templates/header', $data);
            $this->load->view('assignments/create', $data);
            $this->load->view('templates/footer');
          } else {
            $this->assignment_model->create_assignment();
            $this->testcase_model->create_testcase();
	    //Create directory for all sections
	    $sections = $this->section_model->get_sections_by_class_name($this->input->post('class'));
	    $classDir = str_replace(" ", "_", $this->input->post('class'));
	    $aDir = str_replace(" ", "_", $this->input->post('name'));
	    foreach($sections as $s) {
	      mkdir(upload_path().'/'.$classDir.'/'.$s['name'].'/'.$aDir);
	      //Make directory for testfile
	      mkdir(upload_path().'/'.$classDir.'/'.$s['name'].'/'.$aDir.'/testcase');
	      //Copy file to testcase directory
	      copy(upload_path().'/'.$this->input->post('testcase_name'), upload_path().'/'.$classDir.'/'.$s['name'].'/'.$aDir.'/testcase/'.$this->input->post('testcase_name'));
	    }
	    //Remove test file from uploads directory
	    unlink(upload_path().'/'.$this->input->post('testcase_name'));
            redirect(site_url('instructors/view/'.$this->session->userdata("user_id")));
	    echo $this->input->post('testcase_file');
          }
        }  
      } else {
        redirect(site_url('unauthorized'));
      }
    
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
        $this->form_validation->set_message("name_unique", "That assignment name is already taken");
        return false;
      }
    }
  }
