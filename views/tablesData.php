<hr />
<h2>Insertion in '<?=$data['tableName'];?>' table</h2>
<table>
	<thead>
		<?php foreach($data['columnNames'] as $name) : ?>
			<th><?=$name;?></th>
		<?php endforeach; ?>
	</thead>
	<tbody>
		<?php foreach($data['values'] as $values) : ?>
		<tr>
			<?php foreach($values as $value) : ?>
				<td>
					<?php 
						if($value === TRUE)
							echo "TRUE";
						elseif($value === FALSE)
							echo "FALSE";
						elseif(!isset($value))
							echo "empty";
						elseif(mb_strlen($value)>50)
							echo mb_substr($value,0,50);
						else
							echo $value;
					?>
				</td>
			<?php endforeach;?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<hr />