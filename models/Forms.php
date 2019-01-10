<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Forms extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function forms_list($user_id, $is_logged_in) {
        $this->load->helper('common');
        $aColumns = array('form_id', 'name');
        $sJoin_q_fields = "SELECT f.form_id,f.name";
        $sJoin_q = " FROM (forms f)";
        //$sJoin_q .= ' INNER JOIN auction_product_images i ON i.product_id = p.product_id';
        //$sJoin_q .= ' INNER JOIN auction_auction aa ON aa.product_id = p.product_id';
        //$sJoin_q .= ' LEFT JOIN auction_bids ab ON ab.auction_id = aa.auction_id';
        $qJoin = $sJoin_q_fields . $sJoin_q . ' ';
        $rResult = $this->db->query($qJoin);
        $rResult_array = $rResult->result_array();
        $iFilteredTotal = count($rResult_array);
        $output = array(
            "tf" => intval($iFilteredTotal),
            "is_logged_in" => (int) $is_logged_in,
            "aaData" => array()
        );
        foreach ($rResult_array as $aRow) {
            $row = array();
            foreach ($aColumns as $col) {
                $row[$col] = $aRow[$col];
            }
            $output['aaData'][] = $row;
        }
        return $output;
    }

    function get_form_details($sTable, $sWhere, $sOrder, $sLimit, $aColumns, $sIndexColumn, $sEcho, $user_id, $form_id) {

        $sJoin_q_fields = "SELECT SQL_CALC_FOUND_ROWS o.field_id,o.post_id,o.post_values,o.created_date,o.form_process_id as action";
        $sJoin_q = " FROM ($sTable o)";
        //$sJoin_q .= ' INNER JOIN auction_order_transaction ot ON o.order_id = ot.order_id ';
        $sJoin_q .= ' WHERE o.user_id = ' . $user_id . ' AND  o.form_id = ' . $form_id . ' AND o.status=1';
        $qJoin = $sJoin_q_fields . $sJoin_q . ' ' . $sWhere . ' Group BY o.form_process_id ' . $sOrder . ' ' . $sLimit;
        
        $rResult = $this->db->query($qJoin);
        $rResult_array = $rResult->result_array();
        
        $new_data = array();
        foreach ($rResult_array as $k => $v) {
             $post_values=  unserialize($v['post_values']);
             foreach($post_values as $pv => $p){
                 $new_data[$k]['post_id'] = $v['action'];
                 $new_data[$k]['name'] = $post_values['name'];;
                 $new_data[$k]['phone'] = $post_values['phone'];
                 $new_data[$k]['email'] = $post_values['email'];
                 $new_data[$k]['created_date'] = date_dmy_hm_ap($v['created_date']);
                 $new_data[$k]['action'] = $v['action'];
                 $new_data[$k]['field_id'] = $v['field_id'];
             }
        }
        
        $iFilteredTotal = count($new_data);
        $sJoin_q_count = "SELECT COUNT(distinct(" . $sIndexColumn . ")) AS TotalRecords";
        $sQuery_TR = $sJoin_q_count . $sJoin_q . $sWhere . ' ';
        
        $rResult_TR = $this->db->query($sQuery_TR);
        $rResult_array_TR = $rResult_TR->result_array();
        $iTotal = $rResult_array_TR[0]['TotalRecords'];

        $output = array(
            "sEcho" => intval($sEcho),
            "iTotalRecords" => intval($iTotal),
            "iTotalDisplayRecords" => intval($iTotal), //$iFilteredTotal,
            "aaData" => array()
        );
       
        foreach ($new_data as $key  => $aRow) {
            $row = array();
            foreach ($aColumns as $col) {
                $row[] = $aRow[$col];
            }
            $edit_url=base_url().'admin/form/create_new_form/'.$form_id.'/'.$aRow['field_id'].'?fpi='.$aRow['action'];
            $remove_url=base_url().'admin/form/do_remove/'.$aRow['action'].'/'.$form_id;
            $details_url='#';
            $action_links = array(
                'edit' => array('url' =>$edit_url, 'icon' => '<i class="fa fa-pencil-square-o"></i>'), 
                'details' => array('url' => $details_url, 'icon' => '<i class="fa fa-file-text"></i>'), 
                'remove' => array('url' => $remove_url, 'icon' => '<i class="fa fa-remove"></i>'));
            $links = make_action($action_links);
            array_push($row, $links);
            $output['aaData'][] = $row;
        }
        return $output;
    }
    function auction($auction_id) {
        $this->load->helper('common');
        $aColumns = array('id', 'ai', 'name', 'price', 'iid', 'pi', 'asd', 'asdd', 'ps');
        $sJoin_q_fields = "SELECT p.product_id AS id,p.name,p.price,aa.auction_start_date AS asd,aa.auction_id AS ai,aa.auction_start_date AS asdd,aa.status AS ps,i.image_id AS iid,i.file_location As pi";
        $sJoin_q = " FROM (auction_products p)";
        $sJoin_q .= ' INNER JOIN auction_product_images i ON i.product_id = p.product_id';
        $sJoin_q .= ' INNER JOIN auction_auction aa ON aa.product_id = p.product_id';
        $qJoin = $sJoin_q_fields . $sJoin_q . ' WHERE aa.auction_id =' . $auction_id;
        $rResult = $this->db->query($qJoin);
        $rResult_array = $rResult->result_array();
        $iFilteredTotal = count($rResult_array);
        $output = array(
            "tp" => intval($iFilteredTotal),
            "aaData" => array()
        );
        foreach ($rResult_array as $aRow) {
            $row = array();
            foreach ($aColumns as $col) {
                if ($col == 'name') {
                    $row[$col] = truncate($aRow[$col]);
                } else {
                    $row[$col] = $aRow[$col];
                }
            }
            $output['aaData'][] = $row;
        }
        return $output;
    }
}
