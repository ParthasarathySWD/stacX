<?php defined('BASEPATH') or exit('No direct script access allowed');
class CommonController extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }


    public function GetCustomerDetails()
    {
        $CustomerUID = $this->input->post('CustomerUID');

        $mCustomer = $this->Common_Model->get_row('mCustomer', ['CustomerUID'=>$CustomerUID], ['CustomerUID'=>'ASC'], []);
        $mProjectCustomer = $this->Common_Model->get('mProjectCustomer', ['CustomerUID'=>$CustomerUID], ['ProjectUID'=>'ASC'], []);
        
        $this->output->set_content_type('application/json')
            ->set_output(json_encode(['Customer'=>$mCustomer, 'ProjectCustomer'=>$mProjectCustomer]));


    }

    public function GetPriority()
    {
        $ProjectUID = $this->input->post('ProjectUID');

        $mProjectCustomer = $this->Common_Model->get_row('mProjectCustomer', ['ProjectUID'=>$ProjectUID], ['ProjectUID'=>'ASC'], []);
        
        $this->output->set_content_type('application/json')
            ->set_output(json_encode(['ProjectCustomer'=>[$mProjectCustomer]]));


    }


    function GetZipCodeDetails()
    {
        $Zipcode = $this->input->post('Zipcode');

        if ($Zipcode) {

            $City = $this->Common_Model->getCityDetail($Zipcode);
            $State = $this->Common_Model->getStateDetail($Zipcode);
            $County = $this->Common_Model->getCountyDetail($Zipcode);
            if (!empty($State) && !empty($County) && !empty($City)) {

                echo json_encode(array('City' => $City, 'success' => 1, 'State' => $State, 'County' => $County));
            } else {
                echo json_encode(array('details' => '', 'success' => 0));

            }

        } else {
            echo json_encode(array('details' => '', 'success' => 0));
        }
    }


} ?>

