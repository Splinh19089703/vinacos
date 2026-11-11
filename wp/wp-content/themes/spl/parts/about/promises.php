<?php
/**
 * About — Promises Section (LỜI HỨA CỦA BB VINACOS).
 *
 * @package SPL
 */

defined( 'ABSPATH' ) || exit;

$is_en           = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$section         = is_array( $args ?? null ) ? $args : [];
$is_synced       = ( '1' === get_option( 'spl_about_acf_synced_v2' ) );
$is_legacy_stats = ( $section['acf_fc_layout'] ?? '' ) === 'about_stats';
$has_acf         = $is_synced && ! empty( $section ) && ! $is_legacy_stats;

if ( $has_acf && array_key_exists( 'title', $section ) ) {
	$title = (string) $section['title'];
} else {
	$title = ( ! $is_legacy_stats && ! empty( $section['title'] ) )
		? (string) $section['title']
		: ( $is_en ? 'VINACOS PROMISES' : 'LỜI HỨA CỦA BB VINACOS' );
}
$img_dir = get_template_directory_uri() . '/static/img/';

$default_items = [
	[
		'icon'  => $img_dir . 'icon-hop-tac.png',
		'title' => $is_en ? 'Collaboration' : 'Sự hợp tác',
		'desc'  => $is_en ? 'Describe what this value represents within the team and across the company.' : 'Hãy mô tả giá trị đó đại diện cho điều gì trong nhóm và trong công ty.',
	],
	[
		'icon'  => $img_dir . 'icon-trung-thuc.png',
		'title' => $is_en ? 'Integrity' : 'Sự trung thực',
		'desc'  => $is_en ? 'Describe what this value represents within the team and across the company.' : 'Hãy mô tả giá trị đó đại diện cho điều gì trong nhóm và trong công ty.',
	],
	[
		'icon'  => $img_dir . 'icon-xuat-sac.png',
		'title' => $is_en ? 'Excellence' : 'Sự xuất sắc',
		'desc'  => $is_en ? 'Describe what this value represents within the team and across the company.' : 'Hãy mô tả giá trị đó đại diện cho điều gì trong nhóm và trong công ty.',
	],
];

$items = [];
if ( $has_acf && array_key_exists( 'promise_items', $section ) ) {
	if ( is_array( $section['promise_items'] ) ) {
		foreach ( array_values( $section['promise_items'] ) as $row ) {
			$icon_url = function_exists( 'spl_get_image_url' )
				? spl_get_image_url( $row['icon'] ?? null )
				: '';
			$items[]  = [
				'icon'  => $icon_url,
				'title' => (string) ( $row['title'] ?? '' ),
				'desc'  => (string) ( $row['desc'] ?? '' ),
			];
		}
	}
} else {
	if ( ! empty( $section['promise_items'] ) && is_array( $section['promise_items'] ) ) {
		foreach ( array_values( $section['promise_items'] ) as $i => $row ) {
			$fallback_icon = $default_items[ $i % count( $default_items ) ]['icon'];
			$icon_url      = function_exists( 'spl_get_image_url' )
				? spl_get_image_url( $row['icon'] ?? null, $fallback_icon )
				: $fallback_icon;
			$items[]       = [
				'icon'  => $icon_url,
				'title' => (string) ( $row['title'] ?? '' ),
				'desc'  => (string) ( $row['desc'] ?? '' ),
			];
		}
	}
	if ( empty( $items ) ) {
		$items = $default_items;
	}
}
?>

<section class="about-promises-section section-large" id="promises">
	<div class="container">
		<?php if ( '' !== trim( $title ) ) : ?>
			<h2 class="site-title text-center" data-aos="fade-up" data-aos-duration="700">
				<?php echo esc_html( $title ); ?>
			</h2>
		<?php endif; ?>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="promises-grid">
				<?php foreach ( $items as $index => $item ) : ?>
					<div class="promise-item" data-aos="fade-up" data-aos-duration="700" data-aos-delay="<?php echo esc_attr( (string) ( 200 * ( $index + 1 ) ) ); ?>">
						<?php if ( '' !== $item['icon'] ) : ?>
							<div class="promise-icon">
								<img src="<?php echo esc_url( $item['icon'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" width="60" height="60" loading="lazy">
							</div>
						<?php endif; ?>
						<?php if ( '' !== trim( $item['title'] ) ) : ?>
							<div class="promise-tag">
								<?php echo esc_html( $item['title'] ); ?>
							</div>
						<?php endif; ?>
						<?php if ( '' !== trim( $item['desc'] ) ) : ?>
							<p class="promise-desc">
								<?php echo wp_kses_post( nl2br( esc_html( $item['desc'] ) ) ); ?>
							</p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
