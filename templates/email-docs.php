<?
/**
 * @var array $docs
 */
$v_id = zeeba_field( 'rate_data', false )['class_code'];
global $vehicle;
$vehicle = get_posts( [
	'post_type'      => 'vehicle',
	'posts_per_page' => 1,
	'meta_query'     => [
		'relation' => 'OR',
		[
			'key'     => 'sys_class_code',
			'value'   => $v_id,
			'compare' => '=',
		]
	],
] )[0];
// $bill    = zeeba_field( 'bill', false );
$charity = zeeba_get('step_3.charity_bill');
?>


<html>
<body>
<b>Zeebavans Reservation</b><br>
<br>

<table>
    <thead>
    <tr>
        <th colspan="2"><?php echo get_option('step4_ri_heading'); ?></th>
    </tr>
    </thead>
    <tbody>
	<? foreach ( $form as $key=>$el ): ?>
		<? //if ( ! in_array( $key, [ 'card_name', 'card_number', 'card_exp', 'card_type', 'card_cvv','card_month', 'card_year' ] ) ): 
        if ( in_array( $key, [ 'first_name', 'last_name', 'email', 'phone_number', 'country','address', 'zip', 'city', 'state' ] ) ): 

        ?>
            <tr>
                <td><?= $key == 'zip' ? 'Postal Code':$key ?></td>
                <td><?= $el ?></td>
            </tr>
		<? endif; ?>
	<? endforeach; ?>
    <tr>
        <td>Documents</td>
        <td>
			<? foreach ( $docs as $doc ): ?>
                <a href="<?= $doc ?>" target="_blank"><?= $doc ?></a><br/>
			<? endforeach; ?>
        </td>
    </tr>
    </tbody>
</table>
<br><br>
<b><?= get_option('rates_heading') ?><span style="color:red;">*</span></b><br>
<table>
    <thead>
    <tr>
        <th><? echo get_option('rates_col_1'); ?></th>
        <th><? echo get_option('rates_col_2'); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Rental Rate
            <div><?= $bill['days'] ?> day(s) @ $<?= number_format( $bill['rate'], 2, '.', "&nbsp;" ) ?></div>
        </td>
        <td>$<?= number_format( $bill['charge'], 2, '.', "&nbsp;" ) ?></td>
    </tr>
	<? foreach ( $bill['extra'] as $extra ): ?>
        <tr>
            <td><?= $extra['desc'] ?></td>
            <td><?= $extra['amount'] == 0 ? 'FREE' : '$' . number_format( $extra['amount'], 2, '.', "&nbsp;" ) ?></td>
        </tr>
	<? endforeach; ?>
    <tr>
        <td>Subtotal</td>
        <td>$<?= number_format( $bill['subtotal'], 2, '.', "&nbsp;" ) ?></td>
    </tr>
    <tr>
        <td>
            Taxes and Fees
			<? foreach ( $bill['taxes'] as $tax ): ?>
                <div><?= $tax['desc'] . ( $tax['type'] == 'PERCENT' ? ' - ' . ( $tax['rate'] * 100 ) . '%' : '' ) ?></div>
			<? endforeach; ?>
        </td>
        <td>
			<? foreach ( $bill['taxes'] as $tax ): ?>
                <div>$<?= number_format( $tax['charge'], 2, '.', "&nbsp;" ) ?></div>
			<? endforeach; ?>
        </td>
    </tr>
    <tr>
        <td>Mileage</td>
        <td><?= $bill['days'] == 1 ? '250 miles per day' : 'unlimited' ?></td>
    </tr>
    <?php if($charity > 0.00){ ?>
        <tr>
            <td>Donation</td>
            <td>$<?= number_format( $charity, 2, '.', ',' ) ?></td>
        </tr>
        <tr>
            <td>Total</td>
            <td>$<?= number_format( $bill['total']+$charity, 2, '.', ',' ) ?></td>
        </tr>
    <? }else{ ?>
        <tr>
            <td>Total</td>
            <td>$<?= number_format( $bill['total'], 2, '.', ',' ) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<br>
<i style="color:red;">*<? echo get_option('rates_warning'); ?></i>
</body>
</html>