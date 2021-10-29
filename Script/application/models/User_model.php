<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model{
	//get all user types
	public function get_user_types(){
		$this->_table_name='users_roles';
		$result=$this->get($id = NULL, $single = FALSE, $AsOrder = "asc", $limit= NULL, $pageNumber=0);
		return $result;
	}

	//get all non admin user types
	public function get_non_admin_user_types(){
		$this->db->select('r.*');
		$this->db->from('users_roles r');
		$this->db->where('r.role_slug !=', 'admin');
		$query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
	}

	//get all users
	public function get_users($params = array()){
		$this->db->select('u.*,r.role_name,r.role_slug');
        $this->db->from('users u');
		$this->db->join('users_roles r', 'r.id = u.user_role_id', 'inner');

		//filter data by searched keyword
        if(!empty($params['search']['keyword'])){
            $this->db->like('u.full_name', $params['search']['keyword']);
        }
        //filter data by searched user type
        if(!empty($params['search']['user_type'])){
            $this->db->where('u.user_role_id', $params['search']['user_type']);
        }
        //filter data by searched status
        if(!empty($params['search']['status'])){
            if($params['search']['status']=='INACTIVE'){
                $status=0;
            }elseif($params['search']['status']=='ACTIVE'){
                $status=1;
            }elseif($params['search']['status']=='BLOCKED'){
                $status=2;
            }
            $this->db->where('u.status', $status);
		}
		if(array_key_exists("exclued",$params)){
			$this->db->where('u.id !=', $params['exclued']);
		}
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
		$query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
	}

	//get all non customer users
	public function get_non_customer_users(){
		$this->db->select('u.*,r.role_name,r.role_slug');
        $this->db->from('users u');
		$this->db->join('users_roles r', 'r.id = u.user_role_id', 'inner');
		$this->db->where('r.role_slug !=', 'customer');
		$this->db->where('u.status', 1);
		$query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
	}

	//get all non admin non customer users
	public function get_non_admin_non_customer_users(){
		$this->db->select('u.*,r.role_name,r.role_slug');
        $this->db->from('users u');
		$this->db->join('users_roles r', 'r.id = u.user_role_id', 'inner');
		$this->db->where('u.status', 1);
		$this->db->where('r.role_slug', 'manager');
		$this->db->or_where('r.role_slug', 'agent');
		$query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
	}

	//get user by id
	public function get_user($user_id){
		$this->db->select('u.*,r.role_name,r.role_slug');
        $this->db->from('users u');
		$this->db->join('users_roles r', 'r.id = u.user_role_id', 'inner');
		$this->db->where('u.id', $user_id);
		$query = $this->db->get();
		return ($query->num_rows() == 1)?$query->row_array():FALSE;
	}

	//get user role
	public function get_user_role($user_id){
		$this->db->select('r.id as role_id,r.role_name,r.role_slug');
        $this->db->from('users u');
		$this->db->join('users_roles r', 'r.id = u.user_role_id', 'inner');
		$this->db->where('u.id', $user_id);
		$query = $this->db->get();
		return ($query->num_rows() == 1)?$query->row_array():FALSE;
	}
	
	//create new user
	public function create_user($post_data){
		$this->_table_name='users';
		//check user with mail exist
		$condition=array('email'=>$post_data['email']);
		$record=$this->get_by($where=$condition, $single = TRUE, $AsOrder = "asc", $limit= NULL, $pageNumber=0);
		if(isset($record) && $record!=NULL){
			//user already exist
			$return_data=array(
				'status'=>FALSE,
				'label'=>'EXIST',
			);
			return $return_data;
		}else{
			//user not exist
			$this->_table_name='users';
			$this->_timestamps=TRUE;
			//create user
			$insert_id=$this->save($data=$post_data, $id = NULL);
			if($insert_id){
				//if inserted
				$return_data=array(
					'status'=>TRUE,
					'label'=>'SUCCESS',
				);
				return $return_data;
			}else{
				//if not inseted
				$return_data=array(
					'status'=>FALSE,
					'label'=>'ERROR',
				);
				return $return_data;
			}

		}
	}

	//update user
	public function update_user($user_id,$update_data){
		$this->_table_name='users';
		$this->_timestamps=TRUE;
		//update user
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
	}

	//block-unblock user
	public function user_blocking($user_id,$update_data){
		$this->_table_name='users';
		$this->_timestamps=TRUE;
		//update user satus
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
	}

	//delete user
	public function delete_user($user_id){
		$this->db->trans_start();
		//get user to unlink profile image
		$user=$this->get_user($user_id);
		if($user){
			if($user['profile_image']!=NULL){
				@unlink(FCPATH.'uploads/profile/'.$user['profile_image']);
			}
		}
		//delete user permissions
        $this->db->where('user_id', $user_id);
		$this->db->delete('user_permissions');
		//delete user
		$this->db->where('id', $user_id);
        $this->db->limit(1);
		$this->db->delete('users');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            //if not deleted
			$return_data=array(
				'status'=>FALSE,
				'label'=>'ERROR',
			);
			return $return_data;
        }else{
			$this->db->trans_commit();
			
            //if deleted
			$return_data=array(
				'status'=>TRUE,
				'label'=>'SUCCESS',
			);
			return $return_data;
        }
	}

	//change permission
	public function change_permission($post_data){
		$permission_id=$post_data['permission_id'];
		$user_id=$post_data['user_id'];
		$role_id=$post_data['role_id'];
		//check current state in user permissions
		$this->db->select('u.*');
		$this->db->from('user_permissions u');
		$this->db->where('u.user_id', $user_id);
		$this->db->where('u.permission_id', $permission_id);
		$query = $this->db->get();
		//if found
		if($query->num_rows() == 1){
			
			$result=$query->row_array();
			//if is_permitted=0 update to one
			if($result['is_permitted']==0){
				$update_data=array(
					'is_permitted'=>1
				);
			}else{ //if is_permitted=1 update to zero
				$update_data=array(
					'is_permitted'=>0
				);
			}	
			$this->_table_name='user_permissions';
			$this->_timestamps=TRUE;
			$update_id=$this->save($data=$update_data, $id = $result['id']);
			if($update_id){
				$return_data=array(
					'status'=>TRUE,
					'label'=>'SUCCESS',
				);
				return $return_data;
			}else{
				$return_data=array(
					'status'=>FALSE,
					'label'=>'ERROR',
				);
				return $return_data;
			}
		}else{
			//check current state in role permissions
			$this->db->select('r.*');
			$this->db->from('role_permissions r');
			$this->db->where('r.permission_id', $permission_id);
			$this->db->where('r.role_id', $role_id);
			$query = $this->db->get();
			//if found
			if($query->num_rows() == 1){
				$result=$query->row_array();
				//if is_permitted=0 insert as one
				if($result['is_permitted']==0){
					$insert_data=array(
						'user_id'=>$user_id,
						'permission_id'=>$permission_id,
						'is_permitted'=>1,
						'updated_on'=>date('Y-m-d H:i:s'),
					);
				}else{ //if is_permitted=1 insert to zero
					$insert_data=array(
						'user_id'=>$user_id,
						'permission_id'=>$permission_id,
						'is_permitted'=>0,
						'updated_on'=>date('Y-m-d H:i:s'),
					);
				}	
				$this->_table_name='user_permissions';
				$this->_timestamps=FALSE;
				$update_id=$this->save($data=$insert_data, $id = NULL);
				if($update_id){
					$return_data=array(
						'status'=>TRUE,
						'label'=>'SUCCESS',
					);
					return $return_data;
				}else{
					$return_data=array(
						'status'=>FALSE,
						'label'=>'ERROR',
					);
					return $return_data;
				}

			}else{
				//If Permission Not Found
				$return_data=array(
					'status'=>FALSE,
					'label'=>'ERROR',
				);
				return $return_data;
			}
		}
	}

	//update user profile
	public function update_profile($user_id,$update_data){
		//check mail exist for another user
		$this->db->select('u.*');
        $this->db->from('users u');
		$this->db->where('u.email', $update_data['email']);
		$this->db->where('u.id !=', $user_id);
		$query = $this->db->get();
		if($query->num_rows() == 1){
			//If mail exist for another user
			$return_data=array(
				'status'=>FALSE,
				'label'=>'EXIST',
			);
			return $return_data;
		}else{
			$this->_table_name='users';
			$this->_timestamps=TRUE;
			//update user profile
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
		}

	}

	//change password
	public function change_password($user_id,$old_password,$new_password){
		$this->db->select('u.*');
        $this->db->from('users u');
		$this->db->where('u.id', $user_id);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			$result=$query->row_array();
			//check old password maches
			if(password_verify($old_password, $result['password'])){
				$update_data = array(
					'password' => password_hash($new_password, PASSWORD_DEFAULT),
					'updated_by' => $user_id,
				);
				$this->_table_name='users';
				$this->_timestamps=TRUE;
				//change password
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
				//if not updated
				$return_data=array(
					'status'=>FALSE,
					'label'=>'INVALID',
				);
				return $return_data;
			}
		}
	}

	//get user dashboard data
	public function get_dashboard_data($user_id){
		$dashboard_data=array();
		//get all ticktes
		$this->db->select('t.*');
        $this->db->from('tickets t');
		$this->db->where('t.created_by', $user_id);
		$query1 = $this->db->get();
		$dashboard_data['total_tickets']=$query1->num_rows();
		//get new tickets
		$this->db->select('t.*');
        $this->db->from('tickets t');
		$this->db->where('t.created_by', $user_id);
		$this->db->where('t.status', 0);
		$query2 = $this->db->get();
		$dashboard_data['new_tickets']=$query2->num_rows();
		//get in progress tickets
		$this->db->select('t.*');
        $this->db->from('tickets t');
		$this->db->where('t.created_by', $user_id);
		$this->db->where('t.status', 1);
		$query3 = $this->db->get();
		$dashboard_data['inprogress_tickets']=$query3->num_rows();
		//get closed tickets
		$this->db->select('t.*');
        $this->db->from('tickets t');
		$this->db->where('t.created_by', $user_id);
		$this->db->where('t.status', 2);
		$query4 = $this->db->get();
		$dashboard_data['closed_tickets']=$query4->num_rows();
		return $dashboard_data;
	}

	//vote an article
	public function article_voting($user_id,$article_id,$status){
		$total_votes=0;
		$up_votes=0;
		$this->db->trans_start();
		$this->db->select('v.*');
        $this->db->from('article_votes v');
		$this->db->where('v.user_id', $user_id);
		$this->db->where('v.article_id', $article_id);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			$result=$query->row_array();
			$vote_id=$result['id'];
			//update
			$update_data=array(
				'status'=>$status,
				'updated_on'=>date('Y-m-d H:i:s')
			);
			$this->db->where('id', $vote_id);
    		$this->db->update('article_votes', $update_data);
		}else{
			//insert data
			$save_data=array(
				'user_id'=>$user_id,
				'article_id'=>$article_id,
				'status'=>$status,
				'updated_on'=>date('Y-m-d H:i:s')
			);
			$this->db->insert('article_votes',$save_data);

		}
		//get count of all votes
		$this->db->select('v.*');
		$this->db->from('article_votes v');
		$this->db->where('v.article_id', $article_id);
		$query = $this->db->get();
		$total_votes=$query->num_rows();
		//get count of all up votes
		$this->db->select('v.*');
		$this->db->from('article_votes v');
		$this->db->where('v.article_id', $article_id);
		$this->db->where('v.status', 1);
		$query = $this->db->get();
		$up_votes=$query->num_rows();
		$this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            //if not error
			$return_data=array(
				'status'=>FALSE,
				'total_votes'=>$total_votes,
				'up_votes'=>$up_votes,
				'label'=>'ERROR',
			);
			return $return_data;
        }else{
			$this->db->trans_commit();
			
            //if success
			$return_data=array(
				'status'=>TRUE,
				'total_votes'=>$total_votes,
				'up_votes'=>$up_votes,
				'label'=>'SUCCESS',
			);
			return $return_data;
        }
	}

}

?>