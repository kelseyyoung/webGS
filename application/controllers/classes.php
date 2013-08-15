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
      $this->load->model('testcase_model');
      $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
      $this->output->set_header("Pragma: no-cache");
    }

    /**
     * url: classes/view/[class id]
     * INSTRUCTORS ONLY
     * Instructor view of classes
     */
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

    /**
     * url: classes/student_view/[class id]
     * STUDENTS & INSTRUCTORS
     * Student view of class
     */
    public function student_view($id) {
      $user = $this->session->userdata('type');
      if (!$user) {
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
        if ($this->session->flashdata('error')) {
          $data['error'] = $this->session->flashdata('error');
        }
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

    /**
     * url: classes/submit_assignment/[student id]
     * STUDENTS & INSTRUCTORS
     * Submits an assignment
     */
    public function submit_assignment($id) {
      $user = $this->session->userdata('user_id');
      if (!$user) {
	redirect(site_url('unauthorized'));
      }
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

      $canPass = true;
      foreach ($_FILES as $key => $value) {
        $fName = $value['name'];
        if (substr($fName, -5) != ".java") {
          $canPass = false;
        }
        $contents = file_get_contents($value['tmp_name']);
        if (  strpos($contents, "Process") ||
              strpos($contents, "Runtime") ||
              strpos($contents, "getRuntime")) {
          $canPass = false;
        }
      }
      if ($canPass) {
        foreach ($_FILES as $key => $value) {
          if (! $this->upload->do_upload($key)) {
            $canPass = false;
          }
        }
        if (!$canPass) {
          $this->session->set_flashdata('error', $this->upload->display_errors());
          $c = $this->class_model->get_class_by_name($this->input->post('class_name'));
          redirect(site_url('classes/student_view/'.$c['id']));
        } else {
          //Valid upload
          $aObj = $this->assignment_model->get_assignment_by_name($this->input->post('assignment_name'));
          $files = array();
          foreach ($_FILES as $key => $value) {
            array_push($files, $value['name']);
          }
          chdir($path . '/new');
          //Copy all files from testcase to here 
          $string = "cp ../../testcase/* . 2>&1";
          shell_exec($string);
          //Compile all java files
          $string = "javac -cp .:" . asset_path() . "java/junit-4.10.jar:" . asset_path() . "java/ant.jar -d . *.java 2>&1";
          $output = shell_exec($string);
          if ($output) {
            //There was a compile error
            $this->session->set_flashdata('files', $files);
            $this->session->set_flashdata('path', $path);
            $this->session->set_flashdata('assignment_id', $aObj['id']);
            $this->session->set_flashdata('errors', $output);
            redirect(site_url('assignments/results/'.$id));
          } else {
            //Run testcase
            $testcase = $this->testcase_model->get_testcases_by_assignment($aObj['id']);
            $i = strpos($testcase['name'], ".java");
            $testcaseName = substr($testcase['name'], 0, $i);
            $string = "java -cp .:" . asset_path() . "java/junit-4.10.jar:" . 
              asset_path() . "java/ant.jar:" . 
              asset_path() . "java/ant-junit.jar:" .
              $path . "/new" .
              " org.apache.tools.ant.taskdefs.optional.junit.JUnitTestRunner " .
              $testcaseName .
              " formatter=org.apache.tools.ant.taskdefs.optional.junit.XMLJUnitResultFormatter," .
              $path . "/new/results.xml 2>&1";
            shell_exec($string);
            //Redirect, set flash data first
            $this->session->set_flashdata('files', $files);
            $this->session->set_flashdata('path', $path);
            $this->session->set_flashdata('assignment_id', $aObj['id']);
            redirect(site_url('assignments/results/' . $id));
          }
        }
      } else {
        $this->session->set_flashdata('error', "The uploaded files contain prohibited code.");
        $c = $this->class_model->get_class_by_name($this->input->post('class_name'));
        redirect(site_url('classes/student_view/'.$c['id']));
      }
    }

    /**
     * url: classes/add_student/[class id]
     * INSTRUCTORS ONLY
     * Allows instructor to add a student to a class
     */
    public function add_student($id) { 
      $type = $this->session->userdata('type');
      if (!$type || $type != "instructor") {
	redirect(site_url('unauthorized'));
      }
      $this->load->helper('form');
      $this->load->library('form_validation');
      $this->form_validation->set_rules('student', 'Student', 'required|callback_unique_in_class[' .$id . ']');
      $this->form_validation->set_rules('student-section', 'Student Section', 'required');
      
      if ($this->form_validation->run() === TRUE) {
        $this->class_model->add_student($id);
	$username = $this->input->post('student');
        echo json_encode($this->student_model->get_student_by_username($username));
      } else {
        echo json_encode(array("error" => form_error('student')));
      }
    }

    /**
     * url: classes/add_instructor/[class id]
     * INSTRUCTORS ONLY
     * Adds instructor to a class
     */
    public function add_instructor($id) {
      $type = $this->session->userdata('type');
      if (!$type || $type != "instructor") {
	redirect(site_url('unauthorized'));
      }
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

    /**
     * url: classes/remove_student/[class id]/[student id]
     * INSTRUCTORS ONLY
     * Removes a student from a class
     */
    public function remove_student($id, $sid) { 
      $type = $this->session->userdata('type');
      if (!$type || $type != "instructor") {
	redirect(site_url('unauthorized'));
      }
      $this->class_model->remove_student($id, $sid);
      echo json_encode("");
    }
    
    /**
     * url: classes/remove_instructor/[class id]/[instructor id]
     * INSTRUCTORS ONLY
     * Removes an instructor from a class as long as they aren't the last one
     */
    public function remove_instructor($id, $iid) {
      $type = $this->session->userdata('type');
      if (!$type || $type != "instructor") {
	redirect(site_url('unauthorized'));
      }
      $instructors = $this->instructor_model->get_instructors_by_class($id);
      if (count($instructors) == 1) {
	echo json_encode(array("error" => "A class must have at least one instructor"));
      } else if ($this->session->userdata("user_id") == $iid) {
        echo json_encode(array("error" => "You cannot remove yourself from this class."));
      } else {
	$this->class_model->remove_instructor($id, $iid);
	echo json_encode("");
      }
    }

    /**
     * url: classes/create
     * INSTRUCTORS ONLY
     * Creates a class
     */
    public function create() {
      $user = $this->session->userdata("type");
      if (!$user || $user != "instructor") {
	redirect(site_url('unauthorized'));
      }
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
	mkdir(upload_path().$classDir);
	$sections = explode(",", $this->input->post('sections'));
	foreach ($sections as $s) {
	  mkdir(upload_path().$classDir.'/'.$s);
	}
	redirect(site_url('instructors/view/'.$this->session->userdata('user_id')));
      } 
    }

    /**
     * Form Callback Function
     * Makes sure all sections submitted are unique
     */
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

    /**
     * Form Callback Function
     * Makes sure number of sections entered matches the actual sections entered
     */
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

    /**
     * Form Callback Function
     * Makes sure student isn't added twice to class
     */
    public function unique_in_class($student, $id) {
      //get id of student
      $student_row = $this->db->get_where('wgsDB_student', array('username' => $student))->row_array();
      if (empty($student_row)) {
        //Check if student exists
        $this->form_validation->set_message("unique_in_class", "That student does not exist");
        return false;
      }
      $query = $this->db->get_where('wgsDB_student_classes', array("student_id" => $student_row['id'], "class_id" => $id))->row_array();
      if (empty($query)) {
        return true;
      } else {
        $this->form_validation->set_message("unique_in_class", "That student already belongs to this class");
        return false;
      }
    }

    /**
     * Form Callback Function
     * Makes sure instructor isn't added twice to class
     */
    public function unique_instructor($instructor, $id) {
      $instructor_row = $this->db->get_where('wgsDB_instructor', array('username' => $instructor))->row_array();
      $query = $this->db->get_where('wgsDB_class_instructors', array('class_id' => $id, 'instructor_id' => $instructor_row['id']))->row_array();
      if (empty($query)) {
        return true;
      } else {
        $this->form_validation->set_message('unique_instructor', "That instructor already belongs to this class");
        return false;
      }
    }

    /**
     * Form Callback Function
     * Make sure class name is unique
     */
    public function name_unique($name) {
      $query = $this->db->get_where("wgsDB_class", array("name" => $name))->row_array();
      if (empty($query)) {
        return true;
      } else {
        $this->form_validation->set_message("name_unique", "That class name is already taken");
        return false;
      }
    }
  } ?>
