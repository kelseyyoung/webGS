<?php

  class Logout extends CI_Controller {

    public function index() {
      $this->session->unset_userdata("user_id");
      $this->session->unset_userdata("type");
      $this->session->sess_destroy();
      redirect(site_url(''), 'refresh');
    }
  }

?>
