
<label class="notelabel" for="todo_cb_<?php echo $r->ID ?>" style="<?php if($r->status == 1) echo "text-decoration: line-through;" ?>" id="todo_text_<?php echo $r->ID ?>"><?php echo $r->todo ?>
</label>
<?php if($r->completed > 0 && $r->status == 1) : ?>
		<?php $time = $this->common->convert_time_raw($r->completed - $r->timestamp); ?>
    	<p class="small-text fadedtext">Finished in <?php echo $this->common->get_time_string($time) ?></p>
    <?php endif; ?>