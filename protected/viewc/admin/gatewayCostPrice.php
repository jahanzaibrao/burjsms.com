<main id="app-main" class="app-main">
  <?php include('breadcrums.php') ?>
  <div class="wrap">
    <section class="app-content">
      <div class="row">
        <div class="col-md-12">
          <div class="widget p-lg">
            <div class="row no-gutter">
              <h3 class="page-title-sc"><?php echo SCTEXT('Set Gateway Cost Price') ?><small><?php echo SCTEXT('enter price charged by SMPP vendor:') ?> <b><?php echo $data['rdata']->title ?></b></small></h3>
              <hr>
              <?php include('notification.php') ?>
              <div class="col-md-12">
                <!-- start content -->
                <input type="hidden" id="smppid" value="<?php echo $data['rdata']->id ?>">
                <input type="hidden" id="pmode" value="<?php echo $data['rdata']->pricing_preference ?>">
                <div class="clearfix sepH_b">
                  <div class="topselect2 col-md-6 form-group pull-left">
                    <div id="pricing_pref_box" class="panel p-sm bg-inverse m-b-0 m-t-xs">
                      <h5 class="m-h-xs"><?php echo SCTEXT('View Pricing By') ?>:</h5>
                      <hr class="m-h-xs">
                      <div>
                        <div class="radio radio-inline radio-primary">
                          <input onchange="" name="prefsel" id="prefsel_0" <?php if ($data['rdata']->pricing_preference == 0) { ?> checked="true" <?php } ?> type="radio" value="0">
                          <label for="prefsel_0"><?php echo SCTEXT('Country') ?></label>
                        </div>
                        <div class="radio radio-inline radio-primary">
                          <input onchange="" name="prefsel" id="prefsel_1" <?php if ($data['rdata']->pricing_preference == 1) { ?> checked="true" <?php } ?> type="radio" value="1">
                          <label for="prefsel_1">Operator</label>
                        </div>
                        <div class="radio radio-inline radio-primary">
                          <input onchange="" name="prefsel" id="prefsel_2" <?php if ($data['rdata']->pricing_preference == 2) { ?> checked="true" <?php } ?> type="radio" value="2">
                          <label for="prefsel_2">MCCMNC</label>
                        </div>
                      </div>
                      <div id="filterbox">
                        <h5 class="m-h-xs"><?php echo SCTEXT('Filter By') ?></h5>
                        <hr class="m-h-xs">
                        <div class="clearfix">
                          <div class="col-md-6 p-r-sm">
                            <select class="form-control" data-plugin="select2" id="cvsel" data-options="{ templateSelection: function (data){ if(!data.element){return ''};  var nstr = '<div class=\'text-center text-white\'>'+data.text+'</div>';return $(nstr); } }">
                              <option value="0"> <?php echo SCTEXT('All Countries') ?> </option>
                              <?php foreach ($data['cvdata'] as $cv) { ?>
                                <option value="<?php echo $cv->prefix ?>"> <?php echo $cv->country . ' (+' . $cv->prefix . ')' ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="col-md-6 p-r-sm">
                            <select class="form-control" data-plugin="select2" id="opsel" data-options="{ templateSelection: function (data){ if(!data.element){return ''};  var nstr = '<div class=\'text-center text-white\'>'+data.text+'</div>';return $(nstr); } }">
                              <option value="0"> <?php echo SCTEXT('All Operators') ?> </option>
                              <?php foreach ($data['opdata'] as $op) { ?>
                                <option class="opitem" data-cpre="<?php echo $op->country_code ?>" value="<?php echo base64_encode($op->brand . '|' . $op->country_iso) ?>"> <?php echo $op->brand . ' (' . $op->country_iso . ')' ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>


                  </div>
                  <div class="btn-group pull-right">
                    <a href="<?php echo $data['baseurl'] ?>uploadGatewayCostPricing/<?php echo $data['rdata']->id ?>" class="btn btn-primary"><i class="fa fa-upload fa-large"></i>&nbsp; <?php echo SCTEXT('Import Vendor Rates') ?></a>

                  </div>
                </div><br />
                <div id="sorted_dataset">
                  <table id="dt-routes-def-price_sorted" data-plugin="DataTable" data-options="{
                                            ajax: '<?php echo Doo::conf()->APP_URL ?>getGatewayCostPriceSorted/<?php echo $data['rdata']->id ?>/'+$('#cvsel').val()+'/'+$('#opsel').val()+'/<?php echo intval($data['rdata']->pricing_preference) ?>', serverSide: true, processing: true, order:[], ordering: false, language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                    <thead>
                      <tr>
                        <th><?php echo SCTEXT('Country') ?></th>
                        <th data-priority="1">MCCMNC</th>
                        <th><?php echo SCTEXT('Brand') ?></th>
                        <th><?php echo SCTEXT('Operator') ?></th>
                        <th data-priority="3"><?php echo SCTEXT('Cost Price') ?></th>
                        <th data-priority="2"><?php echo SCTEXT('Actions') ?></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>

                <!-- end content -->
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
    <style>
      select option[disabled] {
        display: none;
      }

      .select2-container--default .select2-results__option[aria-disabled=true] {
        display: none;
      }
    </style>