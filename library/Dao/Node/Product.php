<?php
class Dao_Node_Product extends Cl_Dao_Node
{
    public $nodeType = 'samx';
    public $cSchema = array('id' => 'string', 'iid' => 'string', 'name' => 'string', 'images' => 'string');
        
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
    	        'iid' => 'int',
    	        'supplierName' => 'string',
    	        'model' => 'string',
    	        'condition' => 'enum',
    	        'serialNumber' => 'string',
    	        'location' => 'string',
    	        'modifiedDate' => 'int',
    	        'receivedDate' => 'int', // unix timestamp , at 00:00:00 of that date
    	        'soldDate' => 'int', // unix timestamp , at 00:00:00 of that date
    	        'stockStatus' => 'string', // 0 NOTINStock, 1 => InStock, 2 => Missing
    	        'note' => 'string',
    	        'images' => 'string',
    	        'category' => 'array',
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
    	        'counter'	=>	array(
    	                'saled' => 'int', // so luong hang ban duoc
    	                'viewed' => 'int', //so luot ghe tham san pham
    	                'instock' => 'int' //so luong hang ton kho
    	        ),
        	),
	        'indexes' => array(
	                array(
	                        array(
	                                'iid' => 1
	                        ),
	                        array(
	                                "unique" => true,
	                                "dropDups" => true
	                        )
	                ),
	                array(
	                        array(
	                                'serialNumber' => 1
	                        ),
	                        array(
	                                "unique" => true,
	                                "dropDups" => true
	                        )
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
		if($data['images'] != ''){
			$data['images'] = remove_ufiles_from_images_url($data['images']);	
		}
		
	    if (!isset($data['iid']))
	    {
	        $redis = init_redis(RDB_CACHE_DB);
	        $data['iid'] = $redis->incr($this->nodeType . ":iid"); //unique node id
	    }
        return array('success' => true, 'result' => $data);
	}
	
	public function afterInsertNode($data, $row)
	{
        parent::afterInsertNode($data, $row);
        return array(
            'success' => true,
            'result' => $row
        );
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
	
	public function getStandedProduct($product){
		$productNew = $product;
		$productNew['category']['name'] = isset($product['category']['name']) ? $product['category']['name'] : '';
		$productNew['counter']['buy'] = isset($product['count']['buy']) ? $product['count']['buy'] : 0;
		$productNew['price'] = isset($product['price']) ? $product['price'] : 0;
		$productNew['saled'] = isset($product['saled']) ? $product['saled'] : 0;
	
		return $productNew;
	}
	
	public function getHomePageProduct($iid){
		$where = array('iid' => $iid);
		//$where = array('id' => '532246490b08d1eb0c000000');
		$r = Dao_Node_Product::getInstance()->findOne($where);
		 
		if($r['success']){
			$hp_product = $r['result'];
		}else{
			$hp_product = array();
		}
		
		return $hp_product;
	}
	
	public function getRecommendProduct($recommend_products_id){
		//$recommend_products_iid = get_conf('recommend_products_iid', 1);
		//$iids = explode(',',$recommend_products_iid);
		$ids = explode(',',$recommend_products_id);
		$idsNew = array();
		 
		if(count($ids) > 0){
			foreach ($ids as $id){
				if(trim($id) != '')
					$idsNew[] = trim($id);
			}
		}
		 
		$reCWhere = array('id' => array('$in' => $idsNew));
		$cond['limit'] = 4;
		$cond['where'] = $reCWhere; //recommend where
		$r = Dao_Node_Product::getInstance()->find($cond);
		
		if($r['success']){
			$recommend_products = $r['result'];
		}else{
			$recommend_products = array();
		}
		
		return $recommend_products;
	}
	
	public function getProductsByCategoryIid($category_iid){
		//$where = array('category.id' => $category_iid);
		$where = array();
		$cond['where'] = $where;
		$cond['limit'] = 4;
		//$order['ts'] = 1;
		//$cond['order'] = $order;
		
		$r = Dao_Node_Product::getInstance()->find($cond);
		
		if($r['success']){
			$style1_products = $r['result'];
		}else{
			$style1_products  = array();
		}
		
		return $style1_products;
	}
	
	public function getProductsByCategorysIids($category_iids, $limit){
		$cateIds = explode(',',$category_iids);
		$cateIdsNew = array();
			
		if(count($cateIds) > 0){
			foreach ($cateIds as $id){
				if(trim($id) != '')
					$cateIdsNew[] = trim($id);
			}
		}
		
		if(count($cateIdsNew)){
			$categories = array();
			foreach ($cateIdsNew as $cateId){
				//$where = array('category.id' => $category_iid_style1);
				$where = array();
				$cond['where'] = $where;
				$cond['limit'] = $limit;
				//$order['ts'] = 1;
				//$cond['order'] = $order;
		
				$r = Dao_Node_Product::getInstance()->find($cond);
		
				if($r['success']){
					$products = $r['result'];
				}else{
					$products  = array();
				}
		
				$where = array('id' => $cateId);
				$r = Dao_Node_Category::getInstance()->findOne($where);
		
				$detailCate = $r['result'];
				$categorie['detail'] = $detailCate;
				$categorie['products'] = $products;
				$categories[] = $categorie;
			}
		}
		
		return $categories;
	}
}
