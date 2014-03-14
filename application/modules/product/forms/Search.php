<?php 
class Product_Form_Search extends Cl_Form_Search
{

	public function init()
	{
		parent::init();
		$this->method= "GET";
		$this->fieldList = array(
	            'SupplierName','name', 'Model','SerialNumber', 'ReceivedDate',
	            'StockStatus',
	            /*'Note','description',*/
	            'price');
    	$this->setCbHelper('Product_Form_Helper');
    	//$this->setDisplayInline();
	}
	public function setStep($step, $currentRow = null)
	{
	    parent::setStep($step, $currentRow);
	}
	//protected $_fieldListConfig; @see Cl_Dao_Foo
	
	
    //we must have it here as separate from $_fieldListConfig
    //because some configs will be merged with another file
    protected $_formFieldsConfig = array(
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
    	'SerialNumber' => array(
    			'type' => 'Text',
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
   				'type' => 'Textarea',
   				'options' => array(
   						'label' => "Note",
   						'class' => 'isEditor',
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
   						'class' => 'isEditor',
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
   						'filters' => array('StringTrim', 'StripTags','Digits'),
   						'prefixPath' => array(
   								"filter" => array (
   										"Filter" => "Filter/"
   								)
   						)
   				),
   		),
   		'ReceivedDate' => array(
   				'type' => 'Text',
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
    	'name' => array(
    		'type' => 'Text',
    		'options' => array(
    			'label' => "Product name",
	    		'filters' => array('StringTrim', 'StripTags')
    		),
    		'op' => '$like',
    	),
		'items_per_page' => array(
        		'type' => 'Select', 
        		'options' => array(
    				'label' => "Display",
        			'disableLoadDefaultDecorators' => false,
        			'required' => true,
    	    		'filters' => array('StringTrim', 'StripTags')
        		),
        		//or you can implement getItemsPerPageList here
        		//'multiOptions' => array('getItemsPerPageList'),
        		'multiOptions' => array(
		    	    '-1' => "All",
            		'10' => "10/page",
            		'20' => "20/page",
            		'30' => "30/page",	
            		'50' => "50/page"
        		),
        		'defaultValue' => 10
    	),    	
    	'order_by_count' => array(
    		'type' => 'Select',
    		'options' => array(
    			'label' => "order_by_count",
    			'required' => true,
	    		'filters' => array('StringTrim', 'StripTags'),
    		),
    		'op' => '$eq',
    		'multiOptions' => array(
    			'ts' => 'created time',
    			'counter.c' => "comment count",
    		),
    	),
    );
}
