<ul id="breadcrumbs-one">
    <li><a class="" href="<?php echo Doo::conf()->APP_URL ?>"><i class="fa fa-lg fa-home"></i></a></li>
    <?php if(!empty($data['links'])){foreach($data['links'] as $name=>$lnk){ ?>
    <li><a href="<?php echo $lnk ?>"> <?php echo SCTEXT($name) ?></a></li>
    <?php }} ?>
    <li><a href="javascript:void(0);" class="current"><?php echo SCTEXT($data['active_page']) ?></a></li>
</ul> 