
<?php
class Product_Form_Search extends Cl_Form_Search
{
    public function init()
    {
        parent::init();
        $this->method    = "GET";
        $this->fieldList = array(
            'supplierName','name', 'model','serialNumber',
            'price','iid','stockStatus'
        );
        $this->setCbHelper('Product_Form_Helper');
        //$this->setDisplayInline();
    }
    public function setStep($step, $currentRow = null)
    {
        
        parent::setStep($step, $currentRow = null);
    }
    //protected $_fieldListConfig; @see Cl_Dao_Foo
    
    
    //we must have it here as separate from $_fieldListConfig
    //because some configs will be merged with another file
    protected function _formFieldsConfig()
    {
        //$type[''] = 'all';
        $ret = array(
            'serialNumber' => array(
                'type' => 'Text', //Text, Textarea, Select, Checkbox, MultiCheckbox, Radio, Hidden
                'options' => array(
                    'label' => "SerialNumber Search",
                    'disableLoadDefaultDecorators' => true,
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                        'StringToUpper'
                    )
                ),
                //'op' => '$in' // like %$value% . 
                //$in , $eq, $lte,<=
            ),
            'name' => array(
                    'type' => 'Text', //Text, Textarea, Select, Checkbox, MultiCheckbox, Radio, Hidden
                    'options' => array(
                            'label' => "Product name",
                            'disableLoadDefaultDecorators' => true,
                            'filters' => array(
                                    'StringTrim',
                                    'StripTags',
                                    'StringToUpper'
                            )
                    ),
                    //'op' => '$in' // like %$value% .
                    //$in , $eq, $lte,<=
            ),
            'model' => array(
                'type' => 'Text', //Text, Textarea, Select, Checkbox, MultiCheckbox, Radio, Hidden
                'options' => array(
                    'label' => "Model",
                    'disableLoadDefaultDecorators' => true,
                    'filters' => array(
                        'StringTrim',
                        'StripTags',
                        'StringToUpper'
                    )
                ),
            ),
            'supplierName' => array(
                    'type' => 'Text', //Text, Textarea, Select, Checkbox, MultiCheckbox, Radio, Hidden
                    'options' => array(
                            'label' => "SupplierName",
                            'disableLoadDefaultDecorators' => true,
                            'filters' => array(
                                    'StringTrim',
                                    'StripTags',
                                    'StringToUpper'
                            )
                    ),
            ),
            'stockStatus' => array(
                    'type' => 'Select',
                    'options' => array(
                            'label' => "Stock Status",
                            'disableLoadDefaultDecorators' => false,
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags')
                    ),
                    'multiOptionsCallback' => array(array('Product_Form_Helper', 'getStockStatus')),
            ),
            'receivedDate' => array(
                'type' => 'Text',
                'options' => array(
                    'label' => "Received Date",
                    'disableLoadDefaultDecorators' => false,
                    'required' => true,
                    'transformers' => array(
                        'dateToUnixTimestamp'
                    )
                ),
                'filters' => array(
                    'StringTrim',
                    'StripTags'
                )
            ),
            'note' =>  array(
                'type' => 'Checkbox',
                'options' => array(
                    'label' => "Show details",
                    'disableLoadDefaultDecorators' => false,
                ),
                'filters' => array(
                    'StringTrim',
                    'StripTags'
                ),
                'op' => '$ignore'
            ),
            'description' =>  array(
            		'type' => 'Textarea',
            		'options' => array(
            				'label' => "Description",
            				'disableLoadDefaultDecorators' => false,
            		),
            		'filters' => array(
            				'StringTrim',
            				'StripTags'
            		),
            		'op' => '$ignore'
            ),
            'price' =>  array(
                    'type' => 'Text',
                    'options' => array(
                        				'label' => "Price",
                        				'disableLoadDefaultDecorators' => false,
                    ),
                    'filters' => array(
                        				'StringTrim',
                        				'StripTags'
                    ),
                    'op' => '$ignore'
            ),
            'iid' =>  array(
                    'type' => 'Text',
                    'options' => array(
                            'label' => "Price",
                            'disableLoadDefaultDecorators' => false,
                    ),
                    'filters' => array(
                            'StringTrim',
                            'StripTags'
                    ),
            ),
            
        );
        return $ret;
    }
}
    
?>