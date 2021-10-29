<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {
    public function __construct()
	{
        parent::__construct();
        //check if backend login
        $this->is_admin_login();
        //Load Libraries
        $this->load->library(array('pagination'));
        //pagination settings
        $this->perPage = 10;
		//load models
		$this->load->model(array('user_model','settings_model'));
		
	}
    //list users
	public function list_users()
	{
        $data['title']=$this->lang->line("text_users");
        if($this->permitted('list_users')){
            //get all user types
            $user_types = $this->user_model->get_user_types();
            $data['user_types']=$user_types;
            $data['sub_view'] = $this->load->view('admin/users/list_users', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data); 
    }

    //ajax paginate users
    public function list_users_ajax($page=0){
        $conditions = array();
        // Row position
        if($page != 0){
            $page = ($page-1) * $this->perPage;
        }
        $keyword=$this->input->post('keyword');
        $user_type=$this->input->post('user_type');
        $status=$this->input->post('status');
        if(!empty($keyword)){
            $conditions['search']['keyword'] = $keyword;
        }
        if(!empty($user_type)){
            $conditions['search']['user_type'] = $user_type;
        }
        if(!empty($status)){
            $conditions['search']['status'] = $status;
        }
        $conditions['exclued']=$this->get_user_id();
        //get users count
        $users=$this->user_model->get_users($conditions);
        if($users){
            $usersCount=count($users);
        }else{
            $usersCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        //get all users
        $users = $this->user_model->get_users($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'users/list_users_ajax',$total_rows=$usersCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['users']=$users;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('admin/users/list_users_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

    //view user
	public function view_user()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('view_user')){
                $data['title']=$this->lang->line("text_view_user");
                $user_id = $this->input->post('user_id');
                //get user by id
                $user = $this->user_model->get_user($user_id);
                $data['user']=$user;
                $success = true;
                $message = '';
                $content = $this->load->view('admin/users/view_user',$data,TRUE);
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

    //add user - load view
	public function add_user()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('add_user')){
                $data['title']=$this->lang->line("text_add_user");
                //get non admin user types
                $user_types = $this->user_model->get_non_admin_user_types();
                $data['user_types']=$user_types;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/users/add_user',$data,TRUE);
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

    //create user
    public function create_user(){
        if($this->input->post()){
            //check permission
            if($this->permitted('add_user')){
                $this->form_validation->set_rules('user_type','User Type','trim|required');
                $this->form_validation->set_rules('full_name','Full Name','trim|required');
                $this->form_validation->set_rules('email','Email','trim|required|valid_email' );
                $this->form_validation->set_rules('password','password','trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
                }else{
                    $post_data = array(
                        'user_role_id' => $this->input->post('user_type'),
                        'full_name' => $this->input->post('full_name'),
                        'email' => $this->input->post('email'),
                        'password' =>password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                        'status' => 1,
                        'created_by' => $this->get_user_id(),
                        'updated_by' => $this->get_user_id(),
                    );
                    //XXS Clean
                    $post_data = $this->security->xss_clean($post_data);
                    $result = $this->user_model->create_user($post_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_user_created");
                        try{
                            //Send Mail if Settings enabled
                            $send_password_created_new_user=$this->load->get_var('send_password_created_new_user');
                            if($send_password_created_new_user==1 || $send_password_created_new_user=="1"){
                                $to=$this->input->post('email');
                                $site_name=$this->load->get_var('site_name');
                                $data_set=array(
                                    'fullname'=>$this->input->post('full_name'),
                                    'sitename'=>$site_name,
                                    'password'=>$this->input->post('password'),
                                );
                                $email_content=$this->generate_email('user_creation',$data_set);
                                $subject=$this->get_email_subject($slug='user_creation');
                                @$this->sendEmail($to, $subject, $email_content);
                            }
                            
                        }catch(Exception $e){

                        }


                    }elseif($result['status']==FALSE &&$result['label']=='EXIST'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_user_exist");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_user_not_created");
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

    //edit user - load view
	public function edit_user()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_user')){
                $data['title']=$this->lang->line("text_edit_user");
                $user_id = $this->input->post('user_id');
                //get non admin user types
                $user_types = $this->user_model->get_non_admin_user_types();
                $data['user_types']=$user_types;
                //get user by id
                $user = $this->user_model->get_user($user_id);
                $data['user']=$user;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/users/edit_user',$data,TRUE);
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

    //update user
    public function update_user(){
        if($this->input->post()){
            //check permission
            if($this->permitted('edit_user')){
                $this->form_validation->set_rules('user_type','User Type','trim|required');
                $this->form_validation->set_rules('full_name','Full Name','trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $success = FALSE;
                    $message = validation_errors();
                }else{
                    $user_id=$this->input->post('user_id');
                    $update_data = array(
                        'user_role_id' => $this->input->post('user_type'),
                        'full_name' => $this->input->post('full_name'),
                        'updated_by' => $this->get_user_id(),
                    );
                    //XXS Clean
                    $update_data = $this->security->xss_clean($update_data);
                    $result = $this->user_model->update_user($user_id,$update_data);
                    if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                        $success = TRUE;
                        $message = $this->lang->line("alert_user_updated");
                    }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                        $success = FALSE;
                        $message = $this->lang->line("alert_user_not_updated");
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

    //block user - load view
    public function block_user(){
        //check permission
        if($this->permitted('user_blocking')){
            $data['title']=$this->lang->line("text_block_user");
            $user_id = $this->input->post('user_id');
            $data['user_id']=$user_id;
            $success = true;
            $message = '';
            $content = $this->load->view('admin/users/block_user',$data,TRUE);
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
    
    //block user action
    public function block_user_action(){
        //check permission
        if($this->permitted('user_blocking')){
            $user_id = $this->input->post('user_id');
            $update_data=array(
                'status'=>2,
                'updated_by' => $this->get_user_id()
            );
            //user blocking
            $result = $this->user_model->user_blocking($user_id,$update_data);
            if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                $success = TRUE;
                $message = $this->lang->line("alert_user_blocked");
            }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                $success = FALSE;
                $message = $this->lang->line("alert_user_not_blocked");
            }
        }else{
            $success = FALSE;
            $message = $this->lang->line("alert_access_denied");
        }
        $json_array = array('success' => $success, 'message' => $message);
        echo json_encode($json_array);
        exit();
    }

    //unblock user - load view
    public function unblock_user(){
        //check permission
        if($this->permitted('user_blocking')){
            $data['title']=$this->lang->line("text_unblock_user");
            $user_id = $this->input->post('user_id');
            $data['user_id']=$user_id;
            $success = TRUE;
            $message = '';
            $content = $this->load->view('admin/users/unblock_user',$data,TRUE);
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

    //unblock user action
    public function unblock_user_action(){
        //check permission
        if($this->permitted('user_blocking')){
            $user_id = $this->input->post('user_id');
            $update_data=array(
                'status'=>1,
                'updated_by' => $this->get_user_id()
            );
            //user blocking
            $result = $this->user_model->user_blocking($user_id,$update_data);
            if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                $success = TRUE;
                $message = $this->lang->line("alert_user_unblocked");
            }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                $success = FALSE;
                $message = $this->lang->line("alert_user_not_unblocked");
            }
        }else{
            $success = FALSE;
            $message = $this->lang->line("alert_access_denied");
        }
        $json_array = array('success' => $success, 'message' => $message);
        echo json_encode($json_array);
        exit();
    }

    //delete user - load view
	public function delete_user()
	{
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_user')){
                $data['title']=$this->lang->line("text_delete_user");
                $user_id = $this->input->post('user_id');
                $data['user_id']=$user_id;
                $success = TRUE;
                $message = '';
                $content = $this->load->view('admin/users/delete_user',$data,TRUE);
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

    //delete user action
    public function delete_user_action(){
        if($this->input->post()){
            //check permission
            if($this->permitted('delete_user')){
                $user_id = $this->input->post('user_id');
                //delete user
                $result = $this->user_model->delete_user($user_id);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_user_deleted");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_user_not_deleted");
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

    //user based permission
    public function permissions(){
        $data['title']=$this->lang->line("text_user_permissions");
        if($this->permitted('user_permissions')){
            if($this->input->post()){
                $post_data = array(
                    'permission_id' => $this->input->post('permission_id'),
                    'user_id' => $this->input->post('user_id'),
                    'role_id' => $this->input->post('role_id')
                    
                );
                //XXS Clean
                $post_data = $this->security->xss_clean($post_data);
                $result = $this->user_model->change_permission($post_data);
                if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
                    $success = TRUE;
                    $message = $this->lang->line("alert_user_permission_updated");
                }elseif($result['status']==FALSE &&$result['label']=='ERROR'){
                    $success = FALSE;
                    $message = $this->lang->line("alert_user_permission_not_updated");
                }
                $json_array = array('success' => $success, 'message' => $message);
                echo json_encode($json_array);
                exit();
            }
            $users=$this->user_model->get_non_admin_non_customer_users();
            $data['users']=$users;
            $data['title']='Permissions';
            $data['sub_view'] = $this->load->view('admin/users/permissions', $data, TRUE);
        }else{
            $data['sub_view'] = $this->load->view('errors/permission/denied', $data, TRUE);
        }
        $this->load->view('admin/_layout', $data);
    }

    //ajax paginate permissions
    public function list_permissions_ajax($page=0){
        $conditions = array();
        // Row position
        if($page != 0){
            $page = ($page-1) * $this->perPage;
        }
        $keyword=$this->input->post('keyword');
        $user=$this->input->post('user');
        if(!empty($keyword)){
            $conditions['search']['keyword'] = $keyword;
        }
        //get permissions count
        $permissions=$this->settings_model->get_permissions($conditions);
        if($permissions){
            $permissionsCount=count($permissions);
        }else{
            $permissionsCount=0;
        }
        //set start and limit
        $conditions['start'] = $page;
        $conditions['limit'] = $this->perPage;
        //get all permissions
        $permissions = $this->settings_model->get_permissions($conditions);
        //get pagination confing
        $config=$this->pagination_config($base_url=base_url().'users/list_permissions_ajax',$total_rows=$permissionsCount,$per_page=$this->perPage);
        // Initialize
        $this->pagination->initialize($config);
        //get user role
        $role=$this->user_model->get_user_role($user);
        $role_slug=$role['role_slug'];
        $role_id=$role['role_id'];

        //set data array
        $data['pagination'] = $this->pagination->create_links();
        $data['page']=$page;
        $data['user_id']=$user;
        $data['role_id']=$role_id;
        $permissions_list=array();
        if(isset($permissions) && $permissions!=NULL){
            $i=0;
            foreach($permissions as $permission){
                $permissions_list[$i]['id'] = $permission['id'];
                $permissions_list[$i]['name'] = $permission['permission_name'];
                $permissions_list[$i]['slug'] = $permission['permission_slug'];
                $permissions_list[$i]['info'] = $permission['permission_info'];
                $permissions_list[$i]['permission'] = $this->settings_model->check_permission($user_id=$user,$user_role=$role_slug,$permission_slug=$permission['permission_slug']);
                $i++;
            }
        }
        $data['permissions_list']=$permissions_list;
        //response
        $success = true;
        $message = '';
        $content = $this->load->view('admin/users/list_permissions_ajax',$data,TRUE);
        $json_array = array('success' => $success, 'message' => $message,'content'=>$content);
        echo json_encode($json_array);
        exit();
    }

}