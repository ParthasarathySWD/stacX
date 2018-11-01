<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| CONFIG TYPES
| -------------------------------------------------------------------
| For keywords 
|
*/

$config['keywords'] = array (
					'New Order'	=> '0',
					'Reopened Order'=>'5',
					'Order Assigned'=>'10',
					'Flood Api' => '11',
					'Order Work In Progress'=>'15',
					'Partial Draft Complete'=>'19',
					'Draft Complete'=>'20',
					'Review In Progress'=>'35',
					'Partial Review Complete'=>'39',
					'Review Complete'=>'40',
					'Exception Raised'=>'49',
					'Exception Clarified'=>'50',
					'Raised Special Case'=>'59',
					'Clarified'=>'60',
					'Order Exported'=>'90',
					'Order Completed'=>'100',
					'Cancelled'=>'110',
					'Billed'=>'115',
				);

$config['PartyTypeUID'] = array(
					'Mortgagor'=> '1',
					'Mortgagee'=> '2',
					'Grantor'=>'3',
					'Grantee'=>'4',
					'Plaintiff'=> '5',
					'Defendent'=>'6',
	);

$config['DocumentTypeUID'] = array(
					'Deeds'=>'1',
					'Mortgages'=>'2',
					'Property Info'=>'3',
					'Judgment'=>'4',
					'Liens'=>'5',
					'Taxes'=>'6',
	);


$config['Propertyroles'] = array(
             'All' => '1',
             'Attorney in Fact' => '2',
             'Non Borrower' => '3',
             'Payer' => '4',
             'Borrowers' => '5',
             'Sellers' => '6',
             'Buyers' => '7',
             'Listing Agents' => '8',
             'Selling Agents' => '9',
             'Loan Officiers' => '10',
             'Servicers' => '11',
             'Others' => '12',
             'Subscribers' => '13',
             'GAC Counsel' => '14',
             'Underwrites Contact' => '15',
             'Non-Borrowing Spouse' => '16',
             'Non-Borrowing Obligator' => '17',
             'Property Contact' => '18',
             'Processor' => '19',
             'Nationstar' => '20',
             'Payoff Agent' => '21',
             'Broker' => '22',
             'Title Holder - Non' => '23',
             'Title Holder - Borrower' => '24',
             'Borrower - Non Owner' => '25',
             'Proposed Insurer' => '26',
						 );


$config['WorkflowModule'] = array(
					'Assessment'=> '2',
					'Mortgage'=> '2',
					'Deed'=>'2',
					'PropertyInfo'=>'2',
					'Exception'=> '2',
					'Taxes'=>'3',
					'OrderSearch'=>'1',

	);


$config['BROWSER_DEFAULT_VERSION']=array(
					"IE"=>11.0,
					"Chrome"=>67.0,
					"Mozilla"=>61.0,
					"Safari"=>11.0,
					);
$config['BROWSERS']=array(
					"IE"=>'Internet Explorer',
					"Chrome"=>'Chrome',
					"Mozilla"=>'Mozilla',
					"Safari"=>'Safari',
					);

// END OF FILE


