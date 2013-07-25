<style>
</style>

<?php include_once(view_url().'templates/page_begin.php'); ?>
  <div class="row-fluid">
    <div class="span6 offset3 well">
      <h2 class="text-center">Create a class</h2>
      <?php
        $attr = array('class' => 'form-horizontal');
        echo form_open('classes/create', $attr) ?>

        <div class="control-group <?php if (form_error('name')) { ?>error<?php } ?>">
          <label class="control-label" for="name">Class Name: </label>
          <div class="controls">
            <input type="text" id="name" name="name" value="<?php echo set_value('name'); ?>"/>
            <?php if (form_error('name')) { ?>
            <span class="help-block"><?php echo form_error('name'); ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group <?php if(form_error('num_sections')) { ?>error<?php } ?>">
          <label class="control-label" for="num_sections">Number of Sections: </label>
          <div class="controls">
            <input type="text" class="input-small" id="num_sections" name="num_sections" value="<?php echo set_value('num_sections'); ?>" />
            <?php if (form_error('num_sections')) { ?>
            <span class="help-block"><?php echo form_error('num_sections'); ?></span>
            <?php } ?>
          </div>
        </div>
	<div class="control-group <?php if(form_error('sections')) { ?>error<?php } ?>">
	  <label class="control-label" for="sections">List Sections: </label>
	  <div class="controls">
	    <textarea type="text" id="sections" name="sections"><?php echo set_value('sections'); ?></textarea>
	    <span class="help-block">- List the sections separated by commas</span>
	    <?php if (form_error('sections')) { ?>
	    <span class="help-block"><?php echo form_error('sections'); ?></span>
	    <?php } ?>
	  </div>
	</div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit" name="submit">Create Class</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include_once(view_url().'templates/linked_js.php'); ?>

<!--Inline JS here -->
