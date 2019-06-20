<?php if (!empty($form_info)) { ?>
<?php
  if(!empty($form_data)){
    $form_data=  unserialize($form_data[0]->post_values);
  }
?>
<section class="custome-content-header">
    <div>
        <?php echo !empty($form_info) ?  $form_info[0]->form_heading: '';?>
        <small></small>
    </div>
</section>
<div class="box box-info">
    <?php echo form_open(base_url().'admin/form/create_new_form/'.$form_info[0]->form_id.'/6?fpi='.$form_process_id.'&isf=yes', array('class' => "", 'autocomplete' => "on", 'enctype' => "multipart/form-data", 'id' => 'step1', 'name' => 'step1', 'method' => 'post')); ?>
    <div class="box-header">
<!--        <h3 class="box-title"><?php echo !empty($form_info) ?  $form_info[0]->form_heading: '';?></h3>-->
    </div>
    <div class="box-body">
        <input type="hidden" name="form_id" id="form_id" value="<?php echo !empty($form_info) ? $form_info[0]->form_id: '';?>"> 
        <input type="hidden" name="step_id" id="step_id" value="<?php echo $next_step - 1;?>">
        <div class="form-group">
            <label for="name">Approved Name Company Name</label>
            <input type="text" name="approved_name_company_name" value="<?php echo isset($form_data['approved_name_company_name']) ? $form_data['approved_name_company_name']: '';?>" id="approved_name_company_name" placeholder="Name" class="form-control "> 
        </div>
    </div>
    <div class="box-footer">
         <div class="form-group pull-left">
                <a href="<?php echo base_url() . 'admin/form/create_new_form/' . $form_info[0]->form_id . '/5/?fpi=' . $form_process_id . '&p='.$actual_step_id_p.''; ?>" class="btn bg-orange btn-flat pull-right">Prev</a>
            </div>
        <div class="form-group">
            <button type="submit" class="btn bg-orange btn-flat pull-right">Finished</button>
        </div>
    </div>
 <?php echo form_close();?>
</div>
<?php }?>