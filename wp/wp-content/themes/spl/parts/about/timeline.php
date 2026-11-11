<?php
/**
 * About — Timeline section.
 *
 * @package SPL
 */

use SPL\Core\Helper;

defined( 'ABSPATH' ) || exit;

$is_en   = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$section = is_array( $args ?? null ) ? $args : [];
$img_dir = get_template_directory_uri() . '/static/img/vinacos/';
$title   = ! empty( $section['title'] ) ? (string) $section['title'] : ( $is_en ? 'Key Development Milestones' : 'Từng mốc dấu ấn' );

$default_items = [
	[ 'year' => '2015', 'desc' => 'Khởi đầu. Thành lập công ty tại TP. Hồ Chí Minh', 'image' => $img_dir . 'factory-overview.jpg' ],
	[ 'year' => '2016', 'desc' => 'Bước tiến đầu. Nhà máy đầu tiên tại Bến Tre khởi công và đi vào hoạt động.', 'image' => $img_dir . 'cleanroom-factory.jpg' ],
	[ 'year' => '2018', 'desc' => 'Mở rộng. Nhà máy thứ hai chính thức hoạt động, nâng tầm quy mô sản xuất.', 'image' => $img_dir . 'factory-overview.jpg' ],
	[ 'year' => '2022', 'desc' => 'Tái cấu trúc. Tái cơ cấu bộ máy tổ chức và tối ưu hóa quy trình.', 'image' => $img_dir . 'team-office.jpg' ],
	[ 'year' => '2023', 'desc' => 'Chuyển mình. Tập trung nghiên cứu và phát triển sản phẩm mới, đầu tư mạnh vào R&D chuyên sâu, nâng cao trải nghiệm dịch vụ khách hàng toàn diện.', 'image' => $img_dir . 'rd-lab-main.jpg' ],
	[ 'year' => '2024', 'desc' => 'Kỷ nguyên mới. Tái định vị thương hiệu sau 9 năm hình thành và phát triển. VINACOS chuyển mình từ nhà sản xuất thành đối tác R&D.', 'image' => $img_dir . 'team-rd.jpg' ],
	[ 'year' => '2025', 'desc' => 'Đồng hành. Với nền tảng vững chắc và năng lực R&D chuyên sâu, VINACOS đồng hành cùng nhiều thương hiệu Việt chinh phục thị trường mỹ phẩm.', 'image' => $img_dir . 'research-process.jpg' ],
	[ 'year' => '2026', 'desc' => 'Tiến xa. Một cột mốc quan trọng: VINACOS chính thức trở thành Doanh nghiệp Khoa học – Công nghệ, khẳng định cam kết đầu tư mạnh mẽ vào đội ngũ R&D trình độ cao và hệ thống Lab tiêu chuẩn.', 'image' => $img_dir . 'rd-lab-main.jpg' ],
	[ 'year' => 'Đến 2030', 'desc' => 'Khẳng định. VINACOS với vị thế đối tác R&D uy tín hàng đầu, góp phần đưa mỹ phẩm Việt Nam tỏa sáng trên bản đồ thế giới.', 'image' => $img_dir . 'factory-overview.jpg' ],
];

$items = [];
if ( ! empty( $section['timeline_items'] ) && is_array( $section['timeline_items'] ) ) {
	foreach ( array_values( $section['timeline_items'] ) as $i => $row ) {
		$fallback_img = $default_items[ $i % count( $default_items ) ]['image'];
		$img_url      = function_exists( 'spl_get_image_url' )
			? spl_get_image_url( $row['image'] ?? null, $fallback_img )
			: $fallback_img;
		$row_title    = trim( (string) ( $row['title'] ?? '' ) );
		$row_desc     = trim( (string) ( $row['desc'] ?? '' ) );
		$full_desc    = ( '' !== $row_title && '' !== $row_desc ) ? ( $row_title . '. ' . $row_desc ) : ( '' !== $row_desc ? $row_desc : $row_title );
		$items[]      = [
			'year'  => (string) ( $row['year'] ?? '' ),
			'desc'  => $full_desc,
			'image' => $img_url,
		];
	}
}
if ( empty( $items ) ) {
	$items = $default_items;
}
?>
<section class="about-2-section section-large bg-slate-50">
	<div class="container">
		<h2 class="site-title text-center" data-aos="fade-up" data-aos-duration="700" data-aos-delay="300">
			<?php echo esc_html( $title ); ?>
		</h2>
		<div class="swiper-relative mt-10" data-aos="fade-up" data-aos-duration="700" data-aos-delay="800">
			<div class="swiper swiper-thumbs">
				<div class="swiper-wrapper">
					<?php foreach ( $items as $item ) : ?>
						<div class="swiper-slide">
							<div class="year-item">
								<span class="dot"></span>
								<span class="year"><?php echo esc_html( $item['year'] ); ?></span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="swiper-relative swiper-relative-top mt-10 is-page" data-aos="fade-up" data-aos-duration="700" data-aos-delay="1300">
			<div class="swiper swiper-top">
				<div class="swiper-wrapper">
					<?php foreach ( $items as $item ) : ?>
						<div class="swiper-slide">
							<div class="about-2-item">
								<div class="image img-cover">
									<img class="lozad" src="<?php echo esc_url( $item['image'] ); ?>" data-src="<?php echo esc_url( $item['image'] ); ?>" loading="lazy" alt="<?php echo esc_attr( $item['year'] ); ?>">
								</div>
								<div class="caption">
									<div class="year"><?php echo esc_html( $item['year'] ); ?></div>
									<div class="title">
										<p><span style="font-weight: 400;"><?php echo esc_html( $item['desc'] ); ?></span></p>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="desktop-only">
				<div class="swiper-button is-abs is-top-40">
					<div class="button-prev"><?= spl_icon( 'chevron-left', '', 20 ) ?></div>
					<div class="button-next"><?= spl_icon( 'chevron-right', '', 20 ) ?></div>
				</div>
			</div>
		</div>
	</div>
</section>
