<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends MY_Controller {
    public function __construct()
	{
        parent::__construct();
        //check if backend login
        $this->is_admin_login();
        //Load Libraries
        $this->load->library(array('pagination'));
        //pagination settings
        $this->perPage = 10;
		//load faq model
		$this->load->model('faq_model');
		
	}
    //list faqs
	public function list_faqs()
	{
        $data['title']=$this->lang->line("text_faq");
        if($this->permitted('list_faqs')){
            //get all categories
            $categories = $this->faq_model->get_categories($conditions=array());
            $data['categories']=$categories;       
            $data['sub_view'] = $this->load->view('admin/faqs/list_faqs', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data); 
    }

    //ajax paginate faqs
    public function list_faqs_ajax($page=0){
        $conditions = array();
        // Row position
        if($page != 0){
            $page = ($page-1) * $this->perPage;
        }
        $keyword=$this->input->post('keyword');
        $category=$this->input->post('category');
        $status=$this->input->post('status');
        if(!empty($keyword)){
            $conditions['search']['keyword'] = $keyword;
        }
        if(!empty($category)){
            $conditions['search']['category'] = $category;
        }
        if(!empty($status)){
            $conditions['search']['status'] = $status;
        }
        //get faqs count
        $faqs=$this->faq_model->get_faqs($conditions);
        if($faqs){
            $faqsCount=count($faqs);
        }else{
            $faqsCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        //get all faqs
        $faqs = $this->faq_model->get_faqs($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'faqs/list_faqs_ajax',$total_rows=$faqsCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['faqs']=$faqs;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('admin/faqs/list_faqs_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //view faq
	public function view_faq()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('view_faq')){
                $data['title']=$this->lang->line("text_view_faq");
                $faq_id = $this->input->post('faq_id');
                //get faq by id
                $faq = $this->faq_model->get_faq($faq_id);
                $data['faq']=$faq;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/faqs/view_faq',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
            echo json_encode($json_array);
            exit();
        }
    }
    //add faq - load view
	public function add_faq()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('add_faq')){
                $data['title']='Add FAQ';
                //get all categories
                $categories = $this->faq_model->get_categories($conditions=array());
                $data['categories']=$categories;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/faqs/add_faq',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
            echo json_encode($json_array);
            exit();
        }
    }

    //create faq
    public function create_faq(){
        if($this->input->post()){
            //check permission
            if($this->permitted('add_faq')){
                $this->form_validation->set_rules('category','Category','trim|required');
                $this->form_validation->set_rules('title','Title','trim|required');
                $this->form_validation->set_rules('content','Content','trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
                }else{
                    $post_data = array(
                        'category_id' => $this->input->post('category'),
                        'faq_title' => $this->input->post('title'),
                        'faq_description' => $this->input->post('content'),
                        'status' => (int)(bool)$this->input->post('publish'),
                        'created_by' => $this->get_user_id(),
                        'updated_by' => $this->get_user_id(),
                    );
                    //XXS Clean
                    $post_data = $this->security->xss_clean($post_data);
                    $result = $this->faq_model->create_faq($post_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_faq_created");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_faq_not_created");
                    }
                }
            }else{
                $success = FALSE;
                $message = $this->lang->line("alert_access_denied");
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    //edit faq - load view
	public function edit_faq()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_faq')){
                $data['title']=$this->lang->line("text_edit_faq");
                $faq_id = $this->input->post('faq_id');
                //get all categories
                $categories = $this->faq_model->get_categories($conditions=array());
                $data['categories']=$categories; 
                //get faq by id
                $faq = $this->faq_model->get_faq($faq_id);
                $data['faq']=$faq;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/faqs/edit_faq',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
            echo json_encode($json_array);
            exit();
        }
    }

    //update faq
    public function update_faq(){
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_faq')){
                $this->form_validation->set_rules('category','Category','trim|required');
                $this->form_validation->set_rules('title','Title','trim|required');
                $this->form_validation->set_rules('content','Content','trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
                }else{
                    $faq_id=$this->input->post('faq_id');
                    $update_data = array(
                        'category_id' => $this->input->post('category'),
                        'faq_title' => $this->input->post('title'),
                        'faq_description' => $this->input->post('content'),
                        'updated_by' => $this->get_user_id(),
                    );
                    //XXS Clean
                    $update_data = $this->security->xss_clean($update_data);
                    $result = $this->faq_model->update_faq($faq_id,$update_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_faq_updated");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_faq_not_updated");
                    }
                }
            }else{
                $success = FALSE;
                $message = $this->lang->line("alert_access_denied");
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    //publish faq - load view
	public function publish_faq()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('faq_publishing')){
                $data['title']=$this->lang->line("text_publish_faq");
                $faq_id= $this->input->post('faq_id');
                $data['faq_id']=$faq_id;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/faqs/publish_faq',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
            echo json_encode($json_array);
            exit();
        }
    }

    //publish faq action
    public function publish_faq_action(){
        //check permission
        if($this->permitted('faq_publishing')){
            $faq_id = $this->input->post('faq_id');
            $update_data=array(
                'status'=>1,
                'updated_by' => $this->get_user_id()
            );
            //faq publishing
            $result = $this->faq_model->faq_publishing($faq_id,$update_data);
            if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                $success = TRUE;
                $message = $this->lang->line("alert_faq_published");
            }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                $success = FALSE;
                $message = $this->lang->line("alert_faq_not_published");
            }
        }else{
            $success = FALSE;
            $message = $this->lang->line("alert_access_denied");
        }
        $json_array = array('success' => $success, 'message' => $message);
        echo json_encode($json_array);
        exit();
    }

    //unpublish faq - load view
	public function unpublish_faq()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('faq_publishing')){
                $data['title']=$this->lang->line("text_unpublish_faq");
                $faq_id= $this->input->post('faq_id');
                $data['faq_id']=$faq_id;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/faqs/unpublish_faq',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
            echo json_encode($json_array);
            exit();
        }
    }

    //unpublish faq action
    public function unpublish_faq_action(){
        //check permission
        if($this->permitted('faq_publishing')){
            $faq_id = $this->input->post('faq_id');
            $update_data=array(
                'status'=>0,
                'updated_by' => $this->get_user_id()
            );
            //faq publishing
            $result = $this->faq_model->faq_publishing($faq_id,$update_data);
            if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                $success = TRUE;
                $message = $this->lang->line("alert_faq_unpublished");
            }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                $success = FALSE;
                $message = $this->lang->line("alert_faq_not_unpublished");
            }
        }else{
            $success = FALSE;
            $message = $this->lang->line("alert_access_denied");
        }
        $json_array = array('success' => $success, 'message' => $message);
        echo json_encode($json_array);
        exit();
    }

    //faq ordering - load view
    public function faq_ordering()
    {
        if($this->input->post()){
            //check permission
            if($this->permitted('faq_ordering')){
                $data['title']=$this->lang->line("text_faq_ordering");
                $category_id=$this->input->post('category_id');
                //get faqs by category
                $faqs = $this->faq_model->get_faqs_by_category($category_id);
                $data['faqs']=$faqs;
                if($faqs){
                    $listed=TRUE;
                }else{
                    $listed=FALSE;
                }
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/faqs/faq_ordering',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $listed=FALSE;
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content,'listed'=>$listed);
            echo json_encode($json_array);
            exit();
        }
    }

    //update faq ordering
    public function update_faq_ordering(){
        if($this->input->post()){
            //check permission
            if($this->permitted('faq_ordering')){
                $sorted_data=json_decode($_POST['sorted_data']);
                $sorted_data = $this->security->xss_clean($sorted_data);
                $result = $this->faq_model->update_faq_ordering($sorted_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_faq_order_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_faq_order_not_updated");
                }
            }else{
                $success = FALSE;
                $message = $this->lang->line("alert_access_denied");
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    

    //delete faq - load view
	public function delete_faq()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_faq')){
                $data['title']=$this->lang->line("text_delete_faq");
                $faq_id = $this->input->post('faq_id');
                $data['faq_id']=$faq_id;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/faqs/delete_faq',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
            echo json_encode($json_array);
            exit();
        }
    }

    //delete faq action
    public function delete_faq_action(){
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_faq')){
                $faq_id = $this->input->post('faq_id');
                //delete faq
                $result = $this->faq_model->delete_faq($faq_id);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_faq_deleted");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_faq_not_deleted");
                }
            }else{
                $success = FALSE;
                $message = $this->lang->line("alert_access_denied");
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
        
    }
    
    //list faq categories
    public function categories()
    {
        $data['title']=$this->lang->line("text_faq_categories");
        if($this->permitted('list_faq_categories')){
            $data['sub_view'] = $this->load->view('admin/faqs/list_categories', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data);
    }

    //ajax paginate categories
    public function list_categories_ajax($page=0){
        $conditions = array();
        // Row position
        if($page != 0){
            $page = ($page-1) * $this->perPage;
        }
        //get categories count
        $categories=$this->faq_model->get_categories($conditions);
        if($categories){
            $categoriesCount=count($categories);
        }else{
            $categoriesCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        //get all categories
        $categories = $this->faq_model->get_categories($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'faqs/list_categories_ajax',$total_rows=$categoriesCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['categories']=$categories;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('admin/faqs/list_categories_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //view faq category
	public function view_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('view_faq_category')){
                $data['title']=$this->lang->line("text_view_faq_category");
                $category_id = $this->input->post('category_id');
                //get faq category by id
                $category = $this->faq_model->get_category($category_id);
                $data['category']=$category;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/faqs/view_category',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
            echo json_encode($json_array);
            exit();
        }
    }

    //add faq category - load view
	public function add_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('add_faq_category')){
                $data['title']=$this->lang->line("text_add_faq_category");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/faqs/add_category',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
            echo json_encode($json_array);
            exit();
        }
    }

    //create faq category
    public function create_category(){
        if($this->input->post()){
            //check permission
            if($this->permitted('add_faq_category')){
                $this->form_validation->set_rules('title','Title','trim|required');
                $this->form_validation->set_rules('description','Description','trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
                }else{
                    $post_data = array(
                        'category_name' => $this->input->post('title'),
                        'category_description' => $this->input->post('description'),
                        'created_by' => $this->get_user_id(),
                        'updated_by' => $this->get_user_id(),
                    );
                    //XXS Clean
                    $post_data = $this->security->xss_clean($post_data);
                    $result = $this->faq_model->create_category($post_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_faq_category_created");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_faq_category_not_created");
                    }
                }
            }else{
                $success = FALSE;
                $message = $this->lang->line("alert_access_denied");
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    //edit faq category - load view
	public function edit_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_faq_category')){
                $data['title']=$this->lang->line("text_edit_faq_category");
                $category_id = $this->input->post('category_id');
                //get faq category by id
                $category = $this->faq_model->get_category($category_id);
                $data['category']=$category;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/faqs/edit_category',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
            echo json_encode($json_array);
            exit();
        }
    }

    //update faq category
    public function update_category(){
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_faq_category')){
                $this->form_validation->set_rules('title','Title','trim|required');
                $this->form_validation->set_rules('description','Description','trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
                }else{
                    $category_id = $this->input->post('category_id');
                    $update_data = array(
                        'category_name' => $this->input->post('title'),
                        'category_description' => $this->input->post('description'),
                        'updated_by' => $this->get_user_id(),
                    );
                    //XXS Clean
                    $update_data = $this->security->xss_clean($update_data);
                    $result = $this->faq_model->update_category($category_id,$update_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_faq_category_updated");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_faq_category_not_updated");
                    }
                }
            }else{
                $success = FALSE;
                $message = $this->lang->line("alert_access_denied");
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    //faq categories ordering - load view
    public function categories_ordering()
    {
        if($this->input->post()){
            //check permission
            if($this->permitted('faq_category_ordering')){
                $data['title']=$this->lang->line("text_categories_ordering");
                $categories = $this->faq_model->get_categories($conditions=array());
                $data['categories']=$categories;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/faqs/categories_ordering',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
            echo json_encode($json_array);
            exit();
        }
    }

    //update categories ordering
    public function update_categories_ordering(){
        if($this->input->post()){
            //check permission
            if($this->permitted('faq_category_ordering')){
                $sorted_data=json_decode($_POST['sorted_data']);
                $sorted_data = $this->security->xss_clean($sorted_data);
                $result = $this->faq_model->update_category_ordering($sorted_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_faq_category_order_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_faq_category_order_not_updated");
                }
            }else{
                $success = FALSE;
                $message = $this->lang->line("alert_access_denied");
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }
    
    //delete faq category - load view
	public function delete_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_faq_category')){
                $data['title']=$this->lang->line("text_delete_faq_category");
                $category_id = $this->input->post('category_id');
                //check faqs exist in category
                $faqs=$this->faq_model->get_faqs_by_category($category_id);
                if($faqs){
                    //if found need to transfer another category
                    $is_exist=TRUE;
                    //get another categories except this
                    $categories=$this->faq_model->get_categories_except($category_id);
                    $data['categories']=$categories;

                }else{
                    $is_exist=FALSE;
                }
                $data['is_exist']=$is_exist;
                $data['category_id']=$category_id;
                $success = TRUE;
                $message = '';
                $exist = $is_exist;
                $content = $this->load->view('admin/faqs/delete_category',$data,TRUE);
            }else{
                $data['title']=$this->lang->line("alert_access_denied");
                $success = TRUE;
                $message = '';
                $exist = FALSE;
                $content = $this->load->view('errors/permission/denied',$data,TRUE);
            }
            $json_array = array('success' => $success, 'message' => $message,'content'=>$content,'exist'=>$exist);
            echo json_encode($json_array);
            exit();
        }
    }

    //delete category action
    public function delete_category_action(){
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_faq_category')){
                $category_id = $this->input->post('category_id');
                $transfer_category = $this->input->post('transfer_category');
                $complete_delete = $this->input->post('complete_delete');
                //delete faq
                $result = $this->faq_model->delete_faq_category($category_id,$transfer_category,$complete_delete);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_faq_category_deleted");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_faq_category_not_deleted");
                }elseif($result['status']==FALSE &&$result['label']=='NOTEXIST'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_transfer_category_not_exist");
                }
            }else{
                $success = FALSE;
                $message = $this->lang->line("alert_access_denied");
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }
}