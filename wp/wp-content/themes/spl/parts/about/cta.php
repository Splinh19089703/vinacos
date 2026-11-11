<?php
/**
 * About — Factory & Process (about-6-section 100% Unila HTML).
 *
 * @package SPL
 */

defined( 'ABSPATH' ) || exit;

$is_en     = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$section   = is_array( $args ?? null ) ? $args : [];
$is_synced = ( '1' === get_option( 'spl_about_acf_synced_v2' ) );
$has_acf   = $is_synced && ! empty( $section );
$img_dir   = get_template_directory_uri() . '/static/img/vinacos/';

if ( $has_acf && array_key_exists( 'title', $section ) ) {
	$title = (string) $section['title'];
} else {
	$title = ( $is_synced && ! empty( $section['title'] ) )
		? (string) $section['title']
		: ( $is_en ? 'International cGMP/FDA Cleanroom Facilities' : 'Nhà máy & Quy trình sản xuất chuẩn quốc tế' );
}

$default_slides = $is_en ? [
	[
		'title' => 'cGMP & FDA Certified Cleanroom Plant',
		'desc'  => 'High-capacity automated filling & compounding lines capable of producing millions of units annually.',
		'image' => $img_dir . 'factory-overview.jpg',
		'alt'   => 'VINACOS Factory',
	],
	[
		'title' => 'Advanced Analytical R&D Laboratory',
		'desc'  => 'High-tech analytical testing instruments for active compound verification & strict safety stability testing.',
		'image' => $img_dir . 'rd-lab-main.jpg',
		'alt'   => 'VINACOS R&D Lab',
	],
	[
		'title' => 'Turnkey End-to-End Manufacturing Workflow',
		'desc'  => 'From formula engineering, sample trial runs, stability testing to custom packaging & regulatory filing.',
		'image' => $img_dir . 'cleanroom-factory.jpg',
		'alt'   => 'GMP Cleanroom',
	],
] : [
	[
		'title' => 'Nhà máy sản xuất chuẩn GMP & FDA',
		'desc'  => 'Hệ thống phòng sạch hiện đại, tự động hóa cao, đáp ứng công suất hàng triệu sản phẩm mỗi năm.',
		'image' => $img_dir . 'factory-overview.jpg',
		'alt'   => 'VINACOS Factory',
	],
	[
		'title' => 'Phòng Lab R&D chuyên sâu',
		'desc'  => 'Trang thiết bị kiểm nghiệm công nghệ cao, phân tích hoạt chất và đo lường chỉ số an toàn nghiêm ngặt.',
		'image' => $img_dir . 'rd-lab-main.jpg',
		'alt'   => 'VINACOS R&D Lab',
	],
	[
		'title' => 'Quy trình sản xuất khép kín A-Z',
		'desc'  => 'Từ nghiên cứu công thức, kiểm định mẫu thử, thử nghiệm độ ổn định đến đóng gói và bàn giao hồ sơ công bố.',
		'image' => $img_dir . 'cleanroom-factory.jpg',
		'alt'   => 'GMP Cleanroom',
	],
];

$slides = [];
if ( $has_acf && array_key_exists( 'factory_items', $section ) ) {
	if ( is_array( $section['factory_items'] ) ) {
		foreach ( array_values( $section['factory_items'] ) as $row ) {
			$img_url     = function_exists( 'spl_get_image_url' )
				? spl_get_image_url( $row['image'] ?? null )
				: '';
			$slide_title = (string) ( $row['title'] ?? '' );
			$slides[]    = [
				'title' => $slide_title,
				'desc'  => (string) ( $row['desc'] ?? '' ),
				'image' => $img_url,
				'alt'   => '' !== $slide_title ? $slide_title : 'VINACOS Factory',
			];
		}
	}
} else {
	if ( ! empty( $section['factory_items'] ) && is_array( $section['factory_items'] ) ) {
		foreach ( array_values( $section['factory_items'] ) as $i => $row ) {
			$fallback_img = $default_slides[ $i % count( $default_slides ) ]['image'];
			$img_url      = function_exists( 'spl_get_image_url' )
				? spl_get_image_url( $row['image'] ?? null, $fallback_img )
				: $fallback_img;
			$slide_title  = (string) ( $row['title'] ?? '' );
			$slides[]     = [
				'title' => $slide_title,
				'desc'  => (string) ( $row['desc'] ?? '' ),
				'image' => $img_url,
				'alt'   => '' !== $slide_title ? $slide_title : 'VINACOS Factory',
			];
		}
	}
	if ( empty( $slides ) ) {
		$slides = $default_slides;
	}
}

$any_slide_image = false;
foreach ( $slides as $sl ) {
	if ( '' !== $sl['image'] ) {
		$any_slide_image = true;
		break;
	}
}
?>
<section class="about-6-section section-large">
	<div class="container">
		<div class="about-6-wrap">
			<div class="right">
				<?php if ( '' !== trim( $title ) ) : ?>
					<h2 class="site-title text-center" data-aos="fade-up" data-aos-duration="700" data-aos-delay="300">
						<?php echo esc_html( $title ); ?>
					</h2>
				<?php endif; ?>
				<?php if ( ! empty( $slides ) ) : ?>
					<div class="swiper-relative mt-10 is-page">
						<?php if ( $any_slide_image ) : ?>
							<div class="swiper about-6-image" data-aos="fade-up" data-aos-duration="700" data-aos-delay="800">
								<div class="swiper-wrapper">
									<?php foreach ( $slides as $slide ) : ?>
										<div class="swiper-slide">
											<div class="image img-cover">
												<?php if ( '' !== $slide['image'] ) : ?>
													<img class="lozad" src="<?php echo esc_url( $slide['image'] ); ?>" data-src="<?php echo esc_url( $slide['image'] ); ?>" width="1376" height="768" loading="lazy" alt="<?php echo esc_attr( $slide['alt'] ); ?>">
												<?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="swiper about-6-caption mt-10 pt-10">
							<div class="swiper-wrapper">
								<?php foreach ( $slides as $slide ) : ?>
									<div class="swiper-slide">
										<?php if ( '' !== trim( $slide['title'] ) ) : ?>
											<h3 class="title"><?php echo esc_html( $slide['title'] ); ?></h3>
										<?php endif; ?>
										<?php if ( '' !== trim( $slide['desc'] ) ) : ?>
											<div class="desc"><?php echo wp_kses_post( nl2br( esc_html( $slide['desc'] ) ) ); ?></div>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="desktop-only mt-10">
							<div class="swiper-button" data-aos="fade-up" data-aos-duration="700" data-aos-delay="1300">
								<div class="button-prev"><?= spl_icon( 'chevron-left', '', 20 ) ?></div>
								<div class="button-next"><?= spl_icon( 'chevron-right', '', 20 ) ?></div>
							</div>
						</div>
						<div class="mobile-only">
							<div class="swiper-pagination"></div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
