<article>
    <?php
    
    $profile_image_url = isset($list_owner) ? $list_owner['profile_image_url'] :
        $user['profile_image_url'];
    
    ?>
    <h1><img src="<?php echo $profile_image_url; ?>" /><?php echo $title; ?></h1>
    <?php if (!$habits): ?>
        <p><?php echo isset($list_owner) ? $list_owner['name'] . ' has' : 'You have'; ?> no archived habits.</p>
    <?php else: ?>
        <table>
            <colgroup>
                <col class="habit" />
                <col span="2" />
            </colgroup>
            <thead>
                <tr>
                    <th>Habit</th>
                    <th>Date archived</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($habits as $habit): ?>
                    <tr>
                        <td class="habit"><a href="<?php echo site_url('habit/' . $habit['slug']); ?>"><?php echo $habit['name']; ?></a><br /><?php echo $habit['description']; ?></td>
                        <td><?php echo date('j F Y', $habit['date_archived']); ?></td>
                        <?php if ($authorized): ?>
                            <td><a href="<?php echo site_url('list/add/' . $habit['id']); ?>">Restore</a></td>
                        <?php else: ?>
                            <?php if (!$this->Lst_model->is_habit_listed($habit['habit_id'], $user['id'])): ?>
                                <td><a href="<?php echo site_url('list/add/' . $habit['habit_id']); ?>">Adopt</a></td>
                            <?php endif; ?>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</article>