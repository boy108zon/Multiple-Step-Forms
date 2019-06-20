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
    <?php echo form_open(base_url().'admin/form/create_new_form/'.$form_info[0]->form_id.'/4?fpi='.$form_process_id.'', array('class' => "", 'autocomplete' => "on", 'enctype' => "multipart/form-data", 'id' => 'form4', 'name' => 'form4', 'method' => 'post')); ?>
    <div class="box-header">
<!--        <h3 class="box-title"><?php echo !empty($form_info) ?  $form_info[0]->form_heading: '';?></h3>-->
    </div>
    <div class="box-body">
        <input type="hidden" name="form_id" id="form_id" value="<?php echo !empty($form_info) ? $form_info[0]->form_id: '';?>"> 
        <input type="hidden" name="step_id" id="step_id" value="<?php echo $next_step;?>">
        <div class="row">
         <div class="col-md-12">
          <div class="share-capital">
            <table class="table table-condensed">
                <tr>
                  <th>Particular</th>
                  <th>Authroised Capital</th>
                  <th>Paid up Capital</th>
                </tr>
                <tr>
                  <td>No Of Shares</td>
                  <td><div class="form-group"><input type="text" name="authroised_capital_no_of_shares" value="<?php echo isset($form_data['authroised_capital_no_of_shares']) ? $form_data['authroised_capital_no_of_shares']: '';?>" id="authroised_capital_no_of_shares" placeholder="" class="form-control" onkeyup="calculate_authroised_capital();"> </div></td>
                  <td><div class="form-group"><input type="text" name="paid_up_capital_no_of_shares" value="<?php echo isset($form_data['paid_up_capital_no_of_shares']) ? $form_data['paid_up_capital_no_of_shares']: '';?>" id="paid_up_capital_no_of_shares" placeholder="" class="form-control" onkeyup="calculate_paidup_capital();" > </div></td>
                </tr>
                <tr>
                 <tr>
                  <td>Nominal Amount Per Share <span class="textcolor-info">(In rupess)</span></td>
                  <td><div class="form-group"><input type="text" name="authroised_capital_nominal_amount" value="<?php echo isset($form_data['authroised_capital_nominal_amount']) ? $form_data['authroised_capital_nominal_amount']: '';?>" id="authroised_capital_nominal_amount" placeholder="" class="form-control " onkeyup="calculate_authroised_capital();" > </div></td>
                  <td><div class="form-group"><input type="text" name="paid_up_capital_nominal_amount" value="<?php echo isset($form_data['paid_up_capital_nominal_amount']) ? $form_data['paid_up_capital_nominal_amount']: '';?>" id="paid_up_capital_nominal_amount" placeholder="" class="form-control" onkeyup="calculate_paidup_capital();" > </div></td>
                </tr>
                <tr>
                  <td>Total Amount <span class="textcolor-info">(In rupess)</span></td>
                  <td><input type="text" name="authroised_capital_total" value="<?php echo isset($form_data['authroised_capital_total']) ? $form_data['authroised_capital_total']: '';?>" id="authroised_capital_total" placeholder="" class="form-control" readonly=""> </td>
                  <td><input type="text" name="paid_up_capital_total" value="<?php echo isset($form_data['paid_up_capital_total']) ? $form_data['paid_up_capital_total']: '';?>" id="paid_up_capital_total" placeholder="" class="form-control" readonly=""> </td>
                </tr>
              </table>
          </div>
        </div>
        </div>
    </div>
    <div class="box-footer">
         <div class="form-group pull-left">
                <a href="<?php echo base_url() . 'admin/form/create_new_form/' . $form_info[0]->form_id . '/3/?fpi=' . $form_process_id . '&p='.$actual_step_id_p.''; ?>" class="btn bg-orange btn-flat pull-right">Prev</a>
            </div>
        <div class="form-group">
            <button type="submit" class="btn bg-orange btn-flat pull-right">Next</button>
        </div>
    </div>
 <?php echo form_close();?>
</div>
<?php } ?>
