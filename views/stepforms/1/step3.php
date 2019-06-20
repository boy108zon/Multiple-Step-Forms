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
            <?php echo !empty($form_info) ?  $form_info[0]->form_heading: '';?>
            <small></small>
        </div>
    </section>
    <div class="box box-info">
        <?php echo form_open(base_url() . 'admin/form/create_new_form/' . $form_id . '/3?fpi=' . $form_process_id . '', array('class' => "", 'autocomplete' => "on", 'enctype' => "multipart/form-data", 'id' => 'form3', 'name' => 'form3', 'method' => 'post')); ?>
        <div class="box-header with-border">
<!--            <h3 class="box-title"><?php echo!empty($form_info) ? $form_info[0]->form_heading : ''; ?></h3>-->
        </div>
        <div class="box-body">
            <input type="hidden" name="form_id" id="form_id" value="<?php echo!empty($form_info) ? $form_info[0]->form_id : ''; ?>"> 
            <input type="hidden" name="step_id" id="step_id" value="<?php echo $next_step; ?>">
            <div class="form-group">
                <label for="name">Registered Address</label>
                <textarea placeholder="Registered Address" name="registered_address" id="registered_address" class="form-control"><?php echo isset($form_data['registered_address']) ? $form_data['registered_address'] : ''; ?></textarea>
                <span class="textcolor-info">Address should be same in document uploaded.</span>
            </div>

            <div class="row">
                <div class="form-group col-xs-6">
                    <label for="proof_of_registered_address_type" class="">Proof of Registered Address Type</label>
                    <select class="form-control" id="proof_of_registered_address_type" name="proof_of_registered_address_type">
                        <?php if (isset($form_data['proof_of_registered_address_type'])) { ?>
                            <?php if (trim($form_data['proof_of_registered_address_type']) == 'Utility Bill') { ?>
                                <option value="">Select</option>
                                <option value="Utility Bill" selected="selected">Utility Bill</option>
                                <option value="Mobile Bill">Mobile Bill</option>
                            <?php } else { ?>
                                <option value="">Select</option>
                                <option value="Utility Bill">Utility Bill</option>
                                <option value="Mobile Bill" selected="selected">Mobile Bill</option>
                            <?php } ?>

                        <?php } else { ?>
                            <option value="">Select</option>    
                            <option value="Utility Bill">Utility Bill</option>
                            <option value="Mobile Bill">Mobile Bill</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-xs-6">
                    <label for="phone" class="">Attachment</label>
                    <div> 
                        <button type="button" class="btn bg-navy btn-flat" onclick="javascript:loadcustome_banners('<?php echo isset($form_id) ? $form_id : 0; ?>', '<?php echo isset($form_process_id) ? $form_process_id : 0; ?>', '3','proof_of_registered_address_type');">
                            Please click to add 
                        </button>
                    </div>    
                </div>
            </div>
            <div class="form-group">
                <label for="name">Name Of The Owner</label>
                <input type="text" name="name_of_the_owner" value="<?php echo isset($form_data['name_of_the_owner']) ? $form_data['name_of_the_owner'] : ''; ?>" id="name_of_the_owner" placeholder="Name of the owner" class="form-control" />
                <span class="textcolor-info">As per proof attached.</span>
            </div>

            <div class="row">
                <div class="form-group col-xs-4">
                    <label>Nature of Ownership</label>
                    <?php
                     $nature_of_ownership=isset($form_data['nature_of_ownership']) ? $form_data['nature_of_ownership'] : '';
                    ?>
                    <select class="form-control" id="nature_of_ownership" name="nature_of_ownership" onchange="">
                        <option value="">Select</option>
                        <option value="Individual"<?php if($nature_of_ownership == "Individual") echo ' selected="selected"';?>>Individual</option>
                        <option selected="Partnership"<?php if($nature_of_ownership == "Partnership") echo ' selected="selected"';?>>Partnership</option>
                        <option value="LLP"<?php if($nature_of_ownership == "LLP") echo ' selected="selected"';?>>LLP</option>
                        <option value="Company"<?php if($nature_of_ownership == "Company") echo ' selected="selected"';?>>Company</option>
                        <option value="Others"<?php if($nature_of_ownership == "Others") echo ' selected="selected"';?>>Others</option>
                    </select>
                </div>
                <div class="form-group col-xs-4">
                     <label>Ownership Proof Type</label>
                     <?php
                     $ownership_proof_type=isset($form_data['ownership_proof_type']) ? $form_data['ownership_proof_type'] : '';
                     ?>
                        <select class="form-control" id="ownership_proof_type" name="ownership_proof_type" onchange="">
                            <option value="">Select</option>
                            <option value="Rent Agreement"<?php if($ownership_proof_type == "Rent Agreement") echo ' selected="selected"';?>>Rent Agreement</option>
                            <option selected="Lease Agreement"<?php if($ownership_proof_type == "Lease Agreement") echo ' selected="selected"';?>>Lease Agreement</option>
                            <option value="Index II Copy"<?php if($ownership_proof_type == "Index II Copy") echo ' selected="selected"';?>>Index II Copy</option>
                            <option value="Purchase Agreement"<?php if($ownership_proof_type == "Purchase Agreement") echo ' selected="selected"';?>>Purchase Agreement</option>
                        </select>
                </div>
                <div class="form-group col-xs-4">
                    <label for="phone" class="">Attachment</label>
                    <div> 
                        <button type="button" class="btn bg-navy btn-flat" onclick="javascript:loadcustome_banners('<?php echo isset($form_id) ? $form_id : 0; ?>', '<?php echo isset($form_process_id) ? $form_process_id : 0; ?>', '3','ownership_proof_type');">
                            Please click to add 
                        </button>
                    </div>    
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="form-group pull-left">
                <a href="<?php echo base_url() . 'admin/form/create_new_form/' . $form_info[0]->form_id . '/2/?fpi=' . $form_process_id . '&p=' . $actual_step_id_p . ''; ?>" class="btn bg-orange btn-flat pull-right">Prev</a>
            </div>
            <div class="form-group">
                <button type="submit" class="btn bg-orange btn-flat pull-right">Next</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <script>
        $(document).ready(function () {
            $("#trademark").change(function () {
                $(".trademark").toggleClass("hidden");
            });
        });
    </script>
<?php } ?>