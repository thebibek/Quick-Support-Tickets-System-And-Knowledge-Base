<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		//Load Libraries
        $this->load->library(array('pagination'));
        //pagination settings
        $this->perPage = 10;
		//load models
		$this->load->model(array('auth_model'));
		
	}

    //login
	public function login(){
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
					'is_site_login' => TRUE
					
				);
				//XXS Clean
				$post_data = $this->security->xss_clean($post_data);
				$result = $this->auth_model->login($post_data);
				if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
					$login_data = array(
						'user_id' => $result['user']['id'],
						'full_name' => $result['user']['full_name'],
						'email' => $result['user']['email'], 
						'user_role' => $result['user']['role_name'],
						'user_role_slug' => $result['user']['role_slug'],
						'is_admin_login' => FALSE,
						'is_site_login' => TRUE
					);
					if ($post_data['remember']==TRUE) {
						// Set remember me value in session
						$this->session->set_userdata('remember_customer', TRUE);
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
		if (isset($session_data['remember_customer']) && $session_data['remember_customer'] == TRUE) {
			//redirect to dashboard
			redirect('user/profile', 'refresh');
		}else{
			$data['title']=$this->lang->line("text_login");
			$data['sub_view'] = $this->load->view('site/auth/login', $data, TRUE);
			$this->load->view('site/_layout', $data); 
		}
		
	}

	// Logout
	public function logout() {
		// Destroy session data
		$this->session->unset_userdata('login');
		//redirect to login
		redirect('login', 'refresh');
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
    
    //forgot-password
	public function forgot_password(){
		if($this->input->post()){
            $this->form_validation->set_rules('email','email','trim|required|valid_email' );

			if ($this->form_validation->run() == FALSE) {
				$success = FALSE;
                $message = validation_errors();

			}else{
				$email=$this->input->post('email');
				$token=$this->get_token(50);
				$type='customer';
				$result = $this->auth_model->forgot_password($email,$token,$type);
				if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
					try{
						$to=$email;
						$full_name=$result['user']['full_name'];
						$reset_link=base_url().'reset-password/'.$token;
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
		$data['title']=$this->lang->line("text_forgot_password");
		$data['sub_view'] = $this->load->view('site/auth/forgot-password', $data, TRUE);
		$this->load->view('site/_layout', $data);
	}

	//reset password
    public function reset_password($token=NULL)
    {
		if($token!=NULL){
			$result=$this->auth_model->get_user_by_token($token);
			if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
				$data['title']=$this->lang->line("text_reset_password");
				$data['token']=$token;
				$data['sub_view'] = $this->load->view('site/auth/reset-password', $data, TRUE);
				$this->load->view('site/_layout', $data);
			}elseif($result['status']==FALSE &&$result['label']=='INVALID'){
				//invalid token
				redirect('login', 'refresh');
			}
		}else{
			//token is null
			redirect('login', 'refresh');
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

    //register
	public function register(){
		if($this->input->post()){
			$this->form_validation->set_rules('full_name','Full Name','trim|required');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email' );
			$this->form_validation->set_rules('password','Password','trim|required');
			$this->form_validation->set_rules('confirm_password','Confirm Password','trim|required|matches[password]');

			if ($this->form_validation->run() == FALSE) {
				$success = FALSE;
                $message = validation_errors();

			}else{
				$activation_code=mt_rand(100000, 999999);
				$post_data = array(
					'user_role_id' => 4,	//customer
					'full_name' => $this->input->post('full_name'),
					'email' => $this->input->post('email'),
					'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'activation_code' => $activation_code,
					'status' => 0
				);
				//XXS Clean
				$post_data = $this->security->xss_clean($post_data);
				$result = $this->auth_model->register($post_data);
				if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
					//sent activation mail
					try{
						$to=$this->input->post('email');
						$full_name=$this->input->post('full_name');
						$data_set=array(
							'fullname'=>$full_name,
							'activation_code'=>$activation_code,
							'activation_button'=>'<a href="'.base_url().'activation/'.$result['user_id'].'">Click Here</a>'
						);
						$email_content=$this->generate_email('registration',$data_set);
						$subject=$this->get_email_subject($slug='registration');
						@$this->sendEmail($to, $subject, $email_content);
					}catch(Exception $e){

					}

					$user_id=$result['user_id'];
					$success = TRUE;
                	$message = 'Successfully registered user. You will navigate to activation page!';
				}elseif($result['status']==FALSE &&$result['label']=='ERROR'){
					$user_id=NULL;
					$success = FALSE;
                	$message = 'Error on registering user!';
				}elseif($result['status']==FALSE &&$result['label']=='EXIST'){
					$user_id=NULL;
					$success = FALSE;
                	$message = 'User with this mail already exist!';
				}
            }
            $json_array = array('success' => $success, 'message' => $message, 'user_id' => $user_id);
            echo json_encode($json_array);
            exit();
		}
		$data['title']=$this->lang->line("text_register");
		$data['sub_view'] = $this->load->view('site/auth/register', $data, TRUE);
		$this->load->view('site/_layout', $data); 

	}

    //activation
	public function activation($user_id=NULL){
		if($user_id!=NULL){
			if($this->input->post()){
				$this->form_validation->set_rules('activation_code','Activation Code','trim|required');

				if ($this->form_validation->run() == FALSE) {
					$success = FALSE;
					$message = validation_errors();
	
				}else{
					$activation_code=$this->input->post('activation_code');
					$user_id=$this->input->post('user_id');

					$result = $this->auth_model->activate($user_id,$activation_code);
					if ($result['status']==TRUE &&$result['label']=='SUCCESS') {
						$success = TRUE;
						$message = 'Successfully acivated user. You will navigate to login page!';
					}elseif($result['status']==FALSE &&$result['label']=='ERROR'){
						$success = FALSE;
						$message = 'Error on activating user!';
					}elseif($result['status']==FALSE &&$result['label']=='INVALID'){
						$success = FALSE;
						$message = 'Invalid Activation Code!';
					}
				}
				$json_array = array('success' => $success, 'message' => $message);
				echo json_encode($json_array);
				exit();
			}
			$data['title']=$this->lang->line("text_activation");
			$data['user_id']=$user_id;
			$data['sub_view'] = $this->load->view('site/auth/activation', $data, TRUE);
			$this->load->view('site/_layout', $data); 
		}else{
			//redirect to register
			redirect('register', 'refresh');
		}
	}
}
