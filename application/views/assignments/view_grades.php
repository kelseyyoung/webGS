<style type="text/css">
#new-grade {
  margin-bottom: 0px !important;
}
</style>

<?php include_once(view_url() . 'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span12">
      <!--Change grade modal-->
      <div class="modal hide fade" id="change-grade-modal">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="ture">&times;</button>
	  <h3>Change Grade</h3>
	</div>
	<?php echo form_open('assignments/change_grade'); ?>
	<div class="modal-body">
	  <input type="hidden" name="student" id="student"/>
	  <input type="hidden" name="assignment" id="assignment" value="<?php echo $assignment['id']; ?>"/>
	  <input type="hidden" name="class" id="class" value="<?php echo $class['id']; ?>"/>
	  <div class="text-center">
	    <input type="text" class="input-mini" name="new-grade" id="new-grade"/>
	    <span id="total" class="help-inline"></span>
	  </div>
	</div>
	<div class="modal-footer">
	  <button type="submit" name="submit" class="btn btn-primary">Change Grade</button>
	</div>
	</form>
      </div> <!--End change grade modal-->
      <h1>Grades for <?php echo $assignment['name']; ?></h1>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span12">
      <div class="tabbable">
	<ul class="nav nav-tabs">
	  <li class="active"><a data-toggle="tab" href="#all">All</a></li>
	  <?php foreach($all_sections as $s) { ?>
	  <li><a data-toggle="tab" href="#<?php echo $s['name']; ?>">Section <?php echo $s['name']; ?></a></li>
	  <?php } ?>
	</ul>
	<div class="tab-content">
	  <div class="tab-pane active" id="all">
	  <input type="text" id="student-search-query" class="search-query" placeholder="Search for a student" />
	  <button type="button" class="btn btn-primary" id="student-search">Search</button>
	  <table class="table table-hover" id="all">
	    <thead>
	      <tr>
		<th>Username</th>
                <th>Section</th>
		<th>Score</th>
		<th>Actions</th>
	      </tr>
	    </thead>
	    <tbody>
	    <?php foreach($students as $s) { ?> 
	      <tr>
		<td><?php echo $s['username']; ?></td>
                <td><?php echo $s['name']; ?></td>
		<td><?php 
		  //Find score for that student
		  $echo = "--";
		  foreach($scores as $sc) {
		    if ($sc['student_id'] == $s['id']) {
		      $echo = $sc['score'] .'/' . $assignment['total_points'];
		    }
		  }
		  echo $echo;
		?>
		</td>
		<td>
		  <button class="change-grade btn" type="button">Change Grade</button>
		</td>
	      </tr>
	    <?php } ?>
	    </tbody>
	  </table>
	</div>
	<?php foreach($all_sections as $s) { ?>
	<div class="tab-pane" id="<?php echo $s['name']; ?>">
	  <table id="table-<?php echo $s['name']; ?>" class="table table-hover">
	    <thead>
	      <tr>
		<th>Username</th>
		<th>Score</th>
                <th>Actions</th>
	      </tr>
	    </thead>
	    <tbody>
              <?php $ss = $student_sections[$s['name']];
              foreach($ss as $s) { ?>
              <tr>
                <td><?php echo $s['username']; ?></td>
                <td><?php
                  //Find score for that student
                  $echo = "--";
                  foreach ($scores as $sc) {
                    if ($sc['student_id'] == $s['id']) {
                      $echo = $sc['score'] .'/'.$assignment['total_points'];
                    }
                  }
                  echo $echo;
                ?>
                </td>
                <td>
                  <button class="change-grade btn" type="button">Change Grade</button>
                </td>
              </tr>
              <?php } ?>
	    </tbody>
	  </table>
	</div>
	<?php } ?>
      </div>
    </div>
  </div>
</div> <!--End of container -->

<?php include_once(view_url(). 'templates/linked_js.php'); ?>

<!--Inline JS here-->
<script type="text/javascript">

  $(document).ready(function() {
    
    //Searching for students
    $("#student-search-query").keyup(function(e) {
      if (e.which == 13) {
	$("#student-search").click();
      }
    });

    $("#student-search").click(function() {
      var query = new RegExp($("#student-search-query").val(), 'gi');
      var rows = $("#all > tbody > tr");
      for (var i = 0; i < $(rows).length; i++) {
	var row = $(rows)[i];
	if ($(row).children().eq(0).text().match(query) || $(row).children().eq(1).text().match(query)) {
	  $(row).show();
	} else {
	  $(row).hide();
	}
      }
    });

    $(".change-grade").click(function() {
      var currGrade = $(this).parent().prev().text().split('/')[0].trim(); 
      var total = <?php echo $assignment['total_points']; ?>;
      var username = $(this).parent().parent().children().first().text();
      $("#new-grade").val(currGrade);
      $("#total").text("/ " + total);
      $("#student").val(username);
      $("#change-grade-modal").modal('show');
    });

  });
</script>
