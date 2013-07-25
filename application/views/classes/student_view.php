<!--Inline CSS here-->

<?php include_once(view_url().'templates/page_begin.php'); ?>

<div class="row-fluid">
  <div class="span12">
    <h1><?php echo $class['name']; ?></h1>
  </div>
</div>
<hr>
<div class="row-fluid">
  <div class="span12">
    <!--Submit modal-->
    <div id="submit-modal" class="modal hide fade">
      <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3>Submit Assignment</h3>
      </div>
      <?php $attr = array('id' => 'submit-assignment-form');
	echo form_open_multipart('classes/submit_assignment/'. $this->session->userdata('user_id'), $attr); ?>
	<div class="modal-body">
	  <div id="first-upload" class="fileupload fileupload-new text-center" data-provides="fileupload">
	    <span class="btn btn-file">
	      <span class="btn-large btn-block fileupload-new">Select File</span>
	      <span class="btn-large btn-block fileupload-exists">Change</span>
	      <input type="file" name="assignment_submission_1" id="assignment_submission_1" />
	    </span>
	    <span class="fileupload-preview"></span>
	    <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">&times;</a>
	  </div>
	  <button id="add-file" type="button" class="btn btn-success"><i class="icon-plus"></i> Add File</button>
	  <div id="submit-progress" class="hide text-center">
	    <h3>Submitting...</h3>
	    <div class="progress progress-striped active">
	      <div class="bar" style="width: 100%;"></div>
	    </div>
	  </div>
	</div>
	<div class="modal-footer">
	  <input type="submit" name="submit" class="btn btn-primary" />
	  <input type="hidden" name="class_name" id="class_name" value="<?php echo $class['name']; ?>"/>
	  <input type="hidden" name="assignment_name" id="assignment_name" />
	</div>
      </form>	
    </div> <!-- End of modal -->
    <div id="assignments">
    <?php if (empty($assignments)) { ?>
      <p>No assignments have been created for this class</p>
    <?php } else { ?>
      <table class="table table-hover">
	<thead>
	  <tr>
	    <th>Assignment Name</th>
	    <th>Open Date</th>
	    <th>Due Date</th>
	    <th>Score</th>
	    <th>Controls</th>
	  </tr>
	</thead>
	<tbody>
	<?php for ($i = 0; $i < count($assignments); $i++) { ?>
	<tr>
	  <td><?php echo $assignments[$i]->name; ?></td>
	  <td><?php echo $assignments[$i]->startDateTime; ?></td>
	  <td><?php echo $assignments[$i]->endDateTime; ?></td>
	  <td>
	  <?php if (empty($scores[$i])) { ?>
	  --
	  <?php } else { 
	    echo $scores[$i]['score'] . '/' . $assignments[$i]->total_points;
	  } ?>
	  </td>
	  <td>
	    <?php 
	    $startDate = new DateTime($assignments[$i]->startDateTime);
	    $endDate = new DateTime($assignments[$i]->endDateTime);
	    if ($startDate <= new DateTime('now') && $endDate >= new DateTime('now')) { ?>
	    <a type="button" class="btn btn-success open-modal" href="#submit-modal" data-toggle="modal">Submit</a>
	    <?php } else { ?>
	    <a type="button" class="btn btn-danger" disabled="disabled">Submit</a>
	    <?php } ?>
	    <a type="button" class="btn" href="<?php echo site_url('assignments/view_submissions/'.$assignments[$i]->id); ?>">View Submissions</a>
	  </td>
	</tr>
	<?php } ?>
	</tbody>
      </table>
    <?php } ?>
    <?php if (isset($upload_errors)) { ?>
    <div class="alert alert-error">
      <?php echo $upload_errors; ?>
    </div>
    <?php } ?>
    </div> <!-- End assignment table -->
  </div>
</div>

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!--Inline JS here -->
<script type="text/javascript">

  var fileCount = 2;
  var clone = $("#first-upload").clone();

  $(document).ready(function() {

    $(".open-modal").click(function() {
      //Populate assignment field on click
      var a = $(this).parent().prev().prev().prev().prev().text().trim();
      $("#assignment_name").val(a);
    });

    /*
    $("#assignment_submission").on('change', function(e) {
      var filename = $(this).val().split('\\').pop();
      $("#submission_name").val(filename);
    });
    */

    $("#submit-assignment-form").on("submit", function() {
      //Show loading bar
      $("#submit-progress").removeClass('hide');
      return true;
    });

    $("#add-file").click(function() {
      var newClone = clone.clone();
      newClone.attr('id', '');
      newClone.append("<button class='close' data-dismiss='alert' type='button'>&times;</button>");
      newClone.find('#assignment_submission_1').attr('name', 'assignment_submission_' + fileCount);
      newClone.find('#assignment_submission_1').attr('id', 'assignment_submission_' + fileCount++);
      $(this).before(newClone);
    });

  });
</script>

