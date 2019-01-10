<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CustomForms extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_step_from($form_id, $field_id) {
        $this->db->select('ff.*');
        $this->db->from('form_fields ff');
        //$this->db->join($this->tbl_user_to_role.' ur','u.user_id=ur.user_id');
        //$this->db->join($this->tbl_role.' r','r.role_id=ur.role_id');
        $this->db->where('ff.form_id', $form_id);
        $this->db->where('ff.field_id', $field_id);
        $query = $this->db->get();

        return $query->result();
    }

    function get_total_form($form_id) {
        $this->db->select('count(field_id) as totalsteps');
        $this->db->from('form_fields ff');
        $this->db->where('ff.form_id', $form_id);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->totalsteps;
    }

     function get_form_name($form_id) {
        $this->db->select('name');
        $this->db->from('forms');
        $this->db->where('form_id', $form_id);
        $query = $this->db->get();
        $result = $query->result();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return NULL;
        }
    }
    
    function get_from_data($form_process_id, $field_id) {
        $this->db->select('*');
        $this->db->from('post_values pv');
        //$this->db->join('post_values pv','pv.field_id=ff.field_id');
        //$this->db->join($this->tbl_role.' r','r.role_id=ur.role_id');
        $this->db->where('pv.form_process_id', $form_process_id);
        $this->db->where('pv.field_id', $field_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    function get_from_data_by_form_id($form_process_id, $field_id) {
        $this->db->select('post_values,field_id');
        $this->db->from('post_values pv');
        $this->db->where('pv.form_process_id', $form_process_id);
        $query = $this->db->get();
        $result = $query->result();
        $output = array();
        foreach ($result as $key => $value) {
            //unset()
            if ($field_id == $result[$key]->field_id) {
                $output = $result[$key]->post_values;
            }
        }
        return $output;
    }

    function do_update_records($table, $data, $where) {
        $this->db->where($where);
        if ($this->db->update($table, $data)) {
            $query_id = $this->db->select('post_id')->where($where)->get($table);
            return $query_id;
        } else {
            return NULL;
        }
    }

    function do_save_records($table, $data) {

        if ($this->db->insert($table, $data)) {
            return $this->db->insert_id();
        } else {
            return NULL;
        }
    }

    public function get_file_data($form_id,$form_process_id, $step_id,$doc_key) {

        $this->db->select('pd.document_id,pd.file_name,pd.file_source_name');
        $this->db->from('post_documents pd');
        $this->db->join('post_values pv', 'pd.field_id=pd.field_id', 'LEFT');
        $this->db->where('pd.field_id', $step_id);
        $this->db->where('pv.form_process_id', $form_process_id);
        $this->db->where('pv.form_id', $form_id);
        $this->db->where('pd.doc_key', $doc_key);
        $this->db->group_by('pd.document_id');
        $query = $this->db->get();
        #echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return NULL;
    }

}
