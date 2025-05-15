 <div class="wrap p-t-0">

 </div>
 </main>

 <script src="<?php echo Doo::conf()->APP_URL ?>global/js/Google/jsapi.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/js/core.min.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/moment/moment.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/moment/moment-timezone.min.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
 <script>
     $(document).ready(function() {
         //set timezone
         moment.tz.setDefault('<?php echo Doo::conf()->default_server_timezone ?>');
     })
 </script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/bower/fullcalendar/dist/fullcalendar.min.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/js/fullcalendar.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/js/bootbox.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/js/date.js"></script>
 <?php if ($data['current_page'] == 'usms_log' || $data['current_page'] == 'stats') { ?>
     <script src="<?php echo Doo::conf()->APP_URL ?>global/js/search_datepicker.js"></script>
 <?php } else { ?>
     <script src="<?php echo Doo::conf()->APP_URL ?>global/js/daterangepicker.js"></script>
 <?php } ?>
 <?php if ($data['current_page'] == 'stats') { ?>
     <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/misc/echarts/build/dist/echarts-all.js"></script>
     <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/misc/echarts/build/dist/macarons.js"></script>
 <?php } ?>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/assets/js/app.min.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/jquery.sparkline.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/misc/flot/jquery.flot.min.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/misc/flot/jquery.flot.pie.min.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/misc/flot/jquery.flot.stack.min.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/misc/flot/jquery.flot.resize.min.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/misc/flot/jquery.flot.tooltip.min.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/skin/libs/misc/flot/jquery.flot.categories.min.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/js/printThis.js"></script>
 <script src="<?php echo Doo::conf()->APP_URL ?>global/js/pagesFull.js"></script>
 </body>

 </html>
 <script>
     $(document).ready(function() {
         //update activity
         $.ajax({
             type: 'post',
             url: app_url + 'updateActivity'
         });
         //load translation if not loaded or if language is changed

         if (!localStorage.getItem('home') || localStorage.getItem('app_lang') != app_lang) {
             loadScText();
         }
     });
 </script>

 <!-- Localized -->