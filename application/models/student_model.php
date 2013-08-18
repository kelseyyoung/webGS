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
        $data = array(
          'username' => $username
        );

        return $this->db->insert('wgsDB_student', $data);
      }

      public function get_students_by_class($id) {
        $this->db->order_by("wgsDB_student.username", "asc");
        $this->db->select("wgsDB_student.id, wgsDB_student.username, wgsDB_section.name");
        $this->db->join("wgsDB_student_classes", "wgsDB_student.id = wgsDB_student_classes.student_id");
        $this->db->join("wgsDB_section_students", "wgsDB_section_students.student_id = wgsDB_student_classes.student_id");
        $this->db->join("wgsDB_section", "wgsDB_section_students.section_id = wgsDB_section.id");
        return $this->db->get_where("wgsDB_student", array("wgsDB_student_classes.class_id" => $id))->result_array();
      }

      public function get_student_by_username($username) {
        return $this->db->get_where('wgsDB_student', array("username" => $username))->row_array();
      }

      public function student_exists_in_class($student, $cid) {
        $s = $this->db->get_where("wgsDB_student", array("username" => $student))->row_array();
        return $this->db->get_where("wgsDB_student_classes", array("student_id" => $s['id'], "class_id" => $cid))->row_array();
      }
  }
