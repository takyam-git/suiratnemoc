<?php echo Form::open(array('class' => 'form-stacked')); ?>

	<fieldset>
		<div class="actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn primary')); ?>

		</div>
	</fieldset>
<?php echo Form::close(); ?>