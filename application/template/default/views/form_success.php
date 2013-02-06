<font color="green"><h2>Congratulations, your form has been submitted correctly</h2></font>

<p>Your first name: <?php echo $data->f_name; ?></p>
<p>Your last name: <?php echo $data->l_name; ?></p>
<p>Your comment: <?php echo $data->comments; ?></p>

<input type="button" value="Return to Form" onclick="location.href='<?php echo anchor_link('test'); ?>'">
<p>

