<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		//load auth model
		$this->load->model('auth_model');
		
	}
    //login to account
	public function login()
	{
		
        if($this->input->post()){
            $this->form_validation->set_rules('email','email','trim|required|valid_email' );
			$this->form_validation->set_rules('password','password','trim|required');

			if ($this->form_validation->run() == FALSE) {
				$success = FALSE;
                $message = validation_errors();

			}else{
				$post_data = array(
					'email' => $this->input->post('email'),
					'password' => $this->input->post('password'),
					'remember' => (bool)$this->input->post('remember'),
					'is_site_login' => FALSE
					
				);
				//XXS Clean
				$post_data = $this->security->xss_clean($post_data);
				$result = $this->auth_model->login($post_data);
				if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
					$login_data = array(
						'user_id' => $result['user']['id'],
						'email' => $result['user']['email'], 
						'user_role' => $result['user']['role_name'],
						'user_role_slug' => $result['user']['role_slug'],
						'is_admin_login' => TRUE,
						'is_site_login' => FALSE
					);
					if ($post_data['remember']==TRUE) {
						// Set remember me value in session
						$this->session->set_userdata('remember_me', TRUE);
					}
					//set session
					$this->session->set_userdata('login', $login_data);
					//update login info
					$this->update_login_info($result['user']['id']);
					$success = TRUE;
                	$message = $this->lang->line("alert_login_success");
				}elseif($result['status']==FALSE &&$result['label']=='INACTIVE'){
					$success = FALSE;
                	$message = $this->lang->line("alert_login_inactive");
				}elseif($result['status']==FALSE &&$result['label']=='ERROR'){
					$success = FALSE;
                	$message = $this->lang->line("alert_login_invalid");
				}elseif($result['status']==FALSE &&$result['label']=='BLOCKED'){
					$success = FALSE;
                	$message = $this->lang->line("alert_user_blocked");
				}elseif($result['status']==FALSE &&$result['label']=='INVALID'){
					$success = FALSE;
                	$message = $this->lang->line("alert_user_notfound");
				}
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
		}
		$session_data = $this->session->all_userdata();
		if (isset($session_data['remember_me']) && $session_data['remember_me'] == TRUE) {
			//redirect to dashboard
			redirect('admin', 'refresh');
		}else{
			$data['title']='Login';
        	$this->load->view('admin/auth/login', $data); 
		}
		
	}

	//update login info
	public function update_login_info($user_id){
		$update_data = array(
			'login_ip' => $this->get_user_ip(),
			'login_agent' => $this->get_user_agent(),
			'last_login' => date('Y-m-d H:i:s')
		);
		$this->auth_model->update_login_info($user_id,$update_data);
	}
	
	// Logout
	public function logout() {
		// Destroy session data
		$this->session->unset_userdata('login');
		//redirect to login
		redirect('admin/login', 'refresh');
	}
    
    //forgot password
    public function forgot_password()
    {
		if($this->input->post()){
            $this->form_validation->set_rules('email','email','trim|required|valid_email' );
			if ($this->form_validation->run() == FALSE) {
				$success = FALSE;
                $message = validation_errors();

			}else{		
				$email=$this->input->post('email');
				$token=$this->get_token(50);
				$type='admin';
				$result = $this->auth_model->forgot_password($email,$token,$type);
				if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
					try{
						$to=$email;
						$full_name=$result['user']['full_name'];
						$reset_link=base_url().'admin/reset-password/'.$token;
						$data_set=array(
							'fullname'=>$full_name,
							'reset_link'=>'<a href="'.$reset_link.'" target="_blank">Reset Password</a>',
						);
						$email_content=$this->generate_email('forgot_password',$data_set);
						$subject=$this->get_email_subject($slug='forgot_password');
						//send mail
						@$this->sendEmail($to, $subject, $email_content);
						$success = TRUE;
                		$message = $this->lang->line("alert_forgot_password_success");
					}catch(Exception $e){
						$success = FALSE;
                		$message = $this->lang->line("alert_forgot_password_error");
					}
				}elseif($result['status']==FALSE &&$result['label']=='INACTIVE'){
					$success = FALSE;
                	$message = $this->lang->line("alert_login_inactive");
				}elseif($result['status']==FALSE &&$result['label']=='ERROR'){
					$success = FALSE;
                	$message = $this->lang->line("alert_forgot_password_error");
				}elseif($result['status']==FALSE &&$result['label']=='BLOCKED'){
					$success = FALSE;
                	$message = $this->lang->line("alert_user_blocked");
				}elseif($result['status']==FALSE &&$result['label']=='INVALID'){
					$success = FALSE;
                	$message = $this->lang->line("alert_user_notfound");
				}
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
		}
        $data['title']='Forgot Password';
        $this->load->view('admin/auth/forgot-password', $data); 
	}
	
	//reset password
    public function reset_password($token=NULL)
    {
		if($token!=NULL){
			$result=$this->auth_model->get_user_by_token($token);
			if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
				$data['title']='Reset Password';
				$data['token']=$token;
				$this->load->view('admin/auth/reset-password', $data);
			}elseif($result['status']==FALSE &&$result['label']=='INVALID'){
				//invalid token
				redirect('/admin/login', 'refresh');
			}
		}else{
			//token is null
			redirect('/admin/login', 'refresh');
		}
	}
	
	//reset password action
	public function reset_password_action(){
		if($this->input->post()){
            $this->form_validation->set_rules('password','Password','trim|required');
            $this->form_validation->set_rules('confirm_password','Confirm Password','trim|required');
			if ($this->form_validation->run() == FALSE) {
				$success = FALSE;
                $message = validation_errors();

			}else{		
				$password=$this->input->post('password');
				$confirm_password=$this->input->post('confirm_password');
				$token=$this->input->post('token');
				$result = $this->auth_model->reset_password($password,$token);
				if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
					try{
						$to=$result['user']['email'];;
						$full_name=$result['user']['full_name'];
						$data_set=array(
							'fullname'=>$full_name
						);
						$email_content=$this->generate_email('reset_password',$data_set);
						$subject=$this->get_email_subject($slug='reset_password');
						//send mail
						@$this->sendEmail($to, $subject, $email_content);
						
					}catch(Exception $e){

					}
					$success = TRUE;
                	$message = $this->lang->line("alert_reset_password_success");
				}elseif($result['status']==FALSE &&$result['label']=='INACTIVE'){
					$success = FALSE;
                	$message = $this->lang->line("alert_login_inactive");
				}elseif($result['status']==FALSE &&$result['label']=='ERROR'){
					$success = FALSE;
                	$message = $this->lang->line("alert_reset_password_error");
				}elseif($result['status']==FALSE &&$result['label']=='BLOCKED'){
					$success = FALSE;
                	$message = $this->lang->line("alert_user_blocked");
				}elseif($result['status']==FALSE &&$result['label']=='INVALID'){
					$success = FALSE;
                	$message = $this->lang->line("alert_reset_password_token_error");
				}
            }
            $json_array = array('success' => $success, 'message' => $message);
            echo json_encode($json_array);
            exit();
		}
        
	}
}