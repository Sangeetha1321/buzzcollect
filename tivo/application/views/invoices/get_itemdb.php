<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-plus"></span> <?php echo lang("ctn_620") ?></h4>
      </div>
      <div class="modal-body form-horizontal">
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1448") ?></label>
                <div class="col-md-8 ui-front">
                    <select class="form-control" id="item-itemdb">
                    <?php foreach($items->result() as $r) : ?>
                      <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="button" class="btn btn-primary" value="<?php echo lang("ctn_1462") ?>" id="add_item_to_invoice_items">
        
      </div>