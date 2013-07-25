<style>
</style>

<?php include_once(view_url().'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span12">
      <h1><?php echo $class['name']; ?> grades for <?php echo $student['username']; ?></h1>
      <!--File reading modal-->
      <div class="modal hide fade" id="file-contents-modal">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	  <h3 id="filename"></h3>
	</div>
	<div class="modal-body">
	  <pre class="prettyprint linenums language-java" id="file-contents">
	  </pre>
	</div>
      </div>
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
	    <th>Actions</th>
	  </tr>
	</thead>
	<tbody>
	<?php for ($i = 0; $i < count($assignments); $i++) { ?>
	  <tr>
	    <td><?php echo $assignments[$i]['name']; ?></td>
	    <td>
	    <?php if (empty($scores[$i])) { ?>
	    --
	    <?php } else {
	    echo $scores[$i]['score'] . '/' . $assignments[$i]['total_points'];
	    } ?>
	    </td>
	    <td>
	      <div class="btn-group">
		<a type="button" class="btn dropdown-toggle" data-toggle="dropdown" href="#">View Submissions <span class="caret"></span></a>
		<ul class="dropdown-menu">
		  <?php $sub_array = $submissions[$assignments[$i]['id']]; 
		  foreach ($sub_array as $sub) { ?>
		  <li class="view-submission" data-filename="<?php echo $sub['file']; ?>" data-path="<?php echo $sub['path']; ?>">
		    <a class="ignore-link" href="#"><?php echo substr($sub['file'], 0, strrpos($sub['file'], ".")); ?> (<?php echo str_replace("_", " ", substr($sub['file'], strrpos($sub['file'], ".") + 1)); ?>)</a>
		  </li>
		  <?php } ?>
		</ul>
	      </div>
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

  String.prototype.replaceAt = function(index, c) {
    return this.substr(0, index) + c + this.substr(index + c.length);
  }

  $(document).ready(function() {

    prettyPrint();

    $(".ignore-link").click(function(e) {
      e.preventDefault();
    });

    $(".view-submission").click(function() {
      var path = $(this).data("path");
      var file = $(this).data("filename");
      $.get("<?php echo site_url('instructors/get_file_contents'); ?>", {"path" : path, "file": file}, function(data) {
	$("#file-contents").text(data);
	file = file.replace("_", " ");
	$("#filename").text(file.replaceAt(file.lastIndexOf("."), " "));
	$("#file-contents-modal").modal('show').css({
	  'width': function() {
	    return ($(document).width() * .9) + 'px';
	  },
	  'margin-left': function() {
	    return -($(this).width() / 2);
	  }
	});
	prettyPrint();
      });
    });

  });
</script>
