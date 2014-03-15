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
    /**
     * Transform date like 31/12/2009 to Unix time stamp
     * @param unknown_type $elName
     * @param unknown_type $val
     * @param unknown_type $reverse
     */
    public function dateToUnixTimestamp($data, $elName, $val, $reverse = false, $elConfig = array())
    {
    	if (defined('DATE_FORMAT'))
    	{
    		$format = defined('DATE_FORMAT') ? DATE_FORMAT : 'dd/mm/yy';
    	}
    	if ($format == 'dd/mm/yy')
    		list($date, $month, $year) = explode("/", $val);
    	else if ($format == 'mm/dd/yy')
    		list($month, $date, $year) = explode("/", $val);
    
    	if (strpos($elName, '_cl_rrange') !== false)
    	{
    		$minute = 59;
    		$second = 59;
    		$hour = 23;
    	}
    	else // if (strpos($elName, '_cl_lrange') !== false)
    	{
    		$minute = 0;
    		$second = 0;
    		$hour = 0;
    	}
    
    	if ($year < 100)
    	{
    		//TODO: make year a 4 digit number here
    		$year = 2000 + $year;
    	}
    
    	$ts = mktime($hour, $minute, $second, $month, $date, $year);
    	return $ts;
    }
}
