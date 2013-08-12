<?php

  class Class_model extends CI_Model {

      public function __construct() {
      }

      public function get_classes($id = FALSE) {
        
        if ($id == FALSE) {
          //Did not pass in ID, get all
          $query = $this->db->get('wgsDB_class');
          return $query->result_array();
        }

        $query = $this->db->get_where('wgsDB_class', array('id' => $id));
        return $query->row_array();
      }

      public function get_class_by_name($name) {
        return $this->db->get_where('wgsDB_class', array('name' => $name))->row_array();
      }

      public function get_classes_by_instructor($id) {
        $this->db->order_by('wgsDB_class.name', 'asc');
        $this->db->select('wgsDB_class.id, wgsDB_class.name');
        $this->db->join('wgsDB_class_instructors', 'wgsDB_class.id = wgsDB_class_instructors.class_id');
        return $this->db->get_where('wgsDB_class', array('wgsDB_class_instructors.instructor_id' => $id))->result_array();
      }

      public function get_class_by_instructor($id, $cid) {
	return $this->db->get_where('wgsDB_class_instructors', array('instructor_id' => $id, 'class_id' => $cid))->row_array();
      }

      public function get_classes_by_student($id) {
        $this->db->order_by('wgsDB_class.name', 'asc');
        $this->db->select('wgsDB_class.id, wgsDB_class.name');
        $this->db->join('wgsDB_student_classes', 'wgsDB_class.id = wgsDB_student_classes.class_id');
        return $this->db->get_where('wgsDB_class', array('wgsDB_student_classes.student_id' => $id))->result_array();
      }

      public function create_class() {

        $data = array(
          'name' => $this->input->post('name')
        );

        $this->db->insert('wgsDB_class', $data);
        $id = $this->db->get_where('wgsDB_class', array('name' => $this->input->post('name')))->row_array();
        $this->db->insert('wgsDB_class_instructors', array('class_id' => $id['id'], 'instructor_id' => $this->session->userdata('user_id')));
        //Create sections
        $num_sections = $this->input->post('num_sections');
	$sections = explode(",", $this->input->post('sections'));
        for($i = 0; $i < $num_sections; $i++) {
          $this->db->insert('wgsDB_section', array('name' => trim($sections[$i]), 'the_class_id' => $id['id']));
        }
      }

      public function add_student($id) {
	$section = $this->input->post('student-section');
        $squery = $this->db->get_where('wgsDB_student', array('username' => $this->input->post('student')))->row_array();
	$section_query = $this->db->get_where('wgsDB_section', array('name' => $section, 'the_class_id' => $id))->row_array();
	$this->db->insert('wgsDB_section_students', array('section_id' => $section_query['id'], 'student_id' => $squery['id']));
        return $this->db->insert('wgsDB_student_classes', array('student_id' => $squery['id'], 'class_id' => $id));
      }

      public function add_instructor($id) {
        $iquery = $this->db->get_where('wgsDB_instructor', array('username' => $this->input->post('instructor')))->row_array();
        return $this->db->insert('wgsDB_class_instructors', array('class_id' => $id, 'instructor_id' => $iquery['id']));
      }

      public function remove_student($id, $sid) {
	$this->db->delete('wgsDB_student_classes', array('class_id' => $id, 'student_id' => $sid));
	//Also delete from section
	$sections = $this->db->get_where('wgsDB_section', array('the_class_id' => $id))->result_array();
	foreach($sections as $s) {
	  $query = $this->db->get_where('wgsDB_section_students', array('section_id' => $s['id'], 'student_id' => $sid))->row_array();
	  if (!empty($query)) {
	    $this->db->delete('wgsDB_section_students', array('section_id' => $s['id'], 'student_id' => $sid));
	  }
	}
      }

      public function remove_instructor($id, $iid) {
	$this->db->delete('wgsDB_class_instructors', array('class_id' => $id, 'instructor_id' => $iid));
      }
        
  }
