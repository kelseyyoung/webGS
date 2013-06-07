<!--Inline CSS here-->

<?php include_once(view_url().'templates/page_begin.php'); ?>

<div class="row-fluid">
  <div class="span12">
    <h1><?php echo $class['name']; ?></h1>
  </div>
</div>
<hr>
<div class="row-fluid">
  <div class="span12">
    <div id="assignments">
    <?php if (empty($assignments)) { ?>
      <p>No assignments have been created for this class</p>
    <?php } else { ?>
      <table class="table table-hover">
	<thead>
	  <tr>
	    <th>Assignment Name</th>
	    <th>Open Date</th>
	    <th>Due Date</th>
	    <th>Current Score</th>
	    <th>Controls</th>
	  </tr>
	</thead>
	<tbody>
	<?php foreach ($assignments as $a) { ?>
	<tr>
	  <td><?php echo $a->name; ?></td>
	  <td><?php echo $a->startDateTime; ?></td>
	  <td><?php echo $a->endDateTime; ?></td>
	  <td>Current Score</td>
	  <td>
	    <?php 
	    $startDate = new DateTime($a->startDateTime);
	    $endDate = new DateTime($a->endDateTime);
	    if ($startDate <= new DateTime('now') && $endDate >= new DateTime('now')) { ?>
	    <a type="button" class="btn btn-success" href="#">Submit</a>
	    <?php } else { ?>
	    Submission Closed
	    <?php } ?>
	  </td>
	</tr>
	<?php } ?>
	</tbody>
      </table>
    <?php } ?>
    </div> <!-- End assignment table -->
  </div>
</div>

