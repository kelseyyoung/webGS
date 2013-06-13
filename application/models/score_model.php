<?php

  class Score_model extends CI_Model {

    public function __construct() {
    }

    public function get_score($student_id, $assignment_id) {
      return $this->db->get_where('wgsDB_score', array('student_id' => $student_id, 'assignment_id' => $assignment_id))->row_array();

    }

    public function get_scores_by_student($class_id, $student_id) {
      //Gets in order of assignment start date
      $this->db->order_by('startDateTime', 'asc');
      $assignments = $this->db->get_where('wgsDB_assignment', array('the_class_id' => $class_id))->result();
      $ret = array();
      foreach($assignments as $a) {
	$score = $this->db->get_where('wgsDB_score', array('assignment_id' => $a->id, 'student_id' => $student_id))->row_array();
	array_push($ret, $score);
      }
      return $ret;

    }
  }

?>
