<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends MY_Model{
	//check permission
	public function check_permission($user_id,$user_role,$permission_slug){
		if($user_role=='admin'){
			return TRUE;
		}else{
			//check role permission
			$this->db->select('r.is_permitted');
			$this->db->from('role_permissions r');
			$this->db->join('permissions p', 'p.id = r.permission_id', 'inner');
			$this->db->join('users_roles u', 'u.id = r.role_id', 'inner');
			$this->db->where('p.permission_slug', $permission_slug);
			$this->db->where('u.role_slug', $user_role);
			$query = $this->db->get();
			if ($query->num_rows() == 1) {
				$role_result=$query->row_array();
				//if role permission found check if user permission
				$this->db->select('r.is_permitted');
				$this->db->from('user_permissions r');
				$this->db->join('permissions p', 'p.id = r.permission_id', 'inner');
				$this->db->join('users u', 'u.id = r.user_id', 'inner');
				$this->db->where('p.permission_slug', $permission_slug);
				$this->db->where('u.id', $user_id);
				$query = $this->db->get();
				if ($query->num_rows() == 1) {
					$user_result=$query->row_array();
					
					if($user_result['is_permitted']==1){
						return TRUE;
					}else{
						return FALSE;
					}
				}else{
					if($role_result['is_permitted']==1){
						return TRUE;
					}else{
						return FALSE;
					}
				}
			}else{
				//if role permission not found then check user permission
				$this->db->select('r.is_permitted');
				$this->db->from('user_permissions r');
				$this->db->join('permissions p', 'p.id = r.permission_id', 'inner');
				$this->db->join('users u', 'u.id = r.user_id', 'inner');
				$this->db->where('p.permission_slug', $permission_slug);
				$this->db->where('u.id', $user_id);
				$query = $this->db->get();
				if ($query->num_rows() == 1) {
					$user_result=$query->row_array();
					if($user_result['is_permitted']==1){
						return TRUE;
					}else{
						return FALSE;
					}
				}else{
					return FALSE;
				}
			}
		}
		
	}

    //get all permissions
	public function get_permissions($params=array()){
		$this->db->select('p.*');
        $this->db->from('permissions p');
		//filter data by searched keyword
        if(!empty($params['search']['keyword'])){
            $this->db->like('p.permission_name', $params['search']['keyword']);
            $this->db->or_like('p.permission_info', $params['search']['keyword']);
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
    
    //get all config
	public function get_config(){
		$this->_table_name='config';
		$result=$this->get($id = NULL, $single = FALSE, $AsOrder = "asc", $limit= NULL, $pageNumber=0);
		return $result;
    }
    
    //get permission by role
    public function get_role_permissions($role,$permission_slug){
        //check role permission
		$this->db->select('r.id,r.role_id,r.is_permitted');
        $this->db->from('role_permissions r');
        $this->db->join('permissions p', 'p.id = r.permission_id', 'inner');
        $this->db->join('users_roles u', 'u.id = r.role_id', 'inner');
        $this->db->where('p.permission_slug', $permission_slug);
		$this->db->where('u.role_slug', $role);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
            $result=$query->row_array();
            return $result;

        }else{
            $result = array();
            return $result;
        }
	}
	
	//change permission
	public function change_permission($post_data){
		$this->db->select('r.id,r.role_id,r.is_permitted');
		$this->db->from('role_permissions r');
		$this->db->where('r.id', $post_data['role_permission_id']);
		$query = $this->db->get();
		if($query->num_rows() == 1){
			$result=$query->row_array();
			if($result['is_permitted']==1){
				$update_data=array(
					'is_permitted'=>0
				);
			}else{
				$update_data=array(
					'is_permitted'=>1
				);
			}
			$this->_table_name='role_permissions';
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
			//If Permission Not Found
			$return_data=array(
				'status'=>FALSE,
				'label'=>'ERROR',
			);
			return $return_data;
		}
	}

	//save config datas
	public function save_config($post_data){
		$this->db->trans_start();
        foreach($post_data as $key => $value){
            $update_data=array(
                'config_value'=>$value,
                'updated_on'=>date("Y-m-d H:i:s"),
            );
            $this->db->where('config_name', $key);
			$this->db->update('config', $update_data);
        }
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            
            $return_data=array(
                'status'=>false,
                'label'=>'ERROR',
            );
            return $return_data;
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $return_data=array(
                'status'=>true,
                'label'=>'SUCCESS',
            );
            return $return_data;
            
        }
	}

	//count articles
	public function count_articles(){
		$this->_table_name='articles';
		return $this->count_records();
	}

	//count tickets
	public function count_tickets(){
		$this->_table_name='tickets';
		return $this->count_records();
	}

	//count faqs
	public function count_faqs(){
		$this->_table_name='faqs';
		return $this->count_records();
	}

	//count users
	public function count_users(){
		$this->_table_name='users';
		return $this->count_records();
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

	//get email templates
	public function get_email_templates($params = array()){
        $this->db->select('e.*');
        $this->db->from('email_templates e');
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
	
	//get email template by id
    public function get_email_template($template_id){
        $this->db->select('e.*');
        $this->db->from('email_templates e');
        $this->db->where('e.id', $template_id);
        $query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->row_array():FALSE;
	}
	
	//update email template
    public function update_email_template($template_id,$update_data){
        $this->db->trans_start();
        $this->db->where('id', $template_id);
        $this->db->update('email_templates', $update_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $return_data=array(
                'status'=>false,
                'label'=>'ERROR',
            );
            return $return_data;
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $return_data=array(
                'status'=>true,
                'label'=>'SUCCESS',
            );
            return $return_data;
            
        }
	}
	
	//get email template by slug
    public function get_email_template_by_slug($template_slug){
        $this->db->select('e.*');
        $this->db->from('email_templates e');
        $this->db->where('e.slug', $template_slug);
        $query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->row_array():FALSE;
	}
}

?>