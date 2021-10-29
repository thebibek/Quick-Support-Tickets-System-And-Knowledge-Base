<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends MY_Model{
    //get all tickets
	public function get_tickets($params = array()){
		$this->db->select('t.*,c.category_name,c.slug,u.full_name,u.email,u.profile_image,a.full_name as assigned_user,a.profile_image assigned_user_image');
        $this->db->from('tickets t');
        $this->db->join('ticket_categories c', 'c.id = t.category_id', 'inner');
        $this->db->join('users u', 'u.id = t.created_by', 'left');
        $this->db->join('users a', 'a.id = t.assigned_to', 'left');

		//filter data by searched keyword
        if(!empty($params['search']['keyword'])){
            $this->db->like('t.ticket_title', $params['search']['keyword']);
            $this->db->or_like('t.ticket_description', $params['search']['keyword']);
        }
        //filter data by searched priority
        if(!empty($params['search']['priority'])){
            $this->db->where('t.priority', $params['search']['priority']);
        }
        //filter data by searched category
        if(!empty($params['search']['category'])){
            $this->db->where('t.category_id', $params['search']['category']);
        }
        //filter data by searched status
        if(!empty($params['search']['status'])){
            if($params['search']['status']=='NEW'){
                $status=0;
            }elseif($params['search']['status']=='INPROGRESS'){
                $status=1;
            }elseif($params['search']['status']=='CLOSED'){
                $status=2;
            }
            $this->db->where('t.status', $status);
        }
        //filter data by searched status
        if(!empty($params['search']['ticket_type'])){
            if($params['search']['ticket_type']=='GUEST'){
                $this->db->where('t.created_by', 0);
            }else{
                $this->db->where('t.created_by !=', 0);
            }
        }
        //filter assigned ticket if support agent
        if($params['search']['user_type']=='agent'){
            $this->db->where('t.assigned_to', $params['search']['user_id']);
        }
        //get tickets by customer
        elseif($params['search']['user_type']=='customer'){
            $this->db->where('t.created_by', $params['search']['user_id']);
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        $this->db->order_by("created_on","desc");
		$query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }

    //get ticket by id
    public function get_ticket($ticket_id){
        $this->db->select('t.*,c.category_name,c.slug,u.full_name,u.email,u.profile_image,a.full_name as assigned_user,a.email as assigned_user_email,a.profile_image assigned_user_image');
        $this->db->from('tickets t');
        $this->db->join('ticket_categories c', 'c.id = t.category_id', 'inner');
        $this->db->join('users u', 'u.id = t.created_by', 'left');
        $this->db->join('users a', 'a.id = t.assigned_to', 'left');
		$this->db->where('t.id', $ticket_id);
		$query = $this->db->get();
		return ($query->num_rows() == 1)?$query->row_array():FALSE;
    }

    //get user ticket
    public function get_user_ticket($ticket_id,$user_id){
        $this->db->select('t.*,c.category_name,c.slug,u.full_name,u.email,u.profile_image,a.full_name as assigned_user,a.profile_image assigned_user_image');
        $this->db->from('tickets t');
        $this->db->join('ticket_categories c', 'c.id = t.category_id', 'inner');
        $this->db->join('users u', 'u.id = t.created_by', 'left');
        $this->db->join('users a', 'a.id = t.assigned_to', 'left');
		$this->db->where('t.id', $ticket_id);
		$this->db->where('u.id', $user_id);
		$query = $this->db->get();
		return ($query->num_rows() == 1)?$query->row_array():FALSE;
    }

    //get tickets by category
    public function get_tickets_by_category($category_id){
        $this->db->select('t.*,c.category_name,c.slug');
        $this->db->from('tickets t');
		$this->db->join('ticket_categories c', 'c.id = t.category_id', 'inner');
        $this->db->where('t.category_id', $category_id);
        $this->db->order_by("ordering","asc");
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }

    //create ticket
    public function create_ticket($post_data){
        $this->_table_name='tickets';
        $this->_timestamps=TRUE;
        //reply to ticket
        $insert_id=$this->save($data=$post_data, $id = NULL);
        if($insert_id){
            //if updated
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

    //get ticket replies
    public function get_replies($ticket_id){
        $this->db->select('r.*,u.full_name,u.email,u.profile_image,ur.role_name');
        $this->db->from('ticket_replies r');
        $this->db->join('users u', 'u.id = r.created_by', 'left');
        $this->db->join('users_roles ur', 'ur.id = u.user_role_id', 'left');
        $this->db->where('r.ticket_id', $ticket_id);
        $this->db->order_by("created_on","desc");
		$query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;

    }

    //reply for tickets
    public function reply_ticket($post_data){
        $this->_table_name='ticket_replies';
        $this->_timestamps=TRUE;
        //reply to ticket
        $insert_id=$this->save($data=$post_data, $id = NULL);
        if($insert_id){
            //if updated
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

    //assing ticket to user
    public function assign_ticket($ticket_id,$update_data){
        $this->_table_name='tickets';
		$this->_timestamps=TRUE;
		//update user
		$update_id=$this->save($data=$update_data, $id = $ticket_id);
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

    // mark as completed
    public function mark_completed($ticket_id,$update_data){
        $this->_table_name='tickets';
		$this->_timestamps=TRUE;
		//update user
		$update_id=$this->save($data=$update_data, $id = $ticket_id);
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

    //delete ticket
	public function delete_ticket($ticket_id){
        $this->db->trans_start();
        //get ticket replies to unlink attachment
        $replies=$this->get_replies($ticket_id);
        if($replies){
            foreach($replies as $reply){
                if($reply['reply_file']!=NULL){
                    @unlink(FCPATH.'uploads/tickets/'.$reply['reply_file']);
                }
            }
        }
		//delete ticket replies
        $this->db->where('ticket_id', $ticket_id);
        $this->db->delete('ticket_replies');
        //get ticket to unlink attachment
        $ticket=$this->get_ticket($ticket_id);
        if($ticket){
            if($ticket['ticket_file']!=NULL){
                @unlink(FCPATH.'uploads/tickets/'.$ticket['ticket_file']);
            }
        }
		//delete ticket
		$this->db->where('id', $ticket_id);
        $this->db->limit(1);
		$this->db->delete('tickets');
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
    
    //get all categories
	public function get_categories($params = array()){
		$this->db->select('c.*,COUNT(t.id) as num_tickets');
        $this->db->from('ticket_categories c');
        $this->db->join('tickets t', 'c.id = t.category_id', 'left');
        $this->db->group_by('c.id');
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        $this->db->order_by("ordering","asc");
		$query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
    
    //get category by id
    public function get_category($category_id){
        $this->db->select('c.*,COUNT(t.id) as num_tickets');
        $this->db->from('ticket_categories c');
        $this->db->join('tickets t', 'c.id = t.category_id', 'left');
        $this->db->where('c.id', $category_id);
        $this->db->group_by('c.id');
        $query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->row_array():FALSE;
    }

    //get all categories except
    public function get_categories_except($category_id){
        $this->db->select('c.*');
        $this->db->from('ticket_categories c');
        $this->db->where('c.id !=', $category_id);
        $query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
    
    //create new ticket category
    public function create_category($post_data){
        $this->_table_name='ticket_categories';
        $this->_timestamps=TRUE;
        //create ticket caregory
        $insert_id=$this->save($data=$post_data, $id = NULL);
        if($insert_id){
            //create slug
            $slug=$this->create_slug($id=$insert_id, $title=$post_data['category_name']);
            //get order
            $order=$this->get_ordering();
            $update_data=array(
                'slug'=>$slug,
                'ordering'=>$order
            );
            //update ticket caregory
            $update_id=$this->save($data=$update_data, $id = $insert_id);
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
            //if not inseted
            $return_data=array(
                'status'=>FALSE,
                'label'=>'ERROR',
            );
            return $return_data;
        }
    }

    //update category
    public function update_category($category_id,$update_data){
        $this->_table_name='ticket_categories';
		$this->_timestamps=TRUE;
		//update user
		$update_id=$this->save($data=$update_data, $id = $category_id);
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

    //update category ordering
    public function update_category_ordering($sorted_data=array()){
        $this->db->trans_start();
        $i=1;
        foreach($sorted_data as $key => $value){
            $update_data=array(
                'ordering'=>$i,
                'updated_on'=>date("Y-m-d H:i:s"),
            );
            $this->db->where('id', $value);
            $this->db->update('ticket_categories', $update_data);
            $i++;
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

    //delete ticket category
    public function delete_ticket_category($category_id,$transfer_category,$complete_delete){
        $this->db->trans_start();
        if($transfer_category==0 && $complete_delete==0){
            //delete category only
            $this->db->where('id', $category_id);
            $this->db->limit(1);
            $this->db->delete('ticket_categories');
            
        }elseif($transfer_category!=0 && $complete_delete==0){
            $category=$this->get_category($transfer_category);
            if($category){
                //update new category id to items
                $update_data=array(
                    'category_id'=>$transfer_category,
                    'updated_on'=>date('Y-m-d H:i:s')
                );
                $this->db->where('category_id', $category_id);
                $this->db->update('tickets', $update_data);
                //delete category
                $this->db->where('id', $category_id);
                $this->db->limit(1);
                $this->db->delete('ticket_categories');
            }else{
                //if transfering category not exist
                $return_data=array(
                    'status'=>FALSE,
                    'label'=>'NOTEXIST',
                );
                return $return_data;
            }
        }elseif($transfer_category==0 && $complete_delete==1){
            //delete items
            $this->db->where('category_id', $category_id);
            $this->db->delete('tickets');
            //delete category
            $this->db->where('id', $category_id);
            $this->db->limit(1);
            $this->db->delete('ticket_categories');
        }
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

}

?>