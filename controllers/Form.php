<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Form extends MY_Controller {

    public $today = '';
    protected $is_logged_in = 0;
    protected $user_id = 0;
    protected $total_steps = 0;

    public function __construct() {
        parent::__construct();
   }

    public function index() {
        $data['main'] = 'admin/forms/index';
        $data['footer'] = 'admin/template/footer';
        $this->load->vars($data);
        $this->load->view($this->admin_dashboard);
    }

    public function form_details() {

        $form_id = $this->uri->segment(4, 0);
        $this->load->model('common');
        $form_process_id = $this->common->generateFormToken();
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

        $data['meta_title'] = 'Create new Form';
        $data['meta_keywords'] = 'Create new Form';
        $data['meta_desc'] = 'Create new Form';
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

    

    public function do_remove() {

        $this->load->model('common');
        $form_process_id = $this->uri->segment(4);
        $form_id = $this->uri->segment(5);
        $form_info = $this->common->get_records('post_values', '', array('form_process_id' => $form_process_id, 'form_id' => $form_id), $start = '', $limit = '', $flag_total_count = "NO");
        if (count($form_info) > 0) {
            $document_info = $this->common->get_records('post_documents', array('file_name'), array('form_process_id' => $form_process_id), $start = '', $limit = '', $flag_total_count = "NO");
            if (count($document_info) > 0) {
                foreach ($document_info as $key => $value) {
                    $filePath = UPLOAD_PATH_FORMSTEP_BACKEND . '/' . $document_info[$key]['file_name'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                $this->common->do_remove_records('post_documents', array('form_process_id' => $form_process_id));
            }
            $result = $this->common->do_remove_records('post_values', array('form_process_id' => $form_process_id, 'form_id' => $form_id));
            if (!empty($result)) {
                echo json_encode(array('Mstatus' => 'success', 'process_id' => 'tr_' . $form_process_id . '', 'msg' => alertmessage($message_type = 'alert-success', $message = 'Form has been removed successfully.')));
            } else {
                echo json_encode(array('Mstatus' => 'error', 'msg' => alertmessage($message_type = 'alert-danger', $message = 'There is some problem in procesing.')));
            }
        }
    }

    public function load_file_process() {

        if (($this->user_id === 0) && !is_numeric($this->user_id)) {
            echo json_encode(array('Mstatus' => 'error', 'msg' => 'Your session expired. Please login to continue.'));
            exit;
        }

        $form_id = urldecode($this->input->post('form_id', TRUE));
        $form_process_id = urldecode($this->input->post('form_process_id', TRUE));
        $step_id = urldecode($this->input->post('step_id', TRUE));
        $doc_key = urldecode($this->input->post('doc_key', TRUE));

        if (!isset($form_id) && !isset($form_process_id) && $form_id === 0 && $form_process_id === 0) {
            echo json_encode(array('Mstatus' => 'error', 'msg' => 'Invalid request'));
            exit;
        }

        $request_type = 'step_form';
        $this->load->model('CustomForms');

        $data['form_id'] = $form_id;
        $data['form_process_id'] = $form_process_id;
        $data['step_id'] = $step_id;
        $data['doc_key'] = $doc_key;
        $form_info = $this->CustomForms->get_file_data($form_id, $form_process_id, $step_id, $doc_key);
        if (!empty($form_info)) {
            $output = '';
            $files = array();
            foreach ($form_info as $key => $value) {
                $files[] = array("name" => $form_info[$key]->file_name, "size" => 34343, "type" => 'image/jpeg');
            }
            $data['previousFiles'] = json_encode($files);
        }
        $data['form_info'] = $form_info;
        $html = $this->load->view('admin/stepforms/custom_file_upload', $data, true);
        echo json_encode(array('Mstatus' => 'success', 'returnhtml' => $html, 'msg' => '', 'form_id' => $form_id, 'form_process_id' => $form_process_id, 'step_id' => $step_id, 'doc_key' => $doc_key));
    }

    public function list_all() {
        $this->load->model('CustomForms');
        $form_id = urldecode($this->input->post('form_id', TRUE));
        $form_process_id = urldecode($this->input->post('form_process_id', TRUE));
        $step_id = urldecode($this->input->post('step_id', TRUE));
        $doc_key = urldecode($this->input->post('doc_key', TRUE));
        if (!isset($form_id) && !isset($form_process_id) && $form_id === 0 && $form_process_id === 0) {
            echo json_encode(array('Mstatus' => 'error', 'msg' => 'Invalid request'));
            exit;
        }
        $form_info = $this->CustomForms->get_file_data($form_id, $form_process_id, $step_id, $doc_key);
        $files = array();
        foreach ($form_info as $key => $value) {
            $files[] = array('doc_id' => $form_info[$key]->document_id, "name" => $form_info[$key]->file_source_name, "size" => $form_info[$key]->file_size, "type" => $form_info[$key]->file_type, 'path' => base_url() . 'assets/images/formdocs/' . $form_info[$key]->file_name);
        }
        echo json_encode($files);
    }

    function do_upload_form_document() {

        if (($this->user_id === 0) && !is_numeric($this->user_id)) {
            echo json_encode(array('Mstatus' => 'error', 'msg' => 'Your session expired. Please login to continue.'));
            exit;
        }

        $this->load->library('upload');
        $this->load->library('form_validation');
        $form_id = $this->input->post('m_form_id');
        $form_process_id = $this->input->post('m_form_process_id');
        $m_step_id = $this->input->post('m_step_id');
        $m_doc_key = $this->input->post('m_doc_key');
        if (isset($_FILES["file"])) {
            if ($form_id == '' && $form_process_id = '') {
                echo json_encode(array('Mstatus' => 'error', 'msg' => alertmessage($message_type = 'alert-danger', 'Invalid process no form associated.')));
                exit;
            }

            $files = $_FILES;

            $ret = array();
            $cpt = count($_FILES['file']['name']);
            $errors = '';
            $encrypt_name = '';
            $banner_name = '';
            $image_type = '';
            if (isset($_FILES["file"]["name"])) {

                $_FILES['userfile']['name'] = $files['file']['name'];
                $_FILES['userfile']['type'] = $files['file']['type'];
                $_FILES['userfile']['tmp_name'] = $files['file']['tmp_name'];
                $_FILES['userfile']['error'] = $files['file']['error'];
                $_FILES['userfile']['size'] = $files['file']['size'];
                $this->upload->initialize($this->set_upload_options());
                $errors = $this->upload->display_errors('', '');
                if ($errors == '' && empty($errors)) {
                    $this->upload->do_upload('userfile');
                    $errors = $this->upload->display_errors('', '');
                    $Imagedata = $this->upload->data();

                    $ret[] = $Imagedata['file_name'];
                    $encrypt_name = $Imagedata['file_name'];
                    $banner_name = $Imagedata['client_name'];
                    $image_type = $Imagedata['file_type'];
                    $image_size = $Imagedata['file_size'];
                }
            }
            if ($errors != '' && !empty($errors)) {
                $this->form_validation->set_rules('userfile', $errors, 'required');
            }
            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run() !== FALSE) {
                echo json_encode(array('Mstatus' => 'error', 'msg' => alertmessage($message_type = 'alert-danger', validation_errors())));
            } else {
                $data = array('file_size' => $image_size, 'file_type' => $image_type, 'field_id' => $m_step_id, 'form_process_id' => $form_process_id, 'file_source_name' => $banner_name, 'file_name' => $encrypt_name, 'created_date' => $this->today, 'doc_key' => $m_doc_key);
                $this->load->model('common');
                $result = $this->common->do_save_records('post_documents', $data);
                $files = array('id' => $result, "name" => $banner_name, "size" => $image_size, "type" => $image_type, 'path' => base_url() . 'assets/images/formdocs/' . $banner_name);
                echo json_encode($files);
            }
        }
    }

    private function set_upload_options() {
        //  upload an image options
        $config = array();
        $config['upload_path'] = UPLOAD_PATH_FORMSTEP_BACKEND;
        $config['allowed_types'] = 'doc|pdf|docx';
        $config['max_size'] = '0';
        $config['overwrite'] = FALSE;
        $config['remove_spaces'] = TRUE;
        $config['encrypt_name'] = TRUE;
        return $config;
    }

    public function do_remove_form_document() {

        if (($this->user_id === 0) && !is_numeric($this->user_id)) {
            echo json_encode(array('Mstatus' => 'error', 'msg' => 'Your session expired. Please login to continue.'));
            exit;
        }
        $action_type = urldecode($this->input->post('op', true));
        $document_id = urldecode($this->input->post('bid', true));
        
        if (isset($action_type) && $action_type == "remove" && isset($document_id)) {
            $this->load->model('common');
            $document_info = $this->common->get_records('post_documents', array('file_name'), array('document_id' => $document_id), $start = '', $limit = 1, $flag_total_count = "NO");

            if (count($document_info) > 0) {
                $filePath = UPLOAD_PATH_FORMSTEP_BACKEND . '/' . $document_info[0]['file_name'];
                $this->common->do_remove_records('post_documents', array('document_id' => $document_id));
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                echo json_encode(array('Mstatus' => 'success', 'msg' => "", 'idds' => 'custom-status_' . $document_id . ''));
            } else {
                echo json_encode(array('Mstatus' => 'success', 'msg' => "Error in processing your request.", 'idds' => ''));
            }
        }
    }

}
