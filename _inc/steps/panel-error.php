<?
/**
 * @var string $message
 */
?>

<div id="booking-error">
    <div class="container" <? le( 'error_sub_text', 'options' ); ?>>
        <h2><?= $message ?></h2>
        <h3><?= str_replace( '<a>', '<a href="' . get_permalink( get_page_by_path( 'booking' ) ) . '">', gf( 'error_sub_text', 'options' ) ) ?></h3>
    </div>
</div>