<?php

  class Instructor_model extends CI_Model {

      public function __construct() {
      }

      public function get_instructors($id = FALSE) {
        
        if ($id == FALSE) {
          //Did not pass in ID, get all
          $this->db->order_by('username', 'asc');
          $query = $this->db->get('wgsDB_instructor');
          return $query->result_array();
        }

        $query = $this->db->get_where('wgsDB_instructor', array('id' => $id));
        return $query->row_array();
      }

      public function validate_instructor() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        if ($username != "" && $password != "") {
          $query = $this->db->get_where('wgsDB_instructor', array('username' => $username, 'password' => md5($password)));
          return $query->row_array();
        } else {
          return null;
        }
      }

      public function create_instructor() {
 
        //TODO: change is_admin to choice
        $data = array(
          'name' => $this->input->post('name'),
          'username' => $this->input->post('username'),
          'password' => $this->input->post('password'),
          'is_admin' => true
        );

        return $this->db->insert('wgsDB_instructor', $data);
      }

      public function get_instructors_by_class($id) {
        $query = $this->db->get_where('wgsDB_class_instructors', array('class_id' => $id))->result();
        $ret = array();
        foreach($query as $key => $value) {
          $instructor = $this->db->get_where('wgsDB_instructor', array('id' => $value->instructor_id))->row_array();
          array_push($ret, $instructor);
        }
        return $ret;
      }

      public function get_instructor_by_id($username) {
        return $this->db->get_where('wgsDB_instructor', array('username' => $username))->row_array();
      }
  }
