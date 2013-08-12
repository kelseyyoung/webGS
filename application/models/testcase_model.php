<?php

  class Testcase_model extends CI_Model {

    public function __construct() {
    }

    public function create_testcase() {
      $aquery = $this->db->get_where('wgsDB_assignment', array('name' => $this->input->post('name')))->row_array();
      $data = array(
        'name' => $this->input->post('main_testcase_name'),
        'assignment_id' => $aquery['id']
      );

      return $this->db->insert('wgsDB_testcase', $data);
    }

    public function get_testcases_by_assignment($id = FALSE) {
      if ($id == FALSE) {
        return null;
      } else {
        return $this->db->get_where('wgsDB_testcase', array("assignment_id" => $id))->row_array();
      }
    }
    
    public function update_testcase($id) {
      $testcase = $this->db->get_where("wgsDB_testcase", array("assignment_id" => $id))->row_array();

      $data = array(
        'name' => $this->input->post('main_testcase_name'),
        'assignment_id' => $id
      );
      $this->db->where('id', $testcase['id']);
      $this->db->update('wgsDB_testcase', $data);
    }


  }

?>
