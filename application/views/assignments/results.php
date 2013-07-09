<!--Inline CSS here-->

<?php include_once(view_url() . 'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span12">
      <h1>Submission Results</h1>
      <hr>
      <p>Passed <?php echo ($tests - $failures); ?> out of <?php echo $tests; ?> test cases</p> 
      <hr>
      <h3>Errors</h3>
      <p><?php echo $errors; ?></p>
      <hr>
      <h3>
    </div>
  </div>
</div> <!-- end container -->

<?php include_once(view_url() . 'templates/linked_js.php'); ?>

<!-- Inline JS here -->

<script type="text/javascript">
</script>
