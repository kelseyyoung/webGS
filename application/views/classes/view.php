<!-- Inline CSS here -->
  #assignments #students {
    overflow: auto;
  }

  span.active {
    color: green;
  }

  span.inactive {
    color: red;
  }

  form.form-search {
    margin: 0;
  }

  ul.dropdown-menu {
    font-size: 15px;
    line-height: 21px;
    max-width: 206px;
  } 

  h2:hover {
    cursor: pointer;
  }

<?php include_once(view_url().'templates/page_begin.php'); ?>  
  <div class="row-fluid">
    <div class="span12">
      <h1><?php echo $class['name']; ?></h1>
    </div>
  </div>
  <hr />
  <div class="row-fluid">
    <?php
      $attr = array('id' => 'add-instructor-form', 'class' => 'form-search pull-right');
      echo form_open('classes/add_instructor/'.$class['id'], $attr);
    ?>
      <input type="text" name="instructor" autocomplete="off" id="instructor" class="search-query" placeholder="Search by username">
      <button type="submit" class="btn btn-primary">Add Instructor</button>
    </form>
    <a data-toggle="collapse" data-target="#instructors"><h2>Instructors</h2></a>
    <div class="hide text-center alert alert-error"><span></span><button class="close">&times;</button></div>
    <div id="instructors" class="collapse in">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Controls</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($instructors as $i) { ?>
          <tr>
            <td><?php echo $i['name']; ?></td>
            <td><?php echo $i['username']; ?></td>
            <td>
              <a type="button" class="remove-instructor btn btn-danger" href="<?php echo site_url('classes/remove_instructor/'.$class["id"].'/'.$i["id"]); ?>">Remove Instructor</a>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table> <!--end instructor table-->
    </div>
  </div>
  <hr>
  <div class="row-fluid">
    <div class="span12">
      <a data-toggle="collapse" data-target="#assignments"><h2>Assignments</h2></a>
      <div id="assignments" class="collapse in">
      <?php if (empty($assignments)) { ?>
        <p>You do not have any assignments for this class. <a href="<?php echo site_url('assignments/create/?class='.urlencode($class['name'])); ?>">Create one now.</a></p>
      <?php } else { ?>
	<a type="button" class="btn btn-success pull-right" href="<?php echo site_url('assignments/create/?class='.urlencode($class['name'])); ?>">Create Assignment</a> 
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Assignment Name</th>
              <th>Status</th>
              <th>Controls</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($assignments as $a) { ?>
          <tr>
            <td><?php echo $a->name; ?></td>
            <td><?php
              $startDate = new DateTime($a->startDateTime);
              $endDate = new DateTime($a->endDateTime);
              if ($startDate <= new DateTime("now") && $endDate >= new DateTime("now")) {
                ?><span class="active">Active</span>
              <?php } else { ?>
                <span class="inactive">Inactive</span>
              <?php } ?>
            </td>
            <td>
              <a type="button" class="btn" href="<?php echo site_url('assignments/edit/'.$a->id.'/'.$class['id']); ?>">Edit</a>
	      <a type="button" class="btn" href="<?php echo site_url('assignments/view_grades/'.$a->id .'/'.$class['id']); ?>">View Grades</a>
            </td>
          </tr>
          <?php } ?>
          </tbody>
        </table>
      <?php } ?>
      </div> <!-- End assignment table -->
    </div>
  </div>
  <hr>
  <div class="row-fluid">
    <div class="span12">
      <?php
        $attr = array('id' => 'add-student-form', 'class' => 'form-search pull-right');
        echo form_open('classes/add_student/'.$class['id'], $attr);
      ?>
        <input type="text" name="student" autocomplete="off" id="student" class="search-query" placeholder="Search by username">
	<select class="input-medium" id="student-section" name="student-section">
	  <?php foreach($all_sections as $s) { ?>
	  <option value="<?php echo $s["name"]; ?>">Section <?php echo $s['name']; ?></option>
	  <?php } ?>
	</select>
        <button type="submit" class="btn btn-primary">Add Student</button>
      </form>
      <a data-toggle="collapse" data-target="#students"><h2>Students</h2></a>
      <div class="hide text-center alert alert-error"><span></span><button class="close">&times;</button></div>
      <div id="students" class="collapse in">
	<div class="tabbable">
	  <ul class="nav nav-tabs">
	    <li class="active"><a data-toggle="tab" href="#all">All</a></li>
	    <?php foreach($all_sections as $s) { ?>
	    <li><a data-toggle="tab" href="#<?php echo $s['name']; ?>">Section <?php echo $s['name']; ?></a></li>
	    <?php } ?>
	  </ul>
	  <div class="tab-content">
	    <div class="tab-pane active" id="all">
	      <input type="text" class="search-query" placeholder="Search for a student" />
	      <button type="button" class="btn btn-primary">Search</button>
	      <table id="table-all" class="table table-hover">
		<thead>
		  <tr>
		    <th>Name</th>
		    <th>Username</th>
		    <th>Controls</th>
		  </tr>
		</thead>
		<tbody>
		<?php foreach ($students as $s) { ?>
		<tr>
		  <td><?php echo $s['name']; ?></td>
		  <td><?php echo $s['username']; ?></td>
		  <td>
		    <a type="button" class="btn" href="<?php echo site_url('instructors/view_grades/' . $class['id'] .'/' . $s['id']); ?>">View Grades</a>
		    <a type="button" class="remove-student btn btn-danger" href="<?php echo site_url('classes/remove_student/'.$class['id'].'/'.$s['id']); ?>">Remove Student</a>
		  </td>
		</tr>
		<?php } ?>
		</tbody>
	      </table>
	    </div>
	    <?php foreach ($all_sections as $s) { ?>
	    <div class="tab-pane" id="<?php echo $s['name']; ?>">
	      <table id="table-<?php echo $s['name']; ?>" class="table table-hover">
		<thead>
		  <tr>
		    <th>Name</th>
		    <th>Username</th>
		    <th>Controls</th>
		  </tr>
		</thead>
		<tbody>
		  <?php $ss = $student_sections[$s['name']];
		  foreach($ss as $s) { ?>
		  <tr>
		    <td><?php echo $s['name']; ?></td>
		    <td><?php echo $s['username']; ?></td>
		    <td>
		      <a type="button" class="btn" href="#">View Grades</a>
		      <a type="button" class="remove-student btn btn-danger" href="<?php echo site_url('classes/remove_student/'.$class['id'].'/'.$s['id']); ?>">Remove Student</a>
		    </td>
		  </tr>
		  <?php } ?>
		</tbody>
	      </table>
	    </div>
	    <?php } ?>
	  </div>
	</div>
      </div> <!-- End student table -->
    </div>
  </div>
</div>

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!--Inline JS here -->
<script type="text/javascript">
  var students = Array();
  <?php foreach($all_students as $student) { ?>
    students.push("<?php echo $student['username']; ?>");
  <?php } ?>
  $("#student").typeahead({
    source: students
  });

  var instructors = Array();
  <?php foreach($all_instructors as $instructor) { ?>
    instructors.push("<?php echo $instructor["username"]; ?>");
  <?php } ?>
  $("#instructor").typeahead({
    source: instructors
  });

  var class_id="<?php echo $class['id'];?>";

  $("#add-student-form").submit(function(e) {
    e.preventDefault();
    $.post($(this).attr('action'), {"student": $("#student").val(), 'student-section' : $("#student-section").val()}, function(data) {
      data = $.parseJSON(data);
      if (data) {
        //add row to students table
	var table = $("#students table#table-all tbody");
        $(table).append("<tr><td>" + data.name + 
	  "</td><td>" + data.username + 
	  "</td><td>" +
	  "<a type='button' class='btn' href='#'>View Grades</a>" + 
	  "<a type='button' class='btn btn-danger remove-student' href='<?php echo site_url('classes/remove_student/'. $class['id']); ?>/" + data.id + "'>Remove Student</a>" +
	  "</td></tr>");
	var section = $("#student-section").val();
	table = $("#students table#table-"  + section + " tbody"); 
	$(table).append("<tr><td>" + data.name + 
	  "</td><td>" + data.username + 
	  "</td><td>" +
	  "<a type='button' class='btn' href='#'>View Grades</a>" + 
	  "<a type='button' class='btn btn-danger remove-student' href='<?php echo site_url('classes/remove_student/'. $class['id']); ?>/" + data.id + "'>Remove Student</a>" +
	  "</td></tr>");
      } else {
        //show error
        $("#students").prev().find('span').html('That student already belongs to this class');
        $("#students").prev().slideDown();
      }
    });
  });

  $("#add-instructor-form").submit(function(e) {
    e.preventDefault();
    $.post($(this).attr('action'), {"instructor": $("#instructor").val()}, function(data) {
      data = $.parseJSON(data);
      if (data) {
        //add row to instructors table
        var table = $("#instructors table tbody");
        $(table).append("<tr><td>" + data.name + "</td><td>" + data.username + "</td><td>[Section here]</td><td><a type='button' class='btn btn-danger remove-instructor' href='<?php echo site_url('classes/remove_instructor/'. $class["id"]); ?>/" + data.id + "'>Remove Instructor</a></td></tr>");
      } else {
        //show error
        $("#instructors").prev().find('span').html('That instructor already belongs to this class');
        $("#instructors").prev().slideDown();
      }
    });
  });

  $(document).on('click', '.remove-instructor', function(e) {
    e.preventDefault();
    var button = $(this);
    $.post($(this).attr('href'), {}, function(data) {
      data = $.parseJSON(data);
      if (!data) {
	$(button).parent().parent().remove();
      } else {
	$("#instructors").prev().find('span').html(data.error);
	$("#instructors").prev().slideDown();
      }
    });
  });

  $(document).on('click', '.remove-student', function(e) {
    e.preventDefault();
    var button = $(this);
    var name = $(button).parent().prev().text();
    $.post($(this).attr('href'), {}, function(data) {
      data = $.parseJSON(data);
      if (!data) {
	$(button).parent().parent().remove();	
	//Find other row that they're in
	var otherRow = $("#students table tbody tr td:contains('" + name + "')");
	$(otherRow).parent().remove();
      }
    });
  });

  $(".nav-tabs > li > a").click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  });

  $("button.close").click(function() {
    $(this).parent().slideUp();
  });
</script>
