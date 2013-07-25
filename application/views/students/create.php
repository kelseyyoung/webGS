<style>
</style>

<?php include_once(view_url().'templates/page_begin.php'); ?>
 
  <div class="row-fluid">
    <div class="span6 offset3 well">
      <h2 class="text-center">Create a student</h2>
      <?php
        $attr = array('class' => 'form-horizontal'); 
        echo form_open('students/create', $attr) ?>

        <div class="control-group <?php if (form_error('name')) { ?>error<?php } ?>">
          <label class="control-label" for="name">Name: </label>
          <div class="controls">
            <input type="text" id="name" name="name" value="<?php echo set_value('name'); ?>"/>
            <?php if (form_error('name')) { ?>
            <span class="help-block"><?php echo form_error('name'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('username')) { ?>error<?php } ?>">
          <label class="control-label" for="username">Username: </label>
          <div class="controls">
            <input type="text" id="username" name="username" value="<?php echo set_value('username'); ?>"/>
            <?php if (form_error('username')) { ?>
            <span class="help-block"><?php echo form_error('username'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('password')) { ?>error<?php } ?>">
          <label class="control-label" for="password">Password: </label>
          <div class="controls">
            <input type="password" id="password" name="password" />
	    <span class="help-block">
	      Must be between 6 and 20 characters
	    </span>
            <?php if (form_error('password')) { ?>
            <span class="help-block"><?php echo form_error('password'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('password-confirm')) { ?>error<?php } ?>">
          <label class="control-label" for="password-confirm">Confirm Password: </label>
          <div class="controls">
            <input type="password" name="password-confirm" />
            <?php if (form_error('password-confirm')) { ?>
            <span class="help-block"><?php echo form_error('password-confirm'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit" name="submit">Create Student</button>
        </div>

      </form>
    </div>
  </div>
</div>

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!--Inline JS here -->
