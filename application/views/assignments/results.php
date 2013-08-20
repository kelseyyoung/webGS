<style>
</style>

<?php include_once(view_url() . 'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span8 offset2">
      <h1>Submission Results</h1>
      <hr>
      <?php if ($tests != "compile") { ?>
      <p class="lead pull-right">Passed <?php echo ($tests - $failures); ?> out of <?php echo $tests; ?> test cases</p> 
      <?php } else { ?>
      <p class="lead pull-right">An error occured</p>
      <?php } ?>
      <h3>
      <?php echo $score; ?>/<?php echo $total_points; ?>
      </h3>
      <div class="progress">
        <?php if ($tests != "compile") { ?>
	<div class="bar bar-success" style="width: <?php echo ($tests-$failures)/$tests * 100; ?>%;"></div>
	<div class="bar bar-danger" style="width: <?php echo $failures/$tests * 100; ?>%;"></div>
        <?php } else { ?>
        <div class="bar bar-danger" style="width: 100%"></div>
        <?php } ?>
      </div>
      <h3>Hints</h3>
      <?php if (!empty($messages)) {
	foreach($messages as $m) { ?>
      <p><?php echo $m; ?></p>
	<?php }
      } else { ?>
      <p>No errors to display.</p>
      <?php } ?>
      <div class="alert alert-error">
        <p>WARNING! Refreshing this page will cause an error. To review your hints, click "View Submissions" on your class home page.</p>
      </div>
    </div>
  </div>
</div> <!-- end container -->

<?php include_once(view_url() . 'templates/linked_js.php'); ?>

<!-- Inline JS here -->

<script type="text/javascript">
</script>
