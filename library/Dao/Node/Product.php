<?php
class Dao_Node_Product extends Cl_Dao_Node
{
    public $nodeType = 'samx';
    public $cSchema = array(
    		'id' => 'string',
    		'name' => 'string',
    		'avatar' => 'string',
    		//add other stuff u want
    );
        
    protected function relationConfigs($subject = 'user')
    {
    	if ($subject == 'user')
    	{
    		return array(
    				'1' => '1', //vote | like
    				'2' => '2', //follow
    				'3' => '3' , //flag as spam
    		);
    	}
    }
    
	protected function _configs(){
	    $user = Cl_Dao_Util::getUserDao()->cSchema;
    	return array(
    		'collectionName' => 'product',
        	'documentSchemaArray' => array(
    	        'id' => 'int',
    	        'iid' => 'int',
    	        'SupplierName' => 'string',
    	        'Model' => 'string',
    	        'Condition' => 'enum',
    	        'SerialNumber' => 'string',
    	        'Location' => 'string',
    	        'ModifiedDate' => 'int',
    	        'ReceivedDate' => 'int', // unix timestamp , at 00:00:00 of that date
    	        'SoldDate' => 'int', // unix timestamp , at 00:00:00 of that date
    	        'StockStatus' => 'string', // 0 NOTINStock, 1 => InStock, 2 => Missing
    	        'Note' => 'array',
    	        'images' => array(
    	                array(
    	                        'id' => 'string',
    	                        'ext' => 'string',
    	                        'url' => 'string',
    	                        'path' => 'string'
    	                )
    	        ),
    	        'weight' => 'float',
    	        'type' => 'string',
    	        "name" => 'string',
    	        "description" => 'string',
    	        "meta_description" => 'string',
    	        "key_word" => 'string',
    	        'quantity' => 'int',
    	        'manufacturer_id' => 'string',
    	        'shipping' => 'int',
    	        "price" => 'int',
    	        'weight' => 'float',
    	        'length' => 'float',
    	        "width" => 'float',
    	        'height' => 'float',
    	        'viewed' => 'int',
    	        'saled' => 'int',
    	        'counter'	=>	array(
    	                'sale_number' => 'int', // so luong hang ban duoc
    	        ),
        	)
    	);
	}
	
    /**
     * Add new node
     * @param post data Array $data
     */
	public function beforeInsertNode($data)
	{
        return array('success' => true, 'result' => $data);
	}
	
	public function afterInsertNode($data, $row)
	{
        return array('success'=> true, 'result' => $row);
	}
	
    /******************************UPDATE****************************/
    public function beforeUpdateNode($where, $data, $currentRow)
    {
        /*
         * You have $data['$set']['_cl_step'] and $data['$set']['_u'] available
         */
        return array('success' => true, 'result' => $data);
    }
    
	public function afterUpdateNode($where, $data, $currentRow)
    {
        return array('success' => true, 'result' => $data);    
    }   
     
	/******************************INSERT_COMMENT****************************/
    /**
     * You have $node = $data['_node'];
     */
	public function beforeInsertComment($data)
	{
	    $node = $data['_node'];
	    	        
        $data['node'] = array(
            'id'	=>	$data['nid'],
		);
        
		$data['status'] = 'queued';
        
        if (isset($node['name']) && !empty($node['name']))
            $data['node']['name']	=	$node['name'];
        else if (isset($node['content']))
            $data['node']['name']	= word_breadcumb($node['content'], CACHED_POST_TITLE_WORD_LENGTH);
	    
        if(isset($data['attachments']) && (is_null($data['attachments']) || $data['attachments'] == ''))
        	unset($data['attachments']);
        
		return array('success' => true, 'result' => $data);
	}
		
	/**
     * You have $node = $data['_node'];
	 * Add new comment to a post
	 * @param POST data $data
	 */
	public function afterInsertComment($data, $comment)
	{
	    return array('success' => true, 'result' => $comment);
	}
	
	public function beforeUpdateComment($where, $data, $row)
	{
        if($data['$set']['_cl_step'] == 'is_spam') {
            // incresase counter.spam
            $data['$inc'] = array('counter.spam' => 1);
        }
		return array('success' => true, 'result' => $data);
	}
	
	
	public function afterUpdateComment($where, $data, $row)
	{
        if(($data['$set']['_cl_step'] == 'is_spam') && 
                (in_array('admin', $data['$set']['roles']) || in_array('root', $data['$set']['roles']))
           )
        {
            // mark is_spammer
            $dataUpdate = array('$set' => array('is_spam' => 1));
            $cWhere = array('id' => $row['id']);
            Site_Codenamex_Dao_Comment_Product::getInstance()->update($cWhere, $dataUpdate);
            
            // TODO: 
        }
        
		return array('success' => true, 'result' => $data);
	}
	
	/**
	 * Delete a single node by its Id
	 * @param MongoID $nodeId
	 */
	public function afterDeleteNode($row)
	{
	    //delete all comments
	    $commentDao = Site_Codenamex_Dao_Comment_Product::getInstance();
	    $where = array('node.id' => $row['id']);
	    $commentDao->delete($where);
	    
	    return array('success' => true, 'result' => $row);
	}
	
	
	/**
	 * Prepare data for new node insert
	 * @param Array $dataArray
	 */
	public function prepareFormDataForDaoInsert($dataArray = array(), $formName = "Product_Form_New")
	{
		return $dataArray;
	}	
	
	public function prepareCommentFormDataForDao($dataArray = array())
	{
		return $dataArray;
	}	

	/******************************RELATION*********************************/
	public function beforeInsertRelation($data)
	{
		return array('success' => true, 'result' => $data);
	}
	public function afterInsertRelation($data, $newRelations, $currentRow)
	{
		return array('success' => true, 'result' => $data);
	}
	public function afterDeleteRelation($currentRow, $rt, $newRelations = array())
	{
	    return array('success' => true);
	}
	
	public function filterNewObjectForAjax($obj, $formData)
	{
		return array('id' => $obj['id'] /*, 'slug' => $obj['slug'] */);
	}
	
	public function filterUpdatedObjectForAjax($currentRow, $step, $data, $returnedData)
	{
		$ret = array('id' => $currentRow['id']);
		return $ret;
		/*
		 if (isset($data['slug']))
			$ret['slug'] = $data['slug'];
		elseif (isset($currentRow['slug']))
		$ret['slug'] = $currentRow['slug'];
		return $ret;
		*/
	}
	
}
