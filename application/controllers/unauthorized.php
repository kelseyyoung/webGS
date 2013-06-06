
<?php
class Unauthorized extends CI_Controller {

  public function index() {
    $data['title'] = "Unauthorized";
    $this->load->view('templates/header', $data);
    $this->load->view('unauthorized/index');
    $this->load->view('templates/footer');
  }
}

