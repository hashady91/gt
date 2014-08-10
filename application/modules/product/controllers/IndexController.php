<?php
/**
 * Remember you have    
 *  public $dao;
 *  public $node, $nodeUC; //node name : foo|post|item...

 * @author tran
 */ 
class Product_IndexController extends Cl_Controller_Action_NodeIndex 
{
    public function init()
    {
        //$this->daoClass = "Cl_Dao_Node_Product";
        //$this->commentDaoClass = "Cl_Dao_Comment_Product";
        
        /**
         * Chances to check for permission here if you like
         */
        parent::init();
        /**
         * Chances to change layout if you like
         */
    }

    public function indexAction()
    {

    }

    public function newAction()
    {
    	assure_perm('sudo');
    	$this->setLayout("admin");
        $this->genericNew("Product_Form_New", "Dao_Node_Product", "Node");
        
        if(isset($this->ajaxData)) {
            //command the form view to rediect if success
            if (isset($this->ajaxData['result'])) //success
            {
                if (is_preview())
                {
                    $this->setViewParam('row', $this->ajaxData['result']);
                    $this->setViewParam('is_preview', 1);
                    $this->_helper->viewRenderer->setNoRender(true);
                    $ret['data'] = $this->view->render('index/view.phtml');
                    $ret['success'] = true;
                    $ret['callback'] = 'populate_preview';
                    send_json($ret);
                    exit();
                }
                else 
                {
                	$this->ajaxData['callback'] = 'redirect';
                	$this->ajaxData['data'] = array('url' => node_link('product' , $this->ajaxData['result']));
                }
            }
        }
        Bootstrap::$pageTitle = 'Tạo sản phẩm mới';
    }

    public function crawlerAction()
    {
        if (!assure_perm('sudo'))
    	    $this->_redirect('/user/login');
        $title = $this->getStrippedParam('title');
        //$tags = $this->getStrippedParam('tags');
        $itemID = $this->getStrippedParam("itemID");
        $itemName = $this->getStrippedParam("itemName");
        $price = $this->getStrippedParam("price");
        $imageURL = $this->getStrippedParam('image');
        $b64image = base64_decode(file_get_contents($imageURL));
        $binary = base64_decode($b64image);
        $id = new MongoId();
        $today = getdate();
        $file = $id . '.jpg';
        $filePath = STATIC_PATH . $file;
        $url = STATIC_CDN . $file;
        Cl_Utility::getInstance()->saveFile($binary, $filePath);
        if($itemID != '' || $itemID != null)
        {
            //TODO : get OLD imgae URL. and copy new file to old folder
            assure_perm('root');
            $r2 = Dao_Node_Story::getInstance()->findOne(array('id'=> $storyId));
            $url2 = PUBLIC_FILES_UPLOAD_PATH . '/1000/'.$r2['id'];
            Cl_Utility::copyFile($filePath, $url2);
            $i = Dao_Node_Story::getInstance()->uploadAndResizeImage($url2);
            die('OK');
        }
        $tmp = Cl_Dao_Util::getUserDao()->getCacheObject(Zend_Registry::get('user'));
        if ($tmp['success'])
            $u = $tmp['result'];
        
        $values['u'] = $u;
        $values['_u'] = Zend_Registry::get('user');
        $values['ts'] = time();
        $values['name'] = $itemName;
        $values['itemID'] = $itemID;
        $values['price'] = $price;
        $values['images'] = $url;
        $r = Dao_Node_Product::getInstance()->insertNode($values);
        if ($r['success'])
        {
            $r = array('success'=> true, 'result'=>'inserted successfully');
        }
        $this->handleAjaxOrMaster($r);        
    }
    
    public function updateAction()
    {
    	assure_perm('sudo');
    	$this->setLayout("admin");
        /**
         * Permission to update a node is done in 
         * $Node_Form_Update form->customPermissionFilter()
         * Do not do it here
         * @NOTE: object is already filtered in Index.php, done in Cl_Dao_Node::filterUpdatedObjectForAjax()
         */
        $this->genericUpdate("Product_Form_Update", $this->daoClass ,"", "Node");
        Bootstrap::$pageTitle = 'Cập nhật sản phẩm';
    }

    public function searchAction()
    {
    	assure_perm('sudo');
    	$this->setLayout("admin");
        //assure_perm("search_product");//by default
        $this->genericSearch("Product_Form_Search", $this->daoClass, "Node");
        Bootstrap::$pageTitle = 'Quản lý sản phẩm';        
    }
    
    public function searchKeyAction()
    {
    	/**Get categories*/
    	$categories = Dao_Node_Category::getInstance()->getCategoryLevelOne();
    	$this->setViewParam('categories', $categories);
    	
    	$key = $this->getStrippedParam('key');

    	$form = new Product_Form_Search();
    	
    	$data = array( 
    		'name' => $key, 
    	);
    	
    	$form->build($data);
    	
    	$conditions = $form->buildSearchConditions();
    	
    	$conditions['total'] = 1;
    	
    	$dao = Dao_Node_Product::getInstance();
    	$r = $dao->findNode($conditions, true);

    	if($r['success'] && $r['total'] > 0){
    		$products = $r['result'];
    	}else{
    		$products = array();
    		
    		/**
	         * Get dealest products
	         * **/
	    	$dealestProducts = Dao_Node_Product::getInstance()->getProductsByType('dealest', '', 4);
	
	        /**
	         * Get newest products
	         * **/
	    	$newProducts = Dao_Node_Product::getInstance()->getProductsByType('newest', '', 3);
	    	
	    	/**
	    	 * Get best selling products
	    	 * **/
	    	$bestSellingProducts = Dao_Node_Product::getInstance()->getProductsByType('bestSelling', '', 4);
	    	
	    	        
	        $this->setViewParam('newProducts',$newProducts);
	        $this->setViewParam('dealestProducts',$dealestProducts);
	        $this->setViewParam('bestSellingProducts',$bestSellingProducts);
    	}
    	
    	$this->setViewParam('products',$products);
    	$this->setViewParam('key',$key);
    	//assure_perm("search_product");//by default
    	//$this->genericSearch("Product_Form_Search", $this->daoClass, "Node");
    	Bootstrap::$pageTitle = 'Tìm kiếm sản phẩm';
    }
    
    public function searchCommentAction()
    {
        assure_perm("search_product");//by default
        $commentClass =$this->commentDaoClass;
        $this->genericSearch("Product_Form_SearchComment", $commentClass, "");
        Bootstrap::$pageTitle = "Search " . $this->nodeUC . " Comments";        
    }
    
    public function viewAction()
    {
        $id = $this->getStrippedParam('id');
        if($id != '')
            $r = Dao_Node_Product::getInstance()->ProductView($id);
        
        if ($r['success'] && $r['count'] > 0) {
            $this->setViewParam ('row', $r ['result'] );
            //Lay danh sach san pham o cac hang tuong tu
            $list = Dao_Node_Product::getInstance()->getRelatedProductsOfBrands($r['result']['id']);
            $this->setViewParam('related', $list);
        } else {
            $this->_redirect ( "/" );
        }
        
        /**
         * Get dealest products
         * **/
    	$dealestProducts = Dao_Node_Product::getInstance()->getProductsByType('dealest', '', 4);

        /**
         * Get newest products
         * **/
    	$newProducts = Dao_Node_Product::getInstance()->getProductsByType('newest', '', 3);
    	
    	/**
    	 * Get best selling products
    	 * **/
    	$bestSellingProducts = Dao_Node_Product::getInstance()->getProductsByType('bestSelling', '', 4);
    	
    	        
        $this->setViewParam('newProducts',$newProducts);
        $this->setViewParam('dealestProducts',$dealestProducts);
        $this->setViewParam('bestSellingProducts',$bestSellingProducts);
        
        Bootstrap::$pageTitle = 'Xem sản phẩm';
    }
    
    public function deleteNodePermissionCheck($row)
    {
        if (has_perm("delete_product"))
            return array('success' => true);
        else 
            return array('success' => false);
    }
    public function commentAction(){
    	//$this->commentScript = "index/one-comment.phtml";
    	parent::commentAction();
    }
    
    //implements parent::newCommentPermissionCheck
    public function newCommentPermissionCheck($row)
    {
    	//TODO: Implement this
    	return has_perm("new_product_comment");
    }
    public function updateCommentAction()
    {
    	//$this->commentContentScript = "index/one-comment-content.phtml";
    	parent::updateCommentAction();
    }
    
    public function delCommentAction()
    {
    	parent::delCommentAction();
    }
    
    public function bulkDeleteAction()
    {
    	assure_role('admin_product');
    	$ids = $this->getStrippedParam('ids');
    	$in = explode(',', $ids);
    	$where = array('id' => array('$in' => $in));
    	Dao_Node_Product::getInstance()->delete($where);
    	$r = array('success' => true);
    	$this->handleAjaxOrMaster($r);
    }
    
    public function newestAction()
    {
    	$cateId = $this->getStrippedParam('cate_id');
    	$product = Dao_Node_Product::getInstance()->getProductsByType('newest', $cateId, -1);
    	
    	$this->setViewParam('products',$product);
    	//$this->_helper->viewRenderer->setNoRender(true);
    	Bootstrap::$pageTitle = 'Sản phẩm mới nhất';
    }
    
    public function dealestAction()
    {
    	$cateId = $this->getStrippedParam('cate_id');
    	$product = Dao_Node_Product::getInstance()->getProductsByType('dealest', $cateId, -1);
    	
    	$this->setViewParam('products',$product);
    	//$this->_helper->viewRenderer->setNoRender(true);
    	Bootstrap::$pageTitle = 'Sản phẩm giá tốt nhất';
    }
    
    public function bestSellingAction()
    {
    	$cateId = $this->getStrippedParam('cate_id');
    	$product = Dao_Node_Product::getInstance()->getProductsByType('bestSelling', $cateId, -1);
    	
    	$this->setViewParam('products',$product);
    	//$this->_helper->viewRenderer->setNoRender(true);
    	Bootstrap::$pageTitle = 'Sản phẩm bán chạy nhất';
    }
    
    public function parseFileAction(){
    	header( 'Content-Type: text/html; charset="UTF-8"' );
    	$file = 'http://gt.local/products.txt';
    	$file_content = file_get_contents($file, true);	    //concept
    	
    	//Tach tung dong ra de lay tung dong
    	$productIids = explode("\n", $file_content);
    	$this->setViewParam('productIids', $productIids);
    	
    	Bootstrap::$pageTitle = "Phân tích dữ liệu - sản phẩm";
    }
    
    public function insertProductByUrlAction(){
    	Dao_Node_Product::getInstance()->insertProductByUrl();
    	
    	die('oki');	
    }
}

