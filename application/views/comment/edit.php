<article>
    <h1><?php echo $title; ?></h1>
    <?php echo validation_errors(); ?>
    <form action="<?php echo site_url('habit/' . $habit['slug'] . '/comment/edit/' . $comment['id']); ?>" method="POST">
        <fieldset>
            <!-- <legend></legend> -->
            <div>
                <label class="hidden" for="body">Comment:</label>
                <textarea id="body" name="body" required="required" rows="" cols=""><?php echo set_value('body', $comment['body']); ?></textarea>
            </div>
            <div>
                <input name="submit" type="submit" value="Edit comment" />
            </div>
        </fieldset>
    </form>
    <p><a href="<?php echo site_url('habit/' . $habit['slug']); ?>"><?php echo $habit['name']; ?></a></p>
</article>