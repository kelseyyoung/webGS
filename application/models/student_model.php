<?php

  class Student_model extends CI_Model {

      public function __construct() {
      }

      public function get_students($id = FALSE) {
        
        if ($id == FALSE) {
          //Did not pass in ID, get all
          $query = $this->db->get('wgsDB_student');
          return $query->result_array();
        }

        $query = $this->db->get_where('wgsDB_student', array('id' => $id));
        return $query->row_array();
      }
      public function create_student($username) {

        //$this->load->helper('url'); 
        $data = array(
          'username' => $username
        );

        return $this->db->insert('wgsDB_student', $data);
      }
/*
      public function validate_student() {

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        if ($username != "" and $password != "") {
          $query = $this->db->get_where('wgsDB_student', array('username' => $username, 'password' => md5($password)));
          return $query->row_array();
        } else {
          return null;
        }
      }
*/
      public function get_students_by_class($id) {
        //$this->db->order_by("name", "asc");
        $query = $this->db->get_where('wgsDB_student_classes', array('class_id' => $id))->result();
        $ret = array();
        foreach ($query as $key => $value) {
          $student = $this->db->get_where('wgsDB_student', array('id' => $value->student_id))->row_array();
          array_push($ret, $student);
        }
        return $ret;
      }

      public function get_student_by_username($username) {
        return $this->db->get_where('wgsDB_student', array("username" => $username))->row_array();
      }
  }
