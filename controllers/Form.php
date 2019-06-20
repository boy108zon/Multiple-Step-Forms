<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Form extends MY_Controller {

    public $today = '';
    protected $is_logged_in = 0;
    protected $user_id = 0;
    protected $total_steps = 0;

    public function __construct() {
        parent::__construct();
        $this->today = date('Y-m-d H:i:s');
    }

    public function index() {

        $data['meta_title'] = 'Test | Dashboard';
        $data['meta_keywords'] = 'Test  | Dashboard';
        $data['meta_desc'] = 'Test  | Dashboard';

        $data['header'] = 'admin/template/header';
        $data['sidebar'] = 'admin/template/sidebar';
        $data['main'] = 'admin/forms/index';
        $data['footer'] = 'admin/template/footer';
        $this->load->vars($data);
        $this->load->view($this->admin_dashboard);
    }

    public function form_details() {

        $form_id = $this->uri->segment(4, 0);

        $this->load->model('common');
        $form_process_id = $this->common->generateFormToken();

        $data['meta_title'] = 'Test | Dashboard';
        $data['meta_keywords'] = 'Test | Dashboard';
        $data['meta_desc'] = 'Test | Dashboard';

        $forms = $this->common->get_records('forms', $column = array('form_id', 'name'), $where = array('form_id' => $form_id), $start = '', $limit = 1, $flag_total_count = "NO");

        $data['header'] = 'admin/template/header';
        $data['sidebar'] = 'admin/template/sidebar';
        $data['main'] = 'admin/forms/form_details';
        $data['footer'] = 'admin/template/footer';
        $data['form_process_id'] = $form_process_id;
        $data['forms'] = $forms;
        $this->load->vars($data);
        $this->load->view($this->admin_dashboard);
    }

    public function create_new_form() {

        $data['meta_title'] = 'True Startup | Create new Form';
        $data['meta_keywords'] = 'True Startup | Create new Form';
        $data['meta_desc'] = 'True Startup | Create new Form';
        $form_process_id = (int) $this->input->get('fpi');
        $is_previous_action = $this->input->get('p');
        $form_id = $this->uri->segment(4, 0);
        $step_id_u = $this->uri->segment(5, 1);
        $step_id_p = isset($_POST['step_id']) ? (int) $_POST['step_id'] + 1 : $step_id_u;
        $this->load->model('common');
        $this->load->model('CustomForms');
        $total_steps = $this->CustomForms->get_total_form($form_id);
        $form_name_info = $this->CustomForms->get_form_name($form_id);
        if ($step_id_p != '') {
            if ($is_previous_action != '') {
                $field_id = (int) $step_id_p;
            } else {
                $field_id = (int) $step_id_p;
            }
        } else {
            $field_id = $step_id_u;
        }

        $form_info = $this->CustomForms->get_step_from($form_id, $field_id);
        $form_data = $this->CustomForms->get_from_data($form_process_id, $field_id);

        $save_id = '';
        if (!empty($_POST) && is_array($_POST)) {
            $sdata = $this->input->post();
            unset($sdata['step_id']);
            unset($sdata['form_id']);

            if ($this->input->get('isf') == 'yes') {
                if (!empty($form_data)) {
                    $save_step_data = array('post_values' => serialize($sdata), 'status' => 1);
                    $save_id = $this->CustomForms->do_update_records('post_values', $save_step_data, array('form_process_id' => $form_process_id, 'field_id' => $step_id_u));
                } else {
                    $save_step_data = array('status' => 1, 'form_process_id' => $form_process_id, 'user_id' => $this->user_id, 'form_id' => $form_id, 'field_id' => $step_id_u, 'post_values' => serialize($sdata), 'created_date' => $this->today);
                    $save_id = $this->CustomForms->do_save_records('post_values', $save_step_data);
                }
                redirect(base_url() . 'admin/form/form_details/' . $form_id);
                exit;
            } else {
                if (!empty($form_data)) {
                    $save_step_data = array('status' => 1, 'post_values' => serialize($sdata));
                    $save_id = $this->CustomForms->do_update_records('post_values', $save_step_data, array('form_process_id' => $form_process_id, 'field_id' => $step_id_u));
                } else {
                    $save_step_data = array('status' => 1, 'form_process_id' => $form_process_id, 'user_id' => $this->user_id, 'form_id' => $form_id, 'field_id' => $step_id_u, 'post_values' => serialize($sdata), 'created_date' => $this->today);
                    $save_id = $this->CustomForms->do_save_records('post_values', $save_step_data);
                }
            }
        }

        $data['form_process_id'] = $form_process_id;
        $data['form_info'] = $form_info;
        $data['form_data'] = $form_data;
        $data['form_id'] = $form_id;
        $data['next_step'] = $form_info[0]->field_id;
        $data['actual_step_id_p'] = $step_id_p;
        $data['post_id'] = $save_id;
        $data['form_name_info'] = $form_name_info;
        $data['header'] = 'admin/template/header';
        $data['sidebar'] = 'admin/template/sidebar';
        $data['main'] = 'admin/stepforms/' . $form_id . '/' . $form_info[0]->step;
        $data['custom_file_upload'] = 'admin/stepforms/custom_file_upload_mp';
        $data['footer'] = 'admin/template/footer';
        $this->load->vars($data);
        $this->load->view($this->admin_dashboard);
    }
