<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Article_model extends MY_Model{

    //get all articles
	public function get_articles($params = array()){
		$this->db->select('a.*,c.category_name,c.slug as category_slug');
        $this->db->from('articles a');
		$this->db->join('article_categories c', 'c.id = a.category_id', 'inner');

		//filter data by searched keyword
        if(!empty($params['search']['keyword'])){
            $this->db->where("(
                a.article_title LIKE '%".$params['search']['keyword']."%' 
                or a.article_excerpt LIKE '%".$params['search']['keyword']."%' 
                or a.article_description LIKE '%".$params['search']['keyword']."%'                 
                or c.category_name LIKE '%".$params['search']['keyword']."%'                 
            )");
        }
        //filter data by searched category
        if(!empty($params['search']['category'])){
            $this->db->where('a.category_id', $params['search']['category']);
        }
        //filter data by searched status
        if(!empty($params['search']['status'])){
            if($params['search']['status']=='UNPUBLISHED'){
                $status=0;
            }elseif($params['search']['status']=='PUBLISHED'){
                $status=1;
            }
            $this->db->where('a.status', $status);
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        if(array_key_exists("keep_order",$params)){
            if($params['keep_order']==TRUE){
                $this->db->order_by("a.ordering","asc");
            }else{
                $this->db->order_by("a.created_on","desc");
            }
        }else{
            $this->db->order_by("a.created_on","desc");
        }
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $articles=$query->result_array();
            $article_data=array();
            $i=0;
            foreach($articles as $article){
                foreach($article as $key => $val){
                    $article_data[$i][$key]=$val;
                }
                $article_data[$i]['usefull']=$this->get_article_votes($article['id'],1);
                $article_data[$i]['unusefull']=$this->get_article_votes($article['id'],0);
                $i++;
            }
            return $article_data;

        }else{
            return FALSE;
        }
    }
    
 	//get article by id
	public function get_article($article_id){
		$this->db->select('a.*,c.category_name,c.slug');
        $this->db->from('articles a');
		$this->db->join('article_categories c', 'c.id = a.category_id', 'inner');
		$this->db->where('a.id', $article_id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $article=$query->row_array();
            $article['usefull']=$this->get_article_votes($article['id'],1);
            $article['unusefull']=$this->get_article_votes($article['id'],0);
            return $article;

        }else{
            return FALSE;
        }
    }

	//get article by id
	public function get_article_by_slug($slug){
		$this->db->select('a.*,c.category_name,c.slug as category_slug,u.full_name,u.profile_image');
        $this->db->from('articles a');
		$this->db->join('article_categories c', 'c.id = a.category_id', 'inner');
		$this->db->join('users u', 'u.id = a.created_by', 'left');
		$this->db->where('a.slug', $slug);
		$this->db->where('a.status', 1);
		$query = $this->db->get();
		return ($query->num_rows() == 1)?$query->row_array():FALSE;
    }
    
    //get article by category id
    public function get_articles_by_category($category_id){
        $this->db->select('a.*,c.category_name,c.slug');
        $this->db->from('articles a');
		$this->db->join('article_categories c', 'c.id = a.category_id', 'inner');
        $this->db->where('a.category_id', $category_id);
        $this->db->order_by("ordering","asc");
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
	
	//create new article
	public function create_article($post_data){
        $this->_table_name='articles';
        $this->_timestamps=TRUE;
        //create article
        $insert_id=$this->save($data=$post_data, $id = NULL);
        if($insert_id){
            //create slug
            $slug=$this->create_slug($id=$insert_id, $title=$post_data['article_title']);
            //get order
            $order=$this->get_ordering_by_category($post_data['category_id']);
            $update_data=array(
                'slug'=>$slug,
                'ordering'=>$order
            );
            //update article
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

	//update article
	public function update_article($article_id,$update_data){
		$this->_table_name='articles';
		$this->_timestamps=TRUE;
		//update article
		$update_id=$this->save($data=$update_data, $id = $article_id);
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

    //article publishing
	public function article_publishing($article_id,$update_data){
		$this->_table_name='articles';
		$this->_timestamps=TRUE;
		//update article status
		$update_id=$this->save($data=$update_data, $id = $article_id);
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

    //article ordering
    public function update_article_ordering($sorted_data=array()){
        $this->db->trans_start();
        $i=1;
        foreach($sorted_data as $key => $value){
            $update_data=array(
                'ordering'=>$i,
                'updated_on'=>date("Y-m-d H:i:s"),
            );
            $this->db->where('id', $value);
            $this->db->update('articles', $update_data);
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

    //delete article
	public function delete_article($article_id){
		$this->db->trans_start();
		//delete article votes
        $this->db->where('article_id', $article_id);
		$this->db->delete('article_votes');
		//delete ticket
		$this->db->where('id', $article_id);
        $this->db->limit(1);
		$this->db->delete('articles');
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
		$this->db->select('c.*,COUNT(a.id) as num_articles');
        $this->db->from('article_categories c');
        $this->db->join('articles a', 'c.id = a.category_id', 'left');
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
        $this->db->from('article_categories c');
        $this->db->where('c.id !=', $category_id);
        $query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
    
    //get category by id
    public function get_category($category_id){
        $this->db->select('c.*,COUNT(a.id) as num_articles');
        $this->db->from('article_categories c');
        $this->db->join('articles a', 'c.id = a.category_id', 'left');
        $this->db->where('c.id', $category_id);
        $this->db->group_by('c.id');
        $query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->row_array():FALSE;
    }

    //get category by slug
    public function get_category_by_slug($slug){
        $this->db->select('c.*,COUNT(a.id) as num_articles');
        $this->db->from('article_categories c');
        $this->db->join('articles a', 'c.id = a.category_id', 'left');
        $this->db->where('c.slug', $slug);
        $this->db->group_by('c.id');
        $query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->row_array():FALSE;
    }
    
    //create new faq category
    public function create_category($post_data){
        $this->_table_name='article_categories';
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
        $this->_table_name='article_categories';
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
            $this->db->update('article_categories', $update_data);
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

    //delete article category
    public function delete_article_category($category_id,$transfer_category,$complete_delete){
        $this->db->trans_start();
        //get category to unlink category icon 
        $category=$this->get_category($category_id);
        if($category){
            if($category['category_icon']!=NULL){
                @unlink(FCPATH.'uploads/categories/'.$category['category_icon']);
            }
        }
        if($transfer_category==0 && $complete_delete==0){
            //delete category only
            $this->db->where('id', $category_id);
            $this->db->limit(1);
            $this->db->delete('article_categories');
            
        }elseif($transfer_category!=0 && $complete_delete==0){
            $category=$this->get_category($transfer_category);
            if($category){
                //update new category id to items
                $update_data=array(
                    'category_id'=>$transfer_category,
                    'updated_on'=>date('Y-m-d H:i:s')
                );
                $this->db->where('category_id', $category_id);
                $this->db->update('articles', $update_data);
                //delete category
                $this->db->where('id', $category_id);
                $this->db->limit(1);
                $this->db->delete('article_categories');
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
            $this->db->delete('articles');
            //delete category
            $this->db->where('id', $category_id);
            $this->db->limit(1);
            $this->db->delete('article_categories');
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

    //get article votes count
    public function get_article_vote_counts($article_id){
        $total_votes=0;
		$up_votes=0;
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
        //return data
        $return_data=array(
            'total_votes'=>$total_votes,
            'up_votes'=>$up_votes,
        );
        return $return_data;
    }

    //get article votes
    public function get_article_votes($article_id,$status){
        //get count of all up votes
		$this->db->select('v.*');
		$this->db->from('article_votes v');
		$this->db->where('v.article_id', $article_id);
		$this->db->where('v.status', $status);
		$query = $this->db->get();
        return $query->num_rows();
    }

    //Get Recent Articles
    public function get_recent_articles(){
        $this->db->select('a.*,c.category_name,c.slug as category_slug');
        $this->db->from('articles a');
        $this->db->join('article_categories c', 'c.id = a.category_id', 'inner');
        $this->db->where('a.status', 1);
        $this->db->limit(5);
        $this->db->order_by("a.created_on","desc");
        $query = $this->db->get();
		//return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }

}

?>