<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends MY_Model{
	//login
	public function login($post_data){
		$this->db->select('u.*,r.role_name,r.role_slug');
        $this->db->from('users u');
        $this->db->join('users_roles r', 'r.id = u.user_role_id', 'inner');
		$this->db->where('u.email', $post_data['email']);
		if($post_data['is_site_login']==TRUE){
			$this->db->where('r.role_slug', 'customer');
		}elseif($post_data['is_site_login']==FALSE){
			$this->db->where('r.role_slug !=', 'customer');
		}
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			$result=$query->row_array();
			if($result['status']==0){
				//Inactive User
				$return_data=array(
                    'status'=>FALSE,
                    'label'=>'INACTIVE',
                );
                return $return_data;
			}elseif($result['status']==1){
				//Active User
				if(password_verify($post_data['password'], $result['password'])){
					//If Password Matches
					$return_data=array(
						'status'=>TRUE,
						'user'=>$result,
						'label'=>'SUCCESS',
					);
					return $return_data;
				}else{
					//If Password Not Matches
					$return_data=array(
						'status'=>FALSE,
						'label'=>'ERROR',
					);
					return $return_data;
				}
			}elseif($result['status']==2){
				//Blocked User
				$return_data=array(
                    'status'=>FALSE,
                    'label'=>'BLOCKED',
                );
                return $return_data;
			}
		}else{
			//Invalid User
			$return_data=array(
				'status'=>FALSE,
				'label'=>'INVALID',
			);
			return $return_data;
		}
	}

	//update login info
	public function update_login_info($user_id,$update_data){
		$this->db->trans_start();
		$this->db->where('id', $user_id);
		$this->db->update('users', $update_data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } 
        else {
            return TRUE;
            
        }
	}

	//forgot password
	public function forgot_password($email,$token,$type){
		$this->db->select('u.*,r.role_name,r.role_slug');
        $this->db->from('users u');
        $this->db->join('users_roles r', 'r.id = u.user_role_id', 'inner');
		$this->db->where('u.email', $email);
		if($type=='customer'){
			$this->db->where('r.role_slug', 'customer');
		}elseif($type=='admin'){
			$this->db->where('r.role_slug !=', 'customer');
		}
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			$result=$query->row_array();
			if($result['status']==0){
				//Inactive User
				$return_data=array(
                    'status'=>FALSE,
                    'label'=>'INACTIVE',
                );
                return $return_data;
			}elseif($result['status']==1){
				
				//Active User
				$update_data=array(
					'reset_token'=>$token,
					'updated_on'=>date('Y-m-d H:i:s')
				);
				$this->db->trans_start();
				$this->db->where('id', $result['id']);
				$this->db->update('users', $update_data);
				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE){
					//If Password Matches
					$return_data=array(
						'status'=>TRUE,
						'user'=>$result,
						'label'=>'SUCCESS',
					);
					return $return_data;
				}else{
					//If Password Not Matches
					$return_data=array(
						'status'=>FALSE,
						'label'=>'ERROR',
					);
					return $return_data;
				}
			}elseif($result['status']==2){
				//Blocked User
				$return_data=array(
                    'status'=>FALSE,
                    'label'=>'BLOCKED',
                );
                return $return_data;
			}
		}else{
			//Invalid User
			$return_data=array(
				'status'=>FALSE,
				'label'=>'INVALID',
			);
			return $return_data;
		}
	}

	//register
	public function register($post_data){
		$this->db->select('u.*,r.role_name,r.role_slug');
        $this->db->from('users u');
        $this->db->join('users_roles r', 'r.id = u.user_role_id', 'inner');
		$this->db->where('u.email', $post_data['email']);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			//User with mail already exist
			$return_data=array(
				'status'=>FALSE,
				'label'=>'EXIST',
			);
			return $return_data;
		}else{
			$this->_table_name='users';
			$this->_timestamps=TRUE;
			//create user
			$insert_id=$this->save($data=$post_data, $id = NULL);
			if($insert_id){
				$update_data=array(
					'created_by'=>$insert_id,
					'updated_by'=>$insert_id
				);
				//update article
				$update_id=$this->save($data=$update_data, $id = $insert_id);
				if($update_id){
					//if updated
					$return_data=array(
						'status'=>TRUE,
						'user_id'=>$update_id,
						'label'=>'SUCCESS',
					);
					return $return_data;
				}else{
					//if not updated
					$return_data=array(
						'status'=>FALSE,
						'label'=>'ERROR',
					);
					return $return_data;
				}
			}else{
				//If not registered
				$return_data=array(
					'status'=>FALSE,
					'label'=>'ERROR',
				);
				return $return_data;
			}
		}
	}

	//activation
	public function activate($user_id,$activation_code){
		$this->db->select('u.*');
        $this->db->from('users u');
		$this->db->where('u.id', $user_id);
		$this->db->where('u.activation_code', $activation_code);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			$this->_table_name='users';
			$this->_timestamps=TRUE;
			//update id
			$update_data=array(
				'activation_code'=>'',
				'status'=>1
			);
			//update article
			$update_id=$this->save($data=$update_data, $id = $user_id);
			if($update_id){
				//if updated
				$return_data=array(
					'status'=>TRUE,
					'label'=>'SUCCESS',
				);
				return $return_data;
			}else{
				//if not updated
				$return_data=array(
					'status'=>FALSE,
					'label'=>'ERROR',
				);
				return $return_data;
			}
		}else{
			//If invalid activation code
			$return_data=array(
				'status'=>FALSE,
				'label'=>'INVALID',
			);
			return $return_data;
		}
	}

	//get user by reset token
	public function get_user_by_token($token){
		$this->db->select('u.*,r.role_name,r.role_slug');
        $this->db->from('users u');
        $this->db->join('users_roles r', 'r.id = u.user_role_id', 'inner');
        $this->db->where('u.reset_token', $token);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			$result=$query->row_array();
			//If Valid Token
			$return_data=array(
				'status'=>TRUE,
				'user'=>$result,
				'label'=>'SUCCESS',
			);
			return $return_data;
		}else{
			//Invalid Token
			$return_data=array(
				'status'=>FALSE,
				'label'=>'INVALID',
			);
			return $return_data;
		}
	}

	//reset password
	public function reset_password($password,$token){
		$this->db->select('u.*,r.role_name,r.role_slug');
        $this->db->from('users u');
        $this->db->join('users_roles r', 'r.id = u.user_role_id', 'inner');
        $this->db->where('u.reset_token', $token);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			$result=$query->row_array();
			if($result['status']==0){
				//Inactive User
				$return_data=array(
                    'status'=>FALSE,
                    'label'=>'INACTIVE',
                );
                return $return_data;
			}elseif($result['status']==1){
				
				//Active User
				$update_data=array(
					'reset_token'=>'',
					'password'=>password_hash($password, PASSWORD_DEFAULT),
					'updated_on'=>date('Y-m-d H:i:s')
				);
				$this->db->trans_start();
				$this->db->where('id', $result['id']);
				$this->db->update('users', $update_data);
				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE){
					//If Password Matches
					$return_data=array(
						'status'=>TRUE,
						'user'=>$result,
						'label'=>'SUCCESS',
					);
					return $return_data;
				}else{
					//If Password Not Matches
					$return_data=array(
						'status'=>FALSE,
						'label'=>'ERROR',
					);
					return $return_data;
				}
			}elseif($result['status']==2){
				//Blocked User
				$return_data=array(
                    'status'=>FALSE,
                    'label'=>'BLOCKED',
                );
                return $return_data;
			}
		}else{
			//Invalid User
			$return_data=array(
				'status'=>FALSE,
				'label'=>'INVALID',
			);
			return $return_data;
		}
	}

}

?>