<?php

  function asset_url() {
    return base_url().'assets/';
  }

  function asset_path() {
    return dirname(dirname(dirname(__FILE__))). '/assets/';
  }

  function function_path() {
    return dirname(dirname(dirname(__FILE__))). '/functions/';
  }

  function upload_path() {
    return dirname(dirname(dirname(__FILE__))). '/uploads/';
    //return $_SERVER['DOCUMENT_ROOT'].'/uploads/';
  }

  function view_url() {
    return APPPATH.'views/';
  }

  function base_path($path) {
    return base_url().$path;
  }

  date_default_timezone_set('America/Phoenix');
?>
