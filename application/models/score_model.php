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

    public function get_scores_by_assignment($id) {
      return $this->db->get_where('wgsDB_score', array('assignment_id' => $id))->result_array();
    }

    public function update_score($student_id, $assignment_id, $new_score) { 
      $data = array(
	'score' => $new_score,
	'student_id' => $student_id,
	'assignment_id' => $assignment_id
      );
      $this->db->where('student_id', $student_id);
      $this->db->where('assignment_id', $assignment_id);
      $this->db->update('wgsDB_score', $data);
    }

    public function submit_score($student_id, $assignment_id, $score) {
      $data = array(
	'score' => $score,
	'student_id' => $student_id,
	'assignment_id' => $assignment_id
      );
      return $this->db->insert('wgsDB_score', $data);
    }
  }

?>
