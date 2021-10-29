<?php
/**
 * -----------------------------IMPORTANT-------------------------------
 * Programmer should NOT change or add any code without having a better
 * understanding how MY_MODEL and Its methods been used
 * ---------------------------------------------------------------------
 *
 * My_Model will be used for all the CRUD operations in the system.
 *
 * All the other models should be extend form My_Model
 * Most of the common operations been written in the My_Model so that
 * programmer can easily call methods in My_Model Class for all most
 * all Database Communication and minimize the coding in their projects.
 *
 */
class MY_Model extends CI_Model
{
    protected $_table_name = '';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = '';
    public    $rules = array();
    protected $_timestamps = FALSE;
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Purpose of the function is to reduce the code and minimize the error which counld occer
     * during the getting the form data using POST method.
     * in complex form when dealing with large amount of input programmer just need to send an
     * array of the form field list and then this function return all form data inputs as data array,
     * array key as the form input name and value as the form input value
     * example return:
     *        array(
     *         'first_name' => 'Clark',
     *         'last_name' => 'Kent'
     *        );
     *
     * @param  Array      $fields    list of Form feilds name.
     * @throws Some_Exception_Class If something interesting cannot happen
     * @return array result set
     */
    public function array_from_post($fields)
    {
        $data = array();
        foreach($fields as $field)
        {
            $data[$field] = $this->input->post($field);
        }
        return $data;
    }
    /**
     * Purpose of the function is to enable pagination inbuild for the programmer so that Programming could be more rapid.
     *
     * @param  Integer      $perPage    number of records per page, default set to 20
     * @param  Integer      $pageNumber current page number, default set to '0'
     * @param  Array Obj    $where      if additional query required that can be provided with this parameter, by default set to NULL
     * @param  Text         $AsOrder    ordering the result, default set to 'Assending'
     * @throws Some_Exception_Class If something interesting cannot happen
     * @return result set as an Array()
     */
    public function pagination($perPage=20, $pageNumber=0, $where=NULL, $AsOrder = "asc")
    {
        if($where==NULL)
        {
            return $this->get($id = NULL, $single = NULL, $AsOrder, $perPage, $pageNumber);
        }
        else
        {
            $this->db->where($where, NULL);
            return $this->get(NULL, NULL, $AsOrder, $perPage, $pageNumber);
        }
    }
    /**
     * Purpose of the function is to return a result set to used in the dropdown list.
     * array key will be the table ID , value will be the $field
     * example return:
     *          array(
     *              '1' => 'Sri Lanka',
     *              '2' => 'United Kingdon'
     *          );
     *
     * @param  Text         $field    feild name of the databse which reqired to be displayed in the value section in dropdown list.
     * @param  STDObject    $query    if additional query required it should be provided as STDObject example Array('country_name)'=>'Sri Lanka')
     * @throws Some_Exception_Class If something interesting cannot happen
     * @return set of array which suite to use in dropdown boxs' dataset.
     */
    public function dropdown_list($field, $emptySelect='Please Select', $query=null , $firstvalEmpty = 1)
    {
        if($query==null){
            $records = $this->get();
        }else{
            $records = $this->get_by($query);
        }
        // setting the dropdown list array
        if($firstvalEmpty==1){
            $fields_set = array(''=>$emptySelect);
        }
       // $fields_set = array(''=>''); // default/empty value
        // adding each result row to dropdown lisst array
        if(count($records)){
            foreach($records as $record){
                $fields_set[$record->id] = $record->$field;
            }
        }
        // reurn dropdown result set array
        return $fields_set;
    }
    /**
     * Purpose of the function is to return a specific record if '$id' is set which is the table primary key,
     * or return all records in specific table.
     * this function also used by the 'get_by' methods which will return single result as a row or
     * lines of result as a result set.
     *
     * @param  Integer  $id             ID of the table row which is looking for
     * @param  Boolean  $single         construct to help get_by method, If 'TRUE' meaning return single row, ELSE return result set array
     * @param  Text     $AsOrder        record set order, default set to 'Assending'
     * @param  Integer  $limit          Number of records limit, default set to NULL
     * @param  Integer  $pageNumber     page number *** this uses only in the pagination function is executed.***
     * @throws Some_Exception_Class If something interesting cannot happen
     * @return row / all rows in the specific table
     */
    public function get($id = NULL, $single = NULL, $AsOrder = "asc", $limit= NULL, $pageNumber=0)
    {
        if ($id != NULL)
        {
            $filter = $this->_primary_filter;
            $id = $filter($id);  // Added Security
            $this->db->where($this->_primary_key, $id);
            $method = 'row_array'; // single record
        }
          elseif($single == TRUE)
        {
            $method = 'row_array'; // single record
        }
          else
        {
            $method = 'result_array'; // all record
        }

        if($this->db->order_by($this->_order_by)!=null) 
        {
            $this->db->order_by($this->_order_by);
        }

        if(!$limit==NULL)
        {
            $this->db->limit($limit,$pageNumber);
        }

      // return as row or result set according to value of $single
      return $this->db->get($this->_table_name)->$method();
    }

    /**
     * Purpose of the function is to return a specific record/records based on the $where condition.
     * default $single value set to 'FALSE' so that function returns result set. when retrieving single
     * row data $single can be set to 'TRUE' which returns an ROW.
     *
     * @param  Integer  $where  Array() or content which mentioning the scope of filtering
     * @param  Boolean  $single construct to help get_by method, If 'TRUE' meaning return single row, ELSE return result set
     * @return row / all rows in the specific table
     */
    public function get_by($where, $single = FALSE, $AsOrder = "asc", $limit= NULL, $pageNumber=0)
    {
        $this->db->where($where);
        return $this->get(NULL, $single, $AsOrder, $limit, $pageNumber);
    }
    /**
     * Both 'SAVE' and 'INSERT' operations been handled from this function.
     * logic behind is, if $id is set then it will UPDATE and if '$id' set to NULL it will INSERT
     *
     * @param  Array()  $data Array() of data.
     * @param  Integer  $id   Page ID
     * @return id value of the updated/inserted row
     */
    public function save($data, $id = NULL)
    {
      //if timestamp is TRUE, set Timestamp
      if($this->_timestamps == TRUE)
      {
          $now = date('Y-m-d H:i:s');
          $id || $data['created_on'] = $now; // if ID is set leave, else set $data['created_on'] = $now
          $data['updated_on'] = $now;
      }
      //insert
      if($id === NULL)
      {
          !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
          $this->db->set($data);
          $this->db->insert($this->_table_name);
          $id = $this->db->insert_id(); // fetch the ID of the newly inserted row
      }
      //update
      else
      {
          $filter = $this->_primary_filter; // filer the primary key
          $id = $filter($id); // filer the primary key
          $this->db->set($data);
          $this->db->where($this->_primary_key, $id);
          $this->db->update($this->_table_name);
      }
      return $id;
    }
    /**
     * delete record by table primary key
     *
     * @param  Integer  $id   primary key of the table
     * @return false if $id is not set and otherwise NO returns
     */
    public function delete($id)
    {
        $filter = $this->_primary_filter;
        $id = $filter($id);
        if (!$id)
        {
          return FALSE;
        }
        $this->db->trans_start();
        $this->db->where($this->_primary_key, $id);
        $this->db->limit(1);
        $this->db->delete($this->_table_name);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
        }
    }
    /**
     * purpose of this function is to return a record set with NOT EQUAL condition
     * example:
     * get students who are not in Colombo District.
     * $this->student_m->get_by_not('district','colombo');
     *
     * @param  String  $field    database field name
     * @param  String  $values   value which should be check with database column
     * @param  Boolean $single   if TRUE return single ROW and FALSE will return record set Array()
     * @return false if $id is not set and otherwise NO returns
     */
    public function get_by_not($field, $values,$single = FALSE){
        $this->db->where_not_in($field,$values);
        return $this->get(NULL,$single);
    }
    /**
     * purpose of this function is to return a Sum of a given table collum with condition
     * example:
     * get total payment recived on '2014-02-14'
     * $this->student_m->get_sum('amount',array('payment_date' => '2014-02-14'));
     *
     * @param  String  $field    database field name
     * @param  String  $where    query string as referential array
     * @return false if $id is not set and otherwise NO returns
     */
    public function get_sum($field, $where){
        $this->db->select_sum($field);
        $this->db->where($where);
        return $this->db->get($this->_table_name)->row();
    }

    public function create_slug($id,$title)
    {
        $count = 0;
        $title = url_title($title);
        $slug_title = $title;
        while(true) 
        {
            $this->db->select('id');
            $this->db->where('id !=', $id);
            $this->db->where('slug', $slug_title);
            $query = $this->db->get($this->_table_name);
            if ($query->num_rows() == 0) break;
            $slug_title = $title . '-' . (++$count);
        }
        return strtolower($slug_title);
    }

    //get ordering
    public function get_ordering(){
        $this->db->select_max('ordering');
        $result = $this->db->get($this->_table_name)->row();  
        return $result->ordering+1;
    }

    //get ordering by category
    public function get_ordering_by_category($category_id){
        $this->db->select_max('ordering');
        $this->db->where('category_id', $category_id);
        $result = $this->db->get($this->_table_name)->row(); 
        return $result->ordering+1;
    }

    //count table rows
    public function count_records(){
        $result = $this->db->from($this->_table_name)->count_all_results();
        return $result;
    }
}