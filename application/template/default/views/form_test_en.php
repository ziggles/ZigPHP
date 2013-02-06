<h2>This is my awesome form</h2>

<ul>
<?php
if (count($form_errors) > 0)
{
	foreach ($form_errors as $key => $value)
	{
		echo $value;
	}
}
?>
</ul>

<?php $zig->form->create(anchor_link('test')); ?>
	<p>
	First name: <?php $zig->form->text('f_name', $form_config['f_name']); ?>
	</p>
	<p>
	Last Name: <?php $zig->form->text('l_name', $form_config['l_name']); ?>
	</p>
	<p>
	Comments: <br> <?php $zig->form->textarea('comments', $form_config['comments']); ?>
	</p>
	
	<input type="hidden" name="test[]" value="bread">
	<input type="hidden" name="test[]" value="pasta">
	<input type="hidden" name="test[]" value="chicken">
	<?php $zig->form->submit(); ?>
</form>

