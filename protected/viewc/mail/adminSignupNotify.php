<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0">    <!-- So that mobile webkit will display zoomed in -->
    <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->

    <title>New Signup Alert</title>
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
            Hello,
            </div><br>A new Signup occurred on the website. Here are the details:<br><br>

			<table width="200" border="0">
  <tr>
    <td><strong>Name</strong></td>
    <td><?php echo $data['name'] ?></td>
  </tr>
  <tr>
    <td><strong>Email</strong></td>
    <td><?php echo $data['email'] ?></td>
  </tr>
  <tr>
    <td><strong>Login ID</strong></td>
    <td><?php echo $data['loginid'] ?></td>
  </tr>
  
</table><br>
<a href="<?php echo $data['login_url'] ?>" style="background-color: #D03C0F;color: #FFFFFF;display: block;padding: 10px;text-decoration: none;width: 130px;" target="_blank">Login to the App</a><em style="font-style:italic; font-size: 12px; color: #aaa;">or visit here: <?php echo $data['company_url'] ?></em>
            <br>
			<br>

            <br><br>
            <!-- ### END CONTENT ### -->
			<strong>Warm Regards</strong>,<br><br>

			<strong><?php echo $data['company_name'] ?> Support Team</strong><br>
            <?php if($data['helpline']!=''){ ?>Helpline:  <?php echo $data['helpline'].'<br>'; } ?>
			<a href="<?php echo $data['company_url'] ?>">(<?php echo $data['company_domain'] ?>)</a>

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

