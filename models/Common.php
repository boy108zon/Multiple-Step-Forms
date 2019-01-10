<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Model {

    private $tbl_users = 'users';
    private $tbl_role = 'role';
    private $tbl_user_to_role = 'user_to_role';

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }

    function authenticate_email($email) {
        $this->db->select('u.user_id,u.email,password,CONCAT(u.first_name," ",u.last_name) AS name,u.status,u.activation_patch,r.role_id,r.name as role_name');
        $this->db->from($this->tbl_users . ' u');
        $this->db->join($this->tbl_user_to_role . ' ur', 'u.user_id=ur.user_id');
        $this->db->join($this->tbl_role . ' r', 'r.role_id=ur.role_id');
        $this->db->where('LOWER(email)=', strtolower($email));
        $this->db->where('is_deleted', 0);
        $query = $this->db->get();
        return $query->result();
    }

    function authenticate_is_deleted($table, $where) {
        $this->db->where($where);
        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return NULL;
        }
    }

    function is_email_available_update($table, $email, $id) {

        $this->db->select('1', FALSE);
        $this->db->where('LOWER(email)=', strtolower($email));
        $this->db->where('user_id !=', $id);
        $query = $this->db->get($table);
        if ($query->num_rows() == 0) {
            return false;
        } else {
            return true;
        }
    }

    function do_save_records($table, $data) {

        if ($this->db->insert($table, $data)) {
            return $this->db->insert_id();
        } else {
            return NULL;
        }
    }

    function do_remove_records($table, $where) {
        if ($this->db->delete($table, $where)) {
            return 1;
        } else {
            return NULL;
        }
    }

    function is_email_available($table, $email) {

        $this->db->select('1', FALSE);
        $this->db->where('LOWER(email)=', strtolower($email));
        $query = $this->db->get($table);
        if ($query->num_rows() == 0) {
            return NULL;
        } else {
            return true;
        }
    }

    function do_update_records($table, $data, $where) {
        $this->db->where($where);
        if ($this->db->update($table, $data)) {
            return 1;
        } else {
            return NULL;
        }
    }

    function validate_token($token) {
        $this->db->select('cbr.broker_id');
        $this->db->from('cbf_broker_registration cbr');
        $this->db->where('cbr.reset_hash', $token);
        $query = $this->db->get();
        $numrows = $query->num_rows();
        if ($numrows > 0) {
            return $query->result();
            return $row;
        } else {
            return NULL;
        }
    }

    function get_records($table, $columns, $where, $start, $limit, $flag_total_count = "NO") {

        $this->db->select($columns);
        $this->db->from($table);

        if ($flag_total_count != 'YES' && $limit != '')
            $this->db->limit($limit, $start);

        if ($where != '')
            $this->db->where($where);


        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            return NULL;
    }

    function get_re() {

        //$this->load->helper('common');
        $sJoin_q_fields = "SELECT pt.name AS property_name,pt.is_category_wise AS is_category_wise,tf.meta_id,tf.facility_key,tf.facility_value,facility_select,facility_short_info";
        $sJoin_q = " FROM (top_facilities tf)";
        $sJoin_q .= ' INNER JOIN master_top_facility_property_type mtpt ON mtpt.top_facility_id = tf.meta_id';
        $sJoin_q .= ' INNER JOIN property_type pt ON pt.property_id = mtpt.property_id';
        //$sJoin_q .= ' LEFT JOIN auction_bids ab ON ab.auction_id = aa.auction_id';
        $qJoin = $sJoin_q_fields . $sJoin_q . ' ';
        $rResult = $this->db->query($qJoin);
        $rResult_array = $rResult->result_array();
        $iFilteredTotal = count($rResult_array);
        $output = array();
        $all_facility = array_column($rResult_array, 'facility_key');
        foreach ($rResult_array as $key => $val) {
            foreach ($val as $k => $v) {
                if ($v == $all_facility[$key]) {
                    $output[$val['is_category_wise']][$val['property_name']][$v][$val['meta_id']] = $val['facility_value'];
                }
            }
        }

        return $output;
    }

    function generateFormToken() {
        $query = $this->db->query('SELECT LPAD(FLOOR(RAND()*99999), 5, 0) AS random_num FROM post_values WHERE "random_num" NOT IN (SELECT post_id FROM post_values) LIMIT 1');
        $row = $query->row();
        if (isset($row)){
            return $row->random_num;
        }else{
            $query = $this->db->query('SELECT LPAD(FLOOR(RAND()*99999), 5, 0) AS random_num');
            $row = $query->row();
            return $row->random_num;
        }
    }
}
