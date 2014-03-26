<?php
/**
 * Remember you have    
 *  public $dao;
 *  public $node, $nodeUC; //node name : foo|post|item...

 * @author tran
 */ 
class Term_IndexController extends Cl_Controller_Action_NodeIndex 
{
    public function init()
    {
        //$this->daoClass = "Cl_Dao_Node_Term";
        //$this->commentDaoClass = "Cl_Dao_Comment_Term";
        
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
    
    public function hinhThucThanhToanAction()
    {
    	Bootstrap::$pageTitle = 'Hình thức thanh toán';
    }
    
    public function chinhSachVanChuyenAction()
    {
    	Bootstrap::$pageTitle = 'Chính sách vận chuyển';
    }
    
    public function chinhSachDoiTraHangAction()
    {
    	Bootstrap::$pageTitle = 'Chính sách đổi trả hàng';
    }
    
    public function troGiupKhachHangAction()
    {
    	Bootstrap::$pageTitle = 'Trợ giúp khách hàng';
    }
    
    public function cauHoiThuongGapAction()
    {
    	Bootstrap::$pageTitle = 'Câu hỏi thường gặp';
    }
    
    public function privacyAction()
    {
    	Bootstrap::$pageTitle = 'Bảo vệ quyền riêng tư';
    }
    
    public function termsAction()
    {
    	Bootstrap::$pageTitle = 'Điều khoản sử dụng';
    }
}

