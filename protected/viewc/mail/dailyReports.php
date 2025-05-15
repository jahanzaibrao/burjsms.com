<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0">    <!-- So that mobile webkit will display zoomed in -->
    <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->

    <title>Daily Campaign Summary</title>
    <style type="text/css">

        /* Resets: see reset.css for details */
        .ReadMsgBody { width: 100%; background-color: #ebebeb;}
        .ExternalClass {width: 100%; background-color: #ebebeb;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
        body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}
        body {margin:0; padding:0;}
        table {border-spacing:0;}
        table td {border-collapse:collapse;}
        .yshortcuts a {border-bottom: none !important;}


        /* Constrain email width for small screens */
        @media screen and (max-width: 600px) {
            table[class="container"] {
                width: 95% !important;
            }
        }

        /* Give content more room on mobile */
        @media screen and (max-width: 480px) {
            td[class="container-padding"] {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
         }
    </style>
</head>
<body style="margin:0; padding:10px 0;" bgcolor="#ebebeb" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<br>

<!-- 100% wrapper (grey background) -->
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#ebebeb">
  <tr>
    <td align="center" valign="top" bgcolor="#ebebeb" style="background-color: #ebebeb;">

      <!-- 600px container (white background) -->
      <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" bgcolor="#ffffff">
        <tr>
          <td class="container-padding" bgcolor="#ffffff" style="background-color: #ffffff; padding-left: 30px; padding-right: 30px; font-size: 14px; line-height: 20px; font-family: Helvetica, sans-serif; color: #333;">
            <br>
			<div  style="border-bottom:#003 solid 1px;">
            <a href="<?php echo $data['company_url'] ?>"><img style="max-height:125px;" alt="<?php echo $data['company_name'] ?>" src="<?php echo $data['logo'] ?>" border="0" /></a>
            </div><br>
            <!-- ### BEGIN CONTENT ### -->
            <div style="font-weight: bold; font-size: 18px; line-height: 24px; color: #D03C0F">
            Dear <?php echo $data['name']; ?>,
            </div><br>This is your campaign summary for today:<br><br>

			<table style="font-size:14px;line-height: 20px;border-right:1px solid #ccc;" width="60%" border="0">
  <tr>
    <td style="background-color: #188ae2;color:#ffffff;padding: 6px 10px;">Total SMS Sent</td>
    <td style="padding: 6px 10px;border-bottom:1px solid #ccc;border-top:1px solid #ccc;"><?php echo number_format(intval($data['total_sms'])) ?></td>
  </tr>
                <tr>
    <td style="background-color: #10c469;color:#ffffff;padding: 6px 10px;">SMS Delivered</td>
    <td style="padding: 6px 10px;border-bottom:1px solid #ccc;"><?php echo number_format(intval($data['total_del'])) ?></td>
  </tr>
                <tr>
    <td style="background-color: #ff5b5b;color:#ffffff;padding: 6px 10px;">Failed SMS</td>
    <td style="padding: 6px 10px;border-bottom:1px solid #ccc;"><?php echo number_format(intval($data['total_fail'])) ?></td>
  </tr>
                <tr>
    <td style="background-color: #35b8e0;color:#ffffff;padding: 6px 10px;">Credits Used</td>
    <td style="padding: 6px 10px;border-bottom:1px solid #ccc;"><?php echo number_format(intval($data['credits_used'])) ?></td>
  </tr>
                <tr>
    <td style="background-color: #ff8ecc;color:#ffffff;padding: 6px 10px;">Credits Refunded</td>
    <td style="padding: 6px 10px;border-bottom:1px solid #ccc;"><?php echo number_format(intval($data['credits_refunded'])) ?></td>
  </tr>
  
</table>
            <br>
			<br>

            <!-- ### END CONTENT ### -->
			<strong>Warm Regards</strong>,<br>

			<strong><?php echo $data['company_name'] ?> Support Team</strong><br>
            <?php if($data['helpline']!=''){ ?>Helpline:  <?php echo $data['helpline'].'<br>'; } ?>
			<a href="<?php echo $data['company_url'] ?>">(<?php echo $data['company_domain'] ?>)</a>
               <footer style="font-size:12px;color:#585858;">
        <hr>
                   To Unsubscribe, please go to your account settings and disable Daily Email Reports.
                   <hr>
    </footer>
          </td>
        </tr>
      </table>
      <!--/600px container -->

    </td>
  </tr>
</table>
   
<!--/100% wrapper-->
<br>
<br>
</body>
</html>

