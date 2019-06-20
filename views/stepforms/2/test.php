<form class="demo-form">
  <div class="form-section">
    <label for="firstname">First Name:</label>
    <input type="text" class="form-control" name="firstname" required="">

    <label for="lastname">Last Name:</label>
    <input type="text" class="form-control" name="lastname" required="">
  </div>

  <div class="form-section">
    <label for="email">Email:</label>
    <input type="email" class="form-control" name="email" required="">
  </div>

  <div class="form-section">
    <label for="color">Favorite color:</label>
    <input type="text" class="form-control" name="color" required="">
  </div>

  <div class="form-navigation">
    <button type="button" class="previous btn btn-info pull-left">&lt; Previous</button>
    <button type="button" class="next btn btn-info pull-right">Next &gt;</button>
    <input type="submit" class="btn btn-default pull-right">
    <span class="clearfix"></span>
  </div>
</form>
<script src="<?php echo base_url(); ?>assets/dist/ps/parsley.min.js"></script>
<script type="text/javascript">
$(function () {
  var $sections = $('.form-section');

  function navigateTo(index) {
    // Mark the current section with the class 'current'
    $sections
      .removeClass('current')
      .eq(index)
        .addClass('current');
    // Show only the navigation buttons that make sense for the current section:
    $('.form-navigation .previous').toggle(index > 0);
    var atTheEnd = index >= $sections.length - 1;
    $('.form-navigation .next').toggle(!atTheEnd);
    $('.form-navigation [type=submit]').toggle(atTheEnd);
  }

  function curIndex() {
    // Return the current index by looking at which section has the class 'current'
    return $sections.index($sections.filter('.current'));
  }

  // Previous button is easy, just go back
  $('.form-navigation .previous').click(function() {
    navigateTo(curIndex() - 1);
  });

  // Next button goes forward iff current block validates
  $('.form-navigation .next').click(function() {
    $('.demo-form').parsley().whenValidate({
      group: 'block-' + curIndex()
    }).done(function() {
      navigateTo(curIndex() + 1);
    });
  });

  // Prepare sections by setting the `data-parsley-group` attribute to 'block-0', 'block-1', etc.
  $sections.each(function(index, section) {
    $(section).find(':input').attr('data-parsley-group', 'block-' + index);
  });
  navigateTo(0); // Start at the beginning
});
</script>