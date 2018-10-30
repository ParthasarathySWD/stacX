<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class PDFtoImage
{
    public function __construct()
    {
        require_once APPPATH.'third_party/pdf_to_image.php';
    }

}

?>