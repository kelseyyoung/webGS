<style>
</style>

<?php include_once(view_url().'templates/page_begin.php'); ?>

  <div class="row-fluid">
    <div class="span12 text-center">
      <div class="hero-unit">
        <h1>Welcome to WebGS</h1>
      </div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span6 offset3">
      <?php 
        $attr = array('class' => 'form-horizontal');
        echo form_open('', $attr); ?>
        <div class="control-group <?php if (form_error('username')) { ?>error<?php } ?>">
          <label class="control-label" for="username">Username: </label>
          <div class="controls">
            <input type="text" id="username" name="username" />
            <?php if (form_error('username')) { ?>
            <span class="help-block"><?php echo form_error('username'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if (form_error('password')) { ?>error<?php } ?>">
          <label class="control-label" for="password">Password: </label>
          <div class="controls">
            <input type="password" id="password" name="password" />
            <?php if (form_error('password')) { ?>
            <span class="help-block"><?php echo form_error('password'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="controls text-center">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </form>
      <?php if(isset($errors)) { ?>
      <div class="alert alert-error"><?php echo $errors; ?></div>
      <?php } ?>
    </div>
  </div>
  <div class="row-fluid">
    <h5 class="text-center">Don't have an account? Sign up <a href="<?php echo site_url('students/create'); ?>">here.</a></h5>
  </div>
</div>

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!--Inline JS here -->
