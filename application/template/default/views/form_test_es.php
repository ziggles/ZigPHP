<h2>Esta es mi Forma Impresionante</h2>

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
	Nombre: <?php $zig->form->text('f_name', $form_config['f_name']); ?>
	</p>
	<p>
	Apellido: <?php $zig->form->text('l_name', $form_config['l_name']); ?>
	</p>
	<p>
	Commentarios: <br> <?php $zig->form->textarea('comments', $form_config['comments']); ?>
	</p>
	
	<input type="hidden" name="test[]" value="bread">
	<input type="hidden" name="test[]" value="pasta">
	<input type="hidden" name="test[]" value="chicken">
	<?php $zig->form->submit(); ?>
</form>

