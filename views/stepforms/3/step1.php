<?php if(!empty($form_info)){?>
<?php
  if(!empty($form_data)){
    $form_data=  unserialize($form_data[0]->post_values);
    $form_name=$form_name_info[0]->name;
  }
?>
<section class="custome-content-header">
    <div>
        <?php echo !empty($form_info) ?  $form_info[0]->form_heading: '';?>
        <small></small>
    </div>
</section>
<div class="box box-info">
    <?php echo form_open(base_url().'admin/form/create_new_form/'.$form_id.'/1/?fpi='.$form_process_id.'', array('class' => "", 'autocomplete' => "on",'id' => 'form1', 'name' => 'form1', 'method' => 'post')); ?>
    <div class="box-header">
<!--        <h3 class="box-title"><?php echo !empty($form_info) ?  $form_info[0]->form_heading: '';?></h3>-->
    </div>
    <div class="box-body">
        <input type="hidden" name="form_id" id="form_id" value="<?php echo !empty($form_info) ? $form_info[0]->form_id : '';?>"> 
        <input type="hidden" name="step_id" id="step_id" value="<?php echo $next_step;?> "> 
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" value="<?php echo !empty($form_data) ? $form_data['name']: '';?>" id="name" placeholder="Name" label="Name" class="form-control "> 
        </div>
        <div class="form-group">
            <label for="email" class="">Email</label>
            <input type="text" name="email" value="<?php echo !empty($form_data) ? $form_data['email']: '';?>" id="email" placeholder="Email" label="Email" class="form-control "> 
        </div>
        <div class="form-group">
            <label for="phone" class="">Phone</label>
            <input type="text" name="phone" value="<?php echo !empty($form_data) ? $form_data['phone']: '';?>" id="phone" placeholder="Phone Number" label="Phone" class="form-control ">
        </div>
        <div class="form-group"><label for="city" class="">City</label>
            <input type="text" name="city" value="<?php echo !empty($form_data) ? $form_data['city']: '';?>" id="city" placeholder="City" label="City" class="form-control "> 
        </div>    
    </div>
    <div class="box-footer">
        <div class="form-group">
                <input type="submit" class="btn bg-orange btn-flat pull-right" value="Next"/>
        </div>
    </div>
 <?php echo form_close();?>
</div>
<?php }?>
