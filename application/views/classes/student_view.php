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
      <?php echo form_open_multipart('classes/submit_assignment/'. $this->session->userdata('user_id')); ?>
	<div class="modal-body">
	  <div class="fileupload fileupload-new text-center" data-provides="fileupload">
	    <span class="btn btn-file">
	      <span class="btn-large btn-block fileupload-new">Select File</span>
	      <span class="btn-large btn-block fileupload-exists">Change</span>
	      <input type="file" name="assignment_submission" id="assignment_submission" />
	      <input type="hidden" name="submission_name" id="submission_name" />
	    </span>
	    <span class="fileupload-preview"></span>
	    <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">&times;</a>
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
	    <th>Current Score</th>
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
	    echo $scores[$i]['score'];
	  } ?>
	  </td>
	  <td>
	    <?php 
	    $startDate = new DateTime($assignments[$i]->startDateTime);
	    $endDate = new DateTime($assignments[$i]->endDateTime);
	    if ($startDate <= new DateTime('now') && $endDate >= new DateTime('now')) { ?>
	    <a type="button" class="btn btn-success open-modal" href="#submit-modal" data-toggle="modal">Submit</a>
	    <?php } else { ?>
	    Submission Closed
	    <?php } ?>
	  </td>
	</tr>
	<?php } ?>
	</tbody>
      </table>
    <?php } ?>
    </div> <!-- End assignment table -->
  </div>
</div>

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!--Inline JS here -->
<script type="text/javascript">

  $(document).ready(function() {

    $(".open-modal").click(function() {
      //Populate assignment field on click
      var a = $(this).parent().prev().prev().prev().prev().text().trim();
      console.log(a);
      $("#assignment_name").val(a);
    });

  });
</script>

