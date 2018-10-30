<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
require_once dirname(__FILE__) . '/dom/dom_simplehtml.php';
  
class Pdf extends TCPDF
{
 function __construct()
 {
 	parent::__construct();
 }
}