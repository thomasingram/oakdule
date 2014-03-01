<aside id="sidebar" role="complementary">
    <?php if (isset($navigation_links)): ?>
        <nav role="navigation">
            <h2 class="hidden">Section navigation</h2>
            <ul>
                <?php foreach ($navigation_links as $name => $path): ?>
                    <li<?php if (uri_string() == $path) { echo ' class="here"'; } ?>><a href="<?php echo site_url($path); ?>"><?php echo $name; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>
    
    <?php if (isset($page) && $page == 'list'): ?>
        <?php if (isset($list_owner)): ?>
            <p>Twitter: <a href="http://twitter.com/<?php echo $list_owner['username']; ?>">@<?php echo $list_owner['username']; ?></a></p>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php if (isset($page) && $page == 'habit_view'): ?>
        <?php if (!$habit_listed): ?>
            <a class="button button_main" href="<?php echo site_url('list/add/' . $habit['id']); ?>">Adopt habit</a>
        <?php endif; ?>
        
        <h2>Habit info</h2>
        <p>Created <?php echo date('j F Y', $habit['date_created']); ?> by <a href="<?php echo site_url('list/' . $habit_author['username']); ?>"><?php echo $habit_author['name']; ?></a>.</p>
        <?php if ($habit_users): ?>
            <?php
            
            $users_print = count($habit_users);
            $users_print .= ($users_print > 1) ? ' people are' : ' person is';
            $users_print .= ' attempting this habit.';
            
            ?>
            <p><?php echo $users_print; ?></p>
            <ul class="users">
                <?php foreach ($habit_users as $habit_user): ?>
                    <li><a href="<?php echo site_url('list/' . $habit_user['username']); ?>"><img src="<?php echo $habit_user['profile_image_url']; ?>" /> <?php echo $habit_user['name']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
    
    <!--
    <section class="teaser">
        <h2><a href="/get_involved.php">Get involved</a></h2>
        <p>Contribute financially or personally to the project in the form of a donation, grant, second-hand equipment, or your free time.</p>
    </section>
    -->
</aside>