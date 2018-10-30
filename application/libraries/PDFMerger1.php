<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class PDFMerger1
{
    public function __construct()
    {
        require_once APPPATH.'third_party/PDFMerger/PDFMerger.php';
    }

}