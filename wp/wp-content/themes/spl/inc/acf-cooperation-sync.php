<?php
/**
 * One-time non-destructive ACF data sync for VINACOS R&D System / OEM-ODM pages.
 *
 * Ensures WP-Admin ACF Flexible Content (`cooperation_sections`) matches 100% of
 * the live R&D System frontend sections while preserving all user-uploaded media
 * and customized fields, without affecting any other page.
 *
 * @package SPL
 */

declare(strict_types=1);

use SPL\Modules\PLL\ACF\Dispatcher;

defined( 'ABSPATH' ) || exit;

add_action( 'wp_loaded', 'spl_maybe_sync_cooperation_page_acf_data', 23 );

/**
 * Run one-time non-destructive migration when `spl_coop_acf_synced_v1` is not yet set.
 */
function spl_maybe_sync_cooperation_page_acf_data(): void {
	if ( '1' === get_option( 'spl_coop_acf_synced_v1' ) ) {
		return;
	}

	if ( wp_installing() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
		return;
	}

	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) || ! function_exists( 'acf_get_field_group' ) ) {
		return;
	}

	if ( ! function_exists( 'spl_resolve_about_attachment_id' ) || ! function_exists( 'spl_ensure_about_static_attachment' ) ) {
		return;
	}

	if ( false !== get_transient( 'spl_coop_acf_sync_lock' ) ) {
		return;
	}
	set_transient( 'spl_coop_acf_sync_lock', '1', 120 );

	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 120 );
	}

	$had_pll_filter = false;
	if ( class_exists( Dispatcher::class ) && false !== has_filter( 'acf/update_value', [ Dispatcher::class, 'update' ] ) ) {
		remove_filter( 'acf/update_value', [ Dispatcher::class, 'update' ], 5 );
		$had_pll_filter = true;
	}

	try {
		$coop_ids = [ 944, 926 ];
		if ( function_exists( 'pll_get_post' ) ) {
			foreach ( [ 944, 926 ] as $base_id ) {
				$en_id = (int) pll_get_post( $base_id, 'en' );
				if ( $en_id > 0 ) {
					$coop_ids[] = $en_id;
				}
			}
		}

		$tpl_pages = get_posts( [
			'post_type'      => 'page',
			'post_status'    => [ 'publish', 'draft', 'private', 'pending' ],
			'posts_per_page' => 20,
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'templates/template-page-cooperation.php',
			'lang'           => '',
		] );

		if ( is_array( $tpl_pages ) ) {
			foreach ( $tpl_pages as $pid ) {
				$coop_ids[] = (int) $pid;
			}
		}
		$coop_ids = array_values( array_unique( array_filter( array_map( 'intval', $coop_ids ) ) ) );

		$media_map = [
			'banner_vi'   => spl_ensure_about_static_attachment( 'static/img/banner/brand-banner-vi.jpg', 'VINACOS Banner Hệ Thống R&D' ),
			'formulation' => spl_ensure_about_static_attachment( 'static/img/nghien-cuu-nen-chat-vinacos.png', 'VINACOS Nghiên Cứu Nền Chất' ),
			'partners'    => spl_ensure_about_static_attachment( 'static/img/vinacos/rd-material-partners.jpg', 'VINACOS Đối Tác Nguyên Liệu' ),
			'lab_process' => spl_ensure_about_static_attachment( 'static/img/vinacos/rd-lab-process.jpg', 'VINACOS Phòng Lab Nghiên Cứu R&D' ),
			'pub_1'       => spl_find_attachment_by_basename( 'bai-bao-1.png' ),
			'pub_2'       => spl_find_attachment_by_basename( 'bai-bao-2.png' ),
			'pub_3'       => spl_find_attachment_by_basename( 'bai-bao-3.png' ),
		];

		foreach ( $coop_ids as $post_id ) {
			$post_obj = get_post( $post_id );
			if ( ! $post_obj instanceof WP_Post || 'page' !== $post_obj->post_type ) {
				continue;
			}
			spl_sync_single_cooperation_page_acf( $post_id, $media_map );
		}

		update_option( 'spl_coop_acf_synced_v1', '1', true );
	} finally {
		delete_transient( 'spl_coop_acf_sync_lock' );
		if ( $had_pll_filter ) {
			add_filter( 'acf/update_value', [ Dispatcher::class, 'update' ], 5, 3 );
		}
	}
}

/**
 * Find an existing attachment ID in WP Media Library by file basename without creating a new upload.
 */
function spl_find_attachment_by_basename( string $basename ): int {
	global $wpdb;
	if ( ! isset( $wpdb ) || '' === $basename ) {
		return 0;
	}

	$like_suffix = '%/' . $wpdb->esc_like( $basename );
	$existing_id = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT p.ID
			 FROM {$wpdb->posts} p
			 INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
			 WHERE p.post_type = 'attachment'
			   AND pm.meta_key = '_wp_attached_file'
			   AND (pm.meta_value = %s OR pm.meta_value LIKE %s)
			 ORDER BY p.ID DESC
			 LIMIT 1",
			$basename,
			$like_suffix
		)
	);

	return ( $existing_id > 0 && wp_get_attachment_url( $existing_id ) ) ? $existing_id : 0;
}

/**
 * Resolve attachment ID for target language (handles Polylang media_support when active).
 */
function spl_coop_media_for_lang( int $aid, bool $is_en ): int {
	if ( $aid <= 0 ) {
		return 0;
	}
	if ( $is_en && function_exists( 'spl_resolve_about_en_media_id' ) ) {
		return spl_resolve_about_en_media_id( $aid );
	}
	return $aid;
}

/**
 * Non-destructively sync a single R&D / Cooperation page's `cooperation_sections` Flexible Content.
 *
 * @param int               $post_id   Cooperation page post ID.
 * @param array<string,int> $media_map Imported static asset attachment IDs.
 */
function spl_sync_single_cooperation_page_acf( int $post_id, array $media_map ): void {
	$lang  = function_exists( 'pll_get_post_language' ) ? (string) pll_get_post_language( $post_id, 'slug' ) : '';
	$is_en = ( 'en' === $lang );

	$current_rows = get_field( 'cooperation_sections', $post_id );
	if ( ! is_array( $current_rows ) ) {
		$current_rows = [];
	}

	$by_layout = [];
	foreach ( $current_rows as $row ) {
		if ( ! is_array( $row ) || empty( $row['acf_fc_layout'] ) ) {
			continue;
		}
		$lk = (string) $row['acf_fc_layout'];
		if ( ! isset( $by_layout[ $lk ] ) ) {
			$by_layout[ $lk ] = $row;
		}
	}

	// 1. Hero Banner & Breadcrumb
	$old_hero = $by_layout['cooperation_hero'] ?? $by_layout['hero'] ?? [];
	$thumb_id = function_exists( 'get_post_thumbnail_id' ) ? (int) get_post_thumbnail_id( $post_id ) : 0;
	$hero_id  = spl_resolve_about_attachment_id(
		$old_hero['banner_image'] ?? 0,
		$thumb_id > 0 ? $thumb_id : spl_coop_media_for_lang( $media_map['banner_vi'] ?? 0, $is_en )
	);
	$hero_mob = spl_resolve_about_attachment_id( $old_hero['banner_image_mobile'] ?? 0, 0 );

	$row_hero = [
		'acf_fc_layout'       => 'cooperation_hero',
		'disable'             => ! empty( $old_hero['disable'] ) ? 1 : 0,
		'banner_image'        => $hero_id > 0 ? $hero_id : '',
		'banner_image_mobile' => $hero_mob > 0 ? $hero_mob : '',
		'breadcrumb_label'    => ! empty( $old_hero['breadcrumb_label'] )
			? (string) $old_hero['breadcrumb_label']
			: ( $is_en ? 'VINACOS – R&D System & Cosmetics OEM/ODM' : 'VINACOS Việt Nam – Đội ngũ chuyên gia R&D VINACOS' ),
	];

	// 2. Sáng tạo & Sáng chế (.oem-1-section)
	$old_ben       = $by_layout['cooperation_benefits'] ?? [];
	$default_intro = $is_en
		? '<p>VINACOS R&amp;D Center manages the entire lifecycle from formulation development, stability testing, efficacy verification to commercial transfer under strict standards: <strong>Efficacy – Safety – Stability – Regulatory Compliance</strong>.</p><p>Every formula is backed by dermatological science and clean ingredient sourcing to deliver <strong>differentiated, high-performance skincare ready for global market launch</strong>.</p>'
		: '<p>Phòng Nghiên cứu &amp; Phát triển (R&amp;D) VINACOS là bộ phận chuyên môn đảm nhận toàn bộ quy trình từ xây dựng công thức, thử nghiệm, đánh giá ổn định đến chuyển giao sản xuất, đáp ứng các tiêu chí cốt lõi: <strong>hiệu quả – an toàn – ổn định – tuân thủ pháp lý</strong>.</p><p>Mỗi công thức được xây dựng trên nền tảng khoa học, kiểm soát chặt chẽ nguyên liệu và quy trình, ưu tiên thành phần an toàn, thân thiện với da và môi trường, đồng thời tối ưu chi phí, để tạo ra những sản phẩm <strong>khác biệt, có giá trị thực tiễn cao và sẵn sàng thương mại hóa</strong>.</p>';

	$benefit_items = [];
	if ( ! empty( $old_ben['benefit_items'] ) && is_array( $old_ben['benefit_items'] ) ) {
		foreach ( array_values( $old_ben['benefit_items'] ) as $i => $it ) {
			$aid = spl_resolve_about_attachment_id( $it['image'] ?? 0, 0 );
			if ( $aid > 0 ) {
				$benefit_items[] = [
					'image' => $aid,
					'title' => ! empty( $it['title'] ) ? (string) $it['title'] : 'R&D Publication ' . ( $i + 1 ),
				];
			}
		}
	}
	if ( empty( $benefit_items ) ) {
		foreach ( [ 'pub_1', 'pub_2', 'pub_3' ] as $idx => $pk ) {
			$aid = spl_coop_media_for_lang( $media_map[ $pk ] ?? 0, $is_en );
			if ( $aid > 0 ) {
				$benefit_items[] = [
					'image' => $aid,
					'title' => 'R&D Publication ' . ( $idx + 1 ),
				];
			}
		}
	}

	$row_benefits = [
		'acf_fc_layout'   => 'cooperation_benefits',
		'disable'         => ! empty( $old_ben['disable'] ) ? 1 : 0,
		'section_heading' => ! empty( $old_ben['section_heading'] )
			? (string) $old_ben['section_heading']
			: ( $is_en ? 'Formulation Innovation & R&D Excellence' : 'Sáng tạo & sáng chế' ),
		'intro_content'   => ! empty( $old_ben['intro_content'] )
			? (string) $old_ben['intro_content']
			: $default_intro,
		'benefit_items'   => $benefit_items,
	];

	// 3. Nghiên cứu nền chất (.oem-2-section)
	$old_fm        = $by_layout['cooperation_formulation'] ?? [];
	$legacy_fm_raw = get_field( 'rd_image', $post_id ) ?: get_field( 'formulation_image', $post_id );
	$fm_img_id     = spl_resolve_about_attachment_id(
		$old_fm['image'] ?? $legacy_fm_raw ?? 0,
		spl_coop_media_for_lang( $media_map['formulation'] ?? 0, $is_en )
	);

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

	$row_formulation = [
		'acf_fc_layout'   => 'cooperation_formulation',
		'disable'         => ! empty( $old_fm['disable'] ) ? 1 : 0,
		'title'           => ! empty( $old_fm['title'] )
			? (string) $old_fm['title']
			: ( $is_en ? 'Formulation Base R&D' : 'Nghiên cứu nền chất' ),
		'image'           => $fm_img_id > 0 ? $fm_img_id : '',
		'accordion_items' => ( ! empty( $old_fm['accordion_items'] ) && is_array( $old_fm['accordion_items'] ) )
			? array_values( $old_fm['accordion_items'] )
			: $default_accordions,
	];

	// 4. Đối tác (.oem-3-section)
	$old_pt        = $by_layout['cooperation_partners'] ?? [];
	$legacy_pt_raw = get_field( 'partner_image', $post_id ) ?: get_field( 'partners_image', $post_id );
	$pt_img_id     = spl_resolve_about_attachment_id(
		$legacy_pt_raw ?: 0,
		spl_coop_media_for_lang( $media_map['partners'] ?? 0, $is_en )
	);

	$default_pt_desc = $is_en
		? '<p><strong>Academic R&amp;D Collaboration</strong></p><p>VINACOS R&amp;D Center collaborates closely with academic researchers and university faculties specializing in Organic &amp; Cosmetic Technology to ensure high scientific rigor and practical efficacy.</p><p><strong>Global Raw Material Suppliers</strong></p><p>We partner with world-renowned ingredient suppliers meeting international cGMP/FDA standards for full traceability, active purity, and formula innovation.</p>'
		: '<p><strong>Đồng hành nghiên cứu</strong></p><p>Phòng R&amp;D VINACOS hợp tác chặt chẽ với các đối tác nghiên cứu học thuật nhằm nâng cao chiều sâu khoa học trong phát triển sản phẩm.</p><p><strong>Đồng hành nguyên liệu</strong></p><p>Đội ngũ R&amp;D tại VINACOS cộng tác cùng các nhà cung cấp nguyên liệu uy tín toàn cầu, đáp ứng tiêu chí chất lượng, pháp lý và nguồn gốc rõ ràng.</p>';

	$pt_items = [];
	if ( ! empty( $old_pt['partner_items'] ) && is_array( $old_pt['partner_items'] ) ) {
		foreach ( array_values( $old_pt['partner_items'] ) as $it ) {
			$aid = spl_resolve_about_attachment_id( $it['image'] ?? 0, 0 );
			if ( $aid > 0 ) {
				$pt_items[] = [
					'image' => $aid,
					'title' => (string) ( $it['title'] ?? 'Raw Materials Partner' ),
				];
			}
		}
	}
	if ( empty( $pt_items ) && $pt_img_id > 0 ) {
		$pt_items[] = [
			'image' => $pt_img_id,
			'title' => $is_en ? 'Raw Materials Partner' : 'Đối tác nguyên liệu & nghiên cứu VINACOS',
		];
	}

	$row_partners = [
		'acf_fc_layout' => 'cooperation_partners',
		'disable'       => ! empty( $old_pt['disable'] ) ? 1 : 0,
		'title'         => ! empty( $old_pt['title'] )
			? (string) $old_pt['title']
			: ( $is_en ? 'Research & Material Partners' : 'Đối tác' ),
		'desc'          => ! empty( $old_pt['desc'] )
			? (string) $old_pt['desc']
			: $default_pt_desc,
		'partner_items' => $pt_items,
	];

	// 5. Quy trình nghiên cứu và sản xuất (.oem-5-section)
	$old_proc       = $by_layout['cooperation_process'] ?? [];
	$legacy_lab_raw = get_field( 'lab_image', $post_id ) ?: get_field( 'process_image', $post_id );
	$lab_img_id     = spl_resolve_about_attachment_id(
		$old_proc['lab_image'] ?? $legacy_lab_raw ?? 0,
		spl_coop_media_for_lang( $media_map['lab_process'] ?? 0, $is_en )
	);

	$default_proc_intro = $is_en
		? '<p>VINACOS Cosmetics R&amp;D Center operates on two core pillars: <strong>Active Raw Ingredient Research</strong> and <strong>Product Formulation Engineering</strong> to build a solid scientific foundation for clean, safe, and commercially viable beauty solutions.</p><p>Ingredient research focuses on standardization and bio-activity optimization, while product research delivers complete, sensory-pleasing formulations tailored for automated scale-up manufacturing.</p>'
		: '<p>Phòng Nghiên cứu &amp; Phát triển (R&amp;D) mỹ phẩm VINACOS định hướng hoạt động dựa trên hai trụ cột là: <strong>nghiên cứu nguyên liệu</strong> và <strong>nghiên cứu sản phẩm</strong> nhằm xây dựng nền tảng khoa học vững chắc cho các giải pháp mỹ phẩm an toàn, hiệu quả và có tính ứng dụng cao.</p><p>Trong đó, nghiên cứu nguyên liệu hướng đến đánh giá, chuẩn hóa và tối ưu khả năng ứng dụng, còn nghiên cứu sản phẩm tập trung phát triển công thức hoàn chỉnh, tối ưu hiệu quả, cảm quan và khả năng sản xuất, đáp ứng nhu cầu thị trường.</p>';

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

	$row_process = [
		'acf_fc_layout'   => 'cooperation_process',
		'disable'         => ! empty( $old_proc['disable'] ) ? 1 : 0,
		'section_heading' => ! empty( $old_proc['section_heading'] )
			? (string) $old_proc['section_heading']
			: ( $is_en ? 'R&D & Manufacturing Process' : 'Quy trình nghiên cứu và sản xuất' ),
		'subtitle'        => ! empty( $old_proc['subtitle'] ) ? (string) $old_proc['subtitle'] : '',
		'lab_image'       => $lab_img_id > 0 ? $lab_img_id : '',
		'intro_desc'      => ! empty( $old_proc['intro_desc'] )
			? (string) $old_proc['intro_desc']
			: $default_proc_intro,
		'rd_pillars'      => ( ! empty( $old_proc['rd_pillars'] ) && is_array( $old_proc['rd_pillars'] ) )
			? array_values( $old_proc['rd_pillars'] )
			: $default_pillars,
	];

	$synced_rows = [
		$row_hero,
		$row_benefits,
		$row_formulation,
		$row_partners,
		$row_process,
	];

	update_field( 'field_vinacos_coop_fc', $synced_rows, $post_id );
}

add_filter( 'display_post_states', 'spl_coop_admin_post_states', 21, 2 );

/**
 * Clarify active vs duplicate R&D / Cooperation pages in WP-Admin -> Pages list.
 *
 * @param array<string,string> $post_states Existing post states.
 * @param WP_Post              $post        Current post object.
 * @return array<string,string>
 */
function spl_coop_admin_post_states( array $post_states, $post ): array {
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return $post_states;
	}

	$en_rd_id = function_exists( 'pll_get_post' ) ? (int) pll_get_post( 944, 'en' ) : 0;

	if ( 944 === (int) $post->ID ) {
		$post_states['spl_coop_active'] = 'Trang Hệ Thống R&D chính (Menu Web)';
	} elseif ( $en_rd_id > 0 && $en_rd_id === (int) $post->ID ) {
		$post_states['spl_coop_en'] = 'R&D System chính (EN)';
	} elseif ( 926 === (int) $post->ID ) {
		$post_states['spl_coop_legacy'] = 'Bản cũ trùng lặp (/hop-tac/) — Không gắn Menu';
	}

	return $post_states;
}

add_action( 'admin_notices', 'spl_coop_admin_helper_notices' );

/**
 * Warn editors if they accidentally open duplicate page 926 (/hop-tac/) instead of active R&D page 944.
 */
function spl_coop_admin_helper_notices(): void {
	if ( ! is_admin() || ! current_user_can( 'edit_pages' ) || ! function_exists( 'get_current_screen' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	$edit_944 = admin_url( 'post.php?post=944&action=edit' );
	$view_944 = get_permalink( 944 ) ?: home_url( '/oem-odm-gia-cong-unila-viet-nam/' );

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$current_post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;

	if ( 'post' === $screen->base && 926 === $current_post_id ) {
		printf(
			'<div class="notice notice-warning" style="border-left-width:4px;padding:12px 16px;">' .
			'<p style="margin:0 0 8px;font-size:14px;"><strong>⚠️ Chú ý:</strong> Trang <strong>(ID 926 — <code>/hop-tac/</code>)</strong> này là bản cũ trùng lặp, <strong>không gắn trên Menu website</strong>. Sửa tại đây sẽ không hiển thị ngoài trang <code>/oem-odm-gia-cong-unila-viet-nam/</code>.</p>' .
			'<p style="margin:0;"><a class="button button-primary" href="%s">Mở trang "Hệ Thống R&amp;D" (ID 944) đang chạy trên Menu →</a> <a class="button" href="%s" target="_blank" rel="noopener">Xem ngoài web</a></p>' .
			'</div>',
			esc_url( $edit_944 ),
			esc_url( $view_944 )
		);
	}
}
