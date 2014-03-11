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
        	'SupplierName' => array(
        		'type' => 'Text',
        		'options' => array(
        			'label' => "Supplier name",
        			'required' => true,
    	    		'filters' => array('StringTrim', 'StripTags'),
                    'validators' => array('NotEmpty'),
        		),
                //'permission' => 'update_task'
        	),
            'Model' => array(
                    'type' => 'Text',
                    'options' => array(
                            'label' => "Model",
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                    ),
                    //'permission' => 'update_task'
            ),
            'Condition' => array(
                    'type' => 'Text',
                    'options' => array(
                            'label' => "Condition",
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                    ),
                    //'permission' => 'update_task'
            ),
        	'SerialNumber' => array(
        		'type' => 'Textarea',
        		'options' => array(
        	        'label' => "SerialNumber",
    	    		'filters' => array('StringTrim', 'StripTags'),
        			'prefixPath' => array(
        				"filter" => array (
        					"Filter" => "Filter/"
        				)
        			)
        		),
        	),
            'ReceivedDate' => array(
                    'type' => 'Textarea',
                    'options' => array(
                            'label' => "ReceivedDate",
                            'filters' => array('StringTrim', 'StripTags'),
                            'prefixPath' => array(
                                    "filter" => array (
                                            "Filter" => "Filter/"
                                    )
                            )
                    ),
            ),
            'SoldDate' => array(
                    'type' => 'Textarea',
                    'options' => array(
                            'label' => "SoldDate",
                            'filters' => array('StringTrim', 'StripTags'),
                            'prefixPath' => array(
                                    "filter" => array (
                                            "Filter" => "Filter/"
                                    )
                            )
                    ),
            ),
            'StockStatus' => array(
                    'type' => 'Text',
                    'options' => array(
                            'label' => "StockStatus",
                            'filters' => array('StringTrim', 'StripTags'),
                            'prefixPath' => array(
                                    "filter" => array (
                                            "Filter" => "Filter/"
                                    )
                            )
                    ),
            ),
            'Note' => array(
                    'type' => 'Text',
                    'options' => array(
                            'label' => "Note",
                            'filters' => array('StringTrim', 'StripTags'),
                            'prefixPath' => array(
                                    "filter" => array (
                                            "Filter" => "Filter/"
                                    )
                            )
                    ),
            ),
            'Note' => array(
                    'type' => 'Text',
                    'options' => array(
                            'label' => "Note",
                            'filters' => array('StringTrim', 'StripTags'),
                            'prefixPath' => array(
                                    "filter" => array (
                                            "Filter" => "Filter/"
                                    )
                            )
                    ),
            ),
            'weight' => array(
                    'type' => 'Text',
                    'options' => array(
                            'label' => "weight",
                            'filters' => array('StringTrim', 'StripTags'),
                            'prefixPath' => array(
                                    "filter" => array (
                                            "Filter" => "Filter/"
                                    )
                            )
                    ),
            ),
            'type' => array(
                    'type' => 'Text',
                    'options' => array(
                            'label' => "type",
                            'filters' => array('StringTrim', 'StripTags'),
                            'prefixPath' => array(
                                    "filter" => array (
                                            "Filter" => "Filter/"
                                    )
                            )
                    ),
            ),
            'description' => array(
                    'type' => 'Textarea',
                    'options' => array(
                            'label' => "description",
                            'filters' => array('StringTrim', 'StripTags'),
                            'prefixPath' => array(
                                    "filter" => array (
                                            "Filter" => "Filter/"
                                    )
                            )
                    ),
            ),
            'name' => array(
                    'type' => 'Text',
                    'options' => array(
                            'label' => "name",
                            'filters' => array('StringTrim', 'StripTags'),
                            'prefixPath' => array(
                                    "filter" => array (
                                            "Filter" => "Filter/"
                                    )
                            )
                    ),
            ),
            'price' => array(
                    'type' => 'Text',
                    'options' => array(
                            'label' => "price",
                            'filters' => array('StringTrim', 'StripTags','Digit'),
                            'prefixPath' => array(
                                    "filter" => array (
                                            "Filter" => "Filter/"
                                    )
                            )
                    ),
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
