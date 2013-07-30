<?php

  class Logout extends CI_Controller {

    public function index() {
      $this->session->unset_userdata("user_id");
      $this->session->unset_userdata("type");
      $this->session->unset_userdata("netid");
      $this->session->unset_userdata("valid");
      $this->session->unset_userdata("dbkey");
      $this->session->unset_userdata("emplId");
      $this->session->sess_destroy();
      //redirect(site_url(''), 'refresh');
      $redirect = "Location: https://webauth.arizona.edu:8443/webauth/logout?logout_href=http%3A%2F%2Ffaq.cs.arizona.edu%2Findex.php&banner=Department%20of%20Computer%20Science%20-%20WebAuth%20Interface.";
      header($redirect);
    }
  }

?>
