<article>
    <h1><?php echo $title; ?></h1>
    <?php if (!empty($habit['description'])): ?>
        <p><?php echo $habit['description']; ?></p>
    <?php endif; ?>
    <?php
    
    $comments_heading = 'Comments';
    
    if ($comments)
    {
        $number_of_comments = count($comments);
        $comments_heading = substr_replace($comments_heading,
                                           $number_of_comments . ' ', 0, 0);
        if ($number_of_comments == 1)
        {
            $comments_heading = substr($comments_heading, 0, -1);
        }
    }
    
    ?>
    <h2><?php echo $comments_heading; ?></h2>
    <?php if (!$comments): ?>
        <p></p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <article class="comment" id="comment-<?php echo $comment['id']; ?>">
                <p><?php echo $comment['body']; ?></p>
                <footer>
                    <ul>
                        <li><a href="<?php echo site_url('list/' . $comment['user']['username']); ?>"><img src="<?php echo $comment['user']['profile_image_url']; ?>" /> <?php echo $comment['user']['name']; ?></a></li>
                        <li><time datetime="<?php echo date('Y-m-d H:i', $comment['date_created']); ?>"><?php echo date('j F Y &#8211; g:i A', $comment['date_created']); ?></time></li>
                        <?php if ($comment['user_id'] == $user['id']): ?>
                            <li><a href="<?php echo site_url('habit/' . $habit['slug'] . '/comment/edit/' . $comment['id']); ?>">Edit</a></li>
                            <li><a href="<?php echo site_url('habit/' . $habit['slug'] . '/comment/delete/' . $comment['id']); ?>">Delete</a></li>
                        <?php endif; ?>
                    </ul>
                </footer>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
    <h3>Add a comment</h3>
    <?php echo validation_errors(); ?>
    <form id="comment_add" action="<?php echo site_url('habit/' . $habit['slug']); ?>" method="POST">
        <fieldset>
            <!-- <legend></legend> -->
            <div>
                <img src="<?php echo $user['profile_image_url']; ?>" alt="" />
    			<label class="hidden" for="body">Comment:</label>
    			<textarea id="body" name="body" required="required" rows="" cols=""><?php echo set_value('body'); ?></textarea>
    		</div>
    		<div>
                <input name="submit" type="submit" value="Add comment" />
            </div>
        </fieldset>
    </form>
</article>