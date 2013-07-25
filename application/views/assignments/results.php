<style>
</style>

<?php include_once(view_url() . 'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span8 offset2">
      <h1>Submission Results</h1>
      <hr>
      <p class="lead pull-right">Passed <?php echo ($tests - $failures); ?> out of <?php echo $tests; ?> test cases</p> 
      <h3>
      <?php echo $score; ?>/<?php echo $total_points; ?>
      </h3>
      <div class="progress">
	<div class="bar bar-success" style="width: <?php echo ($tests-$failures)/$tests * 100; ?>%;"></div>
	<div class="bar bar-danger" style="width: <?php echo $failures/$tests * 100; ?>%;"></div>
      </div>
      <h3>Hints</h3>
      <?php if (!empty($messages)) {
	foreach($messages as $m) { ?>
      <p><?php echo $m; ?></p>
	<?php }
      } else { ?>
      <p>No errors to display.</p>
      <?php } ?>
      <h3>
    </div>
  </div>
</div> <!-- end container -->

<?php include_once(view_url() . 'templates/linked_js.php'); ?>

<!-- Inline JS here -->

<script type="text/javascript">
</script>
