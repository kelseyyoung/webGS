<style>
  .thumb {
    width: 60%;
  }
</style>

<?php include_once(view_url().'templates/page_begin.php'); ?>  

  <div class="row-fluid">
    <div class="span8 offset2">
      <h2>General Help</h2>
      <h4>Navbar</h4>
      <p>Clicking either the WebGS logo or the Home link will take you to the main page, where you can see your list of classes. In the classes dropdown there are links to the class pages. The Logout button will log you out of WebGS and the University of Arizona WebAuth.</p>
      <hr>
      <h2>Student Help</h2>
      <h4>Classes</h4>
      <p>The main page shows all classes you are enrolled in. Click on View Class to see the assignments for that class.</p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/students/classes.png'; ?>" class="img-polaroid" />
      </p>
      <h4>Submitting Assignments</h4>
      <p>Find the assignment you would like to submit a file for testing for, and click the Submit button for that row. If the button is red, submission for that assignment is no longer available. In the dropdown menu, choose the files to upload and click the Submit button.</p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/students/submit.png'; ?>" class="img-polaroid" />
      </p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/students/submitModal.png'; ?>" class="img-polaroid" />
      </p>
      <h4>Submission Feedback</h4>
      <p>Upon clicking the Submit button, a progress bar will display indicating the tests are being run. The submission results page will show when the tests are completed. Your score and hints for that submission will be displayed. Do not refresh the page, as your results will expire, but can be re-viewed on the View Submissions page.</p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/students/viewResults.png'; ?>" class="img-polaroid" />
      </p>
      <h4>Viewing Submissions</h4>
      <p>To view your old submission for an assignment, click the View Submissions button on the row for the assignment you're interested in. The resulting table shows the date you submitted a file, the score you received, and any hints you may have gotten.</p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/students/viewSubmissions.png'; ?>" class="img-polaroid" />
      </p>
      <hr>
      <h2>Instructor Help</h2>
      <h4>Classes</h4>
      <p>The main page shows the classes you are enrolled in. To view a class, click the View Class button. For a shortcut to create an assignment, click the Create Assignment button. To create a new class, click the New Class button.</p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/instructors/classes.png'; ?>" class="img-polaroid" />
      </p>
      <h4>Create a Class</h4>
      <p>Fill out the form to create a class, noting that no two existing classes can have the same name. For the List Sections box, list the sections separated by commas with no spaces in between the section names.</p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/instructors/createClass.png'; ?>" class="img-polaroid" />
      </p>
      <h4>Create an Assignment</h4>
      <p>Fill out the form to create an assignment. Only .java and .txt files are allowed to be uploaded in the Test Files area. The main testcase must be a .java as well.</p> 
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/instructors/createAssignment.png'; ?>" class="img-polaroid" />
      </p>
      <h4>Viewing a Class</h4>
      <p>The class view page holds information about the class instructors, assignments, and students. There are functions to add/remove instructors and students, edit, create, and view scores for an assignment, and to view grades for a particular student.</p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/instructors/classesView.png'; ?>" class="img-polaroid" />
      </p>
      <h4>Editing an Assignment</h4>
      <p>Change the available fields on the edit assignment page and click the Edit Assignment button to change its properties. Note that if you do not want to change any test files, do not upload any extra files.</p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/instructors/editAssignment.png'; ?>" class="img-polaroid" />
      </p>
      <h4>Viewing Grades per Assignment or Student</h4>
      <p>To view all student's grades for an assignment, click the View Grades button on the Assignments table. You can then view grades for that assignment by section or by all students. To manually change a student's grade, click the Change Grade button. A dropdown will appear to change the grade. Clicking the Download Grades button in a section tab will download a .csv file to import grades to D2L.</p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/instructors/viewGradesAssignment.png'; ?>" class="img-polaroid" />
      </p>
      <p>To view student's grades for all assignments, click the View Grades button in the Students table, either in the All tab or the student's section tab. On the resulting page, you can view student's submissions and change their grade for all the assignments they've submitted for.</p>
      <p class="text-center">
        <img src="<?php echo asset_url().'img/images/instructors/viewGradesStudent.png'; ?>" class="img-polaroid" />
      </p>
    </div>
  </div>
</div> <!--End of container-fluid -->

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!-- Inline JS here-->
<script>
</script>
