<?php

  function asset_url() {
    return base_url().'assets/';
  }

  function upload_path() {
    return $_SERVER['DOCUMENT_ROOT'].'/uploads/';
  }

  function view_url() {
    return APPPATH.'views/';
  }

  function base_path($path) {
    return base_url().$path;
  }
?>
