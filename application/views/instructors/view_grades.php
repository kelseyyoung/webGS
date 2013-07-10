<!--Inline CSS here-->

<?php include_once(view_url().'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span12">
      <h1><?php echo $class['name']; ?> grades for <?php echo $student['username']; ?></h1>
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
	  </tr>
	</thead>
	<tbody>
	<?php for ($i = 0; $i < count($assignments); $i++) { ?>
	  <tr>
	    <td><?php echo $assignments[$i]->name; ?></td>
	    <td>
	    <?php if (empty($scores[$i])) { ?>
	    --
	    <?php } else {
	    echo $scores[$i]['score'] . '/' . $assignments[$i]->total_points;
	    } ?>
	    </td>
	  </tr>
	<?php } ?>
	</tbody>
      </table>
    </div>
  </div>
</div> <!-- End of container -->

<?php include_once(view_url() .'templates/linked_js.php'); ?>

<!--Inline JS here-->
<script type="text/javascript">
</script>
