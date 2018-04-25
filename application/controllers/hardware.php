<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hardware extends CI_Controller {

  function _construct()
  {
    parent::_construct();
    $this->load->helper(array('url','html','form'));
    //$this->lord->model('Hardware_model','Hardware');
  }

  function index()
  {
    $this->load->view('hardware/allhardware');
  }
}
