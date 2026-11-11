<?php
/**
 * About — Message section (Tâm thế cộng sự - Slide 18 Layout).
 *
 * Exact 1:1 match with Canva Slide 18:
 * - Desktop: Full-screen (100vw, 100vh - header) edge-to-edge layout with Canva organic curve and team meeting photo.
 * - Mobile/Tablet: Clean stacked responsive layout with meeting photo on top, content below.
 * - Left/Content area:
 *   - Badge with custom icon (icon-tam-the-cong-su.webp) and Text 1 (subtitle) at top-left.
 *   - Main Quote / Text 2 (title) positioned lower down matching Canva slide 18.
 *
 * @package SPL
 */

defined( 'ABSPATH' ) || exit;

$section   = is_array( $args ?? null ) ? $args : [];
$is_en     = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$is_synced = ( '1' === get_option( 'spl_about_acf_synced_v2' ) );
$has_acf   = $is_synced && ! empty( $section );

// Text 1: Subtitle / Badge text (tùy chỉnh qua ACF)
if ( $has_acf && array_key_exists( 'subtitle', $section ) ) {
	$subtitle = (string) $section['subtitle'];
} else {
	$raw_subtitle = $section['subtitle'] ?? '';
	$subtitle     = ! empty( $raw_subtitle ) ? (string) $raw_subtitle : ( $is_en ? 'Your Great Company' : 'Công ty tuyệt vời của bạn' );
}

// Text 2: Main Quote / Title (tùy chỉnh qua ACF)
$raw_title = $section['title'] ?? '';
if ( ! $has_acf && empty( $raw_title ) ) {
	$title = $is_en
		? "The journey<br>is still long,<br>but we already have<br><strong>many achievements</strong><br>worth celebrating."
		: "Chặng đường<br>vẫn còn dài,<br>nhưng chúng ta đã có<br><strong>rất nhiều thành tựu</strong><br>đáng để ăn mừng.";
} else {
	$title = (string) $raw_title;
	// If no manual line breaks exist, break automatically according to Canva slide 18 rhythm
	if ( '' !== $title && false === strpos( $title, '<br' ) && false === strpos( $title, '<br/>' ) ) {
		$title = str_replace(
			array(
				'Chặng đường vẫn còn dài, nhưng chúng ta đã có',
				'rất nhiều thành tựu đáng để ăn mừng',
				'The journey is still long, but we already have',
				'many achievements worth celebrating',
			),
			array(
				"Chặng đường<br>vẫn còn dài,<br>nhưng chúng ta đã có",
				"rất nhiều thành tựu<br>đáng để ăn mừng",
				"The journey<br>is still long,<br>but we already have",
				"many achievements<br>worth celebrating",
			),
			$title
		);
	}
}

// Custom background image override or default to Slide 18 clean asset
$default_bg = get_template_directory_uri() . '/static/img/vinacos/bg-tam-the-cong-su.webp';
if ( $has_acf && array_key_exists( 'image', $section ) ) {
	$bg_url = function_exists( 'spl_get_image_url' )
		? spl_get_image_url( $section['image'] )
		: ( ! empty( $section['image'] ) && is_numeric( $section['image'] ) ? (string) wp_get_attachment_image_url( (int) $section['image'], 'full' ) : '' );
} else {
	$bg_url = function_exists( 'spl_get_image_url' )
		? spl_get_image_url( $section['image'] ?? null, $default_bg )
		: ( ! empty( $section['image'] ) && is_numeric( $section['image'] ) ? (string) wp_get_attachment_image_url( (int) $section['image'], 'full' ) : $default_bg );
	if ( empty( $bg_url ) ) {
		$bg_url = $default_bg;
	}
}

// Custom mobile image override for About message section
$bg_mobi_url = function_exists( 'spl_get_image_url' )
	? spl_get_image_url( $section['image_mobile'] ?? null, $bg_url )
	: ( ! empty( $section['image_mobile'] ) && is_numeric( $section['image_mobile'] ) ? (string) wp_get_attachment_image_url( (int) $section['image_mobile'], 'full' ) : $bg_url );

$msg_card_style = '' !== $bg_url
	? sprintf( "--msg-bg: url('%s');", esc_url( $bg_url ) )
	: '--msg-bg: none;';
?>

<section class="about-message-section about-brand" id="message">
	<div class="about-message-card" style="<?php echo esc_attr( $msg_card_style ); ?>" data-aos="fade-up" data-aos-duration="700">
		<?php if ( '' !== $bg_mobi_url ) : ?>
			<!-- Mobile Photo (Visible only on < 1025px) -->
			<div class="about-message-mobile-media">
				<img src="<?php echo esc_url( $bg_mobi_url ); ?>" alt="<?php echo esc_attr( '' !== trim( wp_strip_all_tags( $subtitle ) ) ? wp_strip_all_tags( $subtitle ) . ' - VINACOS' : 'VINACOS' ); ?>" loading="lazy" width="800" height="450">
			</div>
		<?php endif; ?>

		<!-- Content Area (Left on Desktop, Below photo on Mobile) -->
		<div class="about-message-content">
			<?php if ( '' !== trim( wp_strip_all_tags( $subtitle ) ) ) : ?>
				<!-- Top Badge: Subtitle -->
				<div class="about-message-badge" data-aos="fade-right" data-aos-duration="600" data-aos-delay="200">
					<span class="about-message-badge-text">
						<?php echo wp_kses_post( nl2br( $subtitle ) ); ?>
					</span>
				</div>
			<?php endif; ?>

			<?php if ( '' !== trim( wp_strip_all_tags( $title ) ) ) : ?>
				<!-- Main Quote: Text 2 (Title) shifted down like Canva sample -->
				<div class="about-message-quote-wrap" data-aos="fade-up" data-aos-duration="700" data-aos-delay="400">
					<blockquote class="about-message-quote">
						<?php echo wp_kses_post( nl2br( $title ) ); ?>
					</blockquote>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
