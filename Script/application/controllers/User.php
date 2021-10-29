<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	public function __construct()
	{
        parent::__construct();
        //check if front end login
        $this->is_site_login();
		//Load Libraries
        $this->load->library(array('pagination'));
        //pagination settings
        $this->perPage = 10;
		//load models
		$this->load->model(array('user_model','ticket_model'));
		
    }

    //dashboard
    public function index(){
        $data['title']=$this->lang->line("text_dashboard");
        //get user by id
        $user_id=$this->get_user_id();
        $user = $this->user_model->get_user($user_id);
        $data['user']=$user;
        //get user dashboard values
        $dashboard_data = $this->user_model->get_dashboard_data($user_id);
        $data['dashboard_data']=$dashboard_data;
        $data['sub_view'] = $this->load->view('site/user/dashboard', $data, TRUE);
        $this->load->view('site/_layout', $data);
    }

    //tickets
    public function tickets(){
        $data['title']=$this->lang->line("text_tickets");
        //get user by id
        $user_id=$this->get_user_id();
        $user = $this->user_model->get_user($user_id);
        $data['user']=$user;
        //get tickets categories
        $categories = $this->ticket_model->get_categories($conditions=array());
        $data['categories']=$categories;
        $data['sub_view'] = $this->load->view('site/user/tickets', $data, TRUE);
        $this->load->view('site/_layout', $data);
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
        $conditions['search']['keyword'] = $keyword;
        $conditions['search']['category'] = $category;
        $conditions['search']['priority'] = $priority;
        $conditions['search']['status'] = $status;
        $conditions['search']['ticket_type'] = '';
        
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
        $config=$this->pagination_config($base_url=base_url().'tickets/list_tickets_ajax',$total_rows=$ticketsCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['tickets']=$tickets;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('site/user/list_tickets_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //submit ticket
    public function submit_ticket(){
        if($this->input->post()){
            //form validation
             $this->form_validation->set_rules('ticket_title','Ticket Title','trim|required');
             $this->form_validation->set_rules('ticket_description','Ticket Description','trim|required');
             $this->form_validation->set_rules('category','Ticket Category','trim|required');
             $this->form_validation->set_rules('priority','Ticket Priority','trim|required');
            if ($this->form_validation->run() == FALSE) {
                $success = FALSE;
                $message = validation_errors();
            }else{
                $post_data = array(
                    'ticket_title' => $this->input->post('ticket_title'),
                    'ticket_description' => $this->input->post('ticket_description'),
                    'category_id' => $this->input->post('category'),
                    'priority' => $this->input->post('priority'),
                    'status' => 0,
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
                if(isset($_FILES['attachment']['name'])){
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('attachment')) {
                        $success = FALSE;
                        $message = $this->upload->display_errors();
                        $json_array = array('success' => $success, 'message' => $message);
                        echo json_encode($json_array);
                        exit();
                    } else {
                        $upload_data=$this->upload->data();
                        $post_data['ticket_file']=$upload_data['file_name'];
                    }
                }

                //XXS Clean
                $post_data = $this->security->xss_clean($post_data);
                $result = $this->ticket_model->create_ticket($post_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    try{
						//Send Mail if Settings enabled
						$email_notify_new_ticket=$this->load->get_var('email_notify_new_ticket');
						if($email_notify_new_ticket==1 || $email_notify_new_ticket=="1"){
                            //get user by id
                            $user_id=$this->get_user_id();
                            $user = $this->user_model->get_user($user_id);
							$to=$this->load->get_var('site_email');
                            $site_name=$this->load->get_var('site_name');
                            $data_set=array(
								'sitename'=>$site_name,
								'fullname'=>$user['full_name'],
								'ticket_title'=>$this->input->post('ticket_title'),
								'ticket_description'=>$this->input->post('ticket_description'),
							);
							$email_content=$this->generate_email('new_ticket',$data_set);
							$subject=$this->get_email_subject($slug='new_ticket');
							@$this->sendEmail($to, $subject, $email_content);
						}
						
					}catch(Exception $e){

					}
                    $success = TRUE;
                    $message = $this->lang->line("alert_ticket_created");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_ticket_not_created");
                }
            }

            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
        $data['title']=$this->lang->line("text_submit_ticket");
        //get user by id
        $user_id=$this->get_user_id();
        $user = $this->user_model->get_user($user_id);
        $data['user']=$user;
        //get tickets categories
        $categories = $this->ticket_model->get_categories($conditions=array());
        $data['categories']=$categories;
        $data['sub_view'] = $this->load->view('site/user/add_ticket', $data, TRUE);
        $this->load->view('site/_layout', $data);
    }

    //veiw ticket
    public function view_ticket($ticket_id=null){
        $data['title']=$this->lang->line("text_view_ticket");
        if($ticket_id!=NULL){
            //get user by id
            $user_id=$this->get_user_id();
            //get user ticket by id
            $ticket = $this->ticket_model->get_user_ticket($ticket_id,$user_id);
            if($ticket){
                $data['ticket']=$ticket;
                //get ticket replies
                $replies = $this->ticket_model->get_replies($ticket_id);
                $data['replies']=$replies;
                //get user by id
                $user = $this->user_model->get_user($user_id);
                $data['user']=$user;
                //load view
                $data['sub_view'] = $this->load->view('site/user/view_ticket', $data, TRUE);
                $this->load->view('site/_layout', $data);
            }else{
                //if ticket id goes wrong
                redirect('/user/tickets', 'refresh');
            }
        }else{
            //if ticket id goes empty
            redirect('/user/tickets', 'refresh');
        }
    }

    //reply to ticket
    public function reply_to_ticket(){
        if($this->input->post()){

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
                    //email reply 
                    try{
                        $ticket_id = $this->input->post('ticket_id');
                        //get ticket by id
                        $ticket = $this->ticket_model->get_ticket($ticket_id);
                        if($ticket['assigned_user_email']==NULL){
                            $to=$this->load->get_var('site_email');
                            $full_name=$this->load->get_var('site_name');
                        }else{
                            $to=$ticket['assigned_user_email'];
                            $full_name=$ticket['assigned_user'];
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
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    //delete ticket
    public function delete_ticket(){
        if($this->input->post()){
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
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    //profile
    public function profile(){
        $data['title']=$this->lang->line("text_profile");
        //get user by id
        $user_id=$this->get_user_id();
        $user = $this->user_model->get_user($user_id);
        $data['user']=$user;
        $data['sub_view'] = $this->load->view('site/user/profile', $data, TRUE);
        $this->load->view('site/_layout', $data);
    }

    //update profile
    public function update_profile(){
        if($this->input->post()){
            $this->form_validation->set_rules('full_name','Full Name','trim|required' );
            $this->form_validation->set_rules('email','Email','trim|required');
            $this->form_validation->set_rules('mobile','Mobile','trim|required');

            if ($this->form_validation->run() == FALSE) {
                $success = FALSE;
                $message = validation_errors();

            }else{
                $photo = $this->input->post('profile_image');
                $user_id=$this->get_user_id();
                $update_data = array(
                    'full_name' => $this->input->post('full_name'),
                    'email' => $this->input->post('email'),
                    'mobile' => $this->input->post('mobile'),
                    'updated_by' => $user_id,
                );
                //XXS Clean
                $update_data = $this->security->xss_clean($update_data);
                $result = $this->user_model->update_profile($user_id,$update_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_profile_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_profile_not_updated");
                }elseif($result['status']==FALSE &&$result['label']=='EXIST'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_profile_email_exist");
                }
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    //update profile image
    public function update_profile_image(){
        if($this->input->post()){
            $photo = $this->input->post('profile_image');
            if ($photo != NULL) {
                define('UPLOAD_DIR', 'uploads/profile/');
                $img = $photo;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $image_data = base64_decode($img);
                $file = UPLOAD_DIR . uniqid() . '.png';
                file_put_contents($file, $image_data);
                $profile_image = $file;

            }else{
                $profile_image = null;
            }
            $user_id=$this->get_user_id();
            $update_data = array(
                'profile_image' => $profile_image,
                'updated_by' => $user_id,
            );
            $result = $this->user_model->update_user($user_id,$update_data);
            if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                $success = TRUE;
                $message = $this->lang->line("alert_profile_image_updated");
            }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                $success = FALSE;
                $message = $this->lang->line("alert_profile_image_not_updated");
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
    }

    //change password
    public function change_password(){
        if($this->input->post()){
            $this->form_validation->set_rules('old_password','Old Password','trim|required' );
            $this->form_validation->set_rules('new_password','New Password','trim|required');
            $this->form_validation->set_rules('confirm_new_password','Confirm New Password','trim|required');

            if ($this->form_validation->run() == FALSE) {
                $success = FALSE;
                $message = validation_errors();

            }else{
                $user_id=$this->get_user_id();
                $old_password=$this->input->post('old_password');
                $new_password=$this->input->post('new_password');
                $result = $this->user_model->change_password($user_id,$old_password,$new_password);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_password_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_password_not_updated");
                }elseif($result['status']==FALSE &&$result['label']=='INVALID'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_old_password_incorrect");
                }
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
        }
        $data['title']=$this->lang->line("text_change_password");
        //get user by id
        $user_id=$this->get_user_id();
        $user = $this->user_model->get_user($user_id);
        $data['user']=$user;
        $data['sub_view'] = $this->load->view('site/user/change_password', $data, TRUE);
        $this->load->view('site/_layout', $data);
    }

    //article voting
    public function article_voting(){
        if($this->input->post()){
            $article_id = $this->input->post('article_id');
            $vote_status = $this->input->post('vote_status');
            $user_id=$this->get_user_id();
            if($vote_status=='Y'){
                $status=1;
            }elseif($vote_status=='N'){
                $status=0;
            }
            $result = $this->user_model->article_voting($user_id,$article_id,$status);
            if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                $success = TRUE;
                $message = $this->lang->line("alert_vote_success");
                $total_votes = $result['total_votes'];
                $up_votes = $result['up_votes'];
            }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                $success = FALSE;
                $message = $this->lang->line("alert_vote_error");
                $total_votes = $result['total_votes'];
                $up_votes = $result['up_votes'];
            }
            $json_array = array('success' => $success, 'message' => $message, 'total_votes'=>$total_votes, 'up_votes'=>$up_votes);
            echo json_encode($json_array);
            exit();
        }
        
    }
}