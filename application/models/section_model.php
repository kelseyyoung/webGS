<?php
  
  class Section_model extends CI_Model {

    public function __construct() {
    }

    public function get_sections_by_class($id) {
      $query = $this->db->get_where("wgsDB_section", array("the_class_id" => $id));
      return $query->result_array();
    }

    public function get_sections_by_class_name($name) {
      $class = $this->db->get_where('wgsDB_class', array('name' => $name))->row_array();
      $query = $this->db->get_where('wgsDB_section', array('the_class_id' => $class['id']));
      return $query->result_array();
    }

    public function get_students_by_class_per_sections($id) {
      $sections = $this->db->get_where("wgsDB_section", array("the_class_id" => $id))->result_array();
      $ret = array();
      foreach($sections as $s) {
	$students = $this->db->get_where('wgsDB_section_students', array('section_id'=> $s['id']))->result_array();	
	$section = array();
	foreach($students as $st) {
	  $student = $this->db->get_where('wgsDB_student', array('id' => $st['student_id']))->row_array();
	  array_push($section, $student);
	}
	$ret[$s['name']] = $section;
      }
      return $ret;
    }

    public function get_section_for_student($sid, $cid) {
      $this->db->select("wgsDB_section.id, wgsDB_section.name, wgsDB_section.the_class_id");
      $this->db->join("wgsDB_section_students", "wgsDB_section.id = wgsDB_section_students.section_id");
      return $this->db->get_where("wgsDB_section", array("wgsDB_section.the_class_id" => $cid, "wgsDB_section_students.student_id" => $sid))->result_array();      
    }

    public function match_section_to_student($sid, $sectionid) {
      $ret = $this->db->get_where("wgsDB_section_students", array("section_id" => $sectionid, "student_id" => $sid));
      return $ret->row_array();
    }

  }
?>
