<!-- Inline CSS here -->

<?php include_once(view_url().'templates/page_begin.php'); ?>
  
  <div class="row-fluid">
    <div class="span10">
      <h1>Hello, <?php echo $instructor['name'] ?></h1>
    </div>
    <div class="span2">
      <?php if (!empty($classes)) { ?>
      <h1>
      <a href="<?php echo site_url('classes/create');?>" type="button" class="btn btn-primary btn-large"><i class="icon-plus"></i> New Class</a>
      </h1>
      <?php } ?>
    </div>
  </div>
  <hr />
  <div class="row-fluid">
    <div class="span12">
    <?php if (empty($classes)) { ?>
      <p>You do not have any classes. <a href="<?php echo site_url('classes/create'); ?>">Create one now.</a></p>
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
          <td><?php echo $class['name']; ?></td>
          <td>
            <a type="button" class="btn btn-success" href="<?php echo site_url('classes/view/'.$class['id']); ?>">View Class</a>
            <a type="button" class="btn btn-success" href="<?php echo site_url('assignments/create/?class='.urlencode($class['name'])); ?>">Create Assignment</a>
          </td>
        </tr>
        <?php } ?>
        </tbody>
      </table>
    <?php } ?>
    </div>
  </div>
</div>

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!--Inline JS here -->
