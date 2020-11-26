<?
$v_id = zeeba_get( 'step_2.rate_data.class_code' );
global $vehicle;
$vehicle = get_posts( [
	'post_type'      => 'vehicle',
	'posts_per_page' => 1,
	'meta_query'     => [
		'relation' => 'OR',
		[
			'key'     => 'sys_class_code',
			'value'   => $v_id,
			'compare' => 'LIKE',
		]
	],
] )[0];

// $bill = zeeba_field( 'bill', false );
$pl = zeeba_get( 'step_1.pickup_location' );
$dl = zeeba_get( 'step_1.dropoff_location' );
$locations = get_posts( [
	'post_type' => 'location',
	'posts_per_page' => -1,
] );
$pl_post = $dl_post = false;
foreach($locations as $location) {
	$code = gf('sys_trn_id', $location);
	if($code == $pl) {
		$pl_post = $location;
	}
	if($code == $dl) {
		$dl_post = $location;
	}
	if($pl_post !== false && $dl_post !== false) {
		break;
	}
}

$shuttle = gf('phone_shuttle', $pl_post);
if(empty($shuttle)) {
	$shuttle = '(866) 531-1599';
}
$dl_phone = gf('phone', $dl_post);
if(empty($dl_phone)) {
	$dl_phone = '(800) 940-9332';
}
?>


<html>
<body>
	<?/*
<b>Your Zeebavans Reservation</b><br>
<br>
Hey fellow van fan! Here's the reservation you placed from zeebavans.com.<br>
<br>
*/?>
<center>
<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
<tbody>
<tr>
<td align="center" valign="top" id="bodyCell">
<!-- BEGIN TEMPLATE // -->
<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer" style="border-top:0 !important;">
    <tbody>
        <!-- static info -->
        <tr>
            <td align="center" valign="top">
            <!-- BEGIN PREHEADER // -->
            <table border="0" cellpadding="0" cellspacing="0" width="600" id="templatePreheader">
            <tbody>
                <tr>
            	<td valign="top" class="preheaderContainer" style="padding-top:9px; padding-bottom:9px">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                        <tbody class="mcnTextBlockOuter">
                            <tr>
                                <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                                  	<!--[if mso]>
                    				<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                    				<tr>
                    				<td valign="top" width="390" style="width:390px;">
                    				<![endif]-->
                                    <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:390px;" width="100%" class="mcnTextContentContainer">
                                        <tbody>
                                            <tr>
                                                <td valign="top" class="mcnTextContent" style="padding-top:0; padding-left:18px; padding-bottom:9px; padding-right:18px;">
                                                    Your Reservation is Confirmed!
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                    				<!--[if mso]>
                    				</td>
                    				</tr>
                    				</table>
                    				<![endif]-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                </tr>
            </tbody>
            </table>
            <!-- // END PREHEADER -->
            </td>
        </tr>
        <!-- static info logo -->
        <tr>
            <td align="center" valign="top">
                <!-- BEGIN HEADER // -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader">
                    <tbody>
                        <tr>
                        <td valign="top" class="headerContainer">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                <tbody class="mcnImageBlockOuter">
                                <tr>
                                    <td valign="top" style="padding:0px" class="mcnImageBlockInner">
                                        <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                            <tbody>
                                                <tr>
                                                <td class="mcnImageContent" valign="top" style="padding-right: 0px; padding-left: 0px; padding-top: 0; padding-bottom: 0; text-align:center;">
                                                    <img align="center" alt="" src="https://gallery.mailchimp.com/a64098b4fca7225e612a155f0/images/a3227a8f-740d-4ba3-a1a2-bbf80edb2f23.jpg" width="362" style="max-width:362px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage">
                                                </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        </tr>
                    </tbody>
                </table>
                <!-- // END HEADER -->
            </td>
        </tr>
        <!-- static info -->
		<tr>
            <td align="center" valign="top">
                <!-- BEGIN UPPER BODY // -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateUpperBody">
                    <tbody>
                        <tr>
                        <td valign="top" class="upperBodyContainer">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                            <tbody class="mcnTextBlockOuter">
                                <tr>
                                    <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                                      	<!--[if mso]>
                        				<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                        				<tr>
                        				<![endif]-->
                        			    
                        				<!--[if mso]>
                        				<td valign="top" width="600" style="width:600px;">
                        				<![endif]-->
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                                            <tbody>
                                                <tr>
                                                <td valign="top" class="mcnTextContent" style="padding: 0px 18px 9px; text-align: center;"><strong>
                                                
                                                    </strong>Your reservation <?= zeeba_form('id') ?> is confirmed. Below are the details of your reservation.<br>Questions about your reservation? Please call 1-800-940-9332
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                        				<!--[if mso]>
                        				</td>
                        				<![endif]-->
                                        
                        				<!--[if mso]>
                        				</tr>
                        				</table>
                        				<![endif]-->
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </td>
                        </tr>
                    </tbody>
                </table>
                <!-- // END UPPER BODY -->
            </td>
        </tr>
        <!-- variable rent info  -->
        <tr>
            <td align="center" valign="top">
                <!-- BEGIN COLUMNS // -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateColumns">
                    <tbody>
                        <tr>
                            <td align="left" valign="top" width="50%" class="columnsContainer">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateColumn">
                                    <tbody>
                                        <tr>
                                        <td valign="top" class="leftColumnContainer">
                                            <p>Hi <?= $form['first_name'] ?> <?= $form['last_name'] ?></p>
                                            <p>Pick Up Location: <?= zeeba_get( 'step_1.pickup_location_text' ) ?> <?= zeeba_get( 'step_1.pickup_address' ) ?><br></p>
                                            <p>Pick Up Date: <?= zeeba_get( 'step_1.pickup_date' ) ?><br></p>
                                            <p>Drop Off Location: <?= zeeba_get( 'step_1.dropoff_location_text' ) ?> <?= zeeba_get( 'step_1.dropoff_address' ) ?><br></p>
                                            <p>Drop Off Date: <?= zeeba_get( 'step_1.dropoff_date' ) ?><br></p>
                                            <p>Vehicle Preference: <?= get_the_title( $vehicle ) ?><br></p>
                                            <p>Confirmation Number: <?= zeeba_get( 'step_4.reservation' ) ?><br></p>
                                        </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td align="left" valign="top" width="50%" class="columnsContainer">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateColumn">
                                    <tbody>
                                        <tr>
                                            <td valign="top" class="rightColumnContainer"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- // END COLUMNS -->
            </td>
        </tr>
        <!-- static info and $shuttle, $dl_phone, social-->
        <tr>
            <td align="center" valign="top">
            <!-- BEGIN LOWER BODY // -->
            <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateLowerBody">
            <tbody>
            <tr>
            <td valign="top" class="lowerBodyContainer">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                    <tbody class="mcnTextBlockOuter">
                    <tr>
                    <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                      	<!--[if mso]>
        				<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
        				<tr>
        				<td valign="top" width="600" style="width:600px;">
        				<![endif]-->
                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                            <tbody>
                                <tr>
                                <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                                    <p style="text-align: left;"><span style="color:#FF0000"><strong>1)</strong>&nbsp;<strong>What to Bring:</strong></span><strong>&nbsp;</strong>All renters must present a valid U.S. issued Driver's License, credit card/debit card, and proof of insurance ID in the renters name at the time of pick up.</p>

                                    <p style="text-align: center;"><strong>If someone else if picking up the rental on your behalf, please complete the&nbsp;<a href="http://payments.zeebavans.com/">Payment Authorization Form</a>.</strong></p>

                                    <p style="text-align: left;"><strong><span style="color:#FF0000">2)&nbsp;Pick up/Drop off:</span>&nbsp;</strong>Van rental pick up and drop off's are by appointment only, as our staff is often out performing deliveries and scheduled maintenance. In order to ensure a smooth rental, please arrive at your scheduled time as indicated on your reservation.</p>

                                    <p style="text-align: left;"><strong><span style="color:#FF0000">3)&nbsp;Airport Shuttle:</span>&nbsp;</strong>If you are flying in to the airport, please call&nbsp;<a><?= $shuttle ?></a>&nbsp;to coordinate shuttle/delivery.</p>

                                    <p style="text-align: left;"><strong><span style="color:#FF0000">4)&nbsp;After hour drop-off:</span>&nbsp;</strong>If you plan to return your rental after our hours of operation (8am - 10pm everyday), please call us at <?= $dl_phone ?> for instructions.</p>

                                    <p style="text-align: left;"><span style="color:#FF0000"><strong>5)&nbsp;Cancellation Policy:</strong></span>&nbsp;All cancellations are subject to a $35 fee. Cancellations within 72 hours of your pick-up date are not permitted and are non-refundable. All cancellations will be followed with an email confirmation.</p>
                                </td>
                                </tr>
                            </tbody>
                        </table>
        				<!--[if mso]>
        				</td>
        				</tr>
        				</table>
        				<![endif]-->
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowBlock" style="min-width:100%;">
                    <tbody class="mcnFollowBlockOuter">
                    <tr>
                        <td align="center" valign="top" style="padding:9px" class="mcnFollowBlockInner">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentContainer" style="min-width:100%;">
                        <tbody>
                        <tr>
                        <td align="center" style="padding-left:9px;padding-right:9px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnFollowContent">
                            <tbody>
                            <tr>
                            <td align="center" valign="top" style="padding-top:9px; padding-right:9px; padding-left:9px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                <td align="center" valign="top">
                                <!--[if mso]>
                                <table align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" valign="top">
                                    <![endif]-->
                                    <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                        <tbody>
                                        <tr>
                                            <td valign="top" style="padding-right:10px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                    <tbody><tr>
                                                        <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                <tbody><tr>
                                                                        <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                            <a href="http://www.facebook.com/zeebavans" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-facebook-48.png" style="display:block;" height="24" width="24" class=""></a>
                                                                        </td>
                                                                </tr>
                                                            </tbody></table>
                                                        </td>
                                                    </tr>
                                                </tbody></table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!--[if mso]>
                                    </td>
                                    <td align="center" valign="top">
                                    <![endif]-->
                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                                <tbody><tr>
                                                    <td valign="top" style="padding-right:10px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                            <tbody><tr>
                                                                <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                        <tbody><tr>
                                                                                <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                                    <a href="http://www.twitter.com/zeebavans" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-twitter-48.png" style="display:block;" height="24" width="24" class=""></a>
                                                                                </td>
                                                                        </tr>
                                                                    </tbody></table>
                                                                </td>
                                                            </tr>
                                                        </tbody></table>
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                        <!--[if mso]>
                                        </td>
                                        <td align="center" valign="top">
                                        <![endif]-->
                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                                <tbody><tr>
                                                    <td valign="top" style="padding-right:10px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                            <tbody><tr>
                                                                <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                        <tbody><tr>
                                                                                <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                                    <a href="http://instagram.com/zeebavans" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-instagram-48.png" style="display:block;" height="24" width="24" class=""></a>
                                                                                </td>
                                                                        </tr>
                                                                    </tbody></table>
                                                                </td>
                                                            </tr>
                                                        </tbody></table>
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                        <!--[if mso]>
                                        </td>
                                        <td align="center" valign="top">
                                        <![endif]-->
                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                                <tbody><tr>
                                                    <td valign="top" style="padding-right:0; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                            <tbody><tr>
                                                                <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                        <tbody><tr>
                                                                                <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                                    <a href="http://www.zeebavans.com" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-link-48.png" style="display:block;" height="24" width="24" class=""></a>
                                                                                </td>
                                                                        </tr>
                                                                    </tbody></table>
                                                                </td>
                                                            </tr>
                                                        </tbody></table>
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                        
                                        <!--[if mso]>
                                        </td>
                                    </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                                </tr>
                                </tbody>
                            </table>
                            </td>
                            </tr>
                            </tbody>
                        </table>
                        </td>
                        </tr>
                        </tbody>
                        </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
                    <tbody class="mcnDividerBlockOuter">
                        <tr>
                            <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 9px 18px 18px;">
                                <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%; border-top: 0px;">
                                    <tbody>
                                        <tr>
                                        <td>
                                            <span></span>
                                        </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--            
                                <td class="mcnDividerBlockInner" style="padding: 18px;">
                                <hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" />
                                -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            </tr>
            </tbody>
            </table>
            <!-- // END LOWER BODY -->
            </td>
        </tr>
        <!-- static info footer-->
        <tr>
            <td align="center" valign="top">
                <!-- BEGIN FOOTER // -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateFooter">
                    <tbody>
                        <tr>
                        <td valign="top" class="footerContainer" style="padding-top:9px; padding-bottom:9px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                            <tbody class="mcnTextBlockOuter">
                                <tr>
                                    <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                                      	<!--[if mso]>
                        				<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                        				<tr>
                        				<td valign="top" width="600" style="width:600px;">
                        				<![endif]-->
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                                            <tbody>
                                                <tr>
                                                <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                                                    <div style="text-align: center;">Copyright © Zeeba Rent-A-Van. All Rights Reserved.</div>
                                                    <div style="text-align: center;"><strong>Our mailing address is:</strong></div>
                                                    <div style="text-align: center;">3200 Wilshire Blvd. ST 1000 | Los Angeles, CA 90010</div>
                                                </td>
                                                </tr>
                                            </tbody>
                                        </table>
                        				<!--[if mso]>
                        				</td>
                        				</tr>
                        				</table>
                        				<![endif]-->
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </td>
                        </tr>
                    </tbody>
                </table>
                <!-- // END FOOTER -->
            </td>
        </tr>
    </tbody>
</table>
<!-- // END TEMPLATE -->
</td>
</tr>
</tbody>
</table>
</center>

</body>
</html>