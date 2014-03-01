<article>
    <h1><?php echo $title; ?></h1>
    <?php echo validation_errors(); ?>
    <form action="<?php echo site_url('list/notes/' . $task['id'] . '/edit/' . $note['id']); ?>" method="POST">
        <fieldset>
            <!-- <legend></legend> -->
            <div>
                <label class="hidden" for="body">Note:</label>
                <textarea id="body" name="body" required="required" rows="" cols=""><?php echo set_value('body', $note['body']); ?></textarea>
            </div>
            <div>
                <input name="submit" type="submit" value="Edit note" />
            </div>
        </fieldset>
    </form>
    <p><a href="<?php echo site_url('list/notes/' . $task['id']); ?>">Notes for <?php echo $task['name']; ?></a></p>
</article>