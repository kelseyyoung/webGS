<!--Inline CSS here -->

<?php include_once(view_url().'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span6 offset3 well">
      <h2 class="text-center">Create an Assignment</h2>
      <?php
        $attr = array('class' => 'form-horizontal');
        echo form_open_multipart('assignments/create', $attr); ?>

        <div class="control-group <?php if (form_error('name')) { ?>error<?php } ?>">
          <label class="control-label" for="name">Name: </label>
          <div class="controls">
            <input type="text" id="name" name="name" value="<?php echo set_value('name'); ?>"/>
            <?php if (form_error('name')) { ?>
            <span class="help-block"><?php echo form_error('name'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('class')) { ?>error<?php } ?>">
          <label class="control-label" for="class">Class: </label>
          <div class="controls">
            <select id="class" name="class">
              <option></option>
              <?php foreach ($classes as $class) { ?>
              <option value="<?php echo $class['name']; ?>"><?php echo $class['name']; ?></option>
              <?php } ?>
            </select>
            <?php if (form_error('class')) { ?>
            <span class="help-block"><?php echo form_error('class'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('due_date_start')) { ?>error<?php } ?>">
          <label class="control-label" for="due_date_start">Start Date: </label>
          <div class="controls">
            <div class="input-append date form_datetime">
              <input type="text" id="due_date_start" name="due_date_start" value="<?php echo set_value('due_date_start'); ?>"/>
              <span class="add-on">
                <i class="icon-th"></i>
              </span>
            </div>
            <?php if (form_error('due_date_start')) { ?>
            <span class="help-block"><?php echo form_error('due_date_start'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('due_date_end')) { ?>error<?php } ?>">
          <label class="control-label" for="due_date_end">End Date: </label>
          <div class="controls">
            <div class="input-append date form_datetime">
              <input type="text" id="due_date_end" name="due_date_end" value="<?php echo set_value('due_date_end'); ?>" />
              <span class="add-on">
                <i class="icon-th"></i>
              </span>
            </div>
            <?php if (form_error('due_date_end')) { ?>
            <span class="help-block"><?php echo form_error('due_date_end'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('testcase_file')) { ?>error<?php } ?>">
          <label class="control-label" for="testcase_file">JUnit File: </label>
          <div class="controls">
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <span class="btn btn-file">
                <span class="fileupload-new">Select file</span>
                <span class="fileupload-exists">Change</span>
                <input type="file" name="testcase_file" id="testcase_file" />
                <input type="hidden" name="testcase_name" id="testcase_name" />
              </span>
              <span class="fileupload-preview"></span>
              <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">&times;</a>
            </div>
            <?php if (form_error('testcase_file')) { ?>
            <span class="help-block"><?php echo form_error('testcase_file'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('num_testcases')) { ?>error<?php } ?>">
          <label class="control-label" for="num_testcases">Number of Testcases</label>
          <div class="controls">
            <input type="text" id="num_testcases" name="num_testcases" value="<?php echo set_value('num_testcases'); ?>"/>
            <?php if (form_error('num_testcases')) { ?>
            <span class="help-block"><?php echo form_error('num_testcases'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('points_per_testcase')) { ?>error<?php } ?>">
          <label class="control-label" for="points_per_testcase">Points per Testcase</label>
          <div class="controls">
            <input type="text" id="points_per_testcase" name="points_per_testcase" value="<?php echo set_value('points_per_testcase'); ?>"/>
            <?php if (form_error('points_per_testcase')) { ?>
            <span class="help-block"><?php echo form_error('points_per_testcase'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('total_points')) { ?>error<?php } ?>">
          <label class="control-label" for="total_points">Total Points</label>
          <div class="controls">
            <input type="text" readonly id="total_points" name="total_points" value="<?php echo set_value('total_points'); ?>"/>
            <?php if (form_error('total_points')) { ?>
            <span class="help-block"><?php echo form_error('total_points'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit" name="submit">Create Assignment</button>
        </div>
      </form>
      <?php if (isset($upload_errors)) { ?>
      <div class="alert alert-error">
        <?php echo $upload_errors; ?>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!--Inline JS here -->
<script type="text/javascript">
  $(".form_datetime").datetimepicker({
    format: "yyyy-mm-dd  hh:ii"
  });

  $("#testcase_file").on('change', function(e) {
    var filename = $(this).val().split('\\').pop();
    $("#testcase_name").val(filename);
  });

  $("input#num_testcases").keyup(function() {
    var count = parseInt($(this).val());
    var worth = parseInt($("input#points_per_testcase").val());
    if ((!isNaN(count)) && (!isNaN(worth))) {
      $("input#total_points").val(count*worth);
    } else {
      $("input#total_points").val("");
    }
  });

  $("input#points_per_testcase").keyup(function() {
    var count = parseInt($(this).val());
    var worth = parseInt($("input#num_testcases").val());
    if ((!isNaN(count)) && (!isNaN(worth))) {
      $("input#total_points").val(count*worth);
    } else {
      $("input#total_points").val("");
    }
  });

  <?php if (isset($_GET['class'])) { ?>
  $("select#class").val("<?php echo urldecode($_GET['class']); ?>");
  <?php } ?>
</script>
