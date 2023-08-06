<form role="form" id="ban_reason" data-parsley-validate="" novalidate=""
      action="<?php echo base_url(); ?>admin/warehouse/make_default/<?=$warehouse_id?>" method="post"
      class="form-horizontal form-groups-bordered">
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= lang('Tips') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">



            <div class="form-group">
                <div class="col-sm-12">
                   <p class="text-bold " style="font-size: 15px"><span style="color:#1966ff;">There is few items that already assign a warehouse.</span></p>
                    <p class="text-bold "style="font-size: 15px"><span style="color:red;">Merge All</span> = all item will be goes to same warehouse</p>
                    <p class="text-bold "style="font-size: 15px"><span style="color:red;"> Not Merge</span> = already assign item will not goes to same  warehouse</p>
<!--                    <div class="well"><a href="#" data-placement="top" data-toggle="popover" title="">Merge = all item will be goes to same warehouse</a></div>-->
<!--                    <div class="well"><a href="#" data-placement="top" data-toggle="popover" title="There are few items that already assign a warehouse!" data-content="already assign item will not goes to same  warehouse">Not merge</a></div>-->

<!--                    <textarea type="text" name="ban_reason" value="" required="" rows="5" readonly-->
<!--                              class="form-control"></textarea>-->

                </div>
            </div>
    </div>

                <div class="modal-footer bt" >

                    <button type="submit" id="btn-merge" name="submit" value="merge" class="btn btn-success " style="text-align: left">Merge All</button>
                    <button type="button" id="close-btn"  class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="btn-notmerge" name="submit" value="notmerge" class="btn btn-primary" style="text-align: right">Not Merge</button>

               </div>





</div>
</form>