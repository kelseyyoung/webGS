<!--Inline CSS here -->

<?php include_once(view_url().'templates/page_begin.php'); ?>  
  
  <div class="row-fluid">
    <div class="span12">
      <h1>Hello, <?php echo $student['name'] ?></h1>
    </div>
  </div>
  <hr />
  <div class="row-fluid">
    <div class="span12">
    <?php if (empty($classes)) { ?>
      <p>You do not belong to any classes.</p>
    <?php } else { ?>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Class Name</th>
            <th>Controls</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($classes as $class) { ?>
        <tr>
          <td><?php echo $class->name; ?></td>
          <td></td>
        </tr>
        <?php } ?>
        </tbody>
      </table>
    <?php } ?>
    </div>
  </div>
</div>

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!-- Inline JS here-->
