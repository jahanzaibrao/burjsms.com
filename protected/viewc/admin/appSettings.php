<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc"><?php echo SCTEXT('App Settings') ?><small><?php echo SCTEXT('manage app configuration parameters') ?></small></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <input type="hidden" id="activeTabId" value="<?php echo $_SESSION['activeTabId'] == '' ? 'tab-1' : $_SESSION['activeTabId'] ?>">
                                <?php unset($_SESSION['activeTabId']); ?>
                                <!-- start content -->
                                <div class="m-b-lg nav-tabs-horizontal">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab-1" aria-controls="tab-1" role="tab" data-toggle="tab" aria-expanded="false"><?php echo SCTEXT('Main Settings') ?></a></li>
                                        <li role="presentation" class=""><a href="#tab-2" aria-controls="tab-2" role="tab" data-toggle="tab" aria-expanded="false"><?php echo SCTEXT('SMS Settings') ?></a></li>
                                        <li role="presentation" class=""><a href="#tab-3" aria-controls="tab-3" role="tab" data-toggle="tab" aria-expanded="true"><?php echo SCTEXT('Kannel Settings') ?></a></li>
                                        <li role="presentation" class=""><a href="#tab-4" aria-controls="tab-4" role="tab" data-toggle="tab" aria-expanded="true"><?php echo SCTEXT('Reseller Settings') ?></a></li>
                                        <li role="presentation" class=""><a href="#tab-5" aria-controls="tab-5" role="tab" data-toggle="tab" aria-expanded="true"><?php echo SCTEXT('Security Settings') ?></a></li>
                                        <li role="presentation" class=""><a href="#tab-6" aria-controls="tab-6" role="tab" data-toggle="tab" aria-expanded="true"><?php echo SCTEXT('Miscellaneous Settings') ?></a></li>
                                    </ul>
                                    <div class="tab-content p-md">

                                        <div role="tabpanel" class="tab-pane fade active in" id="tab-1">
                                            <form class="form-horizontal" id="mainset_form" method="post">
                                                <input type="hidden" name="setcat" value="main">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('System Timezone') ?>:</label>
                                                    <div class="col-md-6">
                                                        <input type="hidden" id="deftz" value="<?php echo Doo::conf()->default_server_timezone ?>" />
                                                        <select data-plugin="select2" data-options="{placeholder: 'Select Timezone'}" class="form-control" id="timezone" name="default_server_timezone">
                                                            <option value="Africa/Abidjan">Africa/Abidjan</option>
                                                            <option value="Africa/Accra">Africa/Accra</option>
                                                            <option value="Africa/Addis_Ababa">Africa/Addis_Ababa</option>
                                                            <option value="Africa/Algiers">Africa/Algiers</option>
                                                            <option value="Africa/Asmara">Africa/Asmara</option>
                                                            <option value="Africa/Asmera">Africa/Asmera</option>
                                                            <option value="Africa/Bamako">Africa/Bamako</option>
                                                            <option value="Africa/Bangui">Africa/Bangui</option>
                                                            <option value="Africa/Banjul">Africa/Banjul</option>
                                                            <option value="Africa/Bissau">Africa/Bissau</option>
                                                            <option value="Africa/Blantyre">Africa/Blantyre</option>
                                                            <option value="Africa/Brazzaville">Africa/Brazzaville</option>
                                                            <option value="Africa/Bujumbura">Africa/Bujumbura</option>
                                                            <option value="Africa/Cairo">Africa/Cairo</option>
                                                            <option value="Africa/Casablanca">Africa/Casablanca</option>
                                                            <option value="Africa/Ceuta">Africa/Ceuta</option>
                                                            <option value="Africa/Conakry">Africa/Conakry</option>
                                                            <option value="Africa/Dakar">Africa/Dakar</option>
                                                            <option value="Africa/Dar_es_Salaam">Africa/Dar_es_Salaam</option>
                                                            <option value="Africa/Djibouti">Africa/Djibouti</option>
                                                            <option value="Africa/Douala">Africa/Douala</option>
                                                            <option value="Africa/El_Aaiun">Africa/El_Aaiun</option>
                                                            <option value="Africa/Freetown">Africa/Freetown</option>
                                                            <option value="Africa/Gaborone">Africa/Gaborone</option>
                                                            <option value="Africa/Harare">Africa/Harare</option>
                                                            <option value="Africa/Johannesburg">Africa/Johannesburg</option>
                                                            <option value="Africa/Juba">Africa/Juba</option>
                                                            <option value="Africa/Kampala">Africa/Kampala</option>
                                                            <option value="Africa/Khartoum">Africa/Khartoum</option>
                                                            <option value="Africa/Kigali">Africa/Kigali</option>
                                                            <option value="Africa/Kinshasa">Africa/Kinshasa</option>
                                                            <option value="Africa/Lagos">Africa/Lagos</option>
                                                            <option value="Africa/Libreville">Africa/Libreville</option>
                                                            <option value="Africa/Lome">Africa/Lome</option>
                                                            <option value="Africa/Luanda">Africa/Luanda</option>
                                                            <option value="Africa/Lubumbashi">Africa/Lubumbashi</option>
                                                            <option value="Africa/Lusaka">Africa/Lusaka</option>
                                                            <option value="Africa/Malabo">Africa/Malabo</option>
                                                            <option value="Africa/Maputo">Africa/Maputo</option>
                                                            <option value="Africa/Maseru">Africa/Maseru</option>
                                                            <option value="Africa/Mbabane">Africa/Mbabane</option>
                                                            <option value="Africa/Mogadishu">Africa/Mogadishu</option>
                                                            <option value="Africa/Monrovia">Africa/Monrovia</option>
                                                            <option value="Africa/Nairobi">Africa/Nairobi</option>
                                                            <option value="Africa/Ndjamena">Africa/Ndjamena</option>
                                                            <option value="Africa/Niamey">Africa/Niamey</option>
                                                            <option value="Africa/Nouakchott">Africa/Nouakchott</option>
                                                            <option value="Africa/Ouagadougou">Africa/Ouagadougou</option>
                                                            <option value="Africa/Porto-Novo">Africa/Porto-Novo</option>
                                                            <option value="Africa/Sao_Tome">Africa/Sao_Tome</option>
                                                            <option value="Africa/Timbuktu">Africa/Timbuktu</option>
                                                            <option value="Africa/Tripoli">Africa/Tripoli</option>
                                                            <option value="Africa/Tunis">Africa/Tunis</option>
                                                            <option value="Africa/Windhoek">Africa/Windhoek</option>
                                                            <option value="America/Adak">America/Adak</option>
                                                            <option value="America/Anchorage">America/Anchorage</option>
                                                            <option value="America/Anguilla">America/Anguilla</option>
                                                            <option value="America/Antigua">America/Antigua</option>
                                                            <option value="America/Araguaina">America/Araguaina</option>
                                                            <option value="America/Argentina/Buenos_Aires">America/Argentina/Buenos_Aires</option>
                                                            <option value="America/Argentina/Catamarca">America/Argentina/Catamarca</option>
                                                            <option value="America/Argentina/ComodRivadavia">America/Argentina/ComodRivadavia</option>
                                                            <option value="America/Argentina/Cordoba">America/Argentina/Cordoba</option>
                                                            <option value="America/Argentina/Jujuy">America/Argentina/Jujuy</option>
                                                            <option value="America/Argentina/La_Rioja">America/Argentina/La_Rioja</option>
                                                            <option value="America/Argentina/Mendoza">America/Argentina/Mendoza</option>
                                                            <option value="America/Argentina/Rio_Gallegos">America/Argentina/Rio_Gallegos</option>
                                                            <option value="America/Argentina/Salta">America/Argentina/Salta</option>
                                                            <option value="America/Argentina/San_Juan">America/Argentina/San_Juan</option>
                                                            <option value="America/Argentina/San_Luis">America/Argentina/San_Luis</option>
                                                            <option value="America/Argentina/Tucuman">America/Argentina/Tucuman</option>
                                                            <option value="America/Argentina/Ushuaia">America/Argentina/Ushuaia</option>
                                                            <option value="America/Aruba">America/Aruba</option>
                                                            <option value="America/Asuncion">America/Asuncion</option>
                                                            <option value="America/Atikokan">America/Atikokan</option>
                                                            <option value="America/Atka">America/Atka</option>
                                                            <option value="America/Bahia">America/Bahia</option>
                                                            <option value="America/Bahia_Banderas">America/Bahia_Banderas</option>
                                                            <option value="America/Barbados">America/Barbados</option>
                                                            <option value="America/Belem">America/Belem</option>
                                                            <option value="America/Belize">America/Belize</option>
                                                            <option value="America/Blanc-Sablon">America/Blanc-Sablon</option>
                                                            <option value="America/Boa_Vista">America/Boa_Vista</option>
                                                            <option value="America/Bogota">America/Bogota</option>
                                                            <option value="America/Boise">America/Boise</option>
                                                            <option value="America/Buenos_Aires">America/Buenos_Aires</option>
                                                            <option value="America/Cambridge_Bay">America/Cambridge_Bay</option>
                                                            <option value="America/Campo_Grande">America/Campo_Grande</option>
                                                            <option value="America/Cancun">America/Cancun</option>
                                                            <option value="America/Caracas">America/Caracas</option>
                                                            <option value="America/Catamarca">America/Catamarca</option>
                                                            <option value="America/Cayenne">America/Cayenne</option>
                                                            <option value="America/Cayman">America/Cayman</option>
                                                            <option value="America/Chicago">America/Chicago</option>
                                                            <option value="America/Chihuahua">America/Chihuahua</option>
                                                            <option value="America/Coral_Harbour">America/Coral_Harbour</option>
                                                            <option value="America/Cordoba">America/Cordoba</option>
                                                            <option value="America/Costa_Rica">America/Costa_Rica</option>
                                                            <option value="America/Cuiaba">America/Cuiaba</option>
                                                            <option value="America/Curacao">America/Curacao</option>
                                                            <option value="America/Danmarkshavn">America/Danmarkshavn</option>
                                                            <option value="America/Dawson">America/Dawson</option>
                                                            <option value="America/Dawson_Creek">America/Dawson_Creek</option>
                                                            <option value="America/Denver">America/Denver</option>
                                                            <option value="America/Detroit">America/Detroit</option>
                                                            <option value="America/Dominica">America/Dominica</option>
                                                            <option value="America/Edmonton">America/Edmonton</option>
                                                            <option value="America/Eirunepe">America/Eirunepe</option>
                                                            <option value="America/El_Salvador">America/El_Salvador</option>
                                                            <option value="America/Ensenada">America/Ensenada</option>
                                                            <option value="America/Fortaleza">America/Fortaleza</option>
                                                            <option value="America/Fort_Wayne">America/Fort_Wayne</option>
                                                            <option value="America/Glace_Bay">America/Glace_Bay</option>
                                                            <option value="America/Godthab">America/Godthab</option>
                                                            <option value="America/Goose_Bay">America/Goose_Bay</option>
                                                            <option value="America/Grand_Turk">America/Grand_Turk</option>
                                                            <option value="America/Grenada">America/Grenada</option>
                                                            <option value="America/Guadeloupe">America/Guadeloupe</option>
                                                            <option value="America/Guatemala">America/Guatemala</option>
                                                            <option value="America/Guayaquil">America/Guayaquil</option>
                                                            <option value="America/Guyana">America/Guyana</option>
                                                            <option value="America/Halifax">America/Halifax</option>
                                                            <option value="America/Havana">America/Havana</option>
                                                            <option value="America/Hermosillo">America/Hermosillo</option>
                                                            <option value="America/Indiana/Indianapolis">America/Indiana/Indianapolis</option>
                                                            <option value="America/Indiana/Knox">America/Indiana/Knox</option>
                                                            <option value="America/Indiana/Marengo">America/Indiana/Marengo</option>
                                                            <option value="America/Indiana/Petersburg">America/Indiana/Petersburg</option>
                                                            <option value="America/Indiana/Tell_City">America/Indiana/Tell_City</option>
                                                            <option value="America/Indiana/Vevay">America/Indiana/Vevay</option>
                                                            <option value="America/Indiana/Vincennes">America/Indiana/Vincennes</option>
                                                            <option value="America/Indiana/Winamac">America/Indiana/Winamac</option>
                                                            <option value="America/Indianapolis">America/Indianapolis</option>
                                                            <option value="America/Inuvik">America/Inuvik</option>
                                                            <option value="America/Iqaluit">America/Iqaluit</option>
                                                            <option value="America/Jamaica">America/Jamaica</option>
                                                            <option value="America/Jujuy">America/Jujuy</option>
                                                            <option value="America/Juneau">America/Juneau</option>
                                                            <option value="America/Kentucky/Louisville">America/Kentucky/Louisville</option>
                                                            <option value="America/Kentucky/Monticello">America/Kentucky/Monticello</option>
                                                            <option value="America/Knox_IN">America/Knox_IN</option>
                                                            <option value="America/Kralendijk">America/Kralendijk</option>
                                                            <option value="America/La_Paz">America/La_Paz</option>
                                                            <option value="America/Lima">America/Lima</option>
                                                            <option value="America/Los_Angeles">America/Los_Angeles</option>
                                                            <option value="America/Louisville">America/Louisville</option>
                                                            <option value="America/Lower_Princes">America/Lower_Princes</option>
                                                            <option value="America/Maceio">America/Maceio</option>
                                                            <option value="America/Managua">America/Managua</option>
                                                            <option value="America/Manaus">America/Manaus</option>
                                                            <option value="America/Marigot">America/Marigot</option>
                                                            <option value="America/Martinique">America/Martinique</option>
                                                            <option value="America/Matamoros">America/Matamoros</option>
                                                            <option value="America/Mazatlan">America/Mazatlan</option>
                                                            <option value="America/Mendoza">America/Mendoza</option>
                                                            <option value="America/Menominee">America/Menominee</option>
                                                            <option value="America/Merida">America/Merida</option>
                                                            <option value="America/Metlakatla">America/Metlakatla</option>
                                                            <option value="America/Mexico_City">America/Mexico_City</option>
                                                            <option value="America/Miquelon">America/Miquelon</option>
                                                            <option value="America/Moncton">America/Moncton</option>
                                                            <option value="America/Monterrey">America/Monterrey</option>
                                                            <option value="America/Montevideo">America/Montevideo</option>
                                                            <option value="America/Montreal">America/Montreal</option>
                                                            <option value="America/Montserrat">America/Montserrat</option>
                                                            <option value="America/Nassau">America/Nassau</option>
                                                            <option value="America/New_York">America/New_York</option>
                                                            <option value="America/Nipigon">America/Nipigon</option>
                                                            <option value="America/Nome">America/Nome</option>
                                                            <option value="America/Noronha">America/Noronha</option>
                                                            <option value="America/North_Dakota/Beulah">America/North_Dakota/Beulah</option>
                                                            <option value="America/North_Dakota/Center">America/North_Dakota/Center</option>
                                                            <option value="America/North_Dakota/New_Salem">America/North_Dakota/New_Salem</option>
                                                            <option value="America/Ojinaga">America/Ojinaga</option>
                                                            <option value="America/Panama">America/Panama</option>
                                                            <option value="America/Pangnirtung">America/Pangnirtung</option>
                                                            <option value="America/Paramaribo">America/Paramaribo</option>
                                                            <option value="America/Phoenix">America/Phoenix</option>
                                                            <option value="America/Port-au-Prince">America/Port-au-Prince</option>
                                                            <option value="America/Porto_Acre">America/Porto_Acre</option>
                                                            <option value="America/Porto_Velho">America/Porto_Velho</option>
                                                            <option value="America/Port_of_Spain">America/Port_of_Spain</option>
                                                            <option value="America/Puerto_Rico">America/Puerto_Rico</option>
                                                            <option value="America/Rainy_River">America/Rainy_River</option>
                                                            <option value="America/Rankin_Inlet">America/Rankin_Inlet</option>
                                                            <option value="America/Recife">America/Recife</option>
                                                            <option value="America/Regina">America/Regina</option>
                                                            <option value="America/Resolute">America/Resolute</option>
                                                            <option value="America/Rio_Branco">America/Rio_Branco</option>
                                                            <option value="America/Rosario">America/Rosario</option>
                                                            <option value="America/Santarem">America/Santarem</option>
                                                            <option value="America/Santa_Isabel">America/Santa_Isabel</option>
                                                            <option value="America/Santiago">America/Santiago</option>
                                                            <option value="America/Santo_Domingo">America/Santo_Domingo</option>
                                                            <option value="America/Sao_Paulo">America/Sao_Paulo</option>
                                                            <option value="America/Scoresbysund">America/Scoresbysund</option>
                                                            <option value="America/Shiprock">America/Shiprock</option>
                                                            <option value="America/Sitka">America/Sitka</option>
                                                            <option value="America/St_Barthelemy">America/St_Barthelemy</option>
                                                            <option value="America/St_Johns">America/St_Johns</option>
                                                            <option value="America/St_Kitts">America/St_Kitts</option>
                                                            <option value="America/St_Lucia">America/St_Lucia</option>
                                                            <option value="America/St_Thomas">America/St_Thomas</option>
                                                            <option value="America/St_Vincent">America/St_Vincent</option>
                                                            <option value="America/Swift_Current">America/Swift_Current</option>
                                                            <option value="America/Tegucigalpa">America/Tegucigalpa</option>
                                                            <option value="America/Thule">America/Thule</option>
                                                            <option value="America/Thunder_Bay">America/Thunder_Bay</option>
                                                            <option value="America/Tijuana">America/Tijuana</option>
                                                            <option value="America/Toronto">America/Toronto</option>
                                                            <option value="America/Tortola">America/Tortola</option>
                                                            <option value="America/Vancouver">America/Vancouver</option>
                                                            <option value="America/Virgin">America/Virgin</option>
                                                            <option value="America/Whitehorse">America/Whitehorse</option>
                                                            <option value="America/Winnipeg">America/Winnipeg</option>
                                                            <option value="America/Yakutat">America/Yakutat</option>
                                                            <option value="America/Yellowknife">America/Yellowknife</option>
                                                            <option value="Antarctica/Casey">Antarctica/Casey</option>
                                                            <option value="Antarctica/Davis">Antarctica/Davis</option>
                                                            <option value="Antarctica/DumontDUrville">Antarctica/DumontDUrville</option>
                                                            <option value="Antarctica/Macquarie">Antarctica/Macquarie</option>
                                                            <option value="Antarctica/Mawson">Antarctica/Mawson</option>
                                                            <option value="Antarctica/McMurdo">Antarctica/McMurdo</option>
                                                            <option value="Antarctica/Palmer">Antarctica/Palmer</option>
                                                            <option value="Antarctica/Rothera">Antarctica/Rothera</option>
                                                            <option value="Antarctica/South_Pole">Antarctica/South_Pole</option>
                                                            <option value="Antarctica/Syowa">Antarctica/Syowa</option>
                                                            <option value="Antarctica/Vostok">Antarctica/Vostok</option>
                                                            <option value="Arctic/Longyearbyen">Arctic/Longyearbyen</option>
                                                            <option value="Asia/Aden">Asia/Aden</option>
                                                            <option value="Asia/Almaty">Asia/Almaty</option>
                                                            <option value="Asia/Amman">Asia/Amman</option>
                                                            <option value="Asia/Anadyr">Asia/Anadyr</option>
                                                            <option value="Asia/Aqtau">Asia/Aqtau</option>
                                                            <option value="Asia/Aqtobe">Asia/Aqtobe</option>
                                                            <option value="Asia/Ashgabat">Asia/Ashgabat</option>
                                                            <option value="Asia/Ashkhabad">Asia/Ashkhabad</option>
                                                            <option value="Asia/Baghdad">Asia/Baghdad</option>
                                                            <option value="Asia/Bahrain">Asia/Bahrain</option>
                                                            <option value="Asia/Baku">Asia/Baku</option>
                                                            <option value="Asia/Bangkok">Asia/Bangkok</option>
                                                            <option value="Asia/Beirut">Asia/Beirut</option>
                                                            <option value="Asia/Bishkek">Asia/Bishkek</option>
                                                            <option value="Asia/Brunei">Asia/Brunei</option>
                                                            <option value="Asia/Calcutta">Asia/Calcutta</option>
                                                            <option value="Asia/Choibalsan">Asia/Choibalsan</option>
                                                            <option value="Asia/Chongqing">Asia/Chongqing</option>
                                                            <option value="Asia/Chungking">Asia/Chungking</option>
                                                            <option value="Asia/Colombo">Asia/Colombo</option>
                                                            <option value="Asia/Dacca">Asia/Dacca</option>
                                                            <option value="Asia/Damascus">Asia/Damascus</option>
                                                            <option value="Asia/Dhaka">Asia/Dhaka</option>
                                                            <option value="Asia/Dili">Asia/Dili</option>
                                                            <option value="Asia/Dubai">Asia/Dubai</option>
                                                            <option value="Asia/Dushanbe">Asia/Dushanbe</option>
                                                            <option value="Asia/Gaza">Asia/Gaza</option>
                                                            <option value="Asia/Harbin">Asia/Harbin</option>
                                                            <option value="Asia/Hebron">Asia/Hebron</option>
                                                            <option value="Asia/Hong_Kong">Asia/Hong_Kong</option>
                                                            <option value="Asia/Hovd">Asia/Hovd</option>
                                                            <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh</option>
                                                            <option value="Asia/Irkutsk">Asia/Irkutsk</option>
                                                            <option value="Asia/Istanbul">Asia/Istanbul</option>
                                                            <option value="Asia/Jakarta">Asia/Jakarta</option>
                                                            <option value="Asia/Jayapura">Asia/Jayapura</option>
                                                            <option value="Asia/Jerusalem">Asia/Jerusalem</option>
                                                            <option value="Asia/Kabul">Asia/Kabul</option>
                                                            <option value="Asia/Kamchatka">Asia/Kamchatka</option>
                                                            <option value="Asia/Karachi">Asia/Karachi</option>
                                                            <option value="Asia/Kashgar">Asia/Kashgar</option>
                                                            <option value="Asia/Kathmandu">Asia/Kathmandu</option>
                                                            <option value="Asia/Katmandu">Asia/Katmandu</option>
                                                            <option value="Asia/Kolkata">Asia/Kolkata</option>
                                                            <option value="Asia/Krasnoyarsk">Asia/Krasnoyarsk</option>
                                                            <option value="Asia/Kuala_Lumpur">Asia/Kuala_Lumpur</option>
                                                            <option value="Asia/Kuching">Asia/Kuching</option>
                                                            <option value="Asia/Kuwait">Asia/Kuwait</option>
                                                            <option value="Asia/Macao">Asia/Macao</option>
                                                            <option value="Asia/Macau">Asia/Macau</option>
                                                            <option value="Asia/Magadan">Asia/Magadan</option>
                                                            <option value="Asia/Makassar">Asia/Makassar</option>
                                                            <option value="Asia/Manila">Asia/Manila</option>
                                                            <option value="Asia/Muscat">Asia/Muscat</option>
                                                            <option value="Asia/Nicosia">Asia/Nicosia</option>
                                                            <option value="Asia/Novokuznetsk">Asia/Novokuznetsk</option>
                                                            <option value="Asia/Novosibirsk">Asia/Novosibirsk</option>
                                                            <option value="Asia/Omsk">Asia/Omsk</option>
                                                            <option value="Asia/Oral">Asia/Oral</option>
                                                            <option value="Asia/Phnom_Penh">Asia/Phnom_Penh</option>
                                                            <option value="Asia/Pontianak">Asia/Pontianak</option>
                                                            <option value="Asia/Pyongyang">Asia/Pyongyang</option>
                                                            <option value="Asia/Qatar">Asia/Qatar</option>
                                                            <option value="Asia/Qyzylorda">Asia/Qyzylorda</option>
                                                            <option value="Asia/Rangoon">Asia/Rangoon</option>
                                                            <option value="Asia/Riyadh">Asia/Riyadh</option>
                                                            <option value="Asia/Saigon">Asia/Saigon</option>
                                                            <option value="Asia/Sakhalin">Asia/Sakhalin</option>
                                                            <option value="Asia/Samarkand">Asia/Samarkand</option>
                                                            <option value="Asia/Seoul">Asia/Seoul</option>
                                                            <option value="Asia/Shanghai">Asia/Shanghai</option>
                                                            <option value="Asia/Singapore">Asia/Singapore</option>
                                                            <option value="Asia/Taipei">Asia/Taipei</option>
                                                            <option value="Asia/Tashkent">Asia/Tashkent</option>
                                                            <option value="Asia/Tbilisi">Asia/Tbilisi</option>
                                                            <option value="Asia/Tehran">Asia/Tehran</option>
                                                            <option value="Asia/Tel_Aviv">Asia/Tel_Aviv</option>
                                                            <option value="Asia/Thimbu">Asia/Thimbu</option>
                                                            <option value="Asia/Thimphu">Asia/Thimphu</option>
                                                            <option value="Asia/Tokyo">Asia/Tokyo</option>
                                                            <option value="Asia/Ujung_Pandang">Asia/Ujung_Pandang</option>
                                                            <option value="Asia/Ulaanbaatar">Asia/Ulaanbaatar</option>
                                                            <option value="Asia/Ulan_Bator">Asia/Ulan_Bator</option>
                                                            <option value="Asia/Urumqi">Asia/Urumqi</option>
                                                            <option value="Asia/Vientiane">Asia/Vientiane</option>
                                                            <option value="Asia/Vladivostok">Asia/Vladivostok</option>
                                                            <option value="Asia/Yakutsk">Asia/Yakutsk</option>
                                                            <option value="Asia/Yekaterinburg">Asia/Yekaterinburg</option>
                                                            <option value="Asia/Yerevan">Asia/Yerevan</option>
                                                            <option value="Atlantic/Azores">Atlantic/Azores</option>
                                                            <option value="Atlantic/Bermuda">Atlantic/Bermuda</option>
                                                            <option value="Atlantic/Canary">Atlantic/Canary</option>
                                                            <option value="Atlantic/Cape_Verde">Atlantic/Cape_Verde</option>
                                                            <option value="Atlantic/Faeroe">Atlantic/Faeroe</option>
                                                            <option value="Atlantic/Faroe">Atlantic/Faroe</option>
                                                            <option value="Atlantic/Jan_Mayen">Atlantic/Jan_Mayen</option>
                                                            <option value="Atlantic/Madeira">Atlantic/Madeira</option>
                                                            <option value="Atlantic/Reykjavik">Atlantic/Reykjavik</option>
                                                            <option value="Atlantic/South_Georgia">Atlantic/South_Georgia</option>
                                                            <option value="Atlantic/Stanley">Atlantic/Stanley</option>
                                                            <option value="Atlantic/St_Helena">Atlantic/St_Helena</option>
                                                            <option value="Australia/ACT">Australia/ACT</option>
                                                            <option value="Australia/Adelaide">Australia/Adelaide</option>
                                                            <option value="Australia/Brisbane">Australia/Brisbane</option>
                                                            <option value="Australia/Broken_Hill">Australia/Broken_Hill</option>
                                                            <option value="Australia/Canberra">Australia/Canberra</option>
                                                            <option value="Australia/Currie">Australia/Currie</option>
                                                            <option value="Australia/Darwin">Australia/Darwin</option>
                                                            <option value="Australia/Eucla">Australia/Eucla</option>
                                                            <option value="Australia/Hobart">Australia/Hobart</option>
                                                            <option value="Australia/LHI">Australia/LHI</option>
                                                            <option value="Australia/Lindeman">Australia/Lindeman</option>
                                                            <option value="Australia/Lord_Howe">Australia/Lord_Howe</option>
                                                            <option value="Australia/Melbourne">Australia/Melbourne</option>
                                                            <option value="Australia/North">Australia/North</option>
                                                            <option value="Australia/NSW">Australia/NSW</option>
                                                            <option value="Australia/Perth">Australia/Perth</option>
                                                            <option value="Australia/Queensland">Australia/Queensland</option>
                                                            <option value="Australia/South">Australia/South</option>
                                                            <option value="Australia/Sydney">Australia/Sydney</option>
                                                            <option value="Australia/Tasmania">Australia/Tasmania</option>
                                                            <option value="Australia/Victoria">Australia/Victoria</option>
                                                            <option value="Australia/West">Australia/West</option>
                                                            <option value="Australia/Yancowinna">Australia/Yancowinna</option>
                                                            <option value="Brazil/Acre">Brazil/Acre</option>
                                                            <option value="Brazil/DeNoronha">Brazil/DeNoronha</option>
                                                            <option value="Brazil/East">Brazil/East</option>
                                                            <option value="Brazil/West">Brazil/West</option>
                                                            <option value="Canada/Atlantic">Canada/Atlantic</option>
                                                            <option value="Canada/Central">Canada/Central</option>
                                                            <option value="Canada/East-Saskatchewan">Canada/East-Saskatchewan</option>
                                                            <option value="Canada/Eastern">Canada/Eastern</option>
                                                            <option value="Canada/Mountain">Canada/Mountain</option>
                                                            <option value="Canada/Newfoundland">Canada/Newfoundland</option>
                                                            <option value="Canada/Pacific">Canada/Pacific</option>
                                                            <option value="Canada/Saskatchewan">Canada/Saskatchewan</option>
                                                            <option value="Canada/Yukon">Canada/Yukon</option>
                                                            <option value="CET">CET</option>
                                                            <option value="Chile/Continental">Chile/Continental</option>
                                                            <option value="Chile/EasterIsland">Chile/EasterIsland</option>
                                                            <option value="CST6CDT">CST6CDT</option>
                                                            <option value="Cuba">Cuba</option>
                                                            <option value="EET">EET</option>
                                                            <option value="Egypt">Egypt</option>
                                                            <option value="Eire">Eire</option>
                                                            <option value="EST">EST</option>
                                                            <option value="EST5EDT">EST5EDT</option>
                                                            <option value="Etc/GMT">Etc/GMT</option>
                                                            <option value="Etc/GMT+0">Etc/GMT+0</option>
                                                            <option value="Etc/GMT+1">Etc/GMT+1</option>
                                                            <option value="Etc/GMT+10">Etc/GMT+10</option>
                                                            <option value="Etc/GMT+11">Etc/GMT+11</option>
                                                            <option value="Etc/GMT+12">Etc/GMT+12</option>
                                                            <option value="Etc/GMT+2">Etc/GMT+2</option>
                                                            <option value="Etc/GMT+3">Etc/GMT+3</option>
                                                            <option value="Etc/GMT+4">Etc/GMT+4</option>
                                                            <option value="Etc/GMT+5">Etc/GMT+5</option>
                                                            <option value="Etc/GMT+6">Etc/GMT+6</option>
                                                            <option value="Etc/GMT+7">Etc/GMT+7</option>
                                                            <option value="Etc/GMT+8">Etc/GMT+8</option>
                                                            <option value="Etc/GMT+9">Etc/GMT+9</option>
                                                            <option value="Etc/GMT-0">Etc/GMT-0</option>
                                                            <option value="Etc/GMT-1">Etc/GMT-1</option>
                                                            <option value="Etc/GMT-10">Etc/GMT-10</option>
                                                            <option value="Etc/GMT-11">Etc/GMT-11</option>
                                                            <option value="Etc/GMT-12">Etc/GMT-12</option>
                                                            <option value="Etc/GMT-13">Etc/GMT-13</option>
                                                            <option value="Etc/GMT-14">Etc/GMT-14</option>
                                                            <option value="Etc/GMT-2">Etc/GMT-2</option>
                                                            <option value="Etc/GMT-3">Etc/GMT-3</option>
                                                            <option value="Etc/GMT-4">Etc/GMT-4</option>
                                                            <option value="Etc/GMT-5">Etc/GMT-5</option>
                                                            <option value="Etc/GMT-6">Etc/GMT-6</option>
                                                            <option value="Etc/GMT-7">Etc/GMT-7</option>
                                                            <option value="Etc/GMT-8">Etc/GMT-8</option>
                                                            <option value="Etc/GMT-9">Etc/GMT-9</option>
                                                            <option value="Etc/GMT0">Etc/GMT0</option>
                                                            <option value="Etc/Greenwich">Etc/Greenwich</option>
                                                            <option value="Etc/UCT">Etc/UCT</option>
                                                            <option value="Etc/Universal">Etc/Universal</option>
                                                            <option value="Etc/UTC">Etc/UTC</option>
                                                            <option value="Etc/Zulu">Etc/Zulu</option>
                                                            <option value="Europe/Amsterdam">Europe/Amsterdam</option>
                                                            <option value="Europe/Andorra">Europe/Andorra</option>
                                                            <option value="Europe/Athens">Europe/Athens</option>
                                                            <option value="Europe/Belfast">Europe/Belfast</option>
                                                            <option value="Europe/Belgrade">Europe/Belgrade</option>
                                                            <option value="Europe/Berlin">Europe/Berlin</option>
                                                            <option value="Europe/Bratislava">Europe/Bratislava</option>
                                                            <option value="Europe/Brussels">Europe/Brussels</option>
                                                            <option value="Europe/Bucharest">Europe/Bucharest</option>
                                                            <option value="Europe/Budapest">Europe/Budapest</option>
                                                            <option value="Europe/Chisinau">Europe/Chisinau</option>
                                                            <option value="Europe/Copenhagen">Europe/Copenhagen</option>
                                                            <option value="Europe/Dublin">Europe/Dublin</option>
                                                            <option value="Europe/Gibraltar">Europe/Gibraltar</option>
                                                            <option value="Europe/Guernsey">Europe/Guernsey</option>
                                                            <option value="Europe/Helsinki">Europe/Helsinki</option>
                                                            <option value="Europe/Isle_of_Man">Europe/Isle_of_Man</option>
                                                            <option value="Europe/Istanbul">Europe/Istanbul</option>
                                                            <option value="Europe/Jersey">Europe/Jersey</option>
                                                            <option value="Europe/Kaliningrad">Europe/Kaliningrad</option>
                                                            <option value="Europe/Kiev">Europe/Kiev</option>
                                                            <option value="Europe/Lisbon">Europe/Lisbon</option>
                                                            <option value="Europe/Ljubljana">Europe/Ljubljana</option>
                                                            <option value="Europe/London">Europe/London</option>
                                                            <option value="Europe/Luxembourg">Europe/Luxembourg</option>
                                                            <option value="Europe/Madrid">Europe/Madrid</option>
                                                            <option value="Europe/Malta">Europe/Malta</option>
                                                            <option value="Europe/Mariehamn">Europe/Mariehamn</option>
                                                            <option value="Europe/Minsk">Europe/Minsk</option>
                                                            <option value="Europe/Monaco">Europe/Monaco</option>
                                                            <option value="Europe/Moscow">Europe/Moscow</option>
                                                            <option value="Europe/Nicosia">Europe/Nicosia</option>
                                                            <option value="Europe/Oslo">Europe/Oslo</option>
                                                            <option value="Europe/Paris">Europe/Paris</option>
                                                            <option value="Europe/Podgorica">Europe/Podgorica</option>
                                                            <option value="Europe/Prague">Europe/Prague</option>
                                                            <option value="Europe/Riga">Europe/Riga</option>
                                                            <option value="Europe/Rome">Europe/Rome</option>
                                                            <option value="Europe/Samara">Europe/Samara</option>
                                                            <option value="Europe/San_Marino">Europe/San_Marino</option>
                                                            <option value="Europe/Sarajevo">Europe/Sarajevo</option>
                                                            <option value="Europe/Simferopol">Europe/Simferopol</option>
                                                            <option value="Europe/Skopje">Europe/Skopje</option>
                                                            <option value="Europe/Sofia">Europe/Sofia</option>
                                                            <option value="Europe/Stockholm">Europe/Stockholm</option>
                                                            <option value="Europe/Tallinn">Europe/Tallinn</option>
                                                            <option value="Europe/Tirane">Europe/Tirane</option>
                                                            <option value="Europe/Tiraspol">Europe/Tiraspol</option>
                                                            <option value="Europe/Uzhgorod">Europe/Uzhgorod</option>
                                                            <option value="Europe/Vaduz">Europe/Vaduz</option>
                                                            <option value="Europe/Vatican">Europe/Vatican</option>
                                                            <option value="Europe/Vienna">Europe/Vienna</option>
                                                            <option value="Europe/Vilnius">Europe/Vilnius</option>
                                                            <option value="Europe/Volgograd">Europe/Volgograd</option>
                                                            <option value="Europe/Warsaw">Europe/Warsaw</option>
                                                            <option value="Europe/Zagreb">Europe/Zagreb</option>
                                                            <option value="Europe/Zaporozhye">Europe/Zaporozhye</option>
                                                            <option value="Europe/Zurich">Europe/Zurich</option>
                                                            <option value="Factory">Factory</option>
                                                            <option value="GB">GB</option>
                                                            <option value="GB-Eire">GB-Eire</option>
                                                            <option value="GMT">GMT</option>
                                                            <option value="GMT+0">GMT+0</option>
                                                            <option value="GMT-0">GMT-0</option>
                                                            <option value="GMT0">GMT0</option>
                                                            <option value="Greenwich">Greenwich</option>
                                                            <option value="Hongkong">Hongkong</option>
                                                            <option value="HST">HST</option>
                                                            <option value="Iceland">Iceland</option>
                                                            <option value="Indian/Antananarivo">Indian/Antananarivo</option>
                                                            <option value="Indian/Chagos">Indian/Chagos</option>
                                                            <option value="Indian/Christmas">Indian/Christmas</option>
                                                            <option value="Indian/Cocos">Indian/Cocos</option>
                                                            <option value="Indian/Comoro">Indian/Comoro</option>
                                                            <option value="Indian/Kerguelen">Indian/Kerguelen</option>
                                                            <option value="Indian/Mahe">Indian/Mahe</option>
                                                            <option value="Indian/Maldives">Indian/Maldives</option>
                                                            <option value="Indian/Mauritius">Indian/Mauritius</option>
                                                            <option value="Indian/Mayotte">Indian/Mayotte</option>
                                                            <option value="Indian/Reunion">Indian/Reunion</option>
                                                            <option value="Iran">Iran</option>
                                                            <option value="Israel">Israel</option>
                                                            <option value="Jamaica">Jamaica</option>
                                                            <option value="Japan">Japan</option>
                                                            <option value="Kwajalein">Kwajalein</option>
                                                            <option value="Libya">Libya</option>
                                                            <option value="MET">MET</option>
                                                            <option value="Mexico/BajaNorte">Mexico/BajaNorte</option>
                                                            <option value="Mexico/BajaSur">Mexico/BajaSur</option>
                                                            <option value="Mexico/General">Mexico/General</option>
                                                            <option value="MST">MST</option>
                                                            <option value="MST7MDT">MST7MDT</option>
                                                            <option value="Navajo">Navajo</option>
                                                            <option value="NZ">NZ</option>
                                                            <option value="NZ-CHAT">NZ-CHAT</option>
                                                            <option value="Pacific/Apia">Pacific/Apia</option>
                                                            <option value="Pacific/Auckland">Pacific/Auckland</option>
                                                            <option value="Pacific/Chatham">Pacific/Chatham</option>
                                                            <option value="Pacific/Chuuk">Pacific/Chuuk</option>
                                                            <option value="Pacific/Easter">Pacific/Easter</option>
                                                            <option value="Pacific/Efate">Pacific/Efate</option>
                                                            <option value="Pacific/Enderbury">Pacific/Enderbury</option>
                                                            <option value="Pacific/Fakaofo">Pacific/Fakaofo</option>
                                                            <option value="Pacific/Fiji">Pacific/Fiji</option>
                                                            <option value="Pacific/Funafuti">Pacific/Funafuti</option>
                                                            <option value="Pacific/Galapagos">Pacific/Galapagos</option>
                                                            <option value="Pacific/Gambier">Pacific/Gambier</option>
                                                            <option value="Pacific/Guadalcanal">Pacific/Guadalcanal</option>
                                                            <option value="Pacific/Guam">Pacific/Guam</option>
                                                            <option value="Pacific/Honolulu">Pacific/Honolulu</option>
                                                            <option value="Pacific/Johnston">Pacific/Johnston</option>
                                                            <option value="Pacific/Kiritimati">Pacific/Kiritimati</option>
                                                            <option value="Pacific/Kosrae">Pacific/Kosrae</option>
                                                            <option value="Pacific/Kwajalein">Pacific/Kwajalein</option>
                                                            <option value="Pacific/Majuro">Pacific/Majuro</option>
                                                            <option value="Pacific/Marquesas">Pacific/Marquesas</option>
                                                            <option value="Pacific/Midway">Pacific/Midway</option>
                                                            <option value="Pacific/Nauru">Pacific/Nauru</option>
                                                            <option value="Pacific/Niue">Pacific/Niue</option>
                                                            <option value="Pacific/Norfolk">Pacific/Norfolk</option>
                                                            <option value="Pacific/Noumea">Pacific/Noumea</option>
                                                            <option value="Pacific/Pago_Pago">Pacific/Pago_Pago</option>
                                                            <option value="Pacific/Palau">Pacific/Palau</option>
                                                            <option value="Pacific/Pitcairn">Pacific/Pitcairn</option>
                                                            <option value="Pacific/Pohnpei">Pacific/Pohnpei</option>
                                                            <option value="Pacific/Ponape">Pacific/Ponape</option>
                                                            <option value="Pacific/Port_Moresby">Pacific/Port_Moresby</option>
                                                            <option value="Pacific/Rarotonga">Pacific/Rarotonga</option>
                                                            <option value="Pacific/Saipan">Pacific/Saipan</option>
                                                            <option value="Pacific/Samoa">Pacific/Samoa</option>
                                                            <option value="Pacific/Tahiti">Pacific/Tahiti</option>
                                                            <option value="Pacific/Tarawa">Pacific/Tarawa</option>
                                                            <option value="Pacific/Tongatapu">Pacific/Tongatapu</option>
                                                            <option value="Pacific/Truk">Pacific/Truk</option>
                                                            <option value="Pacific/Wake">Pacific/Wake</option>
                                                            <option value="Pacific/Wallis">Pacific/Wallis</option>
                                                            <option value="Pacific/Yap">Pacific/Yap</option>
                                                            <option value="Poland">Poland</option>
                                                            <option value="Portugal">Portugal</option>
                                                            <option value="PRC">PRC</option>
                                                            <option value="PST8PDT">PST8PDT</option>
                                                            <option value="ROC">ROC</option>
                                                            <option value="ROK">ROK</option>
                                                            <option value="Singapore">Singapore</option>
                                                            <option value="Turkey">Turkey</option>
                                                            <option value="UCT">UCT</option>
                                                            <option value="Universal">Universal</option>
                                                            <option value="US/Alaska">US/Alaska</option>
                                                            <option value="US/Aleutian">US/Aleutian</option>
                                                            <option value="US/Arizona">US/Arizona</option>
                                                            <option value="US/Central">US/Central</option>
                                                            <option value="US/East-Indiana">US/East-Indiana</option>
                                                            <option value="US/Eastern">US/Eastern</option>
                                                            <option value="US/Hawaii">US/Hawaii</option>
                                                            <option value="US/Indiana-Starke">US/Indiana-Starke</option>
                                                            <option value="US/Michigan">US/Michigan</option>
                                                            <option value="US/Mountain">US/Mountain</option>
                                                            <option value="US/Pacific">US/Pacific</option>
                                                            <option value="US/Pacific-New">US/Pacific-New</option>
                                                            <option value="US/Samoa">US/Samoa</option>
                                                            <option value="UTC">UTC</option>
                                                            <option value="W-SU">W-SU</option>
                                                            <option value="WET">WET</option>
                                                            <option value="Zulu">Zulu</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Demo Mode') ?>:</label>
                                                    <div class="col-md-5">
                                                        <select name="demo_mode" class="form-control" data-plugin="select2">
                                                            <option <?php if (Doo::conf()->demo_mode == 'true') { ?> selected <?php } ?> value="1"><?php echo SCTEXT('True') ?></option>
                                                            <option <?php if (Doo::conf()->demo_mode == 'false') { ?> selected <?php } ?> value="0"><?php echo SCTEXT('False') ?></option>
                                                        </select>
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('Setting this TRUE will stop SMS from reaching Kannel') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Server IP') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="server_ip" placeholder="e.g. 123.45.67.89" value="<?php echo Doo::conf()->server_ip ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Admin Domain') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">http(s)://</span>
                                                            <input type="text" class="form-control" name="admin_domain" placeholder="e.g. www.mysite.com" value="<?php echo Doo::conf()->admin_domain ?>">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('App Currency') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="currency" placeholder="e.g. $ or £ .." value="<?php echo Doo::conf()->currency ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Currency Name') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="currency_name" placeholder="e.g. USD or GBP .." value="<?php echo Doo::conf()->currency_name ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Global Page Title') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="global_page_title" placeholder="<?php echo SCTEXT('e.g. SMS Platform') ?> .." value="<?php echo Doo::conf()->global_page_title ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Daily Alerts time') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group bootstrap-timepicker timepicker col-md-6 col-sm-4">
                                                            <input value="<?php echo Doo::conf()->eod_alert_time ?>" name="eod_alert_time" id="timepicker2" type="text" class="form-control input-small" data-plugin="timepicker" data-options="{ showInputs: false, showMeridian: false }"> <span class="input-group-addon bg-info"><i class="glyphicon glyphicon-time"></i></span>
                                                        </div>
                                                        <span class="help-block m-t-xs"><?php echo SCTEXT("At this time system will send emails to registered clients containing a summary of current day's activity.") ?></span>
                                                    </div>
                                                </div>

                                                <hr>
                                                <div class="form-group">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-8">
                                                        <button class="btn btn-primary save_settings" data-form="mainset_form" type="button"><?php echo SCTEXT('Save Changes') ?></button>
                                                        <button type="button" class="btn btn-default bk"><?php echo SCTEXT('Cancel') ?></button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab-2">
                                            <form class="form-horizontal" id="msgset_form" method="post">
                                                <input type="hidden" name="setcat" value="messaging">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Default Sender') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control input-md" name="default_sender_id" placeholder="e.g. WEBSMS" value="<?php echo Doo::conf()->default_sender_id ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('enter a Sender ID to be assigned to new accounts') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Default DLR Percentage') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="def_dlr_per" placeholder="e.g. 80" value="<?php echo Doo::conf()->def_dlr_per ?>">
                                                            <span class="input-group-addon">%</span>
                                                        </div>

                                                        <span class="help-block m-b-0"><?php echo SCTEXT('This DLR cut percentage will be applied to bulk campaigns') ?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('DLR Callback Mechanism') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="radio radio-inline radio-primary">
                                                            <input id="cb_y" name="dlr_callback_mechanism" <?php if (Doo::conf()->dlr_callback_mechanism == '1') { ?> checked="checked" <?php } ?> type="radio" value="1">
                                                            <label for="cb_y"><?php echo SCTEXT('Batch Processing') ?></label>
                                                        </div>
                                                        <div class="radio radio-inline radio-primary">
                                                            <input id="cb_n" name="dlr_callback_mechanism" <?php if (Doo::conf()->dlr_callback_mechanism == '0') { ?> checked="checked" <?php } ?> type="radio" value="0">
                                                            <label for="cb_n"><?php echo SCTEXT('Instant Single Execution') ?></label>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> <?php echo SCTEXT('DLR Callback Retires') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" name="dlr_callback_retry" id="dlr_callback_retry" class="form-control" placeholder="<?php echo SCTEXT('no. of reties if callback url is unreachable ') ?>" data-plugin="TouchSpin" data-options="{ verticalbuttons: true, buttondown_class: 'btn btn-info', buttonup_class: 'btn btn-info', verticalupclass: 'glyphicon glyphicon-plus', verticaldownclass: 'glyphicon glyphicon-minus' }" value="<?php echo Doo::conf()->dlr_callback_retry ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('DLR cut threshold') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control input-md" name="dlr_per_threshold" placeholder="e.g. 50,000" value="<?php echo Doo::conf()->dlr_per_threshold ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('number of SMS after which DLR cut should be applied') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Default Fake DLR ratio') ?>:</label>
                                                    <div class="col-md-5">
                                                        <select id="fdlrsel" class="form-control" data-plugin="select2" name="systemfdlr">
                                                            <option value="">- Select One -</option>
                                                            <?php foreach ($data['fdlrs'] as $fdlr) { ?>
                                                                <option <?php if (Doo::conf()->fakedlr_composition == $fdlr->composition) { ?> selected <?php } ?> value='<?php echo $fdlr->composition ?>'><?php echo $fdlr->title ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <span class="help-block">
                                                            <h6>Composition</h6>
                                                            <hr class="m-h-sm">
                                                            <p id="fdlr_comp_ctr">
                                                                -
                                                            </p>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Batch Threshold') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="batch_threshold" placeholder="e.g. 20,000" value="<?php echo Doo::conf()->batch_threshold ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('number of SMS after which system should divide campaign into smaller batches') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Batch Size') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="queue_batch_size" placeholder="e.g. 20,000 .." value="<?php echo Doo::conf()->queue_batch_size ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('number of SMS in each batch') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Queue Process Interval') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="queue_process_interval" placeholder="e.g. 60" value="<?php echo Doo::conf()->queue_process_interval ?>">
                                                            <span class="input-group-addon"><?php echo SCTEXT('seconds') ?></span>
                                                        </div>

                                                        <span class="help-block m-b-0"><?php echo SCTEXT('Time between each SMS batch submission from Queue') ?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Schedule Process Interval') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="schedule_process_interval" placeholder="e.g. 60" value="<?php echo Doo::conf()->schedule_process_interval ?>">
                                                            <span class="input-group-addon"><?php echo SCTEXT('seconds') ?></span>
                                                        </div>

                                                        <span class="help-block m-b-0"><?php echo SCTEXT('Time between each SMS batch submission from Scheduled Queue') ?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Temp Store Interval') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="temp_process_interval" placeholder="e.g. 60" value="<?php echo Doo::conf()->temp_process_interval ?>">
                                                            <span class="input-group-addon"><?php echo SCTEXT('seconds') ?></span>
                                                        </div>

                                                        <span class="help-block m-b-0"><?php echo SCTEXT('Time between each SMS batch submission from Temporary store') ?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Store Temp Campaigns') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="radio radio-inline radio-primary">
                                                            <input id="st_y" name="store_temp_campaigns" <?php if (Doo::conf()->store_temp_campaigns == '1') { ?> checked="checked" <?php } ?> type="radio" value="1">
                                                            <label for="st_y"><?php echo SCTEXT('Yes') ?></label>
                                                        </div>
                                                        <div class="radio radio-inline radio-primary">
                                                            <input id="st_n" name="store_temp_campaigns" <?php if (Doo::conf()->store_temp_campaigns == '0') { ?> checked="checked" <?php } ?> type="radio" value="0">
                                                            <label for="st_n"><?php echo SCTEXT('No') ?></label>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('SPAM Action') ?>:</label>
                                                    <div class="col-md-5">
                                                        <select name="spam_action" class="form-control" data-plugin="select2">
                                                            <option <?php if (Doo::conf()->spam_action == 'NOTIFY') { ?> selected <?php } ?> value="NOTIFY"><?php echo SCTEXT('Cancel campaign with Alert') ?></option>
                                                            <option <?php if (Doo::conf()->spam_action == 'NOTIFY_DEDUCT') { ?> selected <?php } ?> value="NOTIFY_DEDUCT"><?php echo SCTEXT('Hold campaign for Approval') ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <hr>
                                                <div class="form-group">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-8">
                                                        <button class="btn btn-primary save_settings" data-form="msgset_form" type="button"><?php echo SCTEXT('Save Changes') ?></button>
                                                        <button type="button" class="btn btn-default bk"><?php echo SCTEXT('Cancel') ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab-3">
                                            <form class="form-horizontal" id="kanset_form" method="post">
                                                <input type="hidden" name="setcat" value="kannel">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Bearerbox Host') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control input-md" name="bearerbox_host" placeholder="e.g. 127.0.0.1" value="<?php echo Doo::conf()->bearerbox_host ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('enter the server IP where Kannel is installed') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Bearerbox Port') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control input-md" name="admin_port" placeholder="e.g. 14000" value="<?php echo Doo::conf()->admin_port ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('port which occupied BB process') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Send SMS Port') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="sendsms_port" placeholder="e.g. 14014" value="<?php echo Doo::conf()->sendsms_port ?>">

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Admin Password') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="admin_password" placeholder="<?php echo SCTEXT('password for kannel administration tasks like adding SMSC etc.') ?>" value="<?php echo Doo::conf()->admin_password ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Status Password') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="status_password" placeholder="<?php echo SCTEXT('enter password for kannel status page') ?> ..." value="<?php echo Doo::conf()->status_password ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('SendSMS User') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="username" placeholder="e.g. mysmsuser .." value="<?php echo Doo::conf()->username ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('enter username for Kannel Sendsms-User value') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('SendSMS User Password') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="password" placeholder="e.g. mypassword .." value="<?php echo Doo::conf()->password ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('enter password for Kannel Sendsms-User value') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Status Interval') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="kannel_default_refresh" placeholder="e.g. 60" value="<?php echo Doo::conf()->kannel_default_refresh ?>">
                                                            <span class="input-group-addon"><?php echo SCTEXT('seconds') ?></span>
                                                        </div>

                                                        <span class="help-block m-b-0"><?php echo SCTEXT('Time after which the Kannel status page will reload') ?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Kannel directory') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="kannel_dir" placeholder="e.g. /usr/local/kannel/sbin" value="<?php echo Doo::conf()->kannel_dir ?>">

                                                        <span class="help-block m-b-0"><?php echo SCTEXT('absolute path of the installation directory') ?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Kannel Conf path') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="kannel_conf_path" placeholder="e.g. /usr/local/kannel/sbin/kannel.conf" value="<?php echo Doo::conf()->kannel_conf_path ?>">

                                                        <span class="help-block m-b-0"><?php echo SCTEXT('absolute path of the kannel.conf file') ?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('SMSBOX Port') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="smsbox_port" placeholder="e.g. 60" value="<?php echo Doo::conf()->smsbox_port ?>">

                                                        <span class="help-block m-b-0"><?php echo SCTEXT('port running SMSBOX process') ?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Log Directory') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="kannel_log_dir" placeholder="e.g. /var/log/kannel" value="<?php echo Doo::conf()->kannel_log_dir ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Kannel DB Host') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="kannel_dlr_db_host" placeholder="e.g. localhost" value="<?php echo Doo::conf()->kannel_dlr_db_host ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Kannel DB Port') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="kannel_dlr_db_port" placeholder="e.g. 3306" value="<?php echo Doo::conf()->kannel_dlr_db_port ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Kannel DB User') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="kannel_dlr_db_user" placeholder="e.g. root" value="<?php echo Doo::conf()->kannel_dlr_db_user ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Kannel DB Password') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="kannel_dlr_db_password" placeholder="e.g. password" value="<?php echo Doo::conf()->kannel_dlr_db_password ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Kannel DB Name') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="kannel_dlr_db_name" placeholder="e.g. smsapp" value="<?php echo Doo::conf()->kannel_dlr_db_name ?>">
                                                    </div>
                                                </div>


                                                <hr>
                                                <div class="form-group">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-8">
                                                        <button class="btn btn-primary save_settings" data-form="kanset_form" type="button"><?php echo SCTEXT('Save Changes') ?></button>
                                                        <button type="button" class="btn btn-default bk"><?php echo SCTEXT('Cancel') ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab-4">
                                            <form class="form-horizontal" id="resset_form" method="post">
                                                <input type="hidden" name="setcat" value="reseller">

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Default Site status') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="radio radio-primary">
                                                            <input id="weby" <?php if (Doo::conf()->default_website_status == '1') { ?> checked="checked" <?php } ?> value="1" type="radio" name="default_website_status">
                                                            <label for="weby"><?php echo SCTEXT('Active') ?></label>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Newly created reseller accounts will have active website available, they can link their domains and host their content') ?></span>
                                                        </div>
                                                        <div class="radio radio-primary">
                                                            <input id="webn" value="0" type="radio" name="default_website_status" <?php if (Doo::conf()->default_website_status == '0') { ?> checked="checked" <?php } ?>>
                                                            <label for="webn"><?php echo SCTEXT('Disabled') ?></label>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Newly created resellers will get access to front-end website after Admin enables them. This will prevent anyone from pointing DNS to your server without being your customer') ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Default Permissions') ?>:</label>
                                                    <div class="col-md-5">
                                                        <select class="form-control" name="default_permissions" data-plugin="select2">
                                                            <?php foreach ($data['permgroups'] as $pg) { ?>
                                                                <option <?php if ($pg->id == Doo::conf()->default_user_permissions) { ?> selected="selected" <?php } ?> value="<?php echo $pg->id ?>"><?php echo $pg->title ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Show Passwords') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="radio radio-primary">
                                                            <input id="pass_y" <?php if (Doo::conf()->reseller_show_password == 'yes') { ?> checked="checked" <?php } ?> value="1" type="radio" name="reseller_show_password">
                                                            <label for="pass_y"><?php echo SCTEXT('Yes') ?></label>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('This will display passwords of your downline. Your resellers can see passwords of their customers only. Helpful in case anyone forgets password. Less secure, more convenient.') ?></span>
                                                        </div>
                                                        <div class="radio radio-primary">
                                                            <input id="pass_n" value="0" type="radio" name="reseller_show_password" <?php if (Doo::conf()->reseller_show_password == 'no') { ?> checked="checked" <?php } ?>>
                                                            <label for="pass_n"><?php echo SCTEXT('No') ?></label>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Passwords will be hidden. If a customer forgets password, only option is to reset it. More secure, less convenient.') ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php $payments = explode(',', Doo::conf()->allowed_payments); ?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Active Payment Methods') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="m-b-xs m-r-xl">
                                                            <input data-size="small" name="payments[paypal]" type="checkbox" data-switchery data-color="#188ae2" <?php if (in_array('paypal', $payments)) { ?> checked <?php } ?>>
                                                            <label>Paypal (Global)</label>
                                                        </div>
                                                        <div class="m-b-xs m-r-xl">
                                                            <input data-size="small" name="payments[stripe]" type="checkbox" data-switchery data-color="#188ae2" <?php if (in_array('stripe', $payments)) { ?> checked <?php } ?>>
                                                            <label>Stripe (Global)</label>
                                                        </div>
                                                        <?php if (Doo::conf()->paystack_ng_pg == 1) { ?>
                                                            <div class="m-b-xs m-r-xl">
                                                                <input data-size="small" name="payments[paystack]" type="checkbox" data-switchery data-color="#188ae2" <?php if (in_array('paystack', $payments)) { ?> checked <?php } ?>>
                                                                <label>Paystack (Africa)</label>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Allow Reseller Payments') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="radio radio-primary">
                                                            <input id="rpay_y" value="1" type="radio" name="allow_reseller_payment" <?php if (Doo::conf()->reseller_pg == 1) { ?> checked <?php } ?>>
                                                            <label for="rpay_y"><?php echo SCTEXT('Yes') ?></label>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('This will allow resellers to receive payments from active payment gateway. Resellers will use their own credentials and will select payment method of their preference.') ?></span>
                                                        </div>
                                                        <div class="radio radio-primary">
                                                            <input id="rpay_n" value="0" type="radio" name="allow_reseller_payment" <?php if (Doo::conf()->reseller_pg == 0) { ?> checked <?php } ?>>
                                                            <label for="rpay_n"><?php echo SCTEXT('No') ?></label>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Resellers will not be able to accept payments from the clients from this app. All payment transactions for resellers will be off the platform.') ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Allow SMS Purchase') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="radio radio-primary">
                                                            <input id="sbuy_y" <?php if (Doo::conf()->allow_buy_sms == '1') { ?> checked="checked" <?php } ?> value="1" type="radio" name="allow_buy_sms">
                                                            <label for="sbuy_y"><?php echo SCTEXT('Yes') ?></label>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('This will allow customers to buy SMS using Paypal. This will be automated and SMS credits will be allotted to user after successful payment. Set only if you accept Paypal payments.') ?></span>
                                                        </div>
                                                        <div class="radio radio-primary">
                                                            <input id="sbuy_n" <?php if (Doo::conf()->allow_buy_sms == '0') { ?> checked="checked" <?php } ?> value="0" type="radio" name="allow_buy_sms">
                                                            <label for="sbuy_n"><?php echo SCTEXT('No') ?></label>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Customer cannot initiate purchase orders. You will assign SMS credits to your customers upon offline request. Choose this if you do not have Paypal configured here.') ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Apply discounts') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="radio radio-inline radio-primary">
                                                            <input id="idb" name="invoice_discount" <?php if (Doo::conf()->invoice_discount == 'before_taxes') { ?> checked="checked" <?php } ?> type="radio" value="before_taxes">
                                                            <label for="idb"><?php echo SCTEXT('Before Taxes') ?></label>
                                                        </div>
                                                        <div class="radio radio-inline radio-primary">
                                                            <input id="ida" name="invoice_discount" <?php if (Doo::conf()->invoice_discount == 'after_taxes') { ?> checked="checked" <?php } ?> type="radio" value="after_taxes">
                                                            <label for="ida"><?php echo SCTEXT('After Taxes') ?></label>
                                                        </div>

                                                    </div>
                                                </div>


                                                <hr>
                                                <div class="form-group">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-8">
                                                        <button class="btn btn-primary save_settings" data-form="resset_form" type="button"><?php echo SCTEXT('Save Changes') ?></button>
                                                        <button type="button" class="btn btn-default bk"><?php echo SCTEXT('Cancel') ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab-5">
                                            <form class="form-horizontal" id="secset_form" method="post">
                                                <input type="hidden" name="setcat" value="security">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Password Strength') ?>:</label>
                                                    <div class="col-md-5">
                                                        <select onchange="document.getElementById('passcontext').innerHTML = this.options[ this.selectedIndex ].getAttribute('data-desc');" name="password_strength" class="form-control" data-plugin="select2">
                                                            <option data-desc="<?php echo SCTEXT('Password must contain at least one uppercase letter, one special character, one number and must be 8 characters long.') ?>" <?php if (Doo::conf()->password_strength == 'strong') { ?> selected <?php } ?> value="strong"><?php echo SCTEXT('Strong') ?></option>
                                                            <option data-desc="<?php echo SCTEXT('Password should contain at least one alphabet and one numeric value and should be at least 8 characters long.') ?>" <?php if (Doo::conf()->password_strength == 'average') { ?> selected <?php } ?> value="average"><?php echo SCTEXT('Medium') ?></option>
                                                            <option data-desc="<?php echo SCTEXT('Password length should be minimum 6 characters.') ?>" <?php if (Doo::conf()->password_strength == 'weak') { ?> selected <?php } ?> value="weak"><?php echo SCTEXT('Normal') ?></option>
                                                        </select>
                                                        <span id="passcontext" class="help-block text-primary m-b-0">
                                                            <?php echo SCTEXT('Password must contain at least one uppercase letter, one special character, one number and must be 8 characters long.') ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Restrict Domain Login') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="radio radio-primary">
                                                            <input id="dly" <?php if (Doo::conf()->restrict_domain_login == '1') { ?> checked="checked" <?php } ?> value="1" type="radio" name="restrict_domain_login">
                                                            <label for="dly"><?php echo SCTEXT('Yes') ?></label>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Client can only login via their respective resellers website. This might help maintain the confidentiality of supply-chain.') ?></span>
                                                        </div>
                                                        <div class="radio radio-primary">
                                                            <input id="dln" value="0" <?php if (Doo::conf()->restrict_domain_login == '0') { ?> checked="checked" <?php } ?> type="radio" name="restrict_domain_login">
                                                            <label for="dln"><?php echo SCTEXT('No') ?></label>
                                                            <span class="help-block m-b-0"><?php echo SCTEXT('Any client can login from any domain pointed at the server. Login will work globally.') ?></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Large Campaign action') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo SCTEXT('Notify for more than') ?></span>
                                                            <input type="text" class="form-control" name="batch_notify" placeholder="e.g. 500,000" value="<?php echo Doo::conf()->batch_notify ?>">
                                                            <span class="input-group-addon">SMS</span>
                                                        </div>

                                                        <span class="help-block m-b-0"><?php echo SCTEXT('Larger campaigns than above in a single upload will be notified to you') ?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Large Order action') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo SCTEXT('Notify for more than') ?></span>
                                                            <input type="text" class="form-control" name="order_notify" placeholder="e.g. 500,000" value="<?php echo Doo::conf()->order_notify ?>">
                                                            <span class="input-group-addon">SMS</span>
                                                        </div>

                                                        <span class="help-block m-b-0"><?php echo SCTEXT('Larger orders than above will be notified to you') ?> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('CAPTCHA display') ?>:</label>
                                                    <div class="col-md-5">
                                                        <select name="captcha_action" class="form-control" data-plugin="select2">
                                                            <option <?php if (Doo::conf()->captcha_action == '1') { ?> selected <?php } ?> value="1"><?php echo SCTEXT('Always display') ?></option>
                                                            <option <?php if (Doo::conf()->captcha_action == '0') { ?> selected <?php } ?> value="0"><?php echo SCTEXT('After failed Login') ?></option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Admin Login alerts') ?>:</label>
                                                    <div class="col-md-5">
                                                        <select name="admin_login_alert" class="form-control" data-plugin="select2">
                                                            <option <?php if (Doo::conf()->admin_login_alert == '0') { ?> selected <?php } ?> value="0"><?php echo SCTEXT('Never') ?></option>
                                                            <option <?php if (Doo::conf()->admin_login_alert == '1') { ?> selected <?php } ?> value="1"><?php echo SCTEXT('At every login') ?></option>
                                                            <option <?php if (Doo::conf()->admin_login_alert == '2') { ?> selected <?php } ?> value="2"><?php echo SCTEXT('Login from a new IP/Platform') ?></option>

                                                        </select>

                                                    </div>
                                                </div>


                                                <hr>
                                                <div class="form-group">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-8">
                                                        <button class="btn btn-primary save_settings" data-form="secset_form" type="button"><?php echo SCTEXT('Save Changes') ?></button>
                                                        <button type="button" class="btn btn-default bk"><?php echo SCTEXT('Cancel') ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div role="tabpanel" class="tab-pane fade" id="tab-6">
                                            <h5 class="m-b-lg"><?php echo SCTEXT('For Date Formats supported by PHP, refer to format information') ?> <b><a target="_blank" href="http://php.net/manual/en/function.date.php#refsect1-function.date-parameters"><?php echo SCTEXT('here') ?><i class="fa fa-external-link-alt m-l-xs"></i></a></b></h5>
                                            <form class="form-horizontal" id="miscset_form" method="post">
                                                <input type="hidden" name="setcat" value="misc">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Database date format') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control datefrmt" name="date_format_db" placeholder="<?php echo SCTEXT('enter a valid PHP date format') ?> ..." value="<?php echo Doo::conf()->date_format_db ?>">
                                                        <span class="help-block text-primary m-b-0"><?php echo SCTEXT('date will be shown as') . ': <b>' . date(Doo::conf()->date_format_db) . '</b>'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Long date format') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control datefrmt" name="date_format_long" placeholder="<?php echo SCTEXT('enter a valid PHP date format') ?> ..." value="<?php echo Doo::conf()->date_format_long ?>">
                                                        <span class="help-block text-primary m-b-0"><?php echo SCTEXT('date will be shown as') . ': <b>' . date(Doo::conf()->date_format_long) . '</b>'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Long date with Time') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control datefrmt" name="date_format_long_time" placeholder="<?php echo SCTEXT('enter a valid PHP date format') ?> ..." value="<?php echo Doo::conf()->date_format_long_time ?>">
                                                        <span class="help-block text-primary m-b-0"><?php echo SCTEXT('date will be shown as') . ': <b>' . date(Doo::conf()->date_format_long_time) . '</b>'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Long date/Time with seconds') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control datefrmt" name="date_format_long_time_s" placeholder="<?php echo SCTEXT('enter a valid PHP date format') ?> ..." value="<?php echo Doo::conf()->date_format_long_time_s ?>">
                                                        <span class="help-block text-primary m-b-0"><?php echo SCTEXT('date will be shown as') . ': <b>' . date(Doo::conf()->date_format_long_time_s) . '</b>'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Medium date format') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control datefrmt" name="date_format_med" placeholder="<?php echo SCTEXT('enter a valid PHP date format') ?> ..." value="<?php echo Doo::conf()->date_format_med ?>">
                                                        <span class="help-block text-primary m-b-0"><?php echo SCTEXT('date will be shown as') . ': <b>' . date(Doo::conf()->date_format_med) . '</b>'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Medium date with Time') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control datefrmt" name="date_format_med_time" placeholder="<?php echo SCTEXT('enter a valid PHP date format') ?> ..." value="<?php echo Doo::conf()->date_format_med_time ?>">
                                                        <span class="help-block text-primary m-b-0"><?php echo SCTEXT('date will be shown as') . ': <b>' . date(Doo::conf()->date_format_med_time) . '</b>'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Medium date/time with seconds') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control datefrmt" name="date_format_med_time_s" placeholder="<?php echo SCTEXT('enter a valid PHP date format') ?> ..." value="<?php echo Doo::conf()->date_format_med_time_s ?>">
                                                        <span class="help-block text-primary m-b-0"><?php echo SCTEXT('date will be shown as') . ': <b>' . date(Doo::conf()->date_format_med_time_s) . '</b>'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Short date format') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control datefrmt" name="date_format_short" placeholder="<?php echo SCTEXT('enter a valid PHP date format') ?> ..." value="<?php echo Doo::conf()->date_format_short ?>">
                                                        <span class="help-block text-primary m-b-0"><?php echo SCTEXT('date will be shown as') . '`: <b>' . date(Doo::conf()->date_format_short) . '</b>'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Short date with Time') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control datefrmt" name="date_format_short_time" placeholder="<?php echo SCTEXT('enter a valid PHP date format') ?> ..." value="<?php echo Doo::conf()->date_format_short_time ?>">
                                                        <span class="help-block text-primary m-b-0"><?php echo SCTEXT('date will be shown as') . ': <b>' . date(Doo::conf()->date_format_short_time) . '</b>'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Short date/time with seconds') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control datefrmt" name="date_format_short_time_s" placeholder="<?php echo SCTEXT('enter a valid PHP date format') ?> ..." value="<?php echo Doo::conf()->date_format_short_time_s ?>">
                                                        <span class="help-block text-primary m-b-0"><?php echo SCTEXT('date will be shown as') . ': <b>' . date(Doo::conf()->date_format_short_time_s) . '</b>'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Global Upload Directory') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="global_upload_dir" placeholder="<?php echo SCTEXT('enter absolute path') ?> ..." value="<?php echo Doo::conf()->global_upload_dir ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('strongly advise to provide path outside web root for security reasons') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Global Export Directory') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo '..' . Doo::conf()->SUBFOLDER ?></span>
                                                            <input type="text" class="form-control" name="global_export_dir" placeholder="e.g. exportDir/" value="<?php echo Doo::conf()->global_export_dir ?>">

                                                        </div>
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('enter path relative to web root with trailing slash') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Image Upload Directory') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="image_upload_dir" placeholder="<?php echo SCTEXT('enter absolute path') ?> ..." value="<?php echo Doo::conf()->image_upload_dir ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('complete absolute path of directory where images will be stored') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Image URL prefix') ?>:</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo Doo::conf()->APP_URL ?></span>
                                                            <input type="text" class="form-control" name="image_upload_url" placeholder="e.g. global/images/" value="<?php echo Doo::conf()->image_upload_url ?>">

                                                        </div>
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('enter valid URI path with trailing slash') ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"><?php echo SCTEXT('Languages Directory') ?>:</label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="language_dir" placeholder="<?php echo SCTEXT('enter absolute path') ?> ..." value="<?php echo Doo::conf()->language_dir ?>">
                                                        <span class="help-block m-b-0"><?php echo SCTEXT('complete absolute path of directory where language files will be stored') ?></span>
                                                    </div>
                                                </div>

                                                <hr>
                                                <div class="form-group">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-8">
                                                        <button class="btn btn-primary save_settings" data-form="miscset_form" type="button"><?php echo SCTEXT('Save Changes') ?></button>
                                                        <button type="button" class="btn btn-default bk"><?php echo SCTEXT('Cancel') ?></button>
                                                    </div>
                                                </div>
                                            </form>
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