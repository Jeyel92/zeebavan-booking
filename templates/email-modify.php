<?
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

$bill = zeeba_field( 'bill', false );
?>


<html>
<body>
<b>Zeebavans Reservation Modification</b><br>
<br>
<? $page = 598; ?>
<table>
    <thead>
    <tr>
        <th colspan="2"><? tf( 'rd_heading', $page ); ?></th>
    </tr>
    </thead>
    <tbody>
	<? foreach ( gf( 'rd_table', $page ) as $el ): ?>
		<? if ( ! in_array( $el['value'], [ 'card_name', 'card_number', 'card_exp', 'card_type' ] ) ): ?>
            <tr>
                <td><?= $el['name'] ?></td>
                <td><?= zeeba_form( $el['value'] ) ?></td>
            </tr>
		<? endif; ?>
	<? endforeach; ?>
    </tbody>
</table>
<br><br>
<b><? tf( 'rq_heading', $page ); ?><span style="color:red;">*</span></b><br>
<table>
    <thead>
    <tr>
        <th><? tf( 'rq_col1', $page ); ?></th>
        <th><? tf( 'rq_col2', $page ); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Rental Rate
            <div><?= zeeba_book()->period() ?> day(s) @ $<?= number_format( $bill['rate'], 2, '.', "&nbsp;" ) ?></div>
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
    <tr>
        <td>Total</td>
        <td>$<?= number_format( $bill['total'], 2, '.', ' ' ) ?></td>
    </tr>
    </tbody>
</table>
<br>
<i style="color:red;">*<? tf( 'warning', $page ); ?></i>
<br><br>
If you have any questions, give us a call at 1 (800) 940-9332 - we'll point you in the right direction.<br>
<br>
-The Zeebavans Team
</body>
</html>