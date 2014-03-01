<?php if ($habits): ?>

<?php if (!isset($user)): ?>

<div class="statement">
	<img src="<?php echo base_url('img/tom.jpg'); ?>" alt="Thomas Ingram" height="60" width="60" />
	<p>&#8216;This is my live habit list. I try to spend a little time every day to work on these habits, and the 20 minute sessions really add up! I made this application this way, in 20 minute increments. See how much you can accomplish with Oakdule.&#8217;</p>
	<p>Thomas Ingram, developer</p>
</div>
<?php endif; ?>
<form id="list-form" action="<?php echo site_url('list'); ?>" method="POST">
	<input name="submit" type="submit" value="Save" />
	<table id="list">
		<thead>
			<tr>
				<td></td>
				
				<?php foreach ($habits as $habit): ?>
				<th id="<?php echo $habit['id']; ?>" scope="col"><span id="<?php echo 'count' . $habit['id']; ?>"><?php echo str_pad($habit['number_of_entries'], 2, '0', STR_PAD_LEFT); ?></span><a href="<?php echo site_url(); ?>"><?php echo $habit['name']; ?></a> <?php if (isset($user)): ?><a class="delete" href="<?php echo site_url('list/delete/' . $habit['id']); ?>">Delete <?php echo $habit['name']; ?></a><?php endif; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			
			<?php
			$today = mktime(0, 0, 0);
			$yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
			
			foreach ($entries as $entry_date => $entry_tasks)
			{
				$date = date('D j M', $entry_date);
				
				if ($entry_date == $today)
				{
					$date = 'Today';
				}
				elseif ($entry_date == $yesterday)
				{
					$date = 'Yesterday';
				}
			?>
			
			<tr>
				<th<?php if ($entry_date == $today): ?> class="today"<?php endif; ?> id="<?php echo date('Y-m-d', $entry_date); ?>" scope="row"><time datetime="<?php echo date('Y-m-d', $entry_date); ?>"><?php echo $date; ?></time></th>
				
				<?php
				foreach ($entry_tasks as $task_id => $task_checked)
				{
					$task_start_date = time();
					
					foreach ($habits as $habit)
					{
						if ($habit['id'] == $task_id)
						{
							$task_start_date = $habit['date_started'];
							break;
						}
					}
					
					// Entries from today and yesterday are editable
					$editable = (isset($user) &&
						$entry_date >= $yesterday &&
						$entry_date >= $task_start_date);
				?>
				<td headers="<?php echo $task_id . ' ' . date('Y-m-d', $entry_date); ?>"><?php if ($editable): ?><input type="checkbox" name="<?php echo 'cb-' . $task_id . '-' . date('Y-m-d', $entry_date); ?>"<?php if ($task_checked): ?> checked="checked"<?php endif; ?> /><?php else: ?><?php if ($task_checked): ?><img src="<?php echo base_url('img/done.png'); ?>" alt="Done" height="9" width="9" /><?php endif; ?><?php endif; ?></td>
				
				<?php
				}
				?>
			</tr>
			
			<?php
			}
			?>
		</tbody>
	</table>
</form>

<?php
if (isset($pagination)) {
	echo $pagination;
}
?>

<?php else: ?>
<p>You have no habits. Why not create one?</p>
<?php endif; ?>