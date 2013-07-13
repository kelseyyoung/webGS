<!--Inline CSS here-->

<?php include_once(view_url() . 'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span12">
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
		<th>Name</th>
		<th>Username</th>
		<th>Score</th>
	      </tr>
	    </thead>
	    <tbody>
	    <?php foreach($students as $s) { ?> 
	      <tr>
		<td><?php echo $s['name']; ?></td>
		<td><?php echo $s['username']; ?></td>
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
		<th>Name</th>
		<th>Username</th>
		<th>Score</th>
	      </tr>
	    </thead>
	    <tbody>
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

  });
</script>
