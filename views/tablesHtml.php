<hr />
<h2>Structure of '<?=$data['tableName'];?>' table</h2>
<table>
	<thead>
		<?php foreach(get_class_vars('db_field') as $key => $value) : ?>
			<th><?=$key;?></th>
		<?php endforeach; ?>
	</thead>
	<tbody>
		<?php foreach($data['fields'] as $field) : ?>
			<tr>
				<?php foreach(get_object_vars($field) as $key => $value) : ?>
					<td>
						<?php 
							if($value === TRUE)
								echo "TRUE";
							elseif($value === FALSE)
								echo "FALSE";
							elseif(!isset($value))
								echo "empty";
							else
								echo $value;
						?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<hr />