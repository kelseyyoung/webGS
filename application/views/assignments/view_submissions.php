<style>
</style>

<?php include_once(view_url().'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span12">
      <h1>Submissions for <?php echo $assignment['name']; ?></h1>
    </div>
  </div>
  <hr>
  <div class="row-fluid">
    <div class="span12">
      <?php if (empty($submissions)) { ?>
      <p>You have not submitted anything for this assignment.</p>
      <?php } else { ?>
      <table class="table table-hover table-condensed">
	<thead>
	  <tr>
	    <th>Date Submitted</th>
	    <th>Score</th>
	    <th>Hints Received</th>
	  </tr>
	</thead>
	<tbody>
	    <?php foreach ($submissions as $s) { ?>
	    <tr>
	      <td><?php echo $s['time_submitted']; ?></td>
	      <td><?php echo $s['score']; ?>/<?php echo $assignment['total_points']; ?></td>
	      <td>
	      <?php $hints = explode('\n', $s['hints']);
	      foreach($hints as $h) {
		echo $h . '<br />';
	      }
	      ?>
	      </td>
	    </tr>
	    <?php }
	  } ?>
	</tbody>
      </table>
    </div>
  </div>
</div> <!--end container-->

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!-- Inline JS here -->

<script type="text/javascript">
</script>
