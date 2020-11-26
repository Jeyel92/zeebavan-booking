<?
/**
 * @var array $bill
 */
$v_id    = zeeba_get( 'step_2.rate_data.class_code' );
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
$mileage = [
	'lt' => gf('mileage_lt', $vehicle),
	'gt' => gf('mileage_gt', $vehicle),
];
$rate_mileage = $bill['days'] == 1 ? $mileage['lt'] : $mileage['gt'];


$extras_list = [];
$extras_excluded = [];
$extras_exists = [];
foreach($bill['extra'] as $extra) {
	$extras_exists[$extra['id']] = $extra['amount'];
}
$i = 0;
$bundles = get_option('bundle_code');
foreach($bundles as $bundle) {
	$i++;
	$codes = explode(' ', $bundle);
	$found = true;
	$found_excluded = 0;
	$sum = 0;
	foreach($codes as $code) {
		if(!array_key_exists($code, $extras_exists)) {
			$found = false;
			break;
		}
		if(in_array($code, $extras_excluded)) {
			$found_excluded++;
		}
		$sum += $extras_exists[$code];
	}
	
	if($found && $found_excluded < count($codes)) {
		$extras_list[] = [
			'id' => 'BUNDLE'.$i,
			'desc' => get_option('bundle_name')[$i],
			'amount' => $sum,
		];
		$extras_excluded = array_merge($extras_excluded, $codes);
	}
}
$extras_list += $bill['extra'];
$extras_list = array_filter($extras_list, function ($el) use ($extras_excluded) {
	return !in_array($el['id'], $extras_excluded);
});
$renames_raw = get_option('renaming_options_code');
$renames = []; ?>
<?php
foreach($renames_raw as $key=>$rename) {
	$renames[strtoupper($rename)] = get_option('renaming_options_text')[$key];
}
?>
<tr>
    <td>Rental Rate
        <div class="sub"><?= $bill['days'] ?> day(s) @
            $<?= number_format( $bill['rate'], 2, '.', "," ) ?></div>
    </td>
    <td>$<?= number_format( $bill['charge'], 2, '.', "," ) ?></td>
	
</tr>

<?php
$adminFeesDisplayed = 1;
foreach ($extras_list as $key => $value) {
	if ($value['id']=='ADF') {
		$adminFeesDisplayed = 2;
	}
}
if ($adminFeesDisplayed!=2) {
	?>
	<tr>
		<td>Admin Fee</td>
		<td>$9.00</td>
	</tr>
	<?php
}

if(is_array($extras_list) && count($extras_list) < 2){ 
	foreach ( $extras_list as $extra ): ?>
		<tr>
			<td><?= array_key_exists($extra['id'], $renames) ? $renames[$extra['id']] : $extra['desc'] ?></td>
			<td><?= $extra['amount'] == 0 ? 'FREE' : '$' . number_format( $extra['amount'], 2, '.', "," ) ?></td>
		</tr>
	<? endforeach; 
}else if(is_array($extras_list) && count($extras_list) > 1){ 
		$amount_t = 0;
		foreach ( $extras_list as $extra ){
			$amount_t = $amount_t+(int)$extra['amount'];
		} ?>
		<tr>
			<td><?= is_array($bundles) ? $bundles[0]['name'] : $bundles[0]['name'] ?></td>
			<td><?= $amount_t == 0 ? 'FREE' : '$' . number_format( $amount_t, 2, '.', "," ) ?></td>
		</tr>
<? } ?>
    

<tr id="rate-subtotal">
    <td>Subtotal</td>
    <td>$<?= number_format( $bill['subtotal'], 2, '.', "," ) ?></td>
</tr>
<tr>
    <td>
        Taxes and Fees
		<? foreach ( $bill['taxes'] as $tax ): ?>
            <div class="sub"><?= $tax['desc'] . ( $tax['type'] == 'PERCENT' ? ' - ' . ( $tax['rate'] * 100 ) . '%' : '' ) ?></div>
		<? endforeach; ?>
    </td>
    <td class="sub-push-down">
		<? foreach ( $bill['taxes'] as $tax ): ?>
            <div class="sub">$<?= number_format( $tax['charge'], 2, '.', "," ) ?></div>
		<? endforeach; ?>
    </td>
</tr>
<tr>
    <td>Mileage</td>
    <td><?= $rate_mileage['unlimited'] ? 'unlimited' : $rate_mileage['limit'].' '.$rate_mileage['unit'] ?></td>
</tr>
<?php if($data['charity'] > 0.00){ ?>
	<tr>
	    <td>Donation</td>
	    <td>$<?= number_format( $data['charity'], 2, '.', ',' ) ?></td>
	</tr>
	<tr>
	    <td>Total</td>
	    <td>$<?= number_format( $bill['total']+$data['charity'], 2, '.', ',' ) ?></td>
	</tr>
<? }else{ ?>
	<tr>
	    <td>Total</td>
	    <td>$<?= number_format( $bill['total'], 2, '.', ',' ) ?></td>
	</tr>
<?php } ?>