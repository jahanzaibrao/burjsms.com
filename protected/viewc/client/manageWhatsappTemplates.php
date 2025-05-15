<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('Manage WhatsApp Templates') ?><small><?php echo SCTEXT('manage templates here for quick use in your WhatsApp campaigns') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="clearfix sepH_b">
                                    <div class="btn-group pull-right">
                                        <a href="<?php echo $data['baseurl'] ?>addWhatsappTemplate" class="btn btn-primary"><i class="fa fa-plus fa-large"></i>&nbsp; <?php echo SCTEXT('Add WhatsApp Template') ?></a>
                                        <a href="<?php echo $data['baseurl'] ?>syncWhatsappTemplates" class="btn btn-info"><i class="fa fa-refresh fa-large"></i>&nbsp; <?php echo SCTEXT('Sync with Meta') ?></a>
                                    </div>
                                </div><br />
                                <div class="">
                                    <table id="dt_wtemps" data-plugin="DataTable" data-options="{
                                             language: {url:'//cdn.datatables.net/plug-ins/1.10.20/i18n/<?php echo locale_get_display_language($_SESSION['APP_LANG']) ?>.json'}, order:[], columns: [null,null,{width:'350px'},null,null,null], responsive: {breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]}}" class="wd100 table row-border order-column">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"><?php echo SCTEXT('Name') ?></th>
                                                <th><?php echo SCTEXT('Category') ?></th>
                                                <th><?php echo SCTEXT('Template Components') ?></th>
                                                <th><?php echo SCTEXT('ID') ?></th>
                                                <th><?php echo SCTEXT('Status') ?></th>
                                                <th data-priority="2"><?php echo SCTEXT('Actions') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            //fetch from the meta

                                            $tempobj = Doo::loadModel('WbaTemplates', true);
                                            $tempobj->user_id = $_SESSION['user']['userid'];
                                            $data['temps'] = Doo::db()->find($tempobj);

                                            foreach ($data['temps'] as $temp) { ?>
                                                <tr>
                                                    <td><?php echo $temp->name ?></td>
                                                    <td><kbd><?php echo json_decode($temp->category_info)->category ?></kbd></td>
                                                    <td>
                                                        <div class="panel planopts">
                                                            <div class="panel-body mt-5" style="background-image: url('/global/img/waba-chat-bg.png');">

                                                                <div class="d-flex justify-content-start mb-4">
                                                                    <div class="message-bubble message-received">
                                                                        <?php $components = json_decode($temp->components); ?>
                                                                        <p class="message-text">
                                                                            <?php foreach ($components as $comp) { ?>
                                                                                <?php if ($comp->type == "HEADER") { ?>
                                                                                    <?php if ($comp->format == "IMAGE") { ?>
                                                                        <p class="m-b-md">
                                                                            <img src="<?php echo $comp->example->header_handle[0] ?>" style="max-width: 120px;">
                                                                        </p>
                                                                    <?php } else { ?>
                                                                        <h4><?php echo $comp->text ?></h4>
                                                                <?php }
                                                                                } ?>
                                                                <?php if ($comp->type == "BODY") { ?>
                                                                    <br>
                                                                    <p><?php echo $comp->text ?></p>
                                                                <?php } ?>
                                                                <?php if ($comp->type == "FOOTER") { ?>
                                                                    <br><br>
                                                                    <span> <?php echo $comp->text ?></span>
                                                                <?php } ?>
                                                                <?php if ($comp->type == "BUTTONS") { ?>
                                                                    <hr>
                                                                    <div class="btn-ctr">
                                                                        <?php foreach ($comp->buttons as $btn) {
                                                                        ?>
                                                                            <button class="btn whatsapp-plain-button" type="button">
                                                                                <?php
                                                                                        if ($btn->type == "QUICK_REPLY") {
                                                                                            echo '<i class="fa fa-reply"></i> ';
                                                                                        } elseif ($btn->type == "URL") {
                                                                                            echo '<i class="fa fa-link"></i> ';
                                                                                        } elseif ($btn->type == "PHONE_NUMBER") {
                                                                                            echo '<i class="fa fa-phone"></i> ';
                                                                                        } elseif ($btn->type == "COPY_CODE") {
                                                                                            echo '<i class="fa fa-copy"></i> ';
                                                                                        }
                                                                                ?>
                                                                                <?php echo $btn->text ?></button>
                                                                        <?php } ?>
                                                                    </div>

                                                                <?php  } ?>
                                                            <?php } ?>
                                                            </p>
                                                            <span class="message-time"><?php echo date('h:i A') ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td><?php $meta = json_decode($temp->meta_info);
                                                        echo $meta->id ?></td>
                                                    <td>
                                                        <?php if ($temp->status == "1") { ?>
                                                            <i class="fa fa-check-circle text-success"></i> Approved By WhatsApp
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown btn-group "><button data-toggle="dropdown" class="btn dropdown-toggle" aria-expanded="true"> Actions <span class="caret"></span></button>
                                                            <ul class="dropdown-menu dropdown-menu-btn pull-right"><!--<li><a href="#">Edit</a></li>-->
                                                                <li><a href="<?php echo Doo::conf()->APP_URL ?>deleteWhatsappTemplate/<?php echo $meta->id ?>/<?php echo base64_encode($temp->name) ?>" class="del-tid">Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>

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
            .message-bubble {
                max-width: 95%;
                padding: 10px 15px;
                border-radius: 20px;
                position: relative;
                display: inline-block;
                word-wrap: break-word;
            }

            .message-sent {
                background-color: #DCF8C6;
                color: #000;
                border-top-right-radius: 0;
            }

            .message-received {
                background-color: #FFF;
                color: #000;
                border-top-left-radius: 0;
                border: 1px solid #e6e6e6;
            }

            .message-text {
                margin: 0;
            }

            .message-time {
                display: block;
                font-size: 0.75rem;
                color: #999;
                margin-top: 5px;
                text-align: right;
            }

            .whatsapp-button {
                background-color: #25D366;
                border-color: #25D366;
                color: white;
                border-radius: 15px;
                margin-right: 10px;
                padding: 5px 15px;
                font-size: 14px;
                transition: background-color 0.3s ease;
            }

            .whatsapp-button:hover {
                background-color: #20c057;
                border-color: #20c057;
            }

            .whatsapp-button:focus {
                box-shadow: none;
            }

            .whatsapp-plain-button {
                background-color: #FFF;
                border-color: #e6e6e6;
                color: #007bff;
                border-radius: 15px;
                margin-right: 10px;
                margin-bottom: 10px;
                padding: 5px 15px;
                font-size: 14px;
                transition: background-color 0.3s ease, border-color 0.3s ease;
                display: block;
            }

            .whatsapp-plain-button:hover {
                background-color: #f1f1f1;
                border-color: #d6d6d6;
            }

            .whatsapp-plain-button:focus {
                box-shadow: none;
            }

            .btn-ctr {
                margin-top: 20px;
                text-align: center;
            }
        </style>