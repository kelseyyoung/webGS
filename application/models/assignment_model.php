<?php

  class Assignment_model extends CI_Model {

      public function __construct() {
      }

      public function get_assignments($id = FALSE) {
        
        if ($id == FALSE) {
          //Did not pass in ID, get all
          $query = $this->db->get('wgsDB_assignment');
          return $query->result_array();
        }

        $query = $this->db->get_where('wgsDB_assignment', array('id' => $id));
        return $query->row_array();
      }

      public function create_assignment() {

        //Get class id
        $query = $this->db->get_where('wgsDB_class', array('name' => $this->input->post('class')))->row_array();

        $data = array(
          'name' => $this->input->post('name'),
          'the_class_id' => $query['id'],
          'startDateTime' => $this->input->post('due_date_start'),
          'endDateTime' => $this->input->post('due_date_end'),
          'num_testcases' => $this->input->post('num_testcases'),
          'points_per_testcase' => $this->input->post('points_per_testcase'),
          'total_points' => $this->input->post('total_points')
        );

        return $this->db->insert('wgsDB_assignment', $data);
      }

      public function update_assignment($id) {
        //Get class id
        $query = $this->db->get_where('wgsDB_class', array('name' => $this->input->post('class')))->row_array();

        $data = array(
          'name' => $this->input->post('name'),
          'the_class_id' => $query['id'],
          'startDateTime' => $this->input->post('due_date_start'),
          'endDateTime' => $this->input->post('due_date_end'),
          'num_testcases' => $this->input->post('num_testcases'),
          'points_per_testcase' => $this->input->post('points_per_testcase'),
          'total_points' => $this->input->post('total_points')
        );
        $this->db->where('id', $id);
        $this->db->update('wgsDB_assignment', $data);
      }

      public function get_assignments_by_class($id) {
        $this->db->order_by("startDateTime", "asc");
        $query = $this->db->get_where('wgsDB_assignment', array('the_class_id' => $id));
        return $query->result();
      }

      public function get_assignment_by_name($name) {
	return $this->db->get_where('wgsDB_assignment', array('name' => $name))->row_array();
      }
  }

  ?>
