<!--Inline CSS here-->

<?php include_once(view_url() . 'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span12">
      <h1>Grades for <?php echo $assignment['name']; ?></h1>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span12">
      <table class="table table-hover">
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
  </div>
</div> <!--End of container -->

<?php include_once(view_url(). 'templates/linked_js.php'); ?>

<!--Inline JS here-->
<script type="text/javascript">
</script>
