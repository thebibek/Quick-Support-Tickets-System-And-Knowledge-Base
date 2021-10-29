<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Faq_model extends MY_Model{

    //get all faqs
	public function get_faqs($params = array()){
		$this->db->select('f.*,c.category_name,c.slug');
        $this->db->from('faqs f');
		$this->db->join('faq_categories c', 'c.id = f.category_id', 'inner');

		//filter data by searched keyword
        if(!empty($params['search']['keyword'])){
            $this->db->like('f.faq_title', $params['search']['keyword']);
            $this->db->or_like('f.faq_description', $params['search']['keyword']);
        }
        //filter data by searched category
        if(!empty($params['search']['category'])){
            $this->db->where('f.category_id', $params['search']['category']);
        }
        //filter data by searched status
        if(!empty($params['search']['status'])){
            if($params['search']['status']=='UNPUBLISHED'){
                $status=0;
            }elseif($params['search']['status']=='PUBLISHED'){
                $status=1;
            }
            $this->db->where('f.status', $status);
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

	//get faq by id
	public function get_faq($faq_id){
		$this->db->select('f.*,c.category_name,c.slug');
        $this->db->from('faqs f');
		$this->db->join('faq_categories c', 'c.id = f.category_id', 'inner');
		$this->db->where('f.id', $faq_id);
		$query = $this->db->get();
		return ($query->num_rows() == 1)?$query->row_array():FALSE;
    }
    
    //get faq by category id
    public function get_faqs_by_category($category_id){
        $this->db->select('f.*,c.category_name,c.slug');
        $this->db->from('faqs f');
		$this->db->join('faq_categories c', 'c.id = f.category_id', 'inner');
        $this->db->where('f.category_id', $category_id);
        $this->db->order_by("ordering","asc");
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
	
	//create new faq
	public function create_faq($post_data){
        $this->_table_name='faqs';
        $this->_timestamps=TRUE;
        //create faq
        $insert_id=$this->save($data=$post_data, $id = NULL);
        if($insert_id){
            //create slug
            $slug=$this->create_slug($id=$insert_id, $title=$post_data['faq_title']);
            //get order
            $order=$this->get_ordering_by_category($post_data['category_id']);
            $update_data=array(
                'slug'=>$slug,
                'ordering'=>$order
            );
            //update faq caregory
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

	//update faq
	public function update_faq($faq_id,$update_data){
		$this->_table_name='faqs';
		$this->_timestamps=TRUE;
		//update faq
		$update_id=$this->save($data=$update_data, $id = $faq_id);
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

    //faq publishing
	public function faq_publishing($faq_id,$update_data){
		$this->_table_name='faqs';
		$this->_timestamps=TRUE;
		//update faq status
		$update_id=$this->save($data=$update_data, $id = $faq_id);
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

    //faq ordering
    public function update_faq_ordering($sorted_data=array()){
        $this->db->trans_start();
        $i=1;
        foreach($sorted_data as $key => $value){
            $update_data=array(
                'ordering'=>$i,
                'updated_on'=>date("Y-m-d H:i:s"),
            );
            $this->db->where('id', $value);
            $this->db->update('faqs', $update_data);
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

    //delete faq
    public function delete_faq($faq_id){
        $this->_table_name='faqs';
        $deleted=$this->delete($faq_id);
        if($deleted){
            //if deleted
			$return_data=array(
				'status'=>TRUE,
				'label'=>'SUCCESS',
			);
			return $return_data;
        }else{
            //if not deleted
			$return_data=array(
				'status'=>FALSE,
				'label'=>'ERROR',
			);
			return $return_data;
        }
    }



    //get all categories
	public function get_categories($params = array()){
		$this->db->select('c.*,COUNT(f.id) as num_faqs');
        $this->db->from('faq_categories c');
        $this->db->join('faqs f', 'c.id = f.category_id', 'left');
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

    //get all categories except
    public function get_categories_except($category_id){
        $this->db->select('c.*');
        $this->db->from('faq_categories c');
        $this->db->where('c.id !=', $category_id);
        $query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
    
    //get category by id
    public function get_category($category_id){
        $this->db->select('c.*,COUNT(f.id) as num_faqs');
        $this->db->from('faq_categories c');
        $this->db->join('faqs f', 'c.id = f.category_id', 'left');
        $this->db->where('c.id', $category_id);
        $this->db->group_by('c.id');
        $query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->row_array():FALSE;
    }
    
    //create new faq category
    public function create_category($post_data){
        $this->_table_name='faq_categories';
        $this->_timestamps=TRUE;
        //create faq caregory
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
            //update faq caregory
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
        $this->_table_name='faq_categories';
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

    //update category order
    public function update_category_ordering($sorted_data=array()){
        $this->db->trans_start();
        $i=1;
        foreach($sorted_data as $key => $value){
            $update_data=array(
                'ordering'=>$i,
                'updated_on'=>date("Y-m-d H:i:s"),
            );
            $this->db->where('id', $value);
            $this->db->update('faq_categories', $update_data);
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

    //delete faq category
    public function delete_faq_category($category_id,$transfer_category,$complete_delete){
        $this->db->trans_start();
        if($transfer_category==0 && $complete_delete==0){
            //delete category only
            $this->db->where('id', $category_id);
            $this->db->limit(1);
            $this->db->delete('faq_categories');
            
        }elseif($transfer_category!=0 && $complete_delete==0){
            $category=$this->get_category($transfer_category);
            if($category){
                //update new category id to items
                $update_data=array(
                    'category_id'=>$transfer_category,
                    'updated_on'=>date('Y-m-d H:i:s')
                );
                $this->db->where('category_id', $category_id);
                $this->db->update('faqs', $update_data);
                //delete category
                $this->db->where('id', $category_id);
                $this->db->limit(1);
                $this->db->delete('faq_categories');
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
            $this->db->delete('faqs');
            //delete category
            $this->db->where('id', $category_id);
            $this->db->limit(1);
            $this->db->delete('faq_categories');
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

    //list faqs - front end
    public function list_faqs(){
        $this->db->select('c.*,COUNT(f.id) as num_faqs');
        $this->db->from('faq_categories c');
        $this->db->join('faqs f', 'c.id = f.category_id', 'left');
        $this->db->group_by('c.id');
        $this->db->order_by("ordering","asc");
		$query = $this->db->get();
        //return fetched data
        if($query->num_rows() > 0){
            $categories=$query->result_array();
            $faqs=array();
            $i=0;
            foreach($categories as $category){
                $faqs[$i]['category_id']=$category['id'];
                $faqs[$i]['category_name']=$category['category_name'];
                $faqs[$i]['category_slug']=$category['slug'];
                $faqs[$i]['category_description']=$category['category_description'];
                $faqs[$i]['num_faqs']=$category['num_faqs'];
                $faqs[$i]['faqs']=$this->get_published_faqs_by_category($category['id']);
                $i++;
            }
            return $faqs;
        }else{
			return FALSE;
        }
    }

    //get published faqs by category
    public function get_published_faqs_by_category($category_id){
        $this->db->select('f.*,c.category_name,c.slug');
        $this->db->from('faqs f');
		$this->db->join('faq_categories c', 'c.id = f.category_id', 'inner');
        $this->db->where('f.category_id', $category_id);
        $this->db->where('f.status', 1);
        $this->db->order_by("ordering","asc");
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }

}

?>