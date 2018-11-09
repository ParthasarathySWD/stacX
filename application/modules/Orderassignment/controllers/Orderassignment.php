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
    $data['Customers'] = $this->Common_Model->get('mCustomer', [], ['CustomerUID'=>'ASC'], []);
    $data['Projects'] = $this->Common_Model->get('mProjectCustomer', [], ['ProjectUID'=>'ASC'], ['CustomerUID']);

    $this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
  }

  public function loadorderreassign()
  {
    $data['content'] = 'orderreassign';
    $data['Customers'] = $this->Common_Model->get('mCustomer', [], ['CustomerUID'=>'ASC'], []);

    $this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
  }


  function assignment_ajax_list()
  {

    //get_post_input_data
      $post['length'] = $this->input->post('length');
        $post['start'] = $this->input->post('start');
        $search = $this->input->post('search');
        $post['search_value'] = $search['value'];
        $post['order'] = $this->input->post('order');
        $post['draw'] = $this->input->post('draw');
        $post['ProjectUID'] = $this->input->post('ProjectUID');
        $post['OrderUID'] = $this->input->post('OrderUID');
        if ($post['ProjectUID'] == 'all' && $post['OrderUID'] != '') {
          $tOrders = $this->Common_Model->get_row('tOrders', 'OrderUID', $OrderUID);
          $post['ProjectUID'] = $tOrders->ProjectUID;
        }
      //get_post_input_data
      //column order
        $post['column_order'] = array('torders.OrderNumber','');
        $post['column_search'] = array('torders.OrderNumber');
        //column order
        $list = $this->Orderassignmentmodel->AssignmentOrders($post);

        $no = $post['start'];
    $myorderslist = [];
    foreach ($list as $key=>$order)
        {
            $checked = '';
            $row = array();
            $row[] = '<a href="'.base_url('Ordersummary/index/'.$order->OrderUID).'" class="ajaxload">
              '.$order->OrderNumber.'</a>';
            $row[] = $order->CustomerName;

            $row[] = $order->ProjectName;
            $row[] = '<a href="#" style=" background: '.$order->StatusColor.' !important;" class="btn  btn-round mt-10">'.$order->StatusName.'</a>';
            $row[] = $order->PropertyAddress1.' '.$order->PropertyAddress2;
            $row[] = $order->PropertyCityName;
            $row[] = $order->PropertyCountyName;
            $row[] = $order->PropertyStateCode;
            $row[] = $order->PropertyZipCode;
            $row[] = $order->OrderEntryDateTime;
            if ($post['OrderUID'] == $order->OrderUID) {
              $checked = 'checked';
            }
            $Action ='<td><div class="form-check"> <label class="form-check-label" for="check'.$key.'"> <input class="form-check-input input_assigncheckbox" value="" id="check'.$key.'" name="input_assigncheckbox" type="checkbox" data-OrderUID="'.$order->OrderUID.'" '.$checked.'> <span class="form-check-sign"> <span class="check"></span> </span> </label> </div>
              </td></tr>';

            $row[] = $Action;
            $assigmentorders[] = $row;
        }


        $data =  array(
          'assigmentorders' => $assigmentorders,
          'post' => $post
        );



    $post = $data['post'];
    $output = array(
      "draw" => $post['draw'],
      "recordsTotal" => $this->Orderassignmentmodel->count_all(),
      "recordsFiltered" =>  $this->Orderassignmentmodel->count_filtered($post),
      "data" => $data['assigmentorders'],
    );

    unset($post);
    unset($data);

    echo json_encode($output);
  }

  public function GetProjectUsers()
  {
    $OrderUID = $this->input->post('OrderUID');

    $tOrders = $this->Common_Model->get_row('tOrders', 'OrderUID', $OrderUID);
    $mProjectUser = $this->Orderassignmentmodel->GetProjectUsers($tOrders->ProjectUID);

    $html = '';
    foreach ($mProjectUser as $key => $value) {
      $html .= '<option value="'.$value->UserUID.'">'.$value->UserName.'</option>';
    }
    $response['validation_error']=0;
    $response['html'] = $html;
    $this->output->set_content_type('applicaton/json')
    ->set_output(json_encode($response));


  }

} ?>

