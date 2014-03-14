<?php
class Site_IndexController extends Cl_Controller_Action_Index
{
    public function indexAction()
    {
    	/**Get categories*/
    	$categories = Dao_Node_Category::getInstance()->getCategoryLevelOne();
    	$this->setViewParam('categories', $categories);

    	/***Get hompage products***
    	 * Sản phẩm được SEO gợi ý đưa lên top 1
    	 * Sản phẩm này sẽ được thay đổi mỗi ngày
    	 * */
    	
    	$iid = get_conf('home_page_product_iid', 1);
    	$hp_product = Dao_Node_Product::getInstance()->getHomePageProduct($iid);
    	
    	$this->setViewParam('hp_product', $hp_product);
    	
    	/****Get products recommend**************
    	 * Cac san pham goi y se duoc admin config
    	 * recommend_products
    	 * */
    	
    	$recommend_products_id = get_conf('recommend_products_id', 1);
    	$recommend_product = Dao_Node_Product::getInstance()->getRecommendProduct($recommend_products_id);
    	 
    	$this->setViewParam('recommend_products', $recommend_products);
    	
    	/**LẤY DANH SÁCH SẢN PHẨM THEO IID ĐƯỢC CONFIG CỦA DANH SÁCH CHUYÊN MỤC :: STYLE 1**
  				Name_cofig:: category_iid_style1
  		*/
  		$category_iid_style1 = get_conf('category_iid_style1','5321e82d0b08d18f16000000');
  		$style1_products = Dao_Node_Product::getInstance()->getStyle1Product($category_iid_style1);
  		
  		$this->setViewParam('style1_products', $style1_products);
  		
  		
  		/**	
  			Name_cofig:: products_categories_iids1
  		 **/
  		
  		$category_iid_style1 = get_conf('products_categories_iids1','5321e82d0b08d18f16000000');
  		$categories = Dao_Node_Product::getInstance()->getStyle1ProductOfCategories($category_iid_style1);
  		
  		$this->setViewParam('categories_iids1', $categories);
  		
    	
        Bootstrap::$pageTitle = "Trang chủ - GT";
    }
	public function errorAction()
	{
		
	}
    //==========================ADMIN==================================
    public function installAction()
    {
    	assure_perm('sudo');
    	$this->setLayout("admin");
    	if ($this->isSubmitted())
    	{
    		Cl_Dao_Util::getUserDao()->installSite();
    	}
    }
    
    public function adminAction()
    {
        assure_perm('sudo');
        $this->setLayout("admin");
        Bootstrap::$pageTitle = "Admin Panel";
    }
}
