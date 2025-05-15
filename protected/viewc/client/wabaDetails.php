<main id="app-main" class="app-main">
  <?php include('breadcrums.php') ?>
  <div class="wrap">
    <section class="app-content">
      <div class="row">
        <div class="col-md-12">
          <div class="widget p-lg">
            <div class="row no-gutter">
              <h3 class="page-title-sc"><?php echo SCTEXT('View WhatsApp Account Details') ?><small><?php echo SCTEXT('view the conversations of this WABA') ?></small></h3>
              <hr>
              <?php include('notification.php') ?>
              <div class="col-md-12">
                <!-- start content -->
                <div class="col-md-4">
                  <div class="panel panel-theme1">
                    <div class="panel-heading bg-theme1" style="min-height:77px;">
                      <select id="waba_phnsel" name="waba_phnsel" class="form-control" data-plugin="select2" data-options="{templateResult: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: middle;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[1]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);}, templateSelection: function (data){var myarr = data.text.split('|'); var nstr = '<div class=\'media-left\'><div class=\'avatar avatar-xs avatar-circle\' style=\'margin-top: 3px;\'><a href=\'javascript:void(0);\'><img src=\''+myarr[0]+'\'></a></div></div><div class=\'media-body\' style=\'vertical-align: top;\'><div class=\' pull-left\'>'+data.title+'</div><div class=\'pull-right\'><span class=\'label label-flat label-md label-'+myarr[2]+'\'>'+myarr[1]+'</span></div><div class=\'clearfix\'></div></div>'; return $(nstr);} }">
                        <?php foreach ($data['wabadata']['waba_phone_data'] as $key => $wabaphndata) { ?>
                          <option value="<?php echo $wabaphndata['id'] ?>" title="<?php echo $wabaphndata['verified_name'] ?>"><?php echo (isset($data['wabadata']['waba_business_profiles'][$key][0]['profile_picture_url']) ? $data['wabadata']['waba_business_profiles'][$key][0]['profile_picture_url'] : 'https://placehold.co/200') . '|' . $wabaphndata['display_phone_number'] . '|primary|' . $data['wabadata']['waba_business_profiles'][$key][0]['email'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="panel-body p-v-0">
                      <?php if (is_countable($data['contacts']) && sizeof($data['contacts']) > 0) { ?>
                        <?php foreach ($data['contacts'] as $contact) { ?>
                          <div class="media-group-item p-l-0 m-b-xs contact-list-item" data-cid="<?php echo $contact->id ?>" data-cname="<?php echo $contact->name ?>" data-cnum="<?php echo $contact->contact ?>" style="cursor:pointer;">
                            <div class="media">
                              <div class="media-left">
                                <div class="avatar avatar-lg avatar-circle"><a href="javascript:void(0);"><img src="<?php echo Doo::conf()->APP_URL ?>global/waba/contacts.png" alt=""></a></div>
                              </div>
                              <div class="media-body p-t-xs">
                                <h5 class="m-t-0 m-b-0"><a href="javascript:void(0);" class="m-r-xs text-inverse"><?php echo trim($contact->name) != "" && trim($contact->name) != "." ? $contact->name : $contact->contact ?></a></h5>
                                <p class="m-b-0" style="display: inline-block;width: 250px;white-space: nowrap;overflow: hidden !important;text-overflow: ellipsis;font-size: 14px;"><small>
                                    <?php echo $data['last_msgs'][$contact->id]['msg'] ?>

                                  </small></p>
                                <p style="float:right;">
                                  <?php if ($data['last_msgs'][$contact->id]['dir'] == 0) {
                                    if ($data['last_msgs'][$contact->id]['status'] == 1) { ?>
                                      <i class="fa fa-check-circle" title="Sent"></i>
                                    <?php } else if ($data['last_msgs'][$contact->id]['status'] == 2) { ?>
                                      <i class="fa fa-check-circle text-primary" title="Delivered"></i>
                                    <?php } else if ($data['last_msgs'][$contact->id]['status'] == 2) { ?>
                                      <i class="fa fa-check-circle text-success" title="Read"></i>
                                    <?php }
                                  } else {
                                    if ($data['last_msgs'][$contact->id]['status'] == 0) { ?>

                                      <i class="fa fa-circle text-danger"></i>
                                  <?php }
                                  } ?>

                                </p>
                                <div class="text-right">
                                  <small>
                                    <?php echo date('D, d/m/y', strtotime($data['last_msgs'][$contact->id]['last_update'])) ?>
                                  </small>
                                </div>


                              </div>
                            </div>

                          </div>
                        <?php } ?>
                      <?php } else { ?>
                        <hr class="m-b-lg">
                        <div class="text-center">No Contacts Available</div>
                        <hr class="m-t-lg">
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div id="waba_chatbox" style="position:relative;" class="col-md-8 pull-right p-v-xs">
                  <div id="loading-overlay" class="hidden">
                    <div class="overlay-content">
                      <div class="loading-spinner"></div>
                      <div class="message">Loading Chats...</div>
                    </div>
                  </div>
                  <?php if (1 == 1) { ?>
                    <div style="height: 500px; background-color:#f8f8f8; text-align:center; padding-top:80px;">
                      <div style="margin: 0 auto;">
                        <img src="<?php echo Doo::conf()->APP_URL ?>global/waba/ww.png">
                      </div>
                      <h5>Start sending Campaigns and click on the contact from the list to view conversations</h5>
                    </div>
                  <?php } else { ?>

                  <?php } ?>


                  <!-- end content -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="hidden" id="emojibox">
          <button type="button" class="emoji-btn" data-emoji="😀">😀</button>
          <button type="button" class="emoji-btn" data-emoji="😃">😃</button>
          <button type="button" class="emoji-btn" data-emoji="😄">😄</button>
          <button type="button" class="emoji-btn" data-emoji="😁">😁</button>
          <button type="button" class="emoji-btn" data-emoji="😆">😆</button>
          <button type="button" class="emoji-btn" data-emoji="😅">😅</button>
          <button type="button" class="emoji-btn" data-emoji="😂">😂</button>
          <button type="button" class="emoji-btn" data-emoji="🤣">🤣</button>
          <button type="button" class="emoji-btn" data-emoji="😊">😊</button>
          <button type="button" class="emoji-btn" data-emoji="😇">😇</button>
          <button type="button" class="emoji-btn" data-emoji="🙂">🙂</button>
          <button type="button" class="emoji-btn" data-emoji="🙃">🙃</button>
          <button type="button" class="emoji-btn" data-emoji="😉">😉</button>
          <button type="button" class="emoji-btn" data-emoji="😌">😌</button>
          <button type="button" class="emoji-btn" data-emoji="😍">😍</button>
          <button type="button" class="emoji-btn" data-emoji="🥰">🥰</button>
          <button type="button" class="emoji-btn" data-emoji="😘">😘</button>
          <button type="button" class="emoji-btn" data-emoji="😗">😗</button>
          <button type="button" class="emoji-btn" data-emoji="😙">😙</button>
          <button type="button" class="emoji-btn" data-emoji="😚">😚</button>
          <button type="button" class="emoji-btn" data-emoji="😋">😋</button>
          <button type="button" class="emoji-btn" data-emoji="😛">😛</button>
          <button type="button" class="emoji-btn" data-emoji="😜">😜</button>
          <button type="button" class="emoji-btn" data-emoji="😝">😝</button>
          <button type="button" class="emoji-btn" data-emoji="🤑">🤑</button>
          <button type="button" class="emoji-btn" data-emoji="🤗">🤗</button>
          <button type="button" class="emoji-btn" data-emoji="🤭">🤭</button>
          <button type="button" class="emoji-btn" data-emoji="🤫">🤫</button>
          <button type="button" class="emoji-btn" data-emoji="🤔">🤔</button>
          <button type="button" class="emoji-btn" data-emoji="🤐">🤐</button>
          <button type="button" class="emoji-btn" data-emoji="🤨">🤨</button>
          <button type="button" class="emoji-btn" data-emoji="😐">😐</button>
          <button type="button" class="emoji-btn" data-emoji="😑">😑</button>
          <button type="button" class="emoji-btn" data-emoji="😶">😶</button>
          <button type="button" class="emoji-btn" data-emoji="😏">😏</button>
          <button type="button" class="emoji-btn" data-emoji="😒">😒</button>
          <button type="button" class="emoji-btn" data-emoji="🙄">🙄</button>
          <button type="button" class="emoji-btn" data-emoji="😬">😬</button>
          <button type="button" class="emoji-btn" data-emoji="🤥">🤥</button>
          <button type="button" class="emoji-btn" data-emoji="😌">😌</button>
          <button type="button" class="emoji-btn" data-emoji="😔">😔</button>
          <button type="button" class="emoji-btn" data-emoji="😪">😪</button>
          <button type="button" class="emoji-btn" data-emoji="🤤">🤤</button>
          <button type="button" class="emoji-btn" data-emoji="😴">😴</button>
          <button type="button" class="emoji-btn" data-emoji="😷">😷</button>
          <button type="button" class="emoji-btn" data-emoji="🤒">🤒</button>
          <button type="button" class="emoji-btn" data-emoji="🤕">🤕</button>
          <button type="button" class="emoji-btn" data-emoji="🤢">🤢</button>
          <button type="button" class="emoji-btn" data-emoji="🤮">🤮</button>
          <button type="button" class="emoji-btn" data-emoji="🤧">🤧</button>
          <button type="button" class="emoji-btn" data-emoji="🥵">🥵</button>
          <button type="button" class="emoji-btn" data-emoji="🥶">🥶</button>
          <button type="button" class="emoji-btn" data-emoji="🥴">🥴</button>
          <button type="button" class="emoji-btn" data-emoji="😵">😵</button>
          <button type="button" class="emoji-btn" data-emoji="🤯">🤯</button>
          <button type="button" class="emoji-btn" data-emoji="🤠">🤠</button>
          <button type="button" class="emoji-btn" data-emoji="🥳">🥳</button>
          <button type="button" class="emoji-btn" data-emoji="😎">😎</button>
          <button type="button" class="emoji-btn" data-emoji="🤓">🤓</button>
          <button type="button" class="emoji-btn" data-emoji="🧐">🧐</button>
          <button type="button" class="emoji-btn" data-emoji="😕">😕</button>
          <button type="button" class="emoji-btn" data-emoji="😟">😟</button>
          <button type="button" class="emoji-btn" data-emoji="🙁">🙁</button>
          <button type="button" class="emoji-btn" data-emoji="☹️">☹️</button>
          <button type="button" class="emoji-btn" data-emoji="😮">😮</button>
          <button type="button" class="emoji-btn" data-emoji="😯">😯</button>
          <button type="button" class="emoji-btn" data-emoji="😲">😲</button>
          <button type="button" class="emoji-btn" data-emoji="😳">😳</button>
          <button type="button" class="emoji-btn" data-emoji="🥺">🥺</button>
          <button type="button" class="emoji-btn" data-emoji="😦">😦</button>
          <button type="button" class="emoji-btn" data-emoji="😧">😧</button>
          <button type="button" class="emoji-btn" data-emoji="😨">😨</button>
          <button type="button" class="emoji-btn" data-emoji="😰">😰</button>
          <button type="button" class="emoji-btn" data-emoji="😥">😥</button>
          <button type="button" class="emoji-btn" data-emoji="😢">😢</button>
          <button type="button" class="emoji-btn" data-emoji="😭">😭</button>
          <button type="button" class="emoji-btn" data-emoji="😱">😱</button>
          <button type="button" class="emoji-btn" data-emoji="😖">😖</button>
          <button type="button" class="emoji-btn" data-emoji="😣">😣</button>
          <button type="button" class="emoji-btn" data-emoji="😞">😞</button>
          <button type="button" class="emoji-btn" data-emoji="😓">😓</button>
          <button type="button" class="emoji-btn" data-emoji="😩">😩</button>
          <button type="button" class="emoji-btn" data-emoji="😫">😫</button>
          <button type="button" class="emoji-btn" data-emoji="🥱">🥱</button>
          <button type="button" class="emoji-btn" data-emoji="😤">😤</button>
          <button type="button" class="emoji-btn" data-emoji="😡">😡</button>
          <button type="button" class="emoji-btn" data-emoji="😠">😠</button>
          <button type="button" class="emoji-btn" data-emoji="🤬">🤬</button>
          <button type="button" class="emoji-btn" data-emoji="😈">😈</button>
          <button type="button" class="emoji-btn" data-emoji="👿">👿</button>
          <button type="button" class="emoji-btn" data-emoji="💀">💀</button>
          <button type="button" class="emoji-btn" data-emoji="☠️">☠️</button>
          <button type="button" class="emoji-btn" data-emoji="💩">💩</button>
          <button type="button" class="emoji-btn" data-emoji="🤡">🤡</button>
          <button type="button" class="emoji-btn" data-emoji="👹">👹</button>
          <button type="button" class="emoji-btn" data-emoji="👺">👺</button>
          <button type="button" class="emoji-btn" data-emoji="👻">👻</button>
          <button type="button" class="emoji-btn" data-emoji="👽">👽</button>
          <button type="button" class="emoji-btn" data-emoji="👾">👾</button>
          <button type="button" class="emoji-btn" data-emoji="🤖">🤖</button>
          <button type="button" class="emoji-btn" data-emoji="😺">😺</button>
          <button type="button" class="emoji-btn" data-emoji="😸">😸</button>
          <button type="button" class="emoji-btn" data-emoji="😹">😹</button>
          <button type="button" class="emoji-btn" data-emoji="😻">😻</button>
          <button type="button" class="emoji-btn" data-emoji="😼">😼</button>
          <button type="button" class="emoji-btn" data-emoji="😽">😽</button>
          <button type="button" class="emoji-btn" data-emoji="🙀">🙀</button>
          <button type="button" class="emoji-btn" data-emoji="😿">😿</button>
          <button type="button" class="emoji-btn" data-emoji="😾">😾</button>
          <button type="button" class="emoji-btn" data-emoji="👐">👐</button>
          <button type="button" class="emoji-btn" data-emoji="🙌">🙌</button>
          <button type="button" class="emoji-btn" data-emoji="👏">👏</button>
          <button type="button" class="emoji-btn" data-emoji="🤝">🤝</button>
          <button type="button" class="emoji-btn" data-emoji="👍">👍</button>
          <button type="button" class="emoji-btn" data-emoji="👎">👎</button>
          <button type="button" class="emoji-btn" data-emoji="👊">👊</button>
          <button type="button" class="emoji-btn" data-emoji="✊">✊</button>
          <button type="button" class="emoji-btn" data-emoji="🤛">🤛</button>
          <button type="button" class="emoji-btn" data-emoji="🤜">🤜</button>
          <button type="button" class="emoji-btn" data-emoji="🤞">🤞</button>
          <button type="button" class="emoji-btn" data-emoji="✌️">✌️</button>
          <button type="button" class="emoji-btn" data-emoji="🤟">🤟</button>
          <button type="button" class="emoji-btn" data-emoji="🤘">🤘</button>
          <button type="button" class="emoji-btn" data-emoji="👌">👌</button>
          <button type="button" class="emoji-btn" data-emoji="👈">👈</button>
          <button type="button" class="emoji-btn" data-emoji="👉">👉</button>
          <button type="button" class="emoji-btn" data-emoji="👆">👆</button>
          <button type="button" class="emoji-btn" data-emoji="👇">👇</button>
          <button type="button" class="emoji-btn" data-emoji="☝️">☝️</button>
          <button type="button" class="emoji-btn" data-emoji="✋">✋</button>
          <button type="button" class="emoji-btn" data-emoji="🤚">🤚</button>
          <button type="button" class="emoji-btn" data-emoji="🖐️">🖐️</button>
          <button type="button" class="emoji-btn" data-emoji="🖖">🖖</button>
          <button type="button" class="emoji-btn" data-emoji="👋">👋</button>
          <button type="button" class="emoji-btn" data-emoji="🤙">🤙</button>
          <button type="button" class="emoji-btn" data-emoji="💪">💪</button>
          <button type="button" class="emoji-btn" data-emoji="🦾">🦾</button>
          <button type="button" class="emoji-btn" data-emoji="🖕">🖕</button>
          <button type="button" class="emoji-btn" data-emoji="✍️">✍️</button>
          <button type="button" class="emoji-btn" data-emoji="🤳">🤳</button>
          <button type="button" class="emoji-btn" data-emoji="💅">💅</button>
          <button type="button" class="emoji-btn" data-emoji="💍">💍</button>
          <button type="button" class="emoji-btn" data-emoji="💄">💄</button>
          <button type="button" class="emoji-btn" data-emoji="💋">💋</button>
          <button type="button" class="emoji-btn" data-emoji="👄">👄</button>
          <button type="button" class="emoji-btn" data-emoji="🦷">🦷</button>
          <button type="button" class="emoji-btn" data-emoji="👅">👅</button>
          <button type="button" class="emoji-btn" data-emoji="👂">👂</button>
          <button type="button" class="emoji-btn" data-emoji="🦻">🦻</button>
          <button type="button" class="emoji-btn" data-emoji="👃">👃</button>
          <button type="button" class="emoji-btn" data-emoji="🧠">🧠</button>
          <button type="button" class="emoji-btn" data-emoji="🫀">🫀</button>
          <button type="button" class="emoji-btn" data-emoji="🫁">🫁</button>
          <button type="button" class="emoji-btn" data-emoji="👣">👣</button>
          <button type="button" class="emoji-btn" data-emoji="🦶">🦶</button>
          <button type="button" class="emoji-btn" data-emoji="🦵">🦵</button>
          <button type="button" class="emoji-btn" data-emoji="🦿">🦿</button>
          <button type="button" class="emoji-btn" data-emoji="🦾">🦾</button>
          <button type="button" class="emoji-btn" data-emoji="🦷">🦷</button>
          <button type="button" class="emoji-btn" data-emoji="🦴">🦴</button>
          <button type="button" class="emoji-btn" data-emoji="🦴">🦴</button>
          <button type="button" class="emoji-btn" data-emoji="🦴">🦴</button>
          <button type="button" class="emoji-btn" data-emoji="🦴">🦴</button>
          <button type="button" class="emoji-btn" data-emoji="🦴">🦴</button>
        </div>
    </section>
    <style>
      .contact-list-item:hover {
        background: hsl(0, 0%, 95%);

      }

      .contact-list-item.active {
        background: hsl(0, 0%, 95%);

      }

      .sc_chatele {
        width: 40% !important;
      }

      #loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent black overlay */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        /* Ensure it's above other elements */
      }

      .overlay-content {
        text-align: center;
        color: white;
      }

      .loading-spinner {
        border: 3px solid #f3f3f3;
        /* Light grey */
        border-top: 3px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
      }

      .message {
        font-size: 18px;
      }

      .emoji-btn {
        padding: 0px 2px 4px 1px;
        margin: 2px 0px 2px 0px;
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }

        100% {
          transform: rotate(360deg);
        }
      }
    </style>
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