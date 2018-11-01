<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrderAssignment extends MY_Controller {


  function __construct()
  {
      parent::__construct();
      $this->load->model('Orderassignmentmodel');
      ini_set('display_errors', '0');
    

  } 

  public function index()
  {
    $data['content'] = 'index';
    $this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
  }

  public function loadassignmentsummary()
  {
    $data['content'] = 'assignmentsummary';
    $this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
  }

  public function loadorderassign()
  {
    $data['content'] = 'orderassign';
    $this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
  }

  public function loadorderreassign()
  {
    $data['content'] = 'orderreassign';
    $this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
  }
} ?>

