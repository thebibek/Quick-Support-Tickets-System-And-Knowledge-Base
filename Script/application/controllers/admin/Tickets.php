<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tickets extends MY_Controller {
    function __construct(){
        parent::__construct();
        //check if backend login
        $this->is_admin_login();
        //Load Libraries
        $this->load->library(array('pagination'));
        //pagination settings
        $this->perPage = 10;
		//load models
        $this->load->model(array('ticket_model','user_model'));
        
    }
    //list tickets
	public function list_tickets()
	{
        $data['title']=$this->lang->line("text_tickets");
        if($this->permitted('list_tickets')){
            //get all categories
            $categories = $this->ticket_model->get_categories($conditions=array());
            $data['categories']=$categories;
            $data['sub_view'] = $this->load->view('admin/tickets/list_tickets', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data); 
    }

    //list tickets ajax
    public function list_tickets_ajax($page=0){
        $conditions = array();
        // Row position
        if($page != 0){
            $page = ($page-1) * $this->perPage;
        }
        $keyword=$this->input->post('keyword');
        $category=$this->input->post('category');
        $priority=$this->input->post('priority');
        $status=$this->input->post('status');
        $ticket_type=$this->input->post('ticket_type');
        if(!empty($keyword)){
            $conditions['search']['keyword'] = $keyword;
        }
        if(!empty($category)){
            $conditions['search']['category'] = $category;
        }
        if(!empty($priority)){
            $conditions['search']['priority'] = $priority;
        }
        if(!empty($status)){
            $conditions['search']['status'] = $status;
        }
        if(!empty($ticket_type)){
            $conditions['search']['ticket_type'] = $ticket_type;
        }
        $conditions['search']['user_type'] = $this->get_user_type();
        $conditions['search']['user_id'] = $this->get_user_id();
        //get tickets count
        $tickets=$this->ticket_model->get_tickets($conditions);
        if($tickets){
            $ticketsCount=count($tickets);
        }else{
            $ticketsCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        //get all tickets
        $tickets = $this->ticket_model->get_tickets($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'admin/tickets/list_tickets_ajax',$total_rows=$ticketsCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['tickets']=$tickets;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('admin/tickets/list_tickets_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //view ticket
	public function view_ticket($id=null)
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('view_reply_ticket')){
                $data['title']=$this->lang->line("text_view_ticket");
                $ticket_id = $this->input->post('ticket_id');
                //get ticket by id
                $ticket = $this->ticket_model->get_ticket($ticket_id);
                $data['ticket']=$ticket;
                //get ticket replies
                $replies = $this->ticket_model->get_replies($ticket_id);
                $data['replies']=$replies;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/tickets/view_ticket',$data,TRUE);
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

    //reply to ticket
	public function reply_to_ticket()
	{
		if($this->input->post()){
            //check permission
            if($this->permitted('view_reply_ticket')){
                $this->form_validation->set_rules('reply_content','Reply','trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
                }else{
                    $post_data = array(
                        'ticket_id' => $this->input->post('ticket_id'),
                        'reply_content' => $this->input->post('reply_content'),
                        'created_by' => $this->get_user_id(),
                        'updated_by' => $this->get_user_id(),
                    );
                    //upload config
                    $config['upload_path'] = 'uploads/tickets/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|doc|docx|pdf';
                    $config['encrypt_name'] = TRUE;
                    $config['overwrite'] = TRUE;
                    $config['max_size'] = '2048'; //2 MB
                    //Upload Ticket File
                    if(isset($_FILES['reply_file']['name'])){
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('reply_file')) {
                            $success = FALSE;
                            $message = $this->upload->display_errors();
                            $json_array = array('success' => $success, 'message' => $message);
                            echo json_encode($json_array);
                            exit();
                        } else {
                            $upload_data=$this->upload->data();
                            $post_data['reply_file']=$upload_data['file_name'];
                        }
                    }

                    //XXS Clean
                    $post_data = $this->security->xss_clean($post_data);
                    $result = $this->ticket_model->reply_ticket($post_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        try{
                            $ticket_id = $this->input->post('ticket_id');
                            //get ticket by id
                            $ticket = $this->ticket_model->get_ticket($ticket_id);
                            if($ticket['email']==NULL){
                                $to=$ticket['client_email'];
                                $full_name=$ticket['client_name'];
                            }else{
                                $to=$ticket['email'];
                                $full_name=$ticket['full_name'];
                            }
                            $data_set=array(
                                'fullname'=>$full_name,
                                'ticket_title'=>$ticket['ticket_title'],
                                'reply_content'=>$post_data['reply_content'],
                            );
                            $email_content=$this->generate_email('reply_ticket',$data_set);
                            $subject=$this->get_email_subject($slug='reply_ticket');
                            @$this->sendEmail($to, $subject, $email_content);
                        }catch(Exception $e){
    
                        }
                        $success = TRUE;
                        $message = $this->lang->line("alert_replied_to_ticket");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_not_replied_to_ticket");
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

    //assign tiket
	public function assign_ticket()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('assign_ticket')){
                $data['title']=$this->lang->line("text_assign_ticket");
                $ticket_id = $this->input->post('ticket_id');
                $data['ticket_id']=$ticket_id;
                //get non customer users
                $users = $this->user_model->get_non_customer_users();
                $data['users']=$users;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/tickets/assign_ticket',$data,TRUE);
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

    //assign ticket - action
    public function assign_ticket_action(){
        if($this->input->post()){
            //check permission
            if($this->permitted('assign_ticket')){
                $this->form_validation->set_rules('assign_to','User','trim|required');
                $this->form_validation->set_rules('priority','Priority','trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
                }else{
                    $ticket_id = $this->input->post('ticket_id');
                    $update_data = array(
                        'assigned_to' => $this->input->post('assign_to'),
                        'priority' => $this->input->post('priority'),
                        'status' => 1,
                        'updated_by' => $this->get_user_id(),
                    );
                    //XXS Clean
                    $update_data = $this->security->xss_clean($update_data);
                    $result = $this->ticket_model->assign_ticket($ticket_id,$update_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        try{
                            //send mail if "Email Notify When Ticket Assigned" is enabled
                            $email_notify_assign_ticket=$this->load->get_var('email_notify_assign_ticket');
                            if($email_notify_assign_ticket==1 || $email_notify_assign_ticket=="1"){
                                $ticket=$this->ticket_model->get_ticket($ticket_id);
                                $user_data=$this->load->get_var('user_data');
                                $to=$user_data['email'];
                                $full_name=$user_data['full_name'];
                                $data_set=array(
                                    'fullname'=>$full_name,
                                    'ticket_title'=>$ticket['ticket_title'],
                                    'ticket_description'=>$ticket['ticket_description'],
                                );
                                $email_content=$this->generate_email('assign_ticket',$data_set);
                                $subject=$this->get_email_subject($slug='assign_ticket');
                                @$this->sendEmail($to, $subject, $email_content);
                            }
                            
                        }catch(Exception $e){

                        }
                        $success = TRUE;
                        $message = $this->lang->line("alert_ticket_assigned");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_ticket_not_assigned");
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

    //mark ticket as completed 
    public function mark_ticket_completed(){
        if($this->input->post()){
            //check permission
            if($this->permitted('ticket_completion')){
                $data['title']=$this->lang->line("text_mark_ticket_completed");
                $ticket_id = $this->input->post('ticket_id');
                $data['ticket_id']=$ticket_id;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/tickets/ticket_completed',$data,TRUE);
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

    //mark ticket as completed - action
    public function mark_ticket_completed_action(){
        if($this->input->post()){
            //check permission
            if($this->permitted('ticket_completion')){
                $ticket_id = $this->input->post('ticket_id');
                $update_data = array(
                    'status' => 2,
                    'updated_by' => $this->get_user_id(),
                );
                //XXS Clean
                $update_data = $this->security->xss_clean($update_data);
                $result = $this->ticket_model->mark_completed($ticket_id,$update_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_marked_completed");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_not_marked_completed");
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

    //mark ticket as incompleted 
    public function mark_ticket_incompleted(){
        if($this->input->post()){
            //check permission
            if($this->permitted('ticket_completion')){
                $data['title']=$this->lang->line("text_mark_ticket_incompleted");
                $ticket_id = $this->input->post('ticket_id');
                $data['ticket_id']=$ticket_id;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/tickets/ticket_incompleted',$data,TRUE);
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

    //mark ticket as incompleted - action
    public function mark_ticket_incompleted_action(){
        if($this->input->post()){
            //check permission
            if($this->permitted('ticket_completion')){
                $ticket_id = $this->input->post('ticket_id');
                $update_data = array(
                    'status' => 1,
                    'updated_by' => $this->get_user_id(),
                );
                //XXS Clean
                $update_data = $this->security->xss_clean($update_data);
                $result = $this->ticket_model->mark_completed($ticket_id,$update_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_marked_incompleted");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_not_marked_incompleted");
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

    //delete ticket
	public function delete_ticket()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_ticket')){
                $data['title']=$this->lang->line("text_delete_ticket");
                $ticket_id = $this->input->post('ticket_id');
                $data['ticket_id']=$ticket_id;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/tickets/delete_ticket',$data,TRUE);
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

    //delete ticket action
    public function delete_ticket_action(){
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_ticket')){
                $ticket_id = $this->input->post('ticket_id');
                //delete ticket
                $result = $this->ticket_model->delete_ticket($ticket_id);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_ticket_deleted");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_ticket_not_deleted");
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

    //list ticket categories
    public function categories()
    {
        $data['title']=$this->lang->line("text_ticket_categories");
        if($this->permitted('list_ticket_categories')){
            $data['sub_view'] = $this->load->view('admin/tickets/list_categories', $data, TRUE);
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
        $categories=$this->ticket_model->get_categories($conditions);
        if($categories){
            $categoriesCount=count($categories);
        }else{
            $categoriesCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        //get all categories
        $categories = $this->ticket_model->get_categories($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'tickets/list_categories_ajax',$total_rows=$categoriesCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['categories']=$categories;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('admin/tickets/list_categories_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //view ticket category
	public function view_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('view_ticket_category')){
                $data['title']=$this->lang->line("text_view_ticket_category");
                $category_id = $this->input->post('category_id');
                //get ticket category by id
                $category = $this->ticket_model->get_category($category_id);
                $data['category']=$category;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/tickets/view_category',$data,TRUE);
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

    //add ticket category
	public function add_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('add_ticket_category')){
                $data['title']=$this->lang->line("text_view_ticket_category");
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/tickets/add_category',$data,TRUE);
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

    //create ticket category
    public function create_category(){
        if($this->input->post()){
            //check permission
            if($this->permitted('add_ticket_category')){
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
                    $result = $this->ticket_model->create_category($post_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_ticket_category_created");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_ticket_category_not_created");
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
    
    //edit ticket category - load view
	public function edit_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('text_edit_ticket_category')){
                $data['title']=$this->lang->line("alert_access_denied");
                $category_id = $this->input->post('category_id');
                //get ticket category by id
                $category = $this->ticket_model->get_category($category_id);
                $data['category']=$category;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/tickets/edit_category',$data,TRUE);
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

    //update ticket category
    public function update_category(){
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_ticket_category')){
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
                    $result = $this->ticket_model->update_category($category_id,$update_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_ticket_category_updated");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_ticket_category_not_updated");
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

    //ticket categories ordering
    public function categories_ordering()
    {
        if($this->input->post()){
            //check permission
            if($this->permitted('ticket_category_ordering')){
                $data['title']=$this->lang->line("text_categories_ordering");
                $categories = $this->ticket_model->get_categories($conditions=array());
                $data['categories']=$categories;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/tickets/categories_ordering',$data,TRUE);
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
            if($this->permitted('ticket_category_ordering')){
                $sorted_data=json_decode($_POST['sorted_data']);
                $sorted_data = $this->security->xss_clean($sorted_data);
                $result = $this->ticket_model->update_category_ordering($sorted_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_ticket_category_order_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_ticket_category_order_not_updated");
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
    //delete ticket category
	public function delete_category()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_ticket_category')){
                $data['title']=$this->lang->line("text_delete_ticket_category");
                $category_id = $this->input->post('category_id');
                //check tickets exist in category
                $tickets=$this->ticket_model->get_tickets_by_category($category_id);
                if($tickets){
                    //if found need to transfer another category
                    $is_exist=TRUE;
                    //get another categories except this
                    $categories=$this->ticket_model->get_categories_except($category_id);
                    $data['categories']=$categories;

                }else{
                    $is_exist=FALSE;
                }
                $data['is_exist']=$is_exist;
                $data['category_id']=$category_id;
                $success = TRUE;
                $message = '';
                $exist = $is_exist;
                $content = $this->load->view('admin/tickets/delete_category',$data,TRUE);
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
            if($this->permitted('delete_ticket_category')){
                $category_id = $this->input->post('category_id');
                $transfer_category = $this->input->post('transfer_category');
                $complete_delete = $this->input->post('complete_delete');
                //delete ticket
                $result = $this->ticket_model->delete_ticket_category($category_id,$transfer_category,$complete_delete);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_ticket_category_deleted");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_ticket_category_not_deleted");
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