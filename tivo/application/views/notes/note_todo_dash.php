
<label class="notelabel" for="todo_cb_<?php echo $r->ID ?>" style="<?php if($r->status == 1) echo "text-decoration: line-through;" ?>" id="todo_text_<?php echo $r->ID ?>"><?php echo $r->todo ?>
</label>
<p class="tiny-text fadedtext">
<?php if($r->completed > 0 && $r->status == 1) : ?>
		<?php $time = $this->common->convert_time_raw($r->completed - $r->timestamp); ?>
    	<?php echo lang("ctn_1482") ?> <?php echo $this->common->get_time_string($time) ?><br />
    <?php endif; ?>
    <a href="javascript:void(0)" onclick="delete_todo(<?php echo $r->ID ?>)"><?php echo lang("ctn_57") ?></a>
    </p>