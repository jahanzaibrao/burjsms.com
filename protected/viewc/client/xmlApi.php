<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc">XML API<small><?php echo SCTEXT('developer API in XML format with sample codes')?></small></h3>
                                <hr>
                                <?php if($data['permission'] == 0){ ?>
                                    <?php include('notification.php') ?>
                                <?php }else{ ?>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                    <!-- start content -->

								<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab1" data-toggle="tab">Text SMS API</a></li>
									<li><a href="#tab2" data-toggle="tab">Flash SMS API</a></li>
									<li><a href="#tab3" data-toggle="tab">WAP-Push API</a></li>
									<li><a href="#tab4" data-toggle="tab">vCard SMS API</a></li>
									<li><a href="#tab5" data-toggle="tab">Unicode SMS API</a></li>
									<li><a href="#tab6" data-toggle="tab"><?php echo SCTEXT('Credit Balance')?> API</a></li>
									<li><a href="#tab7" data-toggle="tab"><?php echo SCTEXT('Delivery Reports')?> API</a></li>
								</ul>

								<div id="apitabctr" class="tab-content p-v-lg">


									<div class="tab-pane active fade in" id="tab1"><br /><br />

                            				<div class="clearfix">
												<div class="col-md-6">
                                                	 <div class="formSep"><span class="label label-info">API Url (GET and POST)</span> <span class="label label-success">TEXT SMS API</span>
												<h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>smsapi/index</h4>

                                                </div>
                                                </div>




                                                <div style="text-align:right" class="col-md-6 bg-warning text-dark"><?php echo SCTEXT('Your API Key is')?>: <strong><?php echo $data['apikey'] ?> </strong>&nbsp;<button class="btn-primary btn"><?php echo SCTEXT('Regenerate Key')?></button>
								                </div>



                                            </div>

                                                <!-----PARAMETERS --->
                                                <div class="m-t-xs clearfix">
                                                <div class="col-md-5 rescroll">


    											  <table class=" table table-striped table-bordered">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('Parameters')?></th>
                                                        <th><?php echo SCTEXT('Meaning')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        <td>key</td>
                                                        <td><?php echo SCTEXT('API Key')?></td>
                                                        <td><?php echo SCTEXT('Your API Key')?> (<?php echo $data['apikey'] ?>)</td>
                                                        </tr>

                                                        <tr>
                                                        <td>campaign</td>
                                                        <td> <?php echo SCTEXT('as defined in description')?></td>
                                                        <td>
                                                       <?php echo SCTEXT("Enter ID of campaign as shown below. You can use 0 (zero) for the default campaign.")?>
                                                       <?php foreach($data['camps'] as $cmp){ ?>
                                                               <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info"><?php echo $cmp->campaign_name ?></span>
                                                                    </div>

                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = '.$cmp->id ?></h5>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        </td>
                                                        </tr>
                                                        <?php if($_SESSION['user']['account_type']!=0){ ?>
                                                        <tr>
                                                        <td>routeid</td>
                                                        <td><?php echo SCTEXT('ID of the route as defined in description')?></td>
                                                        <td>
                                                        	<?php foreach($_SESSION['credits']['routes'] as $rt){ ?>
                                                               <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info"><?php echo $rt['name'] ?></span>
                                                                    </div>

                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = '.$rt['id'] ?></h5>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        </td>
                                                        </tr>
                                                            <?php } ?>
                                                        <tr>
                                                        <td>type</td>
                                                        <td><?php echo SCTEXT('SMS Type')?></td>
                                                        <td><?php echo SCTEXT('Leave this as')?> <strong>text</strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td>contacts</td>
                                                        <td><?php echo SCTEXT('Contact Numbers')?></td>
                                                        <td><?php echo SCTEXT("Contact numbers separated by ' , ' (comma) sign")?><br>e.g. 919887XXXXXX,919567XXXXXX</td>
                                                        </tr>

                                                         <tr>
                                                        <td>senderid</td>
                                                        <td><?php echo SCTEXT('Sender ID')?></td>
                                                        <td><?php echo SCTEXT('Any Approved Sender ID')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>msg</td>
                                                        <td><?php echo SCTEXT('SMS Text')?></td>
                                                        <td><?php echo SCTEXT('Url-encoded SMS text. Must be limited to 720 characters')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>time</td>
                                                        <td><?php echo SCTEXT('Schedule Time')?></td>
                                                        <td><?php echo SCTEXT('Enter the time in the format YYYY-MM-DD H:I e.g. enter 2013-03-19 14:30 for 19th March 2013, 2:30 pm. Leave BLANK to send the SMS instantly.')?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>



                                                <!----- RESPONSES -------->
													<h4 style="margin-top:15px;" class="page-header"><?php echo SCTEXT('Responses')?></h4>
                                               <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('API Response')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        	<td>
                                                            <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Message_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;err>{MESSAGE}&lt;/err><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the response.')?></td>
                                                        </tr>
                                                        <tr>
                                                        	<td>
                                                             <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Message_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;sms_shoot_id>{ID}&lt;/sms_shoot_id><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('This means SMS was submitted successfully, and it returns the shoot-id of the submission. You could use this ID to pull out delivery reports.')?></td>
                                                        </tr>
                                                    </tbody>
                                                    </table>

												</div>



                                                <!----- URL & SAMPLE CODE ---->

                                                <div class="p-l-xs col-md-7 rescroll">

                                                	 <table class="table table-bordered">
                                                    <tbody>
                                                    	<tr>
                                                        <td><strong><?php echo SCTEXT('Sample XML & API Codes')?></strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"><?php echo SCTEXT('Request Format')?></span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">


                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Message><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key>{API Key}&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;campaign>0&lt;/campaign><br /> <?php if($_SESSION['user']['account_type']!=1){ ?>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;routeid>{Route ID}&lt;/routeid><br /> <?php } ?>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;type>{SMS Type}&lt;/type><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;contacts><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br />
                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/contacts><br />
                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;senderid>{Sender ID}&lt;/senderid><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msg>{SMS Content}&lt;/msg><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;time>{Schedule time}&lt;/time><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message>


                                                            </code>
                                                            </div>
                                                        </td>
                                                        </tr>


                                                         <tr>
                                                         <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"> <?php echo SCTEXT('Sample Request Code')?> (PHP)</span>
                                                        <div class="well">
                                                            <code style="white-space:normal;border:none;">
                                                            &lt;?php

                                                           <p class="text-info">
                                                           $xml = '&lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Message><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key><span class="text-error"><?php echo $data['apikey'] ?></span>&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;campaign><span class="text-error">0</span>&lt;/campaign><br />
                                                            <?php if($_SESSION['user']['account_type']!=1){ ?>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;routeid><span class="text-error">14</span>&lt;/routeid><br /> <?php } ?>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;type><span class="text-error">text</span>&lt;/type><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;contacts><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">98765XXXXX</span>&lt;/msisdn><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">84650XXXXX</span>&lt;/msisdn><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">90113XXXXX</span>&lt;/msisdn><br />
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">89041XXXXX</span>&lt;/msisdn><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">97800XXXXX</span>&lt;/msisdn><br />

                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/contacts><br />
                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;senderid><span class="text-error">DEMO</span>&lt;/senderid><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msg><span class="text-error">Hello People, have a nice day.</span>&lt;/msg><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;time>&lt;/time><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message>';<br />

                                                           </p>
                                                            <p class="text-warning">//Submit to server</p>
                                                             <p class="text-info">
                                                             $ch = curl_init();<br />
                                                            curl_setopt($ch,CURLOPT_URL,  "<?php echo $data['baseurl'] ?>smsapi/index");<br />
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);<br />
curl_setopt($ch, CURLOPT_POST, 1);<br />
curl_setopt($ch, CURLOPT_POSTFIELDS, "xml=".<span class="text-error">$xml</span>);<br />
$response = curl_exec($ch);<br />
curl_close($ch);<br />
                                                             <br />
                                                             $xml_response = simplexml_load_string(<span class="text-error">$response</span>);<br />

echo $xml_response->sms_shoot_id?$xml_response->sms_shoot_id:$xml_response->err;
                                                             </p>
                                                            ?&gt;
                                                            </code>
                                                            </div>

                                                        </td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                                </div>




											</div>
									</div>



									<div class="tab-pane fade" id="tab2"><br /><br />
                            				<div class="clearfix">
												<div class="col-md-6">
                                                	 <div class="formSep"><span class="label label-info">API Url (GET and POST)</span> <span class="label label-success">FLASH SMS API</span>
												<h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>smsapi/index</h4>

                                                </div>
                                                </div>




                                                <div style="text-align:right" class="col-md-6 bg-warning text-dark"><?php echo SCTEXT('Your API Key is')?>: <strong><?php echo $data['apikey'] ?> </strong>&nbsp;<button class="btn-primary btn"><?php echo SCTEXT('Regenerate Key')?></button>
								                </div>



                                            </div>
                                                <!-----PARAMETERS --->
                                                <div class="m-t-xs clearfix">
                                                <div class="col-md-5 rescroll">


    											 <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('Parameters')?></th>
                                                        <th><?php echo SCTEXT('Meaning')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        <td>key</td>
                                                        <td><?php echo SCTEXT('API Key')?></td>
                                                        <td><?php echo SCTEXT('Your API Key')?> (<?php echo $data['apikey'] ?>)</td>
                                                        </tr>

                                                        <tr>
                                                        <td>campaign</td>
                                                        <td> <?php echo SCTEXT('as defined in description')?></td>
                                                        <td>
                                                       <?php echo SCTEXT("Enter ID of campaign as shown below. You can use 0 (zero) for the default campaign.")?>
                                                       <?php foreach($data['camps'] as $cmp){ ?>
                                                               <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info"><?php echo $cmp->campaign_name ?></span>
                                                                    </div>

                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = '.$cmp->id ?></h5>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        </td>
                                                        </tr>

                                                        <tr>
                                                        <td>routeid</td>
                                                        <td><?php echo SCTEXT('ID of the route as defined in description')?></td>
                                                        <td>
                                                        	<?php foreach($_SESSION['credits']['routes'] as $rt){ ?>
                                                               <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info"><?php echo $rt['name'] ?></span>
                                                                    </div>

                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = '.$rt['id'] ?></h5>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        </td>
                                                        </tr>

                                                        <tr>
                                                        <td>type</td>
                                                        <td><?php echo SCTEXT('SMS Type')?></td>
                                                        <td><?php echo SCTEXT('Leave this as')?> <strong>flash</strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td>contacts</td>
                                                        <td><?php echo SCTEXT('Contact Numbers')?></td>
                                                        <td><?php echo SCTEXT("Contact numbers separated by ' , ' (comma) sign")?><br>e.g. 9887XXXXXX,9567XXXXXX</td>
                                                        </tr>

                                                         <tr>
                                                        <td>senderid</td>
                                                        <td><?php echo SCTEXT('Sender ID')?></td>
                                                        <td><?php echo SCTEXT('Any Approved Sender ID')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>msg</td>
                                                        <td><?php echo SCTEXT('SMS Text')?></td>
                                                        <td><?php echo SCTEXT('Url-encoded SMS text. Must be limited to 720 characters')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>time</td>
                                                        <td><?php echo SCTEXT('Schedule Time')?></td>
                                                        <td><?php echo SCTEXT('Enter the time in the format YYYY-MM-DD H:I e.g. enter 2013-03-19 14:30 for 19th March 2013, 2:30 pm. Leave BLANK to send the SMS instantly.')?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>


                                                <!----- RESPONSES -------->
													<h4 style="margin-top:15px;" class="page-header"><?php echo SCTEXT('Responses')?></h4>
                                                <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('API Response')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        	<td>
                                                            <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Message_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;err>{MESSAGE}&lt;/err><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the response.')?></td>
                                                        </tr>
                                                        <tr>
                                                        	<td>
                                                             <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Message_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;sms_shoot_id>{ID}&lt;/sms_shoot_id><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('This means SMS was submitted successfully, and it returns the shoot-id of the submission. You could use this ID to pull out delivery reports.')?></td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
													</div>



                                                <!----- URL & SAMPLE CODE ---->

                                                <div class=" p-l-xs col-md-7 rescroll">

                                                	  <table class="table table-bordered">
                                                    <tbody>
                                                    	<tr>
                                                        <td><strong><?php echo SCTEXT('Sample XML & API Codes')?></strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"><?php echo SCTEXT('Request Format')?></span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">


                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Message><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key>{API Key}&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;campaign>0&lt;/campaign><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;routeid>{Route ID}&lt;/routeid><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;type>{SMS Type}&lt;/type><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;contacts><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br />
                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/contacts><br />
                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;senderid>{Sender ID}&lt;/senderid><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msg>{SMS Content}&lt;/msg><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;time>{Schedule time}&lt;/time><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message>


                                                            </code>
                                                            </div>
                                                        </td>
                                                        </tr>


                                                         <tr>
                                                         <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"> <?php echo SCTEXT('Sample Request Code')?> (PHP)</span>
                                                        <div class="well">
                                                            <code style="white-space:normal;border:none;">
                                                            &lt;?php

                                                           <p class="text-info">
                                                           $xml = '&lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Message><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key><span class="text-error"><?php echo $data['apikey'] ?></span>&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;campaign><span class="text-error">0</span>&lt;/campaign><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;routeid><span class="text-error">14</span>&lt;/routeid><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;type><span class="text-error">flash</span>&lt;/type><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;contacts><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">98765XXXXX</span>&lt;/msisdn><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">84650XXXXX</span>&lt;/msisdn><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">90113XXXXX</span>&lt;/msisdn><br />
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">89041XXXXX</span>&lt;/msisdn><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">97800XXXXX</span>&lt;/msisdn><br />

                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/contacts><br />
                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;senderid><span class="text-error">DEMO</span>&lt;/senderid><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msg><span class="text-error">Hello People, have a nice day.</span>&lt;/msg><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;time>&lt;/time><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message>';<br />

                                                           </p>
                                                            <p class="text-warning">//Submit to server</p>
                                                             <p class="text-info">
                                                             $ch = curl_init();<br />
                                                            curl_setopt($ch,CURLOPT_URL,  "<?php echo $data['baseurl'] ?>smsapi/index");<br />
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);<br />
curl_setopt($ch, CURLOPT_POST, 1);<br />
curl_setopt($ch, CURLOPT_POSTFIELDS, "xml=".<span class="text-error">$xml</span>);<br />
$response = curl_exec($ch);<br />
curl_close($ch);<br />
                                                             <br />
                                                             $xml_response = simplexml_load_string(<span class="text-error">$response</span>);<br />

echo $xml_response->sms_shoot_id?$xml_response->sms_shoot_id:$xml_response->err;
                                                             </p>
                                                            ?&gt;
                                                            </code>
                                                            </div>

                                                        </td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                                </div>




											</div>

									</div>



                                    <div class="tab-pane fade" id="tab3"><br /><br />
										<div class="clearfix">

												<div class="col-md-6">
                                                	 <div class="formSep"><span class="label label-info">API Url (GET and POST)</span> <span class="label label-success">WAP-PUSH SMS API</span>
												<h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>smsapi/index</h4>

                                                </div>
                                                </div>


                                                <div style="text-align:right" class="col-md-6 bg-warning text-dark"><?php echo SCTEXT('Your API Key is')?>: <strong><?php echo $data['apikey'] ?> </strong>&nbsp;<button class="btn-primary btn"><?php echo SCTEXT('Regenerate Key')?></button>
								                </div>



                                            </div>
                                                <!-----PARAMETERS --->
                                                <div class="m-t-xs clearfix">
                                                <div class="col-md-5 rescroll">


    											 <table class=" table table-striped table-bordered">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('Parameters')?></th>
                                                        <th><?php echo SCTEXT('Meaning')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        <td>key</td>
                                                        <td><?php echo SCTEXT('API Key')?></td>
                                                        <td><?php echo SCTEXT('Your API Key')?> (<?php echo $data['apikey'] ?>)</td>
                                                        </tr>

                                                        <tr>
                                                        <td>campaign</td>
                                                        <td> <?php echo SCTEXT('as defined in description')?></td>
                                                        <td>
                                                       <?php echo SCTEXT("Enter ID of campaign as shown below. You can use 0 (zero) for the default campaign.")?>
                                                       <?php foreach($data['camps'] as $cmp){ ?>
                                                               <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info"><?php echo $cmp->campaign_name ?></span>
                                                                    </div>

                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = '.$cmp->id ?></h5>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        </td>
                                                        </tr>

                                                        <tr>
                                                        <td>routeid</td>
                                                        <td><?php echo SCTEXT('ID of the route as defined in description')?></td>
                                                        <td>
                                                        	<?php foreach($_SESSION['credits']['routes'] as $rt){ ?>
                                                               <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info"><?php echo $rt['name'] ?></span>
                                                                    </div>

                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = '.$rt['id'] ?></h5>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        </td>
                                                        </tr>

                                                        <tr>
                                                        <td>type</td>
                                                        <td><?php echo SCTEXT('SMS Type')?></td>
                                                        <td><?php echo SCTEXT('Leave this as')?> <strong>wap</strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td>contacts</td>
                                                        <td><?php echo SCTEXT('Contact Numbers')?></td>
                                                        <td><?php echo SCTEXT("Contact numbers separated by ' , ' (comma) sign")?><br>e.g. 9887XXXXXX,9567XXXXXX</td>
                                                        </tr>

                                                         <tr>
                                                        <td>senderid</td>
                                                        <td><?php echo SCTEXT('Sender ID')?></td>
                                                        <td><?php echo SCTEXT('Any Approved Sender ID')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>wap_title</td>
                                                        <td>WAP title</td>
                                                        <td><?php echo SCTEXT('Url-encoded title of WAP-Push. Must be limited to 60 characters')?></td>
                                                        </tr>


                                                         <tr>
                                                        <td>wap_url</td>
                                                        <td><?php echo SCTEXT('Link to the WAP content')?></td>
                                                        <td><?php echo SCTEXT('Url-encoded wap-url, including the protocol (wap/http). Must be limited to 100 characters')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>time</td>
                                                        <td><?php echo SCTEXT('Schedule Time')?></td>
                                                        <td><?php echo SCTEXT('Enter the time in the format YYYY-MM-DD H:I e.g. enter 2013-03-19 14:30 for 19th March 2013, 2:30 pm. Leave BLANK to send the SMS instantly.')?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>


                                                <!----- RESPONSES -------->
													<h4 style="margin-top:15px;" class="page-header"><?php echo SCTEXT('Responses')?></h4>
                                                 <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('API Response')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        	<td>
                                                            <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Message_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;err>{MESSAGE}&lt;/err><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the response.')?></td>
                                                        </tr>
                                                        <tr>
                                                        	<td>
                                                             <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Message_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;sms_shoot_id>{ID}&lt;/sms_shoot_id><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('This means SMS was submitted successfully, and it returns the shoot-id of the submission. You could use this ID to pull out delivery reports.')?></td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
													</div>



                                                <!----- URL & SAMPLE CODE ---->

                                                <div class="p-l-xs col-md-7 rescroll">

                                                	<table class="table table-bordered">
                                                    <tbody>
                                                    	<tr>
                                                        <td><strong><?php echo SCTEXT('Sample XML & API Codes')?></strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"><?php echo SCTEXT('Request Format')?></span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">


                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Message><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key>{API Key}&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;campaign>0&lt;/campaign><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;routeid>{Route ID}&lt;/routeid><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;type>{SMS Type}&lt;/type><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;contacts><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br />
                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/contacts><br />
                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;senderid>{Sender ID}&lt;/senderid><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;wap_title>{WAP Title}&lt;/wap_title><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;wap_url>{URL of WAP content}&lt;/wap_url><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;time>{Schedule time}&lt;/time><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message>


                                                            </code>
                                                            </div>
                                                        </td>
                                                        </tr>


                                                         <tr>
                                                         <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"> <?php echo SCTEXT('Sample Request Code')?> (PHP)</span>
                                                        <div class="well">
                                                            <code style="white-space:normal;border:none;">
                                                            &lt;?php

                                                           <p class="text-info">
                                                           $xml = '&lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Message><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key><span class="text-error"><?php echo $data['apikey'] ?></span>&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;campaign><span class="text-error">0</span>&lt;/campaign><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;routeid><span class="text-error">14</span>&lt;/routeid><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;type><span class="text-error">wap</span>&lt;/type><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;contacts><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">98765XXXXX</span>&lt;/msisdn><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">84650XXXXX</span>&lt;/msisdn><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">90113XXXXX</span>&lt;/msisdn><br />
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">89041XXXXX</span>&lt;/msisdn><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">97800XXXXX</span>&lt;/msisdn><br />

                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/contacts><br />
                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;senderid><span class="text-error">DEMO</span>&lt;/senderid><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;wap_title><span class="text-error">Download Free Wallpapers</span>&lt;/wap_title><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;wap_url><span class="text-error">http://www.mysite.net/wallpapers/</span>&lt;/wap_url><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;time>&lt;/time><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message>';<br />

                                                           </p>
                                                            <p class="text-warning">//Submit to server</p>
                                                             <p class="text-info">
                                                             $ch = curl_init();<br />
                                                            curl_setopt($ch,CURLOPT_URL,  "<?php echo $data['baseurl'] ?>smsapi/index");<br />
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);<br />
curl_setopt($ch, CURLOPT_POST, 1);<br />
curl_setopt($ch, CURLOPT_POSTFIELDS, "xml=".<span class="text-error">$xml</span>);<br />
$response = curl_exec($ch);<br />
curl_close($ch);<br />
                                                             <br />
                                                             $xml_response = simplexml_load_string(<span class="text-error">$response</span>);<br />

echo $xml_response->sms_shoot_id?$xml_response->sms_shoot_id:$xml_response->err;
                                                             </p>
                                                            ?&gt;
                                                            </code>
                                                            </div>

                                                        </td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                                </div>





										</div>
									</div>


                                    <div class="tab-pane fade" id="tab4"><br /><br />

                            				<div class="clearfix">
												<div class="col-md-6">
                                                	 <div class="formSep"><span class="label label-info">API Url (GET and POST)</span> <span class="label label-success">vCARD SMS API</span>
												<h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>smsapi/index</h4>

                                                </div>
                                                </div>

                                                <div style="text-align:right" class="col-md-6 bg-warning text-dark"><?php echo SCTEXT('Your API Key is')?>: <strong><?php echo $data['apikey'] ?> </strong>&nbsp;<button class="btn-primary btn"><?php echo SCTEXT('Regenerate Key')?></button>
								                </div>



                                            </div>
                                                <!-----PARAMETERS --->
                                                <div class="m-t-xs clearfix">
                                                <div class="col-md-5 rescroll">


    											 <table class=" table table-striped table-bordered">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('Parameters')?></th>
                                                        <th><?php echo SCTEXT('Meaning')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        <td>key</td>
                                                        <td><?php echo SCTEXT('API Key')?></td>
                                                        <td><?php echo SCTEXT('Your API Key')?> (<?php echo $data['apikey'] ?>)</td>
                                                        </tr>

                                                        <tr>
                                                        <td>campaign</td>
                                                        <td> <?php echo SCTEXT('as defined in description')?></td>
                                                        <td>
                                                       <?php echo SCTEXT("Enter ID of campaign as shown below. You can use 0 (zero) for the default campaign.")?>
                                                       <?php foreach($data['camps'] as $cmp){ ?>
                                                               <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info"><?php echo $cmp->campaign_name ?></span>
                                                                    </div>

                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = '.$cmp->id ?></h5>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        </td>
                                                        </tr>

                                                        <tr>
                                                        <td>routeid</td>
                                                        <td><?php echo SCTEXT('ID of the route as defined in description')?></td>
                                                        <td>
                                                        	<?php foreach($_SESSION['credits']['routes'] as $rt){ ?>
                                                               <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info"><?php echo $rt['name'] ?></span>
                                                                    </div>

                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = '.$rt['id'] ?></h5>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        </td>
                                                        </tr>

                                                        <tr>
                                                        <td>type</td>
                                                        <td><?php echo SCTEXT('SMS Type')?></td>
                                                        <td><?php echo SCTEXT('Leave this as')?> <strong>vcard</strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td>contacts</td>
                                                        <td><?php echo SCTEXT('Contact Numbers')?></td>
                                                        <td><?php echo SCTEXT("Contact numbers separated by ' , ' (comma) sign")?><br>e.g. 9887XXXXXX,9567XXXXXX</td>
                                                        </tr>

                                                         <tr>
                                                        <td>senderid</td>
                                                        <td><?php echo SCTEXT('Sender ID')?></td>
                                                        <td><?php echo SCTEXT('Any Approved Sender ID')?></td>
                                                        </tr>

                                                        <tr>
                                                        <td>first_name</td>
                                                        <td>-</td>
                                                        <td><?php echo SCTEXT('First Name of the person')?></td>
                                                        </tr>

                                                        <tr>
                                                        <td>last_name</td>
                                                        <td>-</td>
                                                        <td><?php echo SCTEXT('Last Name of the person')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>company</td>
                                                        <td><?php echo SCTEXT('Company Name, additional info')?></td>
                                                        <td><?php echo SCTEXT('Url-encoded company name of the person, e.g. XYZ Pvt Ltd')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>job_title</td>
                                                        <td><?php echo SCTEXT('Job title, additional info')?></td>
                                                        <td><?php echo SCTEXT('Url-encoded job title of the person, e.g. Sr. Marketing Manager')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>telephone</td>
                                                        <td><?php echo SCTEXT('phone or mobile number')?></td>
                                                        <td><?php echo SCTEXT('provide telephonic contact information, <b>without</b> leading zeros or plus sign, <b>include</b> country code')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>email</td>
                                                        <td><?php echo SCTEXT('email contact')?></td>
                                                        <td><?php echo SCTEXT('Email ID of the person')?></td>
                                                        </tr>




                                                         <tr>
                                                        <td>time</td>
                                                        <td><?php echo SCTEXT('Schedule Time')?></td>
                                                        <td><?php echo SCTEXT('Enter the time in the format YYYY-MM-DD H:I e.g. enter 2013-03-19 14:30 for 19th March 2013, 2:30 pm. Leave BLANK to send the SMS instantly.')?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>


                                                <!----- RESPONSES -------->
													<h4 style="margin-top:15px;" class="page-header"><?php echo SCTEXT('Responses')?></h4>
                                                <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('API Response')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        	<td>
                                                            <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Message_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;err>{MESSAGE}&lt;/err><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the response.')?></td>
                                                        </tr>
                                                        <tr>
                                                        	<td>
                                                             <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Message_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;sms_shoot_id>{ID}&lt;/sms_shoot_id><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('This means SMS was submitted successfully, and it returns the shoot-id of the submission. You could use this ID to pull out delivery reports.')?></td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
													</div>



                                                <!----- URL & SAMPLE CODE ---->

                                                <div class="p-l-xs col-md-7 rescroll">

                                                	  <table class="table table-bordered">
                                                    <tbody>
                                                    	<tr>
                                                        <td><strong><?php echo SCTEXT('Sample XML & API Codes')?></strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"><?php echo SCTEXT('Request Format')?></span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">


                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Message><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key>{API Key}&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;campaign>0&lt;/campaign><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;routeid>{Route ID}&lt;/routeid><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;type>{SMS Type}&lt;/type><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;contacts><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br />
                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/contacts><br />
                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;senderid>{Sender ID}&lt;/senderid><br /><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;first_name>{First Name of the vCard Contact}&lt;/first_name><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;last_name>{Last Name of the vCard Contact}&lt;/last_name><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;company>{Company of the vCard Contact}&lt;/company><br />
                                                                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;job_title>{Job title of the vCard Contact}&lt;/job_title><br />
                                                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;telephone>{Phone number of the vCard Contact}&lt;/telephone><br />
                                                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;email>{Email ID of the vCard Contact}&lt;/email><br /><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;time>{Schedule time}&lt;/time><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message>


                                                            </code>
                                                            </div>
                                                        </td>
                                                        </tr>


                                                         <tr>
                                                         <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"> <?php echo SCTEXT('Sample Request Code')?> (PHP)</span>
                                                        <div class="well">
                                                            <code style="white-space:normal;border:none;">
                                                            &lt;?php

                                                           <p class="text-info">
                                                           $xml = '&lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Message><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key><span class="text-error"><?php echo $data['apikey'] ?></span>&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;campaign><span class="text-error">0</span>&lt;/campaign><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;routeid><span class="text-error">14</span>&lt;/routeid><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;type><span class="text-error">vcard</span>&lt;/type><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;contacts><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">98765XXXXX</span>&lt;/msisdn><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">84650XXXXX</span>&lt;/msisdn><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">90113XXXXX</span>&lt;/msisdn><br />
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">89041XXXXX</span>&lt;/msisdn><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">97800XXXXX</span>&lt;/msisdn><br />

                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/contacts><br />
                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;senderid><span class="text-error">DEMO</span>&lt;/senderid><br /><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;first_name><span class="text-error">Sam</span>&lt;/first_name><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;last_name><span class="text-error">Walker</span>&lt;/last_name><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;company><span class="text-error">XYZ Pvt Ltd, CA</span>&lt;/company><br />
                                                                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;job_title><span class="text-error">Senior Sales Executive</span>&lt;/job_title><br />
                                                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;telephone><span class="text-error">1-443-456-1675</span>&lt;/telephone><br />
                                                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;email><span class="text-error">sam.walker@xyz.com</span>&lt;/email><br /><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;time>&lt;/time><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message>';<br />

                                                           </p>
                                                            <p class="text-warning">//Submit to server</p>
                                                             <p class="text-info">
                                                             $ch = curl_init();<br />
                                                            curl_setopt($ch,CURLOPT_URL,  "<?php echo $data['baseurl'] ?>smsapi/index");<br />
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);<br />
curl_setopt($ch, CURLOPT_POST, 1);<br />
curl_setopt($ch, CURLOPT_POSTFIELDS, "xml=".<span class="text-error">$xml</span>);<br />
$response = curl_exec($ch);<br />
curl_close($ch);<br />
                                                             <br />
                                                             $xml_response = simplexml_load_string(<span class="text-error">$response</span>);<br />

echo $xml_response->sms_shoot_id?$xml_response->sms_shoot_id:$xml_response->err;
                                                             </p>
                                                            ?&gt;
                                                            </code>
                                                            </div>

                                                        </td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                                </div>





										</div>
									</div>



                                    <div class="tab-pane fade" id="tab5"><br /><br />

                            				<div class="clearfix">
												<div class="col-md-6">
                                                	 <div class="formSep"><span class="label label-info">API Url (GET and POST)</span> <span class="label label-success">UNICODE SMS API</span>
												<h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>smsapi/index</h4>

                                                </div>
                                                </div>

                                                <div style="text-align:right" class="col-md-6 bg-warning text-dark"><?php echo SCTEXT('Your API Key is')?>: <strong><?php echo $data['apikey'] ?> </strong>&nbsp;<button class="btn-primary btn"><?php echo SCTEXT('Regenerate Key')?></button>
								                </div>



                                            </div>
                                                <!-----PARAMETERS --->
                                                <div class="m-t-xs clearfix">
                                                <div class="col-md-5 rescroll">


    											 <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('Parameters')?></th>
                                                        <th><?php echo SCTEXT('Meaning')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        <td>key</td>
                                                        <td><?php echo SCTEXT('API Key')?></td>
                                                        <td><?php echo SCTEXT('Your API Key')?> (<?php echo $data['apikey'] ?>)</td>
                                                        </tr>

                                                        <tr>
                                                        <td>campaign</td>
                                                        <td> <?php echo SCTEXT('as defined in description')?></td>
                                                        <td>
                                                       <?php echo SCTEXT("Enter ID of campaign as shown below. You can use 0 (zero) for the default campaign.")?>
                                                       <?php foreach($data['camps'] as $cmp){ ?>
                                                               <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info"><?php echo $cmp->campaign_name ?></span>
                                                                    </div>

                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = '.$cmp->id ?></h5>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        </td>
                                                        </tr>

                                                        <tr>
                                                        <td>routeid</td>
                                                        <td><?php echo SCTEXT('ID of the route as defined in description')?></td>
                                                        <td>
                                                        	<?php foreach($_SESSION['credits']['routes'] as $rt){ ?>
                                                               <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info"><?php echo $rt['name'] ?></span>
                                                                    </div>

                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = '.$rt['id'] ?></h5>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                        </td>
                                                        </tr>

                                                        <tr>
                                                        <td>type</td>
                                                        <td><?php echo SCTEXT('SMS Type')?></td>
                                                        <td><?php echo SCTEXT('Leave this as')?> <strong>unicode</strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td>contacts</td>
                                                        <td><?php echo SCTEXT('Contact Numbers')?></td>
                                                        <td><?php echo SCTEXT("Contact numbers separated by ' , ' (comma) sign")?><br>e.g. 9887XXXXXX,9567XXXXXX</td>
                                                        </tr>

                                                         <tr>
                                                        <td>senderid</td>
                                                        <td><?php echo SCTEXT('Sender ID')?></td>
                                                        <td><?php echo SCTEXT('Any Approved Sender ID')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>msg</td>
                                                        <td><?php echo SCTEXT('SMS Text')?></td>
                                                        <td><?php echo SCTEXT('Url-encoded SMS text. Must be limited to 720 characters. The SMS text must be "UTF-8 encoded" before it is "Url encoded" and passed in the API. Refer to sample code.')?></td>
                                                        </tr>

                                                         <tr>
                                                        <td>time</td>
                                                        <td><?php echo SCTEXT('Schedule Time')?></td>
                                                        <td><?php echo SCTEXT('Enter the time in the format YYYY-MM-DD H:I e.g. enter 2013-03-19 14:30 for 19th March 2013, 2:30 pm. Leave BLANK to send the SMS instantly.')?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>


                                                <!----- RESPONSES -------->
													<h4 style="margin-top:15px;" class="page-header"><?php echo SCTEXT('Responses')?></h4>
                                                <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('API Response')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        	<td>
                                                            <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Message_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;err>{MESSAGE}&lt;/err><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the response.')?></td>
                                                        </tr>
                                                        <tr>
                                                        	<td>
                                                             <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Message_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;sms_shoot_id>{ID}&lt;/sms_shoot_id><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('This means SMS was submitted successfully, and it returns the shoot-id of the submission. You could use this ID to pull out delivery reports.')?></td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
													</div>



                                                <!----- URL & SAMPLE CODE ---->

                                                <div class="p-l-xs col-md-7 rescroll">

                                                	  <table class="table table-bordered">
                                                    <tbody>
                                                    	<tr>
                                                        <td><strong><?php echo SCTEXT('Sample XML & API Codes')?></strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"><?php echo SCTEXT('Request Format')?></span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">


                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Message><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key>{API Key}&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;campaign>0&lt;/campaign><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;routeid>{Route ID}&lt;/routeid><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;type>{SMS Type}&lt;/type><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;contacts><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile number}&lt;/msisdn><br />
                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..<br />
                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/contacts><br />
                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;senderid>{Sender ID}&lt;/senderid><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msg>{SMS Content}&lt;/msg><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;time>{Schedule time}&lt;/time><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message>


                                                            </code>
                                                            </div>
                                                        </td>
                                                        </tr>


                                                         <tr>
                                                         <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"> <?php echo SCTEXT('Sample Request Code')?> (PHP)</span>
                                                        <div class="well">
                                                            <code style="white-space:normal;border:none;">
                                                            &lt;?php

                                                           <p class="text-info">
                                                           $sms_text = 'Hindi  - हिंदी , Chinese -  痴呢色 ，Russian - руссиан';<br />
                                                           $encoded_text = utf8_encode($sms_text);<br />
                                                           $message = urlencode($encoded_text);<br /><br />
                                                            <span class="text-warning">/**<br />
Above SMS text looks like this when encoded:<br />
<br />
 $message='<?php echo urlencode(utf8_encode('Hindi  - हिंदी , Chinese -  痴呢色 ，Russian - руссиан')) ?>'; <br />
                                                            **/
</span>
                                                           <br /><br />
                                                           $xml = '&lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Message><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key><span class="text-error"><?php echo $data['apikey'] ?></span>&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;campaign><span class="text-error">0</span>&lt;/campaign><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;routeid><span class="text-error">14</span>&lt;/routeid><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;type><span class="text-error">unicode</span>&lt;/type><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;contacts><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">98765XXXXX</span>&lt;/msisdn><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">84650XXXXX</span>&lt;/msisdn><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">90113XXXXX</span>&lt;/msisdn><br />
                                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">89041XXXXX</span>&lt;/msisdn><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn><span class="text-error">97800XXXXX</span>&lt;/msisdn><br />

                                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/contacts><br />
                                                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;senderid><span class="text-error">DEMO</span>&lt;/senderid><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msg>'.<span class="text-error">$message</span>.'&lt;/msg><br />
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;time>&lt;/time><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Message>';<br />

                                                           </p>
                                                            <p class="text-warning">//Submit to server</p>
                                                             <p class="text-info">
                                                             $ch = curl_init();<br />
                                                            curl_setopt($ch,CURLOPT_URL,  "<?php echo $data['baseurl'] ?>smsapi/index");<br />
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);<br />
curl_setopt($ch, CURLOPT_POST, 1);<br />
curl_setopt($ch, CURLOPT_POSTFIELDS, "xml=".<span class="text-error">$xml</span>);<br />
$response = curl_exec($ch);<br />
curl_close($ch);<br />
                                                             <br />
                                                             $xml_response = simplexml_load_string(<span class="text-error">$response</span>);<br />

echo $xml_response->sms_shoot_id?$xml_response->sms_shoot_id:$xml_response->err;
                                                             </p>
                                                            ?&gt;
                                                            </code>
                                                            </div>

                                                        </td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                                </div>





										</div>
									</div>






									<div class="tab-pane fade" id="tab6"><br /><br />

                            				<div class="clearfix">
												<div class="col-md-7">
                                                	 <div class="formSep"><span class="label label-info">API Url (GET)</span> <span class="label label-success">BALANCE CHECK API</span>
												<h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>miscXmlApi/index</h4>

                                                </div>
                                                </div>


                                                <div style="text-align:right" class="col-md-5 pull-right bg-warning text-dark"><?php echo SCTEXT('Your API Key is')?>: <strong><?php echo $data['apikey'] ?> </strong>&nbsp;<button class="btn-primary btn"><?php echo SCTEXT('Regenerate Key')?></button>
								                </div>



                                            </div>
                                                <!-----PARAMETERS --->
                                                <div class="m-t-xs clearfix">
                                                <div class="col-md-5 rescroll">


    											  <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('Parameters')?></th>
                                                        <th><?php echo SCTEXT('Meaning')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        <td><?php echo SCTEXT('API Key')?></td>
                                                        <td><?php echo SCTEXT('Key provided in description')?></td>
                                                        <td><?php echo SCTEXT('Your API Key')?> (<?php echo $data['apikey'] ?>)</td>
                                                        </tr>
                                                        <tr>
                                                        <td>mode</td>
                                                        <td>Request mode</td>
                                                        <td><?php echo SCTEXT('It must be')?> <strong>getBalance</strong></td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                                <!----- RESPONSES -------->
													<h4 style="margin-top:15px;" class="page-header"><?php echo SCTEXT('Responses')?></h4>
                                                <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('API Response')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        	<td>
                                                            <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Api_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;err>{MESSAGE}&lt;/err><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Api_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the response.')?></td>
                                                        </tr>
                                                        <tr>
                                                        	<td>
                                                             <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Api_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;credits><br>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;route_id>23&lt;/route_id><br>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;route>Name of route&lt;/route><br>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;balance>5000&lt;/balance><br>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/credits><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Api_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('This means request was submitted successfully, and it returned all the routes assigned with the ID, name of the route with available SMS credit balance.')?></td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
													</div>



                                                <!----- URL & SAMPLE CODE ---->

                                                <div class="p-l-xs col-md-7 rescroll">

                                                	   <table class="table table-bordered">
                                                    <tbody>
                                                    	<tr>
                                                        <td><strong><?php echo SCTEXT('Sample XML & API Codes')?></strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"><?php echo SCTEXT('Request Format')?></span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">


                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Api_req><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key>{API Key}&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;mode>getBalance&lt;/mode><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Api_req>


                                                            </code>
                                                            </div>
                                                        </td>
                                                        </tr>


                                                         <tr>
                                                         <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"> <?php echo SCTEXT('Sample Request Code')?> (PHP)</span>
                                                        <div class="well">
                                                            <code style="white-space:normal;border:none;">
                                                            &lt;?php

                                                           <p class="text-info">

                                                           $xml = '&lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Api_req><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key><span class="text-error"><?php echo $data['apikey'] ?></span>&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;mode><span class="text-error">getBalance</span>&lt;/mode><br />

                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Api_req>';<br />

                                                           </p>
                                                            <p class="text-warning">//Submit to server</p>
                                                             <p class="text-info">
                                                             $ch = curl_init();<br />
                                                            curl_setopt($ch,CURLOPT_URL,  "<?php echo $data['baseurl'] ?>miscXmlApi/index");<br />
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);<br />
curl_setopt($ch, CURLOPT_POST, 1);<br />
curl_setopt($ch, CURLOPT_POSTFIELDS, "xml=".<span class="text-error">$xml</span>);<br />
$response = curl_exec($ch);<br />
curl_close($ch);<br />
                                                             <br />
                                                             $xml_response = simplexml_load_string(<span class="text-error">$response</span>);<br />

echo $xml_response->err?$xml_response->err:$xml_response->currency.$xml_response->balance;
                                                             </p>
                                                            ?&gt;
                                                            </code>
                                                            </div>

                                                        </td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                                </div>





										</div>



									</div>






									<div class="tab-pane fade" id="tab7"><br /><br />

                            				<div class="clearfix">
												<div class="col-md-7">
                                                	 <div class="formSep"><span class="label label-info">API Url (GET)</span> <span class="label label-success"> DLR API</span>
												<h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>miscXmlApi/index</h4>

                                                    </div>
                                                </div>




                                                <div style="text-align:right" class="col-md-5 pull-right bg-warning text-dark"><?php echo SCTEXT('Your API Key is')?>: <strong><?php echo $data['apikey'] ?> </strong>&nbsp;<button class="btn-primary btn"><?php echo SCTEXT('Regenerate Key')?></button>
								                </div>



                                            </div>
                                                <!-----PARAMETERS --->
                                                <div class="m-t-xs clearfix">
                                                <div class="col-md-5 rescroll">


    											  <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('Parameters')?></th>
                                                        <th><?php echo SCTEXT('Meaning')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        <td><?php echo SCTEXT('API Key')?></td>
                                                        <td><?php echo SCTEXT('Key provided in description')?></td>
                                                        <td><?php echo SCTEXT('Your API Key')?> (<?php echo $data['apikey'] ?>)</td>
                                                        </tr>
                                                        <tr>
                                                        <td>mode</td>
                                                        <td>Request mode</td>
                                                        <td><?php echo SCTEXT('It must be')?> <strong>getDLR</strong></td>
                                                        </tr>
                                                        <tr>
                                                        <td>sms_shoot_id</td>
                                                        <td><?php echo SCTEXT('ID of a group/single SMS submission')?></td>
                                                        <td><?php echo SCTEXT('Alpha-numeric string returned when submitted SMS via HTTP/XML API')?></td>
                                                        </tr>

                                                    </tbody>
                                                </table>


                                                <!----- RESPONSES -------->
													<h4 style="margin-top:15px;" class="page-header"><?php echo SCTEXT('Responses')?></h4>
                                                <table class=" table table-striped table-bordered ">
                                                	<thead>
                                                    	<tr>
                                                        <th><?php echo SCTEXT('API Response')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    	<tr>
                                                        	<td>
                                                            <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&nbsp;&lt;Api_resp><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;err>{MESSAGE}&lt;/err><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Api_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the response.')?></td>
                                                        </tr>
                                                        <tr>
                                                        	<td>
                                                             <div class="well">
                                                            <code style="border:none;">

                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            		&nbsp;&nbsp;&nbsp;&lt;Api_resp><br /><br />
                                                                    	&nbsp;&nbsp;&nbsp;&nbsp;&lt;report><br />
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>{mobile}&lt;/msisdn><br />
                                                                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;dlr>{dlr status}&lt;/dlr><br />
                                                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;desc>{description}&lt;/desc><br />
                                                                          &nbsp;&nbsp;&nbsp;&nbsp;&lt;/report><br />
                                                                       &nbsp;&nbsp;&nbsp;&nbsp;&lt;report><br />
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;msisdn>98657XXXXX&lt;/msisdn><br />
                                                                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;dlr>Failed&lt;/dlr><br />
                                                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;desc>Absent Subscriber&lt;/desc><br />
                                                                          &nbsp;&nbsp;&nbsp;&nbsp;&lt;/report><br /><br />

                                                                  &nbsp;&nbsp;&nbsp;&lt;/Api_resp>

                                                               </code>
                                                            </div>
                                                            </td>
                                                            <td><?php echo SCTEXT('This means request was submitted successfully, and it returned a list of contact numbers with corresponding DLR status.')?></td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
													</div>



                                                <!----- URL & SAMPLE CODE ---->

                                                <div class="p-l-xs col-md-7 rescroll">

                                                	  <table class="table table-bordered">
                                                    <tbody>
                                                    	<tr>
                                                        <td><strong><?php echo SCTEXT('Sample XML & API Codes')?></strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"><?php echo SCTEXT('Request Format')?></span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">


                                                            &lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Api_req><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key>{API Key}&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;mode>getDLR&lt;/mode><br />
                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;sms_shoot_id>{Shoot ID}&lt;/sms_shoot_id><br />
                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Api_req>


                                                            </code>
                                                            </div>
                                                        </td>
                                                        </tr>


                                                         <tr>
                                                         <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"> <?php echo SCTEXT('Sample Request Code')?> (PHP)</span>
                                                        <div class="well">
                                                            <code style="white-space:normal;border:none;">
                                                            &lt;?php

                                                           <p class="text-info">

                                                           $xml = '&lt;?xml version="1.0" encoding="UTF-8"?><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;Api_req><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;key><span class="text-error"><?php echo $data['apikey'] ?></span>&lt;/key><br />
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;mode><span class="text-error">getDLR</span>&lt;/mode><br />
                                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;sms_shoot_id><span class="text-error">nick51fa4816d8043</span>&lt;/sms_shoot_id><br />

                                                                   &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Api_req>';<br />

                                                           </p>
                                                            <p class="text-warning">//Submit to server</p>
                                                             <p class="text-info">
                                                             $ch = curl_init();<br />
                                                            curl_setopt($ch,CURLOPT_URL,  "<?php echo $data['baseurl'] ?>miscXmlApi/index");<br />
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);<br />
curl_setopt($ch, CURLOPT_POST, 1);<br />
curl_setopt($ch, CURLOPT_POSTFIELDS, "xml=".<span class="text-error">$xml</span>);<br />
$response = curl_exec($ch);<br />
curl_close($ch);<br />
                                                             <br />
                                                             $xml_response = simplexml_load_string(<span class="text-error">$response</span>);<br />

                                                             </p>
                                                            ?&gt;
                                                            </code>
                                                            </div>

                                                        </td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                                </div>





										</div>


									</div>



								</div>
                                <?php } ?>
							</div>

                                    <!-- end content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
