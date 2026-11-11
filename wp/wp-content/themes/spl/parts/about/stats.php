<?php
/**
 * About — Stats section (100% exact Unila HTML with icon images).
 *
 * @package SPL
 */

defined( 'ABSPATH' ) || exit;

$is_en        = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$section      = is_array( $args ?? null ) ? $args : [];
$title        = ! empty( $section['title'] ) ? (string) $section['title'] : ( $is_en ? 'Milestones in Numbers' : 'Những con số biết nói' );
$default_img  = get_template_directory_uri() . '/static/img/stats-about-vinacos.png';
$center_image = function_exists( 'spl_get_image_url' )
	? spl_get_image_url( $section['center_image'] ?? null, $default_img )
	: $default_img;

$default_stats = [
	[
		'number' => '0%',
		'desc'   => 'Tỉ lệ sai sót về pháp lý và hoạt chất cấm',
		'svg'    => '<svg class="w-14 h-14 text-[#1e60a3]" viewBox="0 0 52 52" fill="none" stroke="#1e60a3" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><rect x="14" y="10" width="28" height="20" rx="3" fill="#f8fafc"/><line x1="19" y1="16" x2="31" y2="16"/><line x1="19" y1="21" x2="27" y2="21"/><circle cx="21" cy="33" r="6" fill="#ffffff"/><path d="m21 29 1.3 2.6 2.9.4-2.1 2 .5 2.9-2.6-1.4-2.6 1.4.5-2.9-2.1-2 2.9-.4z"/><path d="m18 38.5-2.5 5.5 3.5-1.8 3.5 1.8-2.5-5.5"/></svg>',
	],
	[
		'number' => '100%',
		'desc'   => 'Kiểm nghiệm công thức và test độ ổn định',
		'svg'    => '<svg class="w-14 h-14 text-[#1e60a3]" viewBox="0 0 52 52" fill="none" stroke="#1e60a3" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><circle cx="26" cy="26" r="21" stroke-dasharray="2 3" stroke-opacity="0.45"/><path d="M18 30h5l3.5-3.5 4 4 4.5-2.5"/><path d="M30 18v5l-3.5 3.5 4 4-2.5 4.5"/><path d="M18 22h8.5l-2.5 5 5 2.5"/><line x1="20" y1="18" x2="26" y2="24"/><line x1="26" y1="32" x2="32" y2="26"/><line x1="32" y1="20" x2="26" y2="26"/></svg>',
	],
	[
		'number' => '30+',
		'desc'   => 'Đề tài nghiên cứu khoa học đã đăng tải',
		'svg'    => '<svg class="w-14 h-14 text-[#1e60a3]" viewBox="0 0 52 52" fill="none" stroke="#1e60a3" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><rect x="14" y="11" width="24" height="32" rx="3" fill="#f8fafc"/><path d="M20 11V8a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v3"/><line x1="19" y1="17" x2="33" y2="17"/><path d="M23 23v4M21 27h4M25 32a4 4 0 1 0 4-4h-4"/><line x1="19" y1="37" x2="33" y2="37"/></svg>',
	],
	[
		'number' => '3+',
		'desc'   => 'Bằng sáng chế & giải pháp hữu ích',
		'svg'    => '<svg class="w-14 h-14 text-[#1e60a3]" viewBox="0 0 52 52" fill="none" stroke="#1e60a3" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><circle cx="26" cy="26" r="21" stroke-dasharray="2 3" stroke-opacity="0.45"/><circle cx="26" cy="20" r="5"/><path d="M16 38c0-5.5 4.5-9 10-9s10 3.5 10 9"/><path d="M33 16l4-4M37 12v4h-4"/></svg>',
	],
];

$stats = [];
if ( ! empty( $section['items'] ) && is_array( $section['items'] ) ) {
	foreach ( array_values( $section['items'] ) as $i => $row ) {
		$stats[] = [
			'number' => trim( (string) ( $row['count'] ?? '' ) . (string) ( $row['suffix'] ?? '' ) ),
			'desc'   => (string) ( $row['title'] ?? '' ),
			'svg'    => $default_stats[ $i % count( $default_stats ) ]['svg'],
		];
	}
}
if ( empty( $stats ) ) {
	$stats = $default_stats;
}

$promises = [
	[
		'title' => 'Chất lượng bền vững',
		'desc'  => 'Mỗi công thức từ VINACOS đều phải vượt qua hàng trăm lần kiểm nghiệm trước khi được phép rời khỏi phòng lab. Chúng tôi không chỉ test độ ổn định của sản phẩm qua thời gian, mà còn kiểm tra khả năng tương thích giữa các hoạt chất, đảm bảo chúng không phản ứng phụ hay làm giảm hiệu quả lẫn nhau.',
		'svg'   => '<svg class="w-16 h-16 text-[#1e60a3]" viewBox="0 0 64 64" fill="none" stroke="#1e60a3" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="12" y="14" width="40" height="28" rx="2"/><circle cx="40" cy="38" r="7" fill="#ffffff"/><path d="M40 33.5l1.5 3 3.3.5-2.4 2.3.6 3.3-3-1.6-3 1.6.6-3.3-2.4-2.3 3.3-.5z"/><path d="M36 44.5l-2.5 6 4.5-2.2 4.5 2.2-2.5-6"/></svg>',
	],
	[
		'title' => 'Năng lực chuyên gia',
		'desc'  => 'Một công thức R&D tại VINACOS mất ít nhất 3-6 tháng để hoàn thiện. Chúng tôi tính toán chính xác từng phần trăm hoạt chất, điều chỉnh pH phù hợp với làn da Việt, test độ thẩm thấu và thử nghiệm hàng chục mẫu trước khi chốt công thức tối ưu.',
		'svg'   => '<svg class="w-16 h-16 text-[#1e60a3]" viewBox="0 0 64 64" fill="none" stroke="#1e60a3" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="12" y="22" width="40" height="28" rx="3"/><path d="M25 22v-4a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3v4"/><line x1="12" y1="33" x2="52" y2="33"/><rect x="22" y="30" width="5" height="6" rx="1" fill="#ffffff"/><rect x="37" y="30" width="5" height="6" rx="1" fill="#ffffff"/></svg>',
	],
	[
		'title' => 'Sự minh bạch',
		'desc'  => 'Chúng tôi công khai 100% thành phần, đầy đủ hồ sơ pháp lý, chứng từ kiểm nghiệm từ đơn vị uy tín và nguồn gốc nguyên liệu rõ ràng. Mỗi sản phẩm đều kèm tài liệu về quy trình sản xuất, kết quả test an toàn và các chứng nhận cần thiết.',
		'svg'   => '<svg class="w-16 h-16 text-[#1e60a3]" viewBox="0 0 64 64" fill="none" stroke="#1e60a3" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="22" y="12" width="28" height="38" rx="2"/><line x1="27" y1="19" x2="43" y2="19"/><line x1="27" y1="25" x2="43" y2="25"/><line x1="27" y1="31" x2="37" y2="31"/><line x1="27" y1="37" x2="35" y2="37"/><circle cx="24" cy="35" r="9" fill="#ffffff"/><line x1="17" y1="42" x2="12" y2="47" stroke-width="2.5"/><line x1="20" y1="35" x2="28" y2="35"/><line x1="24" y1="31" x2="24" y2="39"/></svg>',
	],
	[
		'title' => 'Tâm thế cộng sự',
		'desc'  => 'VINACOS không chỉ nhận đơn và sản xuất, chúng tôi đồng hành cùng bạn từ câu chuyện thương hiệu, phân tích thị trường, tư vấn công thức phù hợp định vị đến hoàn thiện hồ sơ pháp lý để sản phẩm lưu hành hợp pháp.',
		'svg'   => '<svg class="w-16 h-16 text-[#1e60a3]" viewBox="0 0 64 64" fill="none" stroke="#1e60a3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="32" cy="22" r="7"/><path d="M18 45c0-8 6.3-14 14-14s14 6 14 14v4H18v-4z"/></svg>',
	],
];
?>
<section class="about-4-section section-large bg-slate-50">
	<div class="container">
		<h2 class="site-title text-center" data-aos="fade-up" data-aos-duration="700" data-aos-delay="300">
			<?php echo esc_html( $title ); ?>
		</h2>
		<div class="about-4-wrap mt-10 xl:mt-14" data-aos="fade-up" data-aos-duration="700" data-aos-delay="800">
			<div class="about-4-list">
				<?php foreach ( $stats as $stat ) : ?>
					<div class="about-4-item">
						<div class="icon flex items-center justify-center w-28 h-28 rounded-full bg-white shadow-lg border border-slate-100 mx-auto mb-3">
							<?php echo $stat['svg']; ?>
						</div>
						<div class="caption">
							<h3 class="title"><?php echo esc_html( $stat['number'] ); ?></h3>
							<div class="desc"><?php echo esc_html( $stat['desc'] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="about-4-image text-center">
				<img src="<?php echo esc_url( $center_image ); ?>" alt="NHỮNG CON SỐ BIẾT NÓI VINACOS">
			</div>
		</div>

		<div class="about-42 mt-10 xl:mt-20">
			<h2 class="site-title text-center" data-aos="fade-up" data-aos-duration="700" data-aos-delay="300">
				LỜI HỨA CỦA VINACOS
			</h2>
			<div class="about-42-list mt-10">
				<?php foreach ( $promises as $index => $item ) : ?>
					<div class="about-42-item" data-aos="fade-up" data-aos-duration="700" data-aos-delay="<?php echo ( 300 * ( $index + 1 ) ); ?>">
						<div class="arrow"><?= spl_icon( 'arrow-up-right', '', 20 ) ?></div>
						<div class="icon flex items-center justify-center w-20 h-20 mb-4">
							<?php echo $item['svg']; ?>
						</div>
						<div class="caption">
							<h3 class="title"><?php echo esc_html( $item['title'] ); ?></h3>
							<div class="desc"><?php echo esc_html( $item['desc'] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
