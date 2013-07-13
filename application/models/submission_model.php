<?php

  class Submission_model extends CI_Model {

    public function __construct() {
    }

    public function create_submission($score, $hints, $sid, $aid) {
      $hintsString = "";
      foreach($hints as $h) {
	$hintsString .= $h . '\n';
      }
      $data = array(
        'score' => $score,
        'hints' => $hintsString,
	'student_id' => $sid,
	'assignment_id' => $aid
      );
      $this->db->insert('wgsDB_submission', $data);
    }

    public function get_submissions_by_student_and_assignment($sid, $aid) {
      $query = $this->db->get_where('wgsDB_submission', array('student_id' => $sid, 'assignment_id' => $aid));
      return $query->result_array();
    }

  }

?>
