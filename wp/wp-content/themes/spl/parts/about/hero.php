<?php
/**
 * About — Hero section & Breadcrumbs (100% Unila HTML).
 *
 * @package SPL
 */

defined( 'ABSPATH' ) || exit;

$is_en         = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$section       = is_array( $args ?? null ) ? $args : [];
$banner_img    = function_exists( 'spl_get_image_url' )
	? spl_get_image_url( $section['banner_image'] ?? null )
	: ( is_numeric( $section['banner_image'] ?? null ) ? (string) wp_get_attachment_url( (int) $section['banner_image'] ) : (string) ( $section['banner_image'] ?? '' ) );
$banner_mobile = function_exists( 'spl_get_image_url' )
	? spl_get_image_url( $section['banner_image_mobile'] ?? null )
	: ( is_numeric( $section['banner_image_mobile'] ?? null ) ? (string) wp_get_attachment_url( (int) $section['banner_image_mobile'] ) : '' );

if ( empty( $banner_img ) && has_post_thumbnail() ) {
	$banner_img = (string) get_the_post_thumbnail_url( null, 'full' );
}

if ( empty( $banner_img ) || false !== strpos( $banner_img, 'brand-banner-' ) ) {
	$banner_img = get_template_directory_uri() . '/static/img/banner/banner-about.webp';
}

$raw_title = trim( wp_strip_all_tags( (string) ( $section['title'] ?? '' ) ) );
$alt_text  = '' !== $raw_title
	? 'VINACOS - ' . $raw_title
	: 'VINACOS - ' . ( $is_en ? 'About Us' : 'Về chúng tôi' );
?>
<section class="banner-child">
	<div class="swiper">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<div class="image">
					<?php if ( ! empty( $banner_mobile ) ) : ?>
						<picture>
							<source media="(max-width: 767px)" srcset="<?php echo esc_url( $banner_mobile ); ?>">
							<img class="lozad" src="<?php echo esc_url( $banner_img ); ?>" data-src="<?php echo esc_url( $banner_img ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" width="1920" height="600" fetchpriority="high">
						</picture>
					<?php else : ?>
						<img class="lozad" src="<?php echo esc_url( $banner_img ); ?>" data-src="<?php echo esc_url( $banner_img ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" width="1920" height="600" fetchpriority="high">
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
