<?php
/**
 * About — Story section (Nghiên cứu sản xuất mỹ phẩm).
 *
 * Exact 1:1 match with Canva design:
 * - Left: Full-bleed blue shape card from top to bottom with bold white uppercase title.
 * - Right: Clean editorial layout with "Dẫn đầu", "Thấu hiểu", dividers, and exact typography.
 *
 * @package SPL
 */

defined( 'ABSPATH' ) || exit;

$is_en     = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$section   = is_array( $args ?? null ) ? $args : [];
$is_synced = ( '1' === get_option( 'spl_about_acf_synced_v2' ) );
$has_acf   = $is_synced && ! empty( $section );

if ( $has_acf && array_key_exists( 'title', $section ) ) {
	$title = (string) $section['title'];
} else {
	$raw_title = $section['title'] ?? '';
	$title     = ! empty( $raw_title ) ? (string) $raw_title : ( $is_en ? 'COSMETICS R&D & <br/> MANUFACTURING' : 'NGHIÊN CỨU SẢN XUẤT <br/> MỸ PHẨM' );
}

// Normalize 3-line format to wide 2-line format
$title = str_replace(
	array(
		'NGHIÊN CỨU <br/> SẢN XUẤT <br/> MỸ PHẨM',
		'NGHIÊN CỨU <br /> SẢN XUẤT <br /> MỸ PHẨM',
		'NGHIÊN CỨU <br> SẢN XUẤT <br> MỸ PHẨM',
		'NGHIÊN CỨU<br/>SẢN XUẤT<br/>MỸ PHẨM',
		'NGHIÊN CỨU<br />SẢN XUẤT<br />MỸ PHẨM',
		'COSMETICS <br/> R&D & <br/> MANUFACTURING',
		'COSMETICS <br /> R&D & <br /> MANUFACTURING',
		'COSMETICS <br> R&D & <br> MANUFACTURING',
		'COSMETICS<br/>R&D &<br/>MANUFACTURING',
	),
	array(
		'NGHIÊN CỨU SẢN XUẤT <br/> MỸ PHẨM',
		'NGHIÊN CỨU SẢN XUẤT <br/> MỸ PHẨM',
		'NGHIÊN CỨU SẢN XUẤT <br/> MỸ PHẨM',
		'NGHIÊN CỨU SẢN XUẤT <br/> MỸ PHẨM',
		'NGHIÊN CỨU SẢN XUẤT <br/> MỸ PHẨM',
		'COSMETICS R&D & <br/> MANUFACTURING',
		'COSMETICS R&D & <br/> MANUFACTURING',
		'COSMETICS R&D & <br/> MANUFACTURING',
		'COSMETICS R&D & <br/> MANUFACTURING',
	),
	$title
);

// Normalize spaces and add desk-only class to <br> for clean responsive wrapping on mobile
$title_clean = preg_replace( '/\s*<br\s*\/?>\s*/i', ' <br class="desk-only"> ', (string) $title );

$default_bg = get_template_directory_uri() . '/static/img/bg-story-shape.png';
if ( $has_acf && array_key_exists( 'image', $section ) ) {
	$bg_image = function_exists( 'spl_get_image_url' ) ? spl_get_image_url( $section['image'] ) : '';
} else {
	$bg_image = function_exists( 'spl_get_image_url' )
		? spl_get_image_url( $section['image'] ?? null, $default_bg )
		: $default_bg;
}
$shape_style = '' !== $bg_image
	? sprintf( "background-image: url('%s') !important;", esc_url( $bg_image ) )
	: 'background-image: none !important; background-color: #1e60a3 !important;';

$default_headline = $is_en
	? 'VINACOS – Driving a Clean Beauty Era from Vietnamese Botanical Resources.'
	: 'VINACOS – Hành động vì một kỷ nguyên mỹ phẩm sạch từ nguồn lực Việt.';
if ( $has_acf && array_key_exists( 'headline', $section ) ) {
	$headline = (string) $section['headline'];
} else {
	$headline = ! empty( $section['headline'] ) ? (string) $section['headline'] : $default_headline;
}

$default_blocks = $is_en ? [
	[
		'block_title' => 'Pioneering Leadership',
		'block_desc'  => "VINACOS is a science & technology enterprise pioneering clean cosmetics formulation and cGMP/FDA production in Vietnam.\nWe dedicate our heart to human talent, laboratory equipment, and quality control to ensure every formula delivered to brand partners excels in safety, efficacy, and commercial readiness. Anticipating global trends, VINACOS establishes new benchmarks, proving that Vietnamese cosmetics can stand shoulder-to-shoulder internationally.",
	],
	[
		'block_title' => 'Deep Empathy & Co-Creation',
		'block_desc'  => "We partner with beauty brands, leveraging R&D science to create gentle, effective formulations tailored for modern consumers.\nWe understand that Vietnamese consumers deserve skincare made with genuine safety. And brands seeking that vision require a partner who not only manufactures, but actively listens, consults, and co-shapes the product from the very beginning.",
	],
] : [
	[
		'block_title' => 'Dẫn đầu',
		'block_desc'  => "VINACOS là doanh nghiệp khoa học & công nghệ tiên phong trong nghiên cứu và sản xuất mỹ phẩm sạch tại Việt Nam.\nChúng tôi đặt tâm huyết vào con người, thiết bị và quy trình để mỗi sản phẩm đến tay đối tác đều được đảm bảo chất lượng. Dẫn đầu với VINACOS là đặt ra tiêu chuẩn mới, góp phần chứng minh mỹ phẩm Việt hoàn toàn có thể sánh ngang thế giới.",
	],
	[
		'block_title' => 'Thấu hiểu',
		'block_desc'  => "VINACOS đồng hành cùng các thương hiệu Việt, dùng nghiên cứu khoa học và sản xuất tạo ra những sản phẩm lành tính xứng đáng với làn da Việt.\nChúng tôi hiểu rằng người Việt xứng đáng được chăm sóc bằng những gì thực sự an toàn. Và các thương hiệu muốn làm điều đó cần một đối tác không chỉ biết sản xuất, mà còn biết lắng nghe, tư vấn và cùng định hình sản phẩm từ đầu.",
	],
];

if ( $has_acf && array_key_exists( 'story_blocks', $section ) ) {
	$story_blocks = is_array( $section['story_blocks'] ) ? array_values( $section['story_blocks'] ) : [];
} else {
	$story_blocks = ( ! empty( $section['story_blocks'] ) && is_array( $section['story_blocks'] ) )
		? array_values( $section['story_blocks'] )
		: $default_blocks;
}
?>

<section class="about-story-section" id="story">
	<div class="about-story-grid">
		<!-- LEFT: Full-bleed Blue Shape with Title -->
		<div class="about-story-shape-col" data-aos="fade-right" data-aos-duration="700">
			<div class="about-story-shape-card" style="<?php echo $shape_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- URL escaped via esc_url above ?>">
				<?php if ( '' !== trim( wp_strip_all_tags( (string) $title_clean ) ) ) : ?>
					<h2 class="about-story-shape-title">
						<?php echo wp_kses_post( $title_clean ); ?>
					</h2>
				<?php endif; ?>
			</div>
		</div>

		<!-- RIGHT: Content Matching Canva 100% -->
		<div class="about-story-content-col" data-aos="fade-left" data-aos-duration="700" data-aos-delay="200">
			<div class="about-story-content-inner">
				<?php if ( '' !== trim( $headline ) ) : ?>
					<p class="about-story-headline">
						<?php echo wp_kses_post( nl2br( $headline ) ); ?>
					</p>
				<?php endif; ?>

				<?php foreach ( $story_blocks as $idx => $block ) : ?>
					<?php
					$b_title = trim( (string) ( $block['block_title'] ?? '' ) );
					$b_desc  = trim( (string) ( $block['block_desc'] ?? '' ) );
					if ( '' === $b_title && '' === $b_desc ) {
						continue;
					}
					$paragraphs = preg_split( '/\r\n|\r|\n/', $b_desc ) ?: [];
					$paragraphs = array_values( array_filter( array_map( 'trim', $paragraphs ), static fn( string $p ): bool => '' !== $p ) );
					?>
					<?php if ( $idx > 0 ) : ?>
						<hr class="about-story-hr">
					<?php endif; ?>
					<div class="about-story-block">
						<?php if ( '' !== $b_title ) : ?>
							<h3 class="about-story-block-title">
								<?php echo esc_html( $b_title ); ?>
							</h3>
						<?php endif; ?>
						<?php foreach ( $paragraphs as $para ) : ?>
							<p class="about-story-text">
								<?php echo wp_kses_post( $para ); ?>
							</p>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
