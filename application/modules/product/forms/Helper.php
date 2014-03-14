<?php
class Product_Form_Helper extends Cl_Form_NodeHelper
{
    public function getStatus()
    {
    	$ret = array('approved' => 'approved', 'queued' => 'queued');
    	return array('success' =>true, 'result' => $ret);
    }
    
    public function getStockStatus()
    {
        $ret = array(
                '0' => 'Còn hàng',
                '1' => 'Hết hàng'
        );
        return array(
                'success' => true,
                'result' => $ret
        );
    }
    /*
    public function getItemsPerPageList($params)
    {
    	$ret = array(
    	    '-1' => "All",
    		'10' => "10/page",
    		'20' => "20/page",
    		'30' => "30/page",	
    		'50' => "50/page");
    	return array('success' => true, 'result' => $ret);
    }
    */
}
