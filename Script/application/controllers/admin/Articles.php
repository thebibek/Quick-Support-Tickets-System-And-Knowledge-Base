<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articles extends MY_Controller {
    function __construct(){
        parent::__construct();
        //check if backend login
        $this->is_admin_login();
        //Load Libraries
        $this->load->library(array('pagination'));
        //pagination settings
        $this->perPage = 10;
		//load article model
		$this->load->model('article_model');
    }
    
    //list articles
	public function list_articles()
	{
        $data['title']=$this->lang->line("text_articles");
        if($this->permitted('list_articles')){
            //get all categories
            $categories = $this->article_model->get_categories($conditions=array());
            $data['categories']=$categories; 
            $data['sub_view'] = $this->load->view('admin/articles/list_articles', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data); 
    }

    //ajax paginate articles
    public function list_articles_ajax($page=0){
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
        //get articles count
        $articles=$this->article_model->get_articles($conditions);
        if($articles){
            $articlesCount=count($articles);
        }else{
            $articlesCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        //get all articles
        $articles = $this->article_model->get_articles($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'articles/list_articles_ajax',$total_rows=$articlesCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['articles']=$articles;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('admin/articles/list_articles_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //view article 
	public function view_article()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('view_article')){
                $data['title']=$this->lang->line("text_view_article");
                $article_id = $this->input->post('article_id');
                //get article by id
                $article = $this->article_model->get_article($article_id);
                $data['article']=$article;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/articles/view_article',$data,TRUE);
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

    //add article - load view
	public function add_article()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('add_article')){
                $data['title']=$this->lang->line("text_add_article");
                //get all categories
                $categories = $this->article_model->get_categories($conditions=array());
                $data['categories']=$categories;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/articles/add_article',$data,TRUE);
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

    //create article
    public function create_article(){
        if($this->input->post()){
            //check permission
            if($this->permitted('add_article')){
                $this->form_validation->set_rules('category','Category','trim|required');
                $this->form_validation->set_rules('title','Title','trim|required');
                $this->form_validation->set_rules('excerpt','Excerpt','trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
                }else{
                    $article_content=$this->generate_article_content($this->input->post('content'));
                    $post_data = array(
                        'category_id' => $this->input->post('category'),
                        'article_title' => $this->input->post('title'),
                        'article_excerpt' => $this->input->post('excerpt'),
                        'article_description' => $article_content,
                        'status' => (int)(bool)$this->input->post('publish'),
                        'created_by' => $this->get_user_id(),
                        'updated_by' => $this->get_user_id(),
                    );
                    //XXS Clean
                    $post_data = $this->security->xss_clean($post_data);
                    $result = $this->article_model->create_article($post_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_article_created");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_article_not_created");
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

    //edit article - load view
	public function edit_article()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_article')){
                $data['title']=$this->lang->line("text_edit_article");
                //get all categories
                $categories = $this->article_model->get_categories($conditions=array());
                $data['categories']=$categories;
                $article_id = $this->input->post('article_id');
                //get article by id
                $article = $this->article_model->get_article($article_id);
                $data['article']=$article;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/articles/edit_article',$data,TRUE);
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

    //update article
    public function update_article(){
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_article')){
                $this->form_validation->set_rules('category','Category','trim|required');
                $this->form_validation->set_rules('title','Title','trim|required');
                $this->form_validation->set_rules('excerpt','Excerpt','trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
                }else{
                    $article_id=$this->input->post('article_id');
                    $article_content=$this->generate_article_content($this->input->post('content'));
                    $update_data = array(
                        'category_id' => $this->input->post('category'),
                        'article_title' => $this->input->post('title'),
                        'article_excerpt' => $this->input->post('excerpt'),
                        'article_description' => $article_content,
                        'updated_by' => $this->get_user_id(),
                    );
                    //XXS Clean
                    $update_data = $this->security->xss_clean($update_data);
                    $result = $this->article_model->update_article($article_id,$update_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_article_updated");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_article_not_updated");
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

    //publish article - load view
	public function publish_article()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('publishing_article')){
                $data['title']=$this->lang->line("text_publish_article");
                $article_id= $this->input->post('article_id');
                $data['article_id']=$article_id;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/articles/publish_article',$data,TRUE);
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

    //publish article action
    public function publish_article_action(){
        //check permission
        if($this->permitted('publishing_article')){
            $article_id= $this->input->post('article_id');
            $update_data=array(
                'status'=>1,
                'updated_by' => $this->get_user_id()
            );
            //article publishing
            $result = $this->article_model->article_publishing($article_id,$update_data);
            if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                $success = TRUE;
                $message = $this->lang->line("alert_article_published");
            }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                $success = FALSE;
                $message = $this->lang->line("alert_article_not_published");
            }
        }else{
            $success = FALSE;
            $message = $this->lang->line("alert_access_denied");
        }
        $json_array = array('success' => $success, 'message' => $message);
        echo json_encode($json_array);
        exit();
    }

    //unpublish article- load view
	public function unpublish_article()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('publishing_article')){
                $data['title']=$this->lang->line("text_unpublish_article");
                $article_id= $this->input->post('article_id');
                $data['article_id']=$article_id;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/articles/unpublish_article',$data,TRUE);
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

    //unpublish article action
    public function unpublish_article_action(){
        //check permission
        if($this->permitted('publishing_article')){
            $article_id= $this->input->post('article_id');
            $update_data=array(
                'status'=>0,
                'updated_by' => $this->get_user_id()
            );
            //article publishing
            $result = $this->article_model->article_publishing($article_id,$update_data);
            if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                $success = TRUE;
                $message = $this->lang->line("alert_article_unpublished");
            }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                $success = FALSE;
                $message = $this->lang->line("alert_article_not_unpublished");
            }
        }else{
            $success = FALSE;
            $message = $this->lang->line("alert_access_denied");
        }
        $json_array = array('success' => $success, 'message' => $message);
        echo json_encode($json_array);
        exit();
    }

    //article ordering - load view
    public function article_ordering()
    {
        if($this->input->post()){
            //check permission
            if($this->permitted('ordering_article')){
                $data['title']=$this->lang->line("text_article_ordering");
                $category_id=$this->input->post('category_id');
                //get articles by category
                $articles = $this->article_model->get_articles_by_category($category_id);
                $data['articles']=$articles;
                if($articles){
                    $listed=TRUE;
                }else{
                    $listed=FALSE;
                }
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/articles/article_ordering',$data,TRUE);
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

    //update article ordering
    public function update_article_ordering(){
        if($this->input->post()){
            //check permission
            if($this->permitted('ordering_article')){
                $sorted_data=json_decode($_POST['sorted_data']);
                $sorted_data = $this->security->xss_clean($sorted_data);
                $result = $this->article_model->update_article_ordering($sorted_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_article_order_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_article_order_not_updated");
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

    //delete article - load view
	public function delete_article()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_article')){
                $data['title']=$this->lang->line("text_delete_article");
                $article_id = $this->input->post('article_id');
                $data['article_id']=$article_id;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/articles/delete_article',$data,TRUE);
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

    //delete article action
    public function delete_article_action(){
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_article')){
                $article_id = $this->input->post('article_id');
                //delete article
                $result = $this->article_model->delete_article($article_id);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_article_deleted");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_article_not_deleted");
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
    
    
    
    //list article categories
    public function categories()
    {
        $data['title']=$this->lang->line("text_article_categories");
        if($this->permitted('list_article_categories')){
            $data['sub_view'] = $this->load->view('admin/articles/list_categories', $data, TRUE);
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
        $categories=$this->article_model->get_categories($conditions);
        if($categories){
            $categoriesCount=count($categories);
        }else{
            $categoriesCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        //get all categories
        $categories = $this->article_model->get_categories($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'articles/list_categories_ajax',$total_rows=$categoriesCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['categories']=$categories;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('admin/articles/list_categories_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //view article category
	public function view_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('view_article_category')){
                $data['title']=$this->lang->line("text_view_article_category");
                $category_id = $this->input->post('category_id');
                //get article category by id
                $category = $this->article_model->get_category($category_id);
                $data['category']=$category;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/articles/view_category',$data,TRUE);
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

    //add article category - load view
	public function add_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('add_article_category')){
                $data['title']=$this->lang->line("text_add_article_category");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/articles/add_category',$data,TRUE);
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

    //create article category
    public function create_category(){
        if($this->input->post()){
            //check permission
            if($this->permitted('add_article_category')){
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
                    //upload config
                    $config['upload_path'] = 'uploads/categories/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['encrypt_name'] = TRUE;
                    $config['overwrite'] = TRUE;
                    $config['max_size'] = '1024'; //1 MB
                    //Upload Category Icon
                    if(isset($_FILES['category_icon']['name'])){
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('category_icon')) {
                            $success = FALSE;
                            $message = $this->upload->display_errors();
                            $json_array = array('success' => $success, 'message' => $message);
                            echo json_encode($json_array);
                            exit();
                        } else {
                            $upload_data=$this->upload->data();
                            $post_data['category_icon']=$upload_data['file_name'];
                        }
                    }
                    //XXS Clean
                    $post_data = $this->security->xss_clean($post_data);
                    $result = $this->article_model->create_category($post_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_article_category_created");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_article_category_not_created");
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

    //edit article category - load view
	public function edit_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_article_category')){
                $data['title']=$this->lang->line("text_edit_article_category");
                $category_id = $this->input->post('category_id');
                //get article category by id
                $category = $this->article_model->get_category($category_id);
                $data['category']=$category;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/articles/edit_category',$data,TRUE);
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

    //update article category
    public function update_category(){
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_article_category')){
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
                    //upload config
                    $config['upload_path'] = 'uploads/categories/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['encrypt_name'] = TRUE;
                    $config['overwrite'] = TRUE;
                    $config['max_size'] = '1024'; //1 MB
                    //Upload Category Icon
                    if(isset($_FILES['category_icon']['name'])){
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('category_icon')) {
                            $success = FALSE;
                            $message = $this->upload->display_errors();
                            $json_array = array('success' => $success, 'message' => $message);
                            echo json_encode($json_array);
                            exit();
                        } else {
                            $upload_data=$this->upload->data();
                            $update_data['category_icon']=$upload_data['file_name'];
                        }
                    }
                    //XXS Clean
                    $update_data = $this->security->xss_clean($update_data);
                    $result = $this->article_model->update_category($category_id,$update_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_article_category_updated");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_article_category_not_updated");
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

    //article categories ordering - load view
    public function categories_ordering()
    {
        if($this->input->post()){
            //check permission
            if($this->permitted('ordering_article_category')){
                $data['title']=$this->lang->line("text_categories_ordering");
                $categories = $this->article_model->get_categories($conditions=array());
                $data['categories']=$categories;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/articles/categories_ordering',$data,TRUE);
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
            if($this->permitted('ordering_article_category')){
                $sorted_data=json_decode($_POST['sorted_data']);
                $sorted_data = $this->security->xss_clean($sorted_data);
                $result = $this->article_model->update_category_ordering($sorted_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_article_category_ordering_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_article_category_ordering_not_updated");
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

    //delete article category - load view
	public function delete_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_article_category')){
                $data['title']=$this->lang->line("text_delete_article_category");
                $category_id = $this->input->post('category_id');
                //check articles exist in category
                $articles=$this->article_model->get_articles_by_category($category_id);
                if($articles){
                    //if found need to transfer another category
                    $is_exist=TRUE;
                    //get another categories except this
                    $categories=$this->article_model->get_categories_except($category_id);
                    $data['categories']=$categories;

                }else{
                    $is_exist=FALSE;
                }
                $data['is_exist']=$is_exist;
                $data['category_id']=$category_id;
                $success = TRUE;
                $message = '';
                $exist = $is_exist;
                $content = $this->load->view('admin/articles/delete_category',$data,TRUE);
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
            if($this->permitted('delete_article_category')){
                $category_id = $this->input->post('category_id');
                $transfer_category = $this->input->post('transfer_category');
                $complete_delete = $this->input->post('complete_delete');
                //delete article
                $result = $this->article_model->delete_article_category($category_id,$transfer_category,$complete_delete);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_article_category_deleted");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_article_category_not_deleted");
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