<?php
/**
 * About — Team section.
 *
 * @package SPL
 */

use SPL\Core\Helper;

defined( 'ABSPATH' ) || exit;

$is_en          = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$section        = is_array( $args ?? null ) ? $args : [];
$is_synced      = ( '1' === get_option( 'spl_about_acf_synced_v2' ) );
$title          = ( $is_synced && ! empty( $section['title'] ) )
	? (string) $section['title']
	: ( $is_en ? 'Collective Strength & Team' : 'Sức mạnh tập thể' );
$img_dir        = get_template_directory_uri() . '/static/img/vinacos/';
$default_banner = get_template_directory_uri() . '/static/img/suc-manh-tap-the-vinacos.png';
$banner_img     = function_exists( 'spl_get_image_url' )
	? spl_get_image_url( $section['banner_image'] ?? null, $default_banner )
	: $default_banner;

$default_depts = $is_en ? [
	[
		'title' => 'EXECUTIVE BOARD & LEADERSHIP',
		'desc'  => 'Guiding vision, steering strategy, and fostering a culture of dedication across the organization.',
		'image' => $img_dir . 'ceo-founder.jpg',
	],
	[
		'title' => 'R&D RESEARCH & DEVELOPMENT',
		'desc'  => 'Formulation architects decoding botanical active efficacy through scientific rigor and clinical testing.',
		'image' => $img_dir . 'team-rd.jpg',
	],
	[
		'title' => 'PRODUCTION & MANUFACTURING',
		'desc'  => 'Disciplined manufacturing operations adhering to strict cGMP cleanroom protocols and automation.',
		'image' => $img_dir . 'team-production.jpg',
	],
	[
		'title' => 'QUALITY ASSURANCE & CONTROL (QA/QC)',
		'desc'  => 'Ensuring 100% compliance and stability from raw material intake to finished goods.',
		'image' => $img_dir . 'team-qa.jpg',
	],
	[
		'title' => 'OEM/ODM BUSINESS DEVELOPMENT',
		'desc'  => 'Trusted advisors actively listening and turning product concepts into commercial reality.',
		'image' => $img_dir . 'team-office.jpg',
	],
	[
		'title' => 'MARKETING & COMMUNICATIONS',
		'desc'  => 'Storytellers connecting scientific formulation value with brand identity and consumers.',
		'image' => $img_dir . 'team-office.jpg',
	],
	[
		'title' => 'HUMAN RESOURCES & ADMINISTRATION',
		'desc'  => 'Fostering an empowering workplace to attract and develop top industry talent.',
		'image' => $img_dir . 'team-office.jpg',
	],
	[
		'title' => 'PROCUREMENT & SUPPLY CHAIN',
		'desc'  => 'Selecting certified global raw ingredients with full COA and international traceability.',
		'image' => $img_dir . 'research-process.jpg',
	],
] : [
	[
		'title' => 'BAN GIÁM ĐỐC',
		'desc'  => 'Định hướng tầm nhìn, dẫn dắt tương lai. Dẫn dắt chiến lược phát triển và đảm bảo VINACOS luôn đi đúng hướng, truyền cảm hứng và xây dựng văn hóa tận tâm cho toàn bộ đội ngũ.',
		'image' => $img_dir . 'ceo-founder.jpg',
	],
	[
		'title' => 'R&D PHÒNG NGHIÊN CỨU & PHÁT TRIỂN',
		'desc'  => 'Những kiến trúc sư công thức, mã hóa vẻ đẹp Việt bằng nghiên cứu và khoa học. Đội ngũ chuyên gia tâm huyết thử nghiệm, chuẩn hóa và tạo nên các công thức mỹ phẩm độc quyền.',
		'image' => $img_dir . 'team-rd.jpg',
	],
	[
		'title' => 'BỘ PHẬN SẢN XUẤT',
		'desc'  => 'Kỷ luật thép trong từng thao tác, đảm bảo sự đồng nhất 100%. Với quy trình nghiêm ngặt chuẩn GMP và thiết bị hiện đại, đội ngũ sản xuất biến công thức thành hiện thực.',
		'image' => $img_dir . 'team-production.jpg',
	],
	[
		'title' => 'QUẢN LÝ CHẤT LƯỢNG (QA/QC)',
		'desc'  => 'Người gác cổng cuối cùng, đảm bảo chất lượng đạt chuẩn tuyệt đối. Từ nguyên liệu đầu vào đến thành phẩm, kiểm soát nghiêm ngặt để mọi tiêu chuẩn được tuân thủ.',
		'image' => $img_dir . 'team-qa.jpg',
	],
	[
		'title' => 'KINH DOANH & TƯ VẤN OEM/ODM',
		'desc'  => 'Những người đồng hành đầu tiên, lắng nghe và biến ý tưởng thành dự án cụ thể. Là cầu nối tin cậy giữa VINACOS và các thương hiệu đối tác.',
		'image' => $img_dir . 'team-office.jpg',
	],
	[
		'title' => 'MARKETING & TRUYỀN THÔNG',
		'desc'  => 'Người kể chuyện chính trực, đưa giá trị thực đến tay người dùng. Biến giá trị khoa học thành thông điệp cảm xúc, kết nối thương hiệu với khách hàng.',
		'image' => $img_dir . 'team-office.jpg',
	],
	[
		'title' => 'NHÂN SỰ & QUẢN TRỊ',
		'desc'  => 'Người xây dựng đội ngũ, tạo môi trường để mỗi thành viên phát huy tối đa năng lực. Thu hút và phát triển nguồn nhân lực trình độ cao cho VINACOS.',
		'image' => $img_dir . 'team-office.jpg',
	],
	[
		'title' => 'MUA HÀNG & CHUỖI CUNG ỨNG',
		'desc'  => 'Người quản trị nguồn lực, chọn lọc nguyên liệu nhập khẩu chính ngạch chuẩn COA, tạo nền tảng vững chắc cho sứ mệnh mỹ phẩm sạch.',
		'image' => $img_dir . 'research-process.jpg',
	],
];

$depts     = [];
$raw_items = $section['team_items'] ?? [];
if ( ! empty( $raw_items ) && is_array( $raw_items ) ) {
	$has_custom_images = false;
	foreach ( $raw_items as $r ) {
		if ( ! empty( $r['image'] ) ) {
			$has_custom_images = true;
			break;
		}
	}
	if ( $is_synced || $has_custom_images ) {
		foreach ( array_values( $raw_items ) as $i => $row ) {
			$fallback_img = $default_depts[ $i % count( $default_depts ) ]['image'];
			$img_url      = function_exists( 'spl_get_image_url' )
				? spl_get_image_url( $row['image'] ?? null, $fallback_img )
				: $fallback_img;
			$depts[]      = [
				'title' => (string) ( $row['name'] ?? $row['title'] ?? '' ),
				'desc'  => (string) ( $row['desc'] ?? '' ),
				'image' => $img_url,
			];
		}
	}
}
if ( empty( $depts ) ) {
	$depts = $default_depts;
}
?>
<section class="about-5-section section-t-large">
	<div class="container">
		<h2 class="site-title text-center" data-aos="fade-up" data-aos-duration="700" data-aos-delay="300">
			<?php echo esc_html( $title ); ?>
		</h2>
		<div class="image text-center" data-aos="fade-up" data-aos-duration="700" data-aos-delay="800">
			<img class="lozad" src="<?php echo esc_url( $banner_img ); ?>" data-src="<?php echo esc_url( $banner_img ); ?>" loading="lazy" alt="Sức Mạnh Tập Thể VINACOS">
		</div>
		<div class="swiper-relative about-5-list is-page" data-aos="fade-up" data-aos-duration="700" data-aos-delay="1300">
			<div class="swiper about-5-image">
				<div class="swiper-wrapper">
					<?php foreach ( $depts as $dept ) : ?>
						<div class="swiper-slide">
							<div class="image img-cover">
								<img class="lozad" src="<?php echo esc_url( $dept['image'] ); ?>" data-src="<?php echo esc_url( $dept['image'] ); ?>" loading="lazy" alt="<?php echo esc_attr( $dept['title'] ); ?>">
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="caption mt-6">
				<div class="swiper about-5-caption">
					<div class="swiper-wrapper">
						<?php foreach ( $depts as $dept ) : ?>
							<div class="swiper-slide">
								<h3 class="title"><?php echo esc_html( $dept['title'] ); ?></h3>
								<div class="desc"><?php echo wp_kses_post( nl2br( esc_html( $dept['desc'] ) ) ); ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="desktop-only mt-8">
					<div class="swiper-button">
						<div class="button-prev"><?= spl_icon( 'chevron-left', '', 20 ) ?></div>
						<div class="button-next"><?= spl_icon( 'chevron-right', '', 20 ) ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
