<?php
/**
 * Remember you have    
 *  public $dao;
 *  public $node, $nodeUC; //node name : foo|post|item...

 * @author tran
 */ 
class Category_IndexController extends Cl_Controller_Action_NodeIndex 
{
    public function init()
    {
        //$this->daoClass = "Cl_Dao_Node_Category";
        //$this->commentDaoClass = "Cl_Dao_Comment_Category";
        
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
        $this->genericNew("Category_Form_New", "Dao_Node_Category", "Node");
        
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
                	$this->ajaxData['data'] = array('url' => node_link('category' , $this->ajaxData['result']));
                }
            }
        }
        Bootstrap::$pageTitle = t("new_category",1);
    }

    public function updateAction()
    {
        /**
         * Permission to update a node is done in 
         * $Node_Form_Update form->customPermissionFilter()
         * Do not do it here
         * @NOTE: object is already filtered in Index.php, done in Cl_Dao_Node::filterUpdatedObjectForAjax()
         */
        $this->genericUpdate("Category_Form_Update", $this->daoClass ,"", "Node");
        Bootstrap::$pageTitle = t("update_category",1);
    }

    public function searchAction()
    {
        assure_perm("search_category");//by default
        $this->genericSearch("Category_Form_Search", $this->daoClass, "Node");
        Bootstrap::$pageTitle = t("search_category",1);        
    }
    
    public function searchCommentAction()
    {
        assure_perm("search_category");//by default
        $commentClass =$this->commentDaoClass;
        $this->genericSearch("Category_Form_SearchComment", $commentClass, "");
        Bootstrap::$pageTitle = "Search " . $this->nodeUC . " Comments";        
    }
    
    public function viewAction()
    {
        //TODO Your permission here
        parent::viewAction();//no permission yet
        if ($row = $this->getViewParam('row'))
        {
            $id = $this->getStrippedParam('id');
            $where = array('node.id' => $id);
            $commentClass =$this->commentDaoClass;
            $r = $commentClass::getInstance()->findAll(array('where' => $where));
            if ($r['success'] && $r['count'] > 0)
            {
                $comments = $this->dao->generateCommentTree($r['result'], 0);
                //Construct comment trees here
                $this->setViewParam('comments', $comments);
            }
            if(is_rest()) {
                if ($r['success'] && $r['count'] > 0)
                {
                    $row['comments'] = $comments;
    	            $r = array('success' => true, 'result' => $row);
                }
    	        $this->handleAjaxOrMaster($r);
            }
        }        
        Bootstrap::$pageTitle = t("view_category",1);
    }
    
    public function deleteNodePermissionCheck($row)
    {
        if (has_perm("delete_category"))
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
    	return has_perm("new_category_comment");
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
    	assure_role('admin_category');
    	$ids = $this->getStrippedParam('ids');
    	$in = explode(',', $ids);
    	$where = array('id' => array('$in' => $in));
    	Dao_Node_Category::getInstance()->delete($where);
    	$r = array('success' => true);
    	$this->handleAjaxOrMaster($r);
    }
    
}

