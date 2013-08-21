<style>
</style>

<?php include_once(view_url().'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span12">
      <!--Change grade modal-->
      <div class="modal hide fade" id="change-grade-modal">
        <div class="modal-header">
          <button type="button" class="close ignore-slide" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3>Change Grade</h3>
        </div>
        <?php $attr = array("id" => "change-grade-form");
        echo form_open('assignments/change_grade_instructors', $attr); ?>
        <div class="modal-body">
          <input type="hidden" name="student" id="student"/>
          <input type="hidden" name="assignment" id="assignment"/>
          <input type="hidden" name="class" id="class" value="<?php echo $class['id']; ?>"/>
          <div class="text-center">
            <input type="text" class="input-mini" name="new-grade" id="new-grade"/>
            <span id="total" class="help-inline"></span>
          </div>
          <p></p>
          <div id="change-grade-error" class="alert alert-error hide">
            <button class="close" type="button">&times;</button>
            <span></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="submit" class="btn btn-primary">Change Grade</button>
        </div>
        </form>
      </div> <!--End change grade modal-->
      <h1><?php echo $class['name']; ?> grades for <?php echo $student['username']; ?></h1>
      <!--File reading modal-->
      <div class="modal hide fade" id="file-contents-modal">
	<div class="modal-header">
	  <button type="button" class="close ignore-slide" data-dismiss="modal" aria-hidden="true">&times;</button>
	  <h3 id="filename"></h3>
	</div>
	<div class="modal-body">
	  <pre class="prettyprint linenums language-java" id="file-contents">
	  </pre>
	</div>
      </div> <!--End of file reading modal-->
    </div>
  </div>
  <hr>
  <div class="row-fluid">
    <div class="span12">
      <table class="table table-hover">
	<thead>
	  <tr>
	    <th>Assignment Name</th>
	    <th>Score</th>
	    <th>Actions</th>
	  </tr>
	</thead>
	<tbody>
	<?php foreach ($assignments as $a) { ?>
	  <tr>
	    <td>
              <?php echo $a['name']; ?>
              <input type="hidden" class="assignment-id" value="<?php echo $a['id']; ?>"/>
            </td>
	    <td>
            <?php $echo = "--";
            foreach ($scores as $s) {
              if ($s['assignment_id'] == $a['id']) {
                $echo = $s['score'].'/'.$a['total_points'];
              }
            }
            echo $echo;
            ?>
	    </td>
	    <td>
              <?php if (empty($submissions[$a['id']])) { ?>
              <span>No submissions</span>
              <?php } else { ?>
	      <div class="btn-group">
		<a type="button" class="btn dropdown-toggle" data-toggle="dropdown" href="#">View Submissions <span class="caret"></span></a>
		<ul class="dropdown-menu">
		  <?php $sub_array = $submissions[$a['id']]; 
		  foreach ($sub_array as $sub) { ?>
		  <li class="view-submission" data-filename="<?php echo $sub['file']; ?>" data-path="<?php echo $sub['path']; ?>">
		    <a class="ignore-link" href="#"><?php echo substr($sub['file'], 0, strrpos($sub['file'], ".")); ?> (<?php echo str_replace("_", " ", substr($sub['file'], strrpos($sub['file'], ".") + 1)); ?>)</a>
		  </li>
		  <?php } ?>
		</ul>
	      </div>
              <?php } ?>
              <button class="change-grade btn" type="button">Change Grade</button>
	    </td>
	  </tr>
	<?php } ?>
	</tbody>
      </table>
    </div>
    <div class="hide">
    <?php echo form_open(''); ?>
    </form>
    </div>
  </div>
</div> <!-- End of container -->

<?php include_once(view_url() .'templates/linked_js.php'); ?>

<!--Inline JS here-->
<script type="text/javascript">

  String.prototype.replaceAt = function(index, c) {
    return this.substr(0, index) + c + this.substr(index + c.length);
  }

  $(document).ready(function() {

    prettyPrint();

    $(".ignore-link").click(function(e) {
      e.preventDefault();
    });

    $(".view-submission").click(function() {
      var path = $(this).data("path");
      var file = $(this).data("filename");
      var csrf = $("input[name=webGS_csrf_token]")[0];
      $.get("<?php echo site_url('instructors/get_file_contents'); ?>", {"path" : path, "file": file, "webGS_csrf_token": $(csrf).val()}, function(data) {
	$("#file-contents").text(data);
	file = file.replace("_", " ");
	$("#filename").text(file.replaceAt(file.lastIndexOf("."), " "));
	$("#file-contents-modal").modal('show').css({
	  'width': function() {
	    return ($(document).width() * .9) + 'px';
	  },
	  'margin-left': function() {
	    return -($(this).width() / 2);
	  }
	});
	prettyPrint();
      });
    });

    $(".change-grade").click(function() {
      var currGrade = $(this).parent().prev().text().split('/')[0].trim();
      var total = $(this).parent().prev().text().split('/')[1].trim();
      var username =  "<?php echo $student['username']; ?>";
      var assignment = $(this).parent().prev().prev().find('.assignment-id').val();
      $("#new-grade").val(currGrade);
      $("#total").text("/ " + total);
      $("#student").val(username);
      $("#assignment").val(assignment);
      $("#change-grade-modal").modal("show");
    });

    $("#change-grade-form").submit(function() {
      var newGrade = $("#new-grade").val();
      if (!isNaN(newGrade)) {
        return true;
      } else {
        $("#change-grade-error > span").html("The grade must be a number.");
        $("#change-grade-error").slideDown();
        //Not a number, show error and don't submit
        return false;
      }
    });

    $("button.close").click(function() {
      if (!$(this).hasClass("ignore-slide")) {
        $(this).parent().slideUp();
      }
    });

  }); //End of document ready
</script>
