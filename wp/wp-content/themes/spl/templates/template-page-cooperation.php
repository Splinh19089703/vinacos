<?php
/**
 * Template Name: OEM/ODM R&D System — 100% Exact Unila HTML layout for /oem-odm-gia-cong-unila-viet-nam/.
 *
 * @package SPL
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $post;
$page_id       = (int) ( $post->ID ?? get_the_ID() );
$is_en         = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$is_synced     = ( '1' === get_option( 'spl_coop_acf_synced_v1' ) );
$coop_sections = ( $page_id && function_exists( 'get_field' ) ) ? get_field( 'cooperation_sections', $page_id ) : false;
$theme_uri     = get_template_directory_uri();

// Default datasets (used prior to v1 sync or when ACF is inactive)
$default_breadcrumb = $is_en ? 'VINACOS – R&D System & Cosmetics OEM/ODM' : 'VINACOS Việt Nam – Đội ngũ chuyên gia R&D VINACOS';

$default_ben_heading = $is_en ? 'Formulation Innovation & R&D Excellence' : 'Sáng tạo & sáng chế';
$default_ben_intro   = $is_en
	? '<p>VINACOS R&amp;D Center manages the entire lifecycle from formulation development, stability testing, efficacy verification to commercial transfer under strict standards: <b>Efficacy – Safety – Stability – Regulatory Compliance</b>.</p><p>Every formula is backed by dermatological science and clean ingredient sourcing to deliver <b>differentiated, high-performance skincare ready for global market launch</b>.</p>'
	: '<p>Phòng Nghiên cứu &amp; Phát triển (R&amp;D) VINACOS là bộ phận chuyên môn đảm nhận toàn bộ quy trình từ xây dựng công thức, thử nghiệm, đánh giá ổn định đến chuyển giao sản xuất, đáp ứng các tiêu chí cốt lõi: <b>hiệu quả – an toàn – ổn định – tuân thủ pháp lý</b>.</p><p>Mỗi công thức được xây dựng trên nền tảng khoa học, kiểm soát chặt chẽ nguyên liệu và quy trình, ưu tiên thành phần an toàn, thân thiện với da và môi trường, đồng thời tối ưu chi phí, để tạo ra những sản phẩm <b>khác biệt, có giá trị thực tiễn cao và sẵn sàng thương mại hóa</b>.</p>';

$default_accordions = $is_en ? [
	[
		'heading'   => 'SILICONE-FREE WATER-DROPLET CREAM BASE',
		'sub_title' => 'Silicone-free water-droplet cream base',
		'desc'      => 'Water-burst texture breaks into refreshing micro-water droplets upon application, delivering intense cooling & hydration without silicone buildup. VINACOS successfully engineered a 100% silicone-free lipid-friendly base.',
	],
	[
		'heading'   => 'RICE HUSK SILICA EXFOLIATING BASE',
		'sub_title' => 'Bio-sustainable natural rice husk silica scrubbing system',
		'desc'      => 'Uses upcycled silica extracted from Vietnamese agricultural rice husk, replacing microplastics and mined sand. Controlled spherical particles gently cleanse dead skin cells without micro-tears while remaining 100% eco-biodegradable.',
	],
	[
		'heading'   => 'PURIFYING MINERAL CLAY MASK BASE',
		'sub_title' => 'Purifying mineral clay mask base',
		'desc'      => 'Formulated with ultra-purified kaolin & bentonite clay complex to selectively absorb excess sebum & impurities without tight drying sensation.',
	],
	[
		'heading'   => 'CHAMOMILE SOOTHING MUD MASK BASE',
		'sub_title' => 'Chamomile soothing mud mask base',
		'desc'      => 'Combines trace-element mineral mud with Bisabolol-rich Chamomile extract for instant redness relief and skin barrier strengthening.',
	],
] : [
	[
		'heading'   => 'NỀN KEM VỠ NƯỚC FREE SILICONE',
		'sub_title' => 'Nền kem vỡ nước free silicone',
		'desc'      => 'Kem vỡ nước khi thoa lên da, chất kem sẽ vỡ ra thành các giọt nước, các giọt nước đủ lớn để tạo hiệu ứng thị giác tươi mát, cảm quan mang lại hiệu quả làm mát và dưỡng ẩm. VINACOS đã nghiên cứu thành công nền vỡ nước free silicone hoàn toàn thân thiện với màng lipit làn da.',
	],
	[
		'heading'   => 'NỀN TẨY TẾ BÀO CHẾT SILICA NGUỒN GỐC TỪ VỎ TRẤU VIỆT NAM',
		'sub_title' => 'Nền tẩy tế bào chết silica từ vỏ trấu Việt Nam – Hệ hạt sinh học bền vững',
		'desc'      => 'Nền chất sử dụng silica chiết xuất từ vỏ trấu Việt Nam, nguồn nguyên liệu tái tạo từ phụ phẩm nông nghiệp, thay thế silica truyền thống từ cát. Hạt silica được kiểm soát kích thước và bề mặt, giúp loại bỏ tế bào chết nhẹ nhàng mà không gây trầy xước vi mô.',
	],
	[
		'heading'   => 'MẶT NẠ ĐẤT SÉT',
		'sub_title' => 'Mặt nạ đất sét',
		'desc'      => 'Nền chất được xây dựng trên tổ hợp đất sét khoáng (kaolin, bentonite hoặc các biến thể xử lý tinh sạch) với khả năng hấp phụ chọn lọc bã nhờn và tạp chất trong lỗ chân lông.',
	],
	[
		'heading'   => 'MẶT NẠ BÙN CÚC LA MÃ',
		'sub_title' => 'Mặt nạ bùn cúc La Mã',
		'desc'      => 'Mặt nạ bùn sử dụng bùn khoáng giàu vi lượng kết hợp với chiết xuất Cúc La Mã mang lại hiệu quả làm dịu da tức thì và phục hồi hàng rào bảo vệ da.',
	],
];

$default_pt_desc = $is_en
	? '<p><strong>Academic R&amp;D Collaboration</strong></p><p>VINACOS R&amp;D Center collaborates closely with academic researchers and university faculties specializing in Organic &amp; Cosmetic Technology to ensure high scientific rigor and practical efficacy.</p><p><b>Global Raw Material Suppliers</b></p><p>We partner with world-renowned ingredient suppliers meeting international cGMP/FDA standards for full traceability, active purity, and formula innovation.</p>'
	: '<p><strong>Đồng hành nghiên cứu</strong></p><p>Phòng R&amp;D VINACOS hợp tác chặt chẽ với các đối tác nghiên cứu học thuật nhằm nâng cao chiều sâu khoa học trong phát triển sản phẩm.</p><p><b>Đồng hành nguyên liệu</b></p><p>Đội ngũ R&amp;D tại VINACOS cộng tác cùng các nhà cung cấp nguyên liệu uy tín toàn cầu, đáp ứng tiêu chí chất lượng, pháp lý và nguồn gốc rõ ràng.</p>';

$default_proc_intro = $is_en
	? '<p style="margin-bottom: 14px;">VINACOS Cosmetics R&amp;D Center operates on two core pillars: <strong style="color: #1e293b; font-weight: 600;">Active Raw Ingredient Research</strong> and <strong style="color: #1e293b; font-weight: 600;">Product Formulation Engineering</strong> to build a solid scientific foundation for clean, safe, and commercially viable beauty solutions.</p><p>Ingredient research focuses on standardization and bio-activity optimization, while product research delivers complete, sensory-pleasing formulations tailored for automated scale-up manufacturing.</p>'
	: '<p style="margin-bottom: 14px;">Phòng Nghiên cứu &amp; Phát triển (R&amp;D) mỹ phẩm VINACOS định hướng hoạt động dựa trên hai trụ cột là: <strong style="color: #1e293b; font-weight: 600;">nghiên cứu nguyên liệu</strong> và <strong style="color: #1e293b; font-weight: 600;">nghiên cứu sản phẩm</strong> nhằm xây dựng nền tảng khoa học vững chắc cho các giải pháp mỹ phẩm an toàn, hiệu quả và có tính ứng dụng cao.</p><p>Trong đó, nghiên cứu nguyên liệu hướng đến đánh giá, chuẩn hóa và tối ưu khả năng ứng dụng, còn nghiên cứu sản phẩm tập trung phát triển công thức hoàn chỉnh, tối ưu hiệu quả, cảm quan và khả năng sản xuất, đáp ứng nhu cầu thị trường.</p>';

$default_pillars = $is_en ? [
	[
		'title'    => '01 - Benchmarking Existing Products',
		'subtitle' => 'For brands seeking formula re-engineering or performance upgrading.',
		'desc'     => 'VINACOS analyzes benchmark sample formulations, identifies key active ratios, and re-designs formulas with optimized ingredients for superior stability & cost efficiency.',
	],
	[
		'title'    => '02 - Active Ingredient Centric',
		'subtitle' => 'For brands with a designated hero active ingredient.',
		'desc'     => 'Starting from specified hero actives, VINACOS evaluates physicochemical properties, selects compatible delivery systems, and builds optimal cosmetic bases.',
	],
	[
		'title'    => '03 - Natural Botanical Formulation',
		'subtitle' => 'For brands focused on organic, herbal & eco-friendly lines.',
		'desc'     => 'VINACOS selects bio-proven botanical extracts, optimizes extraction purity, and formulates stable natural skincare preserving full herbal potency.',
	],
] : [
	[
		'title'    => '01 - Từ sản phẩm có sẵn',
		'subtitle' => 'Dành cho doanh nghiệp muốn tái tạo, cải tiến hoặc phát triển sản phẩm tương đương.',
		'desc'     => 'VINACOS phân tích công thức mẫu, xác định vai trò từng thành phần, sau đó thiết kế lại công thức với nguyên liệu phù hợp, đảm bảo tương đương về hiệu quả, tối ưu hơn về chi phí và độ ổn định.',
	],
	[
		'title'    => '02 - Từ hoạt chất chính',
		'subtitle' => 'Dành cho doanh nghiệp đã có định hướng thành phần cốt lõi.',
		'desc'     => 'Từ hoạt chất chỉ định, VINACOS đánh giá tính chất lý hóa, lựa chọn hệ nền và tá dược tương thích, xây dựng công thức phù hợp với dạng bào chế và đối tượng mục tiêu – đảm bảo hoạt chất phát huy tối đa hiệu quả.',
	],
	[
		'title'    => '03 - Từ nguyên liệu thảo mộc',
		'subtitle' => 'Dành cho doanh nghiệp phát triển theo hướng thiên nhiên, thảo dược.',
		'desc'     => 'VINACOS lựa chọn nguyên liệu có công dụng được kiểm chứng, tối ưu phương pháp chiết xuất, phối hợp hoạt chất bổ trợ và xây dựng công thức ổn định – giữ trọn hiệu quả từ thiên nhiên trong từng sản phẩm.',
	],
];

// Locate Hero layout
$hero_sec = null;
if ( is_array( $coop_sections ) ) {
	foreach ( $coop_sections as $sec ) {
		if ( in_array( $sec['acf_fc_layout'] ?? '', [ 'cooperation_hero', 'hero' ], true ) ) {
			$hero_sec = $sec;
			break;
		}
	}
}

$hero_disabled = is_array( $hero_sec ) && ! empty( $hero_sec['disable'] );
if ( ! $hero_disabled ) :
	$hero_banner_img = '';
	$hero_mobile_img = '';

	if ( $is_synced && is_array( $hero_sec ) && array_key_exists( 'banner_image', $hero_sec ) ) {
		$hero_banner_img = function_exists( 'spl_get_image_url' ) ? spl_get_image_url( $hero_sec['banner_image'] ) : '';
		$hero_mobile_img = function_exists( 'spl_get_image_url' ) ? spl_get_image_url( $hero_sec['banner_image_mobile'] ?? null ) : '';
	} else {
		if ( $page_id && has_post_thumbnail( $page_id ) ) {
			$hero_banner_img = (string) get_the_post_thumbnail_url( $page_id, 'full' );
		}
		if ( '' === $hero_banner_img && is_array( $hero_sec ) && ! empty( $hero_sec['banner_image'] ) && function_exists( 'spl_get_image_url' ) ) {
			$hero_banner_img = spl_get_image_url( $hero_sec['banner_image'] );
		}
		if ( '' === $hero_banner_img ) {
			$rd_banner_rel   = '/static/img/banner/' . ( $is_en ? 'rd-system-banner-en.jpg' : 'rd-system-banner-vi.jpg' );
			$hero_banner_img = file_exists( get_template_directory() . $rd_banner_rel )
				? $theme_uri . $rd_banner_rel
				: $theme_uri . '/static/img/banner/' . ( $is_en ? 'brand-banner-en.jpg' : 'brand-banner-vi.jpg' );
		}
	}

	if ( $is_synced && is_array( $hero_sec ) && array_key_exists( 'breadcrumb_label', $hero_sec ) ) {
		$breadcrumb_label = (string) $hero_sec['breadcrumb_label'];
	} else {
		$breadcrumb_label = ! empty( $hero_sec['breadcrumb_label'] ) ? (string) $hero_sec['breadcrumb_label'] : $default_breadcrumb;
	}
	?>
	<?php if ( '' !== $hero_banner_img ) : ?>
		<section class="banner-child">
			<div class="swiper">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						<div class="image img-cover">
							<?php if ( '' !== $hero_mobile_img ) : ?>
								<picture>
									<source media="(max-width: 767px)" srcset="<?php echo esc_url( $hero_mobile_img ); ?>">
									<img src="<?php echo esc_url( $hero_banner_img ); ?>" fetchpriority="high" alt="<?php echo esc_attr( '' !== $breadcrumb_label ? $breadcrumb_label : 'OEM/ODM VINACOS' ); ?>">
								</picture>
							<?php else : ?>
								<img src="<?php echo esc_url( $hero_banner_img ); ?>" fetchpriority="high" alt="<?php echo esc_attr( '' !== $breadcrumb_label ? $breadcrumb_label : 'OEM/ODM VINACOS' ); ?>">
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( '' !== trim( $breadcrumb_label ) ) : ?>
		<section class="global-breadcrumb">
			<div class="container">
				<nav aria-label="breadcrumbs" class="rank-math-breadcrumb">
					<p>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $is_en ? 'Home' : 'Trang chủ' ); ?></a>
						<span class="separator"> - </span>
						<span class="last"><?php echo esc_html( $breadcrumb_label ); ?></span>
					</p>
				</nav>
			</div>
		</section>
	<?php endif; ?>
<?php endif; ?>

<?php
// Prepare ordered list of body sections to render
if ( $is_synced && is_array( $coop_sections ) && ! empty( $coop_sections ) ) {
	$render_sections = $coop_sections;
} else {
	$existing_by_layout = [];
	if ( is_array( $coop_sections ) ) {
		foreach ( $coop_sections as $s ) {
			if ( is_array( $s ) && ! empty( $s['acf_fc_layout'] ) && ! isset( $existing_by_layout[ $s['acf_fc_layout'] ] ) ) {
				$existing_by_layout[ $s['acf_fc_layout'] ] = $s;
			}
		}
	}
	$render_sections = [
		$existing_by_layout['cooperation_benefits'] ?? [ 'acf_fc_layout' => 'cooperation_benefits' ],
		$existing_by_layout['cooperation_formulation'] ?? [ 'acf_fc_layout' => 'cooperation_formulation' ],
		$existing_by_layout['cooperation_partners'] ?? [ 'acf_fc_layout' => 'cooperation_partners' ],
		$existing_by_layout['cooperation_process'] ?? [ 'acf_fc_layout' => 'cooperation_process' ],
	];
}

foreach ( $render_sections as $sec ) :
	if ( ! is_array( $sec ) || ! empty( $sec['disable'] ) ) {
		continue;
	}
	$layout = (string) ( $sec['acf_fc_layout'] ?? '' );

	if ( in_array( $layout, [ 'cooperation_hero', 'hero', 'cooperation_form' ], true ) ) {
		continue;
	}

	// -----------------------------------------------------------------
	// 2. SÁNG TẠO & SÁNG CHẾ (.oem-1-section)
	// -----------------------------------------------------------------
	if ( 'cooperation_benefits' === $layout ) :
		$has_sec     = $is_synced && array_key_exists( 'section_heading', $sec );
		$ben_heading = $has_sec
			? (string) $sec['section_heading']
			: ( ! empty( $sec['section_heading'] ) ? (string) $sec['section_heading'] : $default_ben_heading );

		$ben_intro = ( $is_synced && array_key_exists( 'intro_content', $sec ) )
			? (string) $sec['intro_content']
			: ( ! empty( $sec['intro_content'] ) ? (string) $sec['intro_content'] : $default_ben_intro );

		$ben_items = [];
		if ( $is_synced && array_key_exists( 'benefit_items', $sec ) ) {
			if ( is_array( $sec['benefit_items'] ) ) {
				foreach ( array_values( $sec['benefit_items'] ) as $idx => $b_item ) {
					$b_img = function_exists( 'spl_get_image_url' ) ? spl_get_image_url( $b_item['image'] ?? null ) : '';
					if ( '' !== $b_img ) {
						$ben_items[] = [
							'image' => $b_img,
							'title' => ! empty( $b_item['title'] ) ? (string) $b_item['title'] : 'R&D Publication ' . ( $idx + 1 ),
						];
					}
				}
			}
		} elseif ( ! empty( $sec['benefit_items'] ) && is_array( $sec['benefit_items'] ) ) {
			foreach ( array_values( $sec['benefit_items'] ) as $idx => $b_item ) {
				$b_img = function_exists( 'spl_get_image_url' ) ? spl_get_image_url( $b_item['image'] ?? null ) : '';
				if ( '' !== $b_img ) {
					$ben_items[] = [
						'image' => $b_img,
						'title' => ! empty( $b_item['title'] ) ? (string) $b_item['title'] : 'R&D Publication ' . ( $idx + 1 ),
					];
				}
			}
		}
		?>
		<section class="oem-1-section section-t-large section-b-small">
			<div class="container">
				<div class="block-content text-center">
					<?php if ( '' !== trim( $ben_heading ) ) : ?>
						<h1 class="site-title" data-aos="fade-up" data-aos-duration="700" data-aos-delay="150">
							<?php echo esc_html( $ben_heading ); ?>
						</h1>
					<?php endif; ?>
					<?php if ( '' !== trim( wp_strip_all_tags( $ben_intro ) ) ) : ?>
						<div class="site-desc mt-3" data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">
							<?php echo wp_kses_post( $ben_intro ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $ben_items ) ) : ?>
					<div class="oem-1-list mt-10">
						<?php foreach ( $ben_items as $b_idx => $b_item ) :
							$delay = 200 + ( $b_idx * 150 );
							?>
							<div class="oem-1-item" data-aos="fade-up" data-aos-duration="700" data-aos-delay="<?php echo esc_attr( (string) $delay ); ?>">
								<div class="image">
									<img class="lozad" src="<?php echo esc_url( $b_item['image'] ); ?>" data-src="<?php echo esc_url( $b_item['image'] ); ?>" loading="lazy" alt="<?php echo esc_attr( $b_item['title'] ); ?>">
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php

	// -----------------------------------------------------------------
	// 3. NGHIÊN CỨU NỀN CHẤT (.oem-2-section)
	// -----------------------------------------------------------------
	elseif ( 'cooperation_formulation' === $layout ) :
		$fm_title = ( $is_synced && array_key_exists( 'title', $sec ) )
			? (string) $sec['title']
			: ( ! empty( $sec['title'] ) ? (string) $sec['title'] : ( $is_en ? 'Formulation Base R&D' : 'Nghiên cứu nền chất' ) );

		$default_fm_img = $theme_uri . '/static/img/nghien-cuu-nen-chat-vinacos.png';
		if ( $is_synced && array_key_exists( 'image', $sec ) ) {
			$fm_img = function_exists( 'spl_get_image_url' ) ? spl_get_image_url( $sec['image'] ) : '';
		} else {
			$legacy_fm = function_exists( 'get_field' ) ? ( get_field( 'rd_image', $page_id ) ?: get_field( 'formulation_image', $page_id ) ) : null;
			$fm_img    = ! empty( $legacy_fm ) && function_exists( 'spl_get_image_url' )
				? spl_get_image_url( $legacy_fm, $default_fm_img )
				: $default_fm_img;
		}

		$accordions = ( $is_synced && array_key_exists( 'accordion_items', $sec ) )
			? ( is_array( $sec['accordion_items'] ) ? array_values( $sec['accordion_items'] ) : [] )
			: ( ! empty( $sec['accordion_items'] ) && is_array( $sec['accordion_items'] ) ? array_values( $sec['accordion_items'] ) : $default_accordions );
		?>
		<section class="oem-2-section section-small">
			<div class="container">
				<div class="row -mt-10 items-center">
					<div class="col w-full mt-10 lg:w-1/2">
						<div class="block-content" data-aos="fade-right" data-aos-duration="700" data-aos-delay="200">
							<?php if ( '' !== trim( $fm_title ) ) : ?>
								<h2 class="site-title">
									<?php echo esc_html( $fm_title ); ?>
								</h2>
							<?php endif; ?>
							<?php if ( ! empty( $accordions ) ) : ?>
								<div class="accordion-list mt-6">
									<?php foreach ( $accordions as $a_idx => $acc ) :
										$is_first  = ( 0 === $a_idx );
										$acc_head  = (string) ( $acc['heading'] ?? '' );
										$acc_sub   = (string) ( $acc['sub_title'] ?? '' );
										$acc_desc  = (string) ( $acc['desc'] ?? '' );
										?>
										<div class="accordion-item<?php echo $is_first ? ' active' : ''; ?>">
											<div class="accordion-head">
												<h3 class="accordion-title">
													<?php echo esc_html( $acc_head ); ?>
												</h3>
												<i class="accordion-icon fa-light fa-plus"></i>
											</div>
											<div class="accordion-content"<?php echo $is_first ? ' style="display: block;"' : ''; ?>>
												<div class="full-content">
													<?php if ( '' !== trim( $acc_sub ) ) : ?>
														<h3><strong><?php echo esc_html( $acc_sub ); ?></strong></h3>
													<?php endif; ?>
													<?php if ( '' !== trim( $acc_desc ) ) : ?>
														<p><?php echo wp_kses_post( nl2br( esc_html( $acc_desc ) ) ); ?></p>
													<?php endif; ?>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<?php if ( '' !== $fm_img ) : ?>
						<div class="col w-full mt-10 lg:w-1/2">
							<div class="swiper-relative one-slider is-page" data-aos="fade-left" data-aos-duration="700" data-aos-delay="250">
								<div class="swiper">
									<div class="swiper-wrapper">
										<div class="swiper-slide">
											<div class="image img-cover">
												<img class="lozad" src="<?php echo esc_url( $fm_img ); ?>" data-src="<?php echo esc_url( $fm_img ); ?>" width="640" height="888" loading="lazy" alt="<?php echo esc_attr( '' !== trim( $fm_title ) ? $fm_title : 'VINACOS R&D Formulation Base' ); ?>">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php

	// -----------------------------------------------------------------
	// 4. ĐỐI TÁC (.oem-3-section)
	// -----------------------------------------------------------------
	elseif ( 'cooperation_partners' === $layout ) :
		$pt_title = ( $is_synced && array_key_exists( 'title', $sec ) )
			? (string) $sec['title']
			: ( ! empty( $sec['title'] ) ? (string) $sec['title'] : ( $is_en ? 'Research & Material Partners' : 'Đối tác' ) );

		$pt_desc = ( $is_synced && array_key_exists( 'desc', $sec ) )
			? (string) $sec['desc']
			: ( ! empty( $sec['desc'] ) ? (string) $sec['desc'] : $default_pt_desc );

		$default_pt_img = $theme_uri . '/static/img/vinacos/rd-material-partners.jpg';
		$pt_slides      = [];

		if ( $is_synced && array_key_exists( 'partner_items', $sec ) ) {
			if ( is_array( $sec['partner_items'] ) ) {
				foreach ( array_values( $sec['partner_items'] ) as $p_item ) {
					$p_img = function_exists( 'spl_get_image_url' ) ? spl_get_image_url( $p_item['image'] ?? null ) : '';
					if ( '' !== $p_img ) {
						$pt_slides[] = [
							'image' => $p_img,
							'title' => ! empty( $p_item['title'] ) ? (string) $p_item['title'] : 'Raw Materials Partner',
						];
					}
				}
			}
		} else {
			$legacy_pt = function_exists( 'get_field' ) ? ( get_field( 'partner_image', $page_id ) ?: get_field( 'partners_image', $page_id ) ) : null;
			$p_img     = ! empty( $legacy_pt ) && function_exists( 'spl_get_image_url' )
				? spl_get_image_url( $legacy_pt, $default_pt_img )
				: $default_pt_img;
			$pt_slides[] = [
				'image' => $p_img,
				'title' => 'Raw Materials Partner',
			];
		}
		?>
		<section class="oem-3-section section-small">
			<div class="container">
				<div class="block-content text-center">
					<?php if ( '' !== trim( $pt_title ) ) : ?>
						<h2 class="site-title" data-aos="fade-up" data-aos-duration="700" data-aos-delay="150">
							<?php echo esc_html( $pt_title ); ?>
						</h2>
					<?php endif; ?>
					<?php if ( '' !== trim( wp_strip_all_tags( $pt_desc ) ) ) : ?>
						<div class="site-desc mt-3" data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">
							<?php echo wp_kses_post( $pt_desc ); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $pt_slides ) ) : ?>
					<div class="swiper-relative two-slider is-page mt-10" data-aos="fade-up" data-aos-duration="700" data-aos-delay="300">
						<div class="swiper">
							<div class="swiper-wrapper">
								<?php foreach ( $pt_slides as $p_slide ) : ?>
									<div class="swiper-slide">
										<div class="oem-3-item">
											<div class="image img-cover">
												<img class="lozad" src="<?php echo esc_url( $p_slide['image'] ); ?>" data-src="<?php echo esc_url( $p_slide['image'] ); ?>" width="680" height="454" loading="lazy" alt="<?php echo esc_attr( $p_slide['title'] ); ?>">
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<?php if ( count( $pt_slides ) > 1 ) : ?>
							<div class="mobile-only">
								<div class="swiper-pagination"></div>
							</div>
							<div class="desktop-only">
								<div class="swiper-button is-abs">
									<div class="button-prev"><i class="fa-light fa-chevron-left"></i></div>
									<div class="button-next"><i class="fa-light fa-chevron-right"></i></div>
								</div>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php

	// -----------------------------------------------------------------
	// 5. QUY TRÌNH NGHIÊN CỨU VÀ SẢN XUẤT (.oem-5-section)
	// -----------------------------------------------------------------
	elseif ( 'cooperation_process' === $layout ) :
		$proc_heading = ( $is_synced && array_key_exists( 'section_heading', $sec ) )
			? (string) $sec['section_heading']
			: ( ! empty( $sec['section_heading'] ) ? (string) $sec['section_heading'] : ( $is_en ? 'R&D & Manufacturing Process' : 'Quy trình nghiên cứu và sản xuất' ) );

		$proc_sub = ( $is_synced && array_key_exists( 'subtitle', $sec ) )
			? (string) $sec['subtitle']
			: (string) ( $sec['subtitle'] ?? '' );

		$default_lab_img = $theme_uri . '/static/img/vinacos/rd-lab-process.jpg';
		if ( $is_synced && array_key_exists( 'lab_image', $sec ) ) {
			$lab_img = function_exists( 'spl_get_image_url' ) ? spl_get_image_url( $sec['lab_image'] ) : '';
		} else {
			$legacy_lab = function_exists( 'get_field' ) ? ( get_field( 'lab_image', $page_id ) ?: get_field( 'process_image', $page_id ) ) : null;
			$lab_img    = ! empty( $legacy_lab ) && function_exists( 'spl_get_image_url' )
				? spl_get_image_url( $legacy_lab, $default_lab_img )
				: $default_lab_img;
		}

		$proc_intro = ( $is_synced && array_key_exists( 'intro_desc', $sec ) )
			? (string) $sec['intro_desc']
			: ( ! empty( $sec['intro_desc'] ) ? (string) $sec['intro_desc'] : $default_proc_intro );

		$pillars = ( $is_synced && array_key_exists( 'rd_pillars', $sec ) )
			? ( is_array( $sec['rd_pillars'] ) ? array_values( $sec['rd_pillars'] ) : [] )
			: ( ! empty( $sec['rd_pillars'] ) && is_array( $sec['rd_pillars'] ) ? array_values( $sec['rd_pillars'] ) : $default_pillars );
		?>
		<section class="oem-5-section section-t-small section-b-large">
			<div class="container">
				<div class="block-content text-center">
					<?php if ( '' !== trim( $proc_heading ) ) : ?>
						<h2 class="site-title" data-aos="fade-up" data-aos-duration="700" data-aos-delay="150">
							<?php echo esc_html( $proc_heading ); ?>
						</h2>
					<?php endif; ?>
					<?php if ( '' !== trim( $proc_sub ) ) : ?>
						<div class="site-desc mt-3" data-aos="fade-up" data-aos-duration="700" data-aos-delay="250">
							<p><?php echo esc_html( $proc_sub ); ?></p>
						</div>
					<?php endif; ?>
				</div>

				<div class="oem-5-list mt-10 xl:mt-14">
					<?php if ( '' !== $lab_img || '' !== trim( wp_strip_all_tags( $proc_intro ) ) ) : ?>
						<div class="oem-5-item" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">
							<?php if ( '' !== $lab_img ) : ?>
								<div class="image img-cover">
									<img class="lozad" src="<?php echo esc_url( $lab_img ); ?>" data-src="<?php echo esc_url( $lab_img ); ?>" width="400" height="533" loading="lazy" alt="<?php echo esc_attr( '' !== trim( $proc_heading ) ? $proc_heading : 'VINACOS R&D Center' ); ?>">
								</div>
							<?php endif; ?>
							<?php if ( '' !== trim( wp_strip_all_tags( $proc_intro ) ) ) : ?>
								<div class="caption">
									<div class="desc" style="max-width: 540px; font-size: 15px; line-height: 1.65; color: #475569;">
										<?php echo wp_kses_post( $proc_intro ); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php foreach ( $pillars as $p_idx => $pil ) :
						$p_delay    = 250 + ( $p_idx * 150 );
						$pil_title  = (string) ( $pil['title'] ?? '' );
						$pil_sub    = (string) ( $pil['subtitle'] ?? '' );
						$pil_desc   = (string) ( $pil['desc'] ?? '' );
						?>
						<div class="oem-5-item" data-aos="fade-up" data-aos-duration="700" data-aos-delay="<?php echo esc_attr( (string) $p_delay ); ?>">
							<div class="caption">
								<?php if ( '' !== trim( $pil_title ) ) : ?>
									<h3 class="title">
										<?php echo esc_html( $pil_title ); ?>
									</h3>
								<?php endif; ?>
								<?php if ( '' !== trim( $pil_sub ) ) : ?>
									<p class="job mt-3">
										<?php echo esc_html( $pil_sub ); ?>
									</p>
								<?php endif; ?>
								<?php if ( '' !== trim( $pil_desc ) ) : ?>
									<div class="desc mt-6">
										<p><?php echo wp_kses_post( nl2br( esc_html( $pil_desc ) ) ); ?></p>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	endif;
endforeach;

get_footer();
