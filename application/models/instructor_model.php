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

      public function get_instructors_by_class($id) {
        $this->db->order_by('wgsDB_instructor.username', 'asc');
        $this->db->select('wgsDB_instructor.id, wgsDB_instructor.username');
        $this->db->join('wgsDB_class_instructors', 'wgsDB_instructor.id = wgsDB_class_instructors.instructor_id');
        return $this->db->get_where('wgsDB_instructor', array("wgsDB_class_instructors.class_id" => $id))->result_array();
      }

      public function get_instructor_by_username($username) {
        return $this->db->get_where('wgsDB_instructor', array('username' => $username))->row_array();
      }
  }
