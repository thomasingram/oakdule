<article>
    <h1><?php echo $title; ?></h1>
    <?php if (!empty($habit['description'])): ?>
        <p><?php echo $habit['description']; ?></p>
    <?php endif; ?>
    <?php if (!$notes): ?>
        <p></p>
    <?php else: ?>
        <?php foreach ($notes as $note): ?>
            <article class="comment note" id="note-<?php echo $note['id']; ?>">
                <p><?php echo $note['body']; ?></p>
                <footer>
                    <ul>
                        <li><time datetime="<?php echo date('Y-m-d H:i', $note['date_created']); ?>"><?php echo date('j F Y &#8211; g:i A', $note['date_created']); ?></time></li>
                        <li><a href="<?php echo site_url('list/notes/' . $task['id'] . '/edit/' . $note['id']); ?>">Edit</a></li>
                        <li><a href="<?php echo site_url('list/notes/' . $task['id'] . '/delete/' . $note['id']); ?>">Delete</a></li>
                    </ul>
                </footer>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
    <h3>Add a note</h3>
    <?php echo validation_errors(); ?>
    <form action="<?php echo site_url('list/notes/' . $task['id']); ?>" method="POST">
        <fieldset>
            <!-- <legend></legend> -->
            <div>
    			<label class="hidden" for="body">Note:</label>
    			<textarea id="body" name="body" required="required" rows="" cols=""><?php echo set_value('body'); ?></textarea>
    		</div>
    		<div>
                <input name="submit" type="submit" value="Add note" />
            </div>
        </fieldset>
    </form>
</article>