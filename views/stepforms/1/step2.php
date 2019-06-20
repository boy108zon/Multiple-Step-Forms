<?php if (!empty($form_info)) { ?>
    <?php
    if (!empty($custom_file_upload))
        $this->load->view($custom_file_upload);
    if (!empty($form_data)) {
        $form_data = unserialize($form_data[0]->post_values);
    }
    ?>
    <section class="custome-content-header">
        <div>
            <?php echo!empty($form_info) ? $form_info[0]->form_heading : ''; ?>
            <small></small>
        </div>
    </section>
    <div class="box box-info">
        <?php echo form_open(base_url() . 'admin/form/create_new_form/' . $form_id . '/2?fpi=' . $form_process_id . '', array('class' => "", 'autocomplete' => "on", 'enctype' => "multipart/form-data", 'id' => 'form2', 'name' => 'form2', 'method' => 'post')); ?>
        <div class="box-header with-border">
    <!--            <h3 class="box-title"><?php echo!empty($form_info) ? $form_info[0]->form_heading : ''; ?></h3>-->
        </div>
        <div class="box-body">
            <input type="hidden" name="form_id" id="form_id" value="<?php echo!empty($form_info) ? $form_info[0]->form_id : ''; ?>"> 
            <input type="hidden" name="step_id" id="step_id" value="<?php echo $next_step; ?>">
            <div class="form-group">
                <label for="name">Proposed Company Name 1</label>
                <input type="text" name="proposed_company_name_1" value="<?php echo isset($form_data['proposed_company_name_1']) ? $form_data['proposed_company_name_1'] : ''; ?>" id="proposed_company_name_1" placeholder="Proposed Company Name 1" class="form-control" /> 
            </div>
            <div class="form-group">
                <label for="email" class="">Proposed Company Name 2</label>
                <input type="text" name="proposed_company_name_2" value="<?php echo isset($form_data['proposed_company_name_2']) ? $form_data['proposed_company_name_2'] : ''; ?>" id="proposed_company_name_2" placeholder="Proposed Company Name 2" class="form-control" /> 
            </div>
            <div class="row">
                <div class="form-group col-xs-7">
                    <label for="phone" class="">Main Object</label>
                    <input type="text" name="main_object" value="<?php echo isset($form_data['main_object']) ? $form_data['main_object'] : ''; ?>" id="main_object" placeholder="Main Object" class="form-control" />
                </div>
                <div class="form-group col-xs-4">
                    <label for="phone" class="">Attachment</label>
                    <div> 
                        <button type="button" class="btn bg-navy btn-flat" onclick="javascript:loadcustome_banners('<?php echo isset($form_id) ? $form_id : 0; ?>', '<?php echo isset($form_process_id) ? $form_process_id : 0; ?>', '2', 'main_object');">
                            Please click to add 
                        </button>
                    </div>    
                </div>
            </div>
            <div class="form-group">
                <label>TradeMark</label>
                <?php $open_status = ''; ?>
                <?php
                $open_status = '';
                if (isset($form_data['trademark'])) {
                    if (trim($form_data['trademark']) == 'Yes') {
                        $open_status = '';
                    } else {
                        $open_status = ' hidden';
                    }
                }
                ?>
                <select class="form-control" id="trademark" name="trademark">
                    <?php if (isset($form_data['trademark'])) { ?>
                        <?php if (trim($form_data['trademark']) == 'Yes') { ?>
                            <option value="Yes" selected="selected">Yes</option>
                            <option value="No">No</option>
                        <?php } else { ?>
                            <option value="Yes">Yes</option>
                            <option value="No" selected="selected">No</option>
                        <?php } ?>

                    <?php } else { ?>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group trademark <?php echo $open_status; ?>">
                <label for="city" class="">Trademark Application</label>
                <input type="text" name="trademark_yes_no" value="<?php echo isset($form_data['trademark_yes_no']) ? $form_data['trademark_yes_no'] : ''; ?>" id="trademark_yes_no" placeholder="TradeMark" class="form-control " /> 
            </div>    
        </div>
        <div class="box-footer">
            <div class="form-group pull-left">

                <a href="<?php echo base_url() . 'admin/form/create_new_form/' . $form_info[0]->form_id . '/1/?fpi=' . $form_process_id . '&p=' . $actual_step_id_p . ''; ?>" class="btn bg-orange btn-flat pull-right">Prev</a>
            </div>
            <div class="form-group">
                <input type="submit" class="btn bg-orange btn-flat pull-right" value="Next"/>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <script>
        $(document).ready(function () {
            $("#trademark").change(function () {
                if (this.value == 'Yes') {
                    $(".trademark").removeClass('hidden');
                } else {
                    $(".trademark").addClass('hidden');
                }
            });
        });
    </script>
    <?php
}?>