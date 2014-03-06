<?php 
class Product_Form_New extends Cl_Form
{
	public function init()
	{
		parent::init();
		$this->fieldList = array(
    	        'SupplierName',
    	        'Model',
    	        'Condition',
    	        'SerialNumber',
    	        'Location',
    	        'ModifiedDate',
    	        'ReceivedDate', // unix timestamp , at 00:00:00 of that date
    	        'SoldDate', // unix timestamp , at 00:00:00 of that date
    	        'StockStatus', // 0 NOTINStock, 1 => InStock, 2 => Missing
    	        'Note',
    	        'images',
    	        'category',
    	        'weight',
    	        'type',
    	        "name",
    	        "description",
    	        "meta_description",
    	        "key_word",
    	        'quantity',
    	        'manufacturer_id',
    	        'shipping',
    	        "price",
    	        'weight',
    	        'length',
    	        "width",
    	        'height',
    	        'viewed',
    	        'saled',
    	        'counter',);
		$this->setCbHelper('Product_Form_Helper');
		
	}
	public function setStep($step, $currentRow = null)
	{
		parent::setStep($step, $currentRow);
	}
	
    protected function _formFieldsConfigCallback()
    {
        $ret = array(
        	'name' => array(
        		'type' => 'Text',
        		'options' => array(
        			'label' => "Product name",
        			'required' => true,
    	    		'filters' => array('StringTrim', 'StripTags'),
                    'validators' => array('NotEmpty'),
        		),
                //'permission' => 'update_task'
        	),
        	'content' => array(
        		'type' => 'Textarea',
        		'options' => array(
        	        'label' => "Product Content",
        	        'class' => 'isEditor',
    	    		'filters' => array('StringTrim', 'NodePost'),
        			'prefixPath' => array(
        				"filter" => array (
        					"Filter" => "Filter/"
        				)
        			)
        		),
        	),
            'status' => array(
            		'type' => 'Select',
            		'options' => array(
            				'label' => 'Status',
            				'required' => true,
            		),
            		'multiOptionsCallback' => array('getStatus')
            ),
        	'avatar' => array(
        			'type' => 'Hidden',
        			'options' => array(
        					'class' => 'cl_upload',
        					'filters' => array('StringTrim', 'StripTags')
        			),
        	)
        );
        return $ret;
    }
    /**TODO: hook here if needed
    public function customIsValid($data)
    {
        return array('success' => false, 'err' => "If customIsValid exist. You must implement it");
    }
    */
}
