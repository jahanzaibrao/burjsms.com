<main id="app-main" class="app-main">
        <?php include('breadcrums.php') ?>
        <div class="wrap">
            <section class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget p-lg">
                            <div class="row no-gutter">
                                <h3 class="page-title-sc">WhatsApp API<small><?php echo SCTEXT('RESTFUL APIs for sending WhatsApp campaigns')?></small></h3>
                                <hr>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                    <!-- start content -->

								<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab1" data-toggle="tab">Send Campaign</a></li>
									<li><a href="#tab2" data-toggle="tab">Fetch Reports</a></li>
								</ul>

								<div id="apitabctr" class="tab-content p-v-lg">


									<div class="tab-pane active fade in" id="tab1"><br /><br />

                            				<div class="clearfix">
												<div class="col-md-6">
                                                	 <div class="formSep"><span class="label label-info">API Url (GET and POST)</span> <span class="label label-success">Send WhatsApp</span>
												<h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>whatsapp/index</h4>

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
                                                        <td>wba_id</td>
                                                        <td> <?php echo SCTEXT('as defined in description')?></td>
                                                        <td>
                                                       <?php echo SCTEXT("Mandatory. The mobile number linked with the WhatsApp Business Account")?>
                                                        </td>
                                                        </tr>

                                                        <tr>
                                                        <td>name</td>
                                                        <td> <?php echo SCTEXT('name for the campaign')?></td>
                                                        <td>
                                                       <?php echo SCTEXT("Mandatory. Enter a name for your campaign. URL-encode the value if there are spaces or special characters in the campaign name")?>
                                                        </td>
                                                        </tr>

                                                        <tr>
                                                        <td>contacts</td>
                                                        <td><?php echo SCTEXT('phone numbers to send this campaign')?></td>
                                                        <td><?php echo SCTEXT("mobile numbers with country code, comma separated")?><br>e.g. 919887XXXXXX,657443xxxxxx<br><br>
                                                        Use JSON format as shown below for personalized campaigns. Your supplied template must have variables in it to support this and number of variables must match the variables in the JSON body below:<br><br>

                                                        <code style="white-space:normal;border:none;width:180px;">
                                                                [ {<br>
                                                                    "contact": "919887xxxxxx",<br>
                                                                    "name": "Sam"<br>
                                                                    "amount": "445"<br>
                                                                    "duedate": "04-02-2025"<br>
                                                                },<br>
                                                                {<br>
                                                                    "contact": "657889xxxxxx",<br>
                                                                    "name": "Nick"<br>
                                                                    "amount": "900"<br>
                                                                    "duedate": "05-12-2024"<br>
                                                                } ]<br>
                                                                </code>
                                                    
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                        <td>template_id</td>
                                                        <td> <?php echo SCTEXT('ID of the template saved in your portal')?></td>
                                                        <td>
                                                       <?php echo SCTEXT("Mandatory. Enter ID from below table to supply the template.")?>
                                                       <br><br>
                                                       <div class="clearfix">
                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                        <span class="label label-info">Loop Foods</span>
                                                                    </div>

                                                                    <div class="p-r-0 text-left col-md-6 col-sm-6 col-xs-6">
                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = 12' ?></h5>
                                                                    </div>
                                                                </div>
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                        <td>dlr_url</td>
                                                        <td> <?php echo SCTEXT('External URL where the reports will be posted when available')?></td>
                                                        <td>
                                                       <?php echo SCTEXT("Optional. This parameter must be URL encoded if supplied. The data will be posted in JSON format using POST method")?>
                                                        </td>
                                                        </tr>

                                                    </tbody>
                                                </table>



                                                <!----- RESPONSES -------->
													<h4 style="margin-top:15px;" class="page-header"><?php echo SCTEXT('Responses')?></h4>
                                                <table class=" table table-striped table-bordered">
                                                	<thead>
                                                    	<tr>
                                                        <th style="width:180px"><?php echo SCTEXT('API Response')?></th>
                                                        <th><?php echo SCTEXT('Description')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                        	<td>
                                                                <code style="white-space:normal;border:none;width:180px;">
                                                                {<br>
                                                                    "result": "success",<br>
                                                                    "reference": "KSJ734NDJX88E9MDI"<br>
                                                                }
                                                                </code>
                                                            </td>
                                                            <td><?php echo SCTEXT('API call was successful and a reference will be provided. Use this to fetch delivery reports later')?></td>
                                                        </tr>
                                                    	<tr>
                                                        	<td>
                                                                <code style="white-space:normal;border:none;">
                                                                {<br>
                                                                    "result": "error",<br>
                                                                    "message": "INCORRECT WBA ID"<br>
                                                                }
                                                                </code>
                                                            </td>
                                                            <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the braces {}, for instance, "ERR: INCORRECT WBA ID" means WhatsApp business number was not correct. The error messages are not ciphered and pretty much intuitive.')?></td>
                                                        </tr>

                                                    </tbody>
                                                    </table>


												</div>



                                                <!----- URL & SAMPLE CODE ---->

                                                <div class="p-l-xs col-md-7 rescroll">

                                                	  <table class="table table-bordered">
                                                    <tbody>
                                                    	<tr>
                                                        <td><strong><?php echo SCTEXT('Sample URL & Sample API Codes')?></strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"><?php echo SCTEXT('Simple Call')?></span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">

                                                            $api_url = "<?php echo $data['baseurl'] ?>whatsapp/index?key=<?php echo $data['apikey'] ?>&amp;wba_id=919008488382&amp;mobile=9198871XXXXX&amp;name=Order%20Confirmation&amp;template_id=12";

                                                            </code>
                                                            </div>
                                                        </td>
                                                        </tr>

                                                         <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"> CURL Example <?php echo SCTEXT('Sample Code')?> (PHP)</span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">
                                                            &lt;?php

                                                           <p class="text-info">
                                                           $api_key = '<?php echo $data['apikey'] ?>';<br />
                                                           $mobile = '9198871XXXXX';<br />
                                                           $wba_id = '919008858333';<br />
                                                           $name = 'Order Confirmation';<br />
                                                           $template_id = 12;<br />
                                                           </p>
                                                            <p class="text-warning">//Submit to server</p>
                                                             <p class="text-info">
                                                             $ch = curl_init();<br />
                                                            curl_setopt($ch,CURLOPT_URL,  "<?php echo $data['baseurl'] ?>whatsapp/index");<br />
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);<br />
                                                            curl_setopt($ch, CURLOPT_POST, 1);<br />
                                                            curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".<span class="text-error">$api_key</span>."&amp;channel_id=".<span class="text-error">$channel_id</span>."mobile=".<span class="text-error">$mobile</span>);<br />
                                                            $response = curl_exec($ch);<br />
                                                            curl_close($ch);<br />
                                                             echo $response;
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
                                                	 <div class="formSep"><span class="label label-info">API Url (GET and POST)</span> <span class="label label-success">Fetch WhatsApp Reports API</span>
												<h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>whatsapp-reports/index</h4>

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
                                                        <td>reference</td>
                                                        <td> <?php echo SCTEXT('as defined in description')?></td>
                                                        <td>
                                                       <?php echo SCTEXT("Mandatory. Supply the reference received in the response when WhatsApp campaign was sent.")?>
                                                        </td>
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
                                                        	<td>ERR: {&lt;MESSAGE&gt;}</td>
                                                            <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the braces {}, for instance, "ERR: INVALID API KEY" means the API key entered is expired or does not belong to any user. The error messages are not ciphered and pretty much intuitive.')?></td>
                                                        </tr>
                                                        <tr>
                                                        	<td>[{<br />
                                                            "contact":"< mobile no >",<br />
                                                            "status":"< dlr status >",<br />
                                                            "last_seen":"< timestamp >"<br />
                                                            },<br />
                                                             {
                                                            "MSISDN":"98876XXXXX",<br />
                                                            "DLR":"Failed",<br />
                                                            "DESC":"Absent Subscriber"<br />
                                                            },.......
                                                            ]
                                                            </td>
                                                            <td><?php echo SCTEXT('JSON encoded array of DLR status. Three parameters would be returned, MSISDN refers to destination phone number, DLR gives generic delivery status and DESC provides the description of the DLR.')?></td>
                                                        </tr>
                                                    </tbody>
                                                    </table>


												</div>



                                                <!----- URL & SAMPLE CODE ---->

                                                <div class="p-l-xs col-md-7 rescroll">

                                                	  <table class="table table-bordered">
                                                    <tbody>
                                                    	<tr>
                                                        <td><strong><?php echo SCTEXT('Sample URL & Sample API Codes')?></strong></td>
                                                        </tr>

                                                        <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"><?php echo SCTEXT('Simple Call')?></span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">

                                                            $api_url = "<?php echo $data['baseurl'] ?>whatsapp-report/index?key=<?php echo $data['apikey'] ?>&amp;reference=DJJD730ND930C993";

                                                            </code>
                                                            </div>
                                                        </td>
                                                        </tr>

                                                         <tr>
                                                        <td style="position:relative;">
                                                        <span class="label label-info" style="position:absolute"> CURL Example <?php echo SCTEXT('Sample Code')?> (PHP)</span>
                                                            <div class="well">
                                                            <code style="white-space:normal;border:none;">
                                                            &lt;?php

                                                           <p class="text-info">
                                                           $api_key = '<?php echo $data['apikey'] ?>';<br />
                                                           $reference = 'DJJD730ND930C993';<br />
                                                           </p>
                                                            <p class="text-warning">//Submit to server</p>
                                                             <p class="text-info">
                                                             $ch = curl_init();<br />
                                                            curl_setopt($ch,CURLOPT_URL,  "<?php echo $data['baseurl'] ?>whatsapp-report/index");<br />
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);<br />
                                                            curl_setopt($ch, CURLOPT_POST, 1);<br />
                                                            curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".<span class="text-error">$api_key</span>."&amp;reference=".<span class="text-error">$reference</span>);<br />
                                                            $response = curl_exec($ch);<br />
                                                            curl_close($ch);<br />
                                                             echo $response;
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
							</div>

                                    <!-- end content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
