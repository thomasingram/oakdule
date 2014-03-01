<article>
	<h1>Create new habit</h1>
	<?php echo validation_errors(); ?>
	<form action="<?php echo site_url('list/add'); ?>" method="POST">
		<fieldset>
			<!-- <legend></legend> -->
			<div>
    			<label for="name">Name:</label>
    			<input id="name" name="name" type="text" value="<?php echo set_value('name'); ?>" required="required" />
    		</div>
			<div>
    			<label for="description">Description:</label>
    			<textarea id="description" name="description" placeholder="Provide more details about your new habit"><?php echo set_value('description'); ?></textarea>
    		</div>
			<div>
    			<input name="submit" type="submit" value="Create habit" />
    		</div>
		</fieldset>
	</form>
</article>