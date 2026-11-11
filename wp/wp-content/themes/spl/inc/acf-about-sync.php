<?php
/**
 * One-time non-destructive ACF data sync for VINACOS About Us pages.
 *
 * Ensures WP-Admin ACF Flexible Content (`about_sections`) matches 100% of the
 * live About Us frontend sections while preserving all user-uploaded media and
 * customized fields, without affecting any other page.
 *
 * @package SPL
 */

declare(strict_types=1);

use SPL\Modules\PLL\ACF\Dispatcher;

defined( 'ABSPATH' ) || exit;

add_action( 'wp_loaded', 'spl_maybe_sync_about_page_acf_data', 20 );

/**
 * Run one-time migration if `spl_about_acf_synced_v2` option is not yet set.
 */
function spl_maybe_sync_about_page_acf_data(): void {
	if ( '1' === get_option( 'spl_about_acf_synced_v2' ) ) {
		return;
	}

	if ( wp_installing() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
		return;
	}

	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) || ! function_exists( 'acf_get_field_group' ) ) {
		return;
	}

	// Prevent concurrent execution across simultaneous requests.
	if ( false !== get_transient( 'spl_about_acf_sync_lock' ) ) {
		return;
	}
	set_transient( 'spl_about_acf_sync_lock', '1', 120 );

	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 120 );
	}

	$had_pll_filter = false;
	if ( class_exists( Dispatcher::class ) && false !== has_filter( 'acf/update_value', [ Dispatcher::class, 'update' ] ) ) {
		remove_filter( 'acf/update_value', [ Dispatcher::class, 'update' ], 5 );
		$had_pll_filter = true;
	}

	try {
		$about_ids = [ 942, 1052, 936 ];
		$tpl_pages = get_posts( [
			'post_type'      => 'page',
			'post_status'    => [ 'publish', 'draft', 'private', 'pending' ],
			'posts_per_page' => 20,
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'templates/template-page-about.php',
			'lang'           => '',
		] );

		if ( is_array( $tpl_pages ) ) {
			foreach ( $tpl_pages as $pid ) {
				$about_ids[] = (int) $pid;
			}
		}
		$about_ids = array_values( array_unique( array_filter( array_map( 'intval', $about_ids ) ) ) );

		// Import static theme images into Media Library once (reuses existing attachments if present).
		$media_map = [
			'banner_about'    => spl_ensure_about_static_attachment( 'static/img/banner/banner-about.webp', 'VINACOS Banner Về Chúng Tôi' ),
			'story_shape'     => spl_ensure_about_static_attachment( 'static/img/bg-story-shape.png', 'VINACOS Khung Giới Thiệu' ),
			'msg_bg'          => spl_ensure_about_static_attachment( 'static/img/vinacos/bg-tam-the-cong-su.webp', 'VINACOS Tâm Thế Cộng Sự' ),
			'icon_hop_tac'    => spl_ensure_about_static_attachment( 'static/img/icon-hop-tac.png', 'Icon Sự Hợp Tác VINACOS' ),
			'icon_trung_thuc' => spl_ensure_about_static_attachment( 'static/img/icon-trung-thuc.png', 'Icon Sự Trung Thực VINACOS' ),
			'icon_xuat_sac'   => spl_ensure_about_static_attachment( 'static/img/icon-xuat-sac.png', 'Icon Sự Xuất Sắc VINACOS' ),
			'team_banner'     => spl_ensure_about_static_attachment( 'static/img/suc-manh-tap-the-vinacos.png', 'Sức Mạnh Tập Thể VINACOS' ),
			'ceo_founder'     => spl_ensure_about_static_attachment( 'static/img/vinacos/ceo-founder.jpg', 'Ban Giám Đốc VINACOS' ),
			'team_rd'         => spl_ensure_about_static_attachment( 'static/img/vinacos/team-rd.jpg', 'Đội Ngũ R&D VINACOS' ),
			'team_production' => spl_ensure_about_static_attachment( 'static/img/vinacos/team-production.jpg', 'Bộ Phận Sản Xuất VINACOS' ),
			'team_qa'         => spl_ensure_about_static_attachment( 'static/img/vinacos/team-qa.jpg', 'Quản Lý Chất Lượng QA QC VINACOS' ),
			'team_office'     => spl_ensure_about_static_attachment( 'static/img/vinacos/team-office.jpg', 'Khối Văn Phòng & Kinh Doanh VINACOS' ),
			'research_proc'   => spl_ensure_about_static_attachment( 'static/img/vinacos/research-process.jpg', 'Chuỗi Cung Ứng & Nghiên Cứu VINACOS' ),
			'factory_ovw'     => spl_ensure_about_static_attachment( 'static/img/vinacos/factory-overview.jpg', 'Nhà Máy Sản Xuất Chuẩn GMP FDA VINACOS' ),
			'rd_lab_main'     => spl_ensure_about_static_attachment( 'static/img/vinacos/rd-lab-main.jpg', 'Phòng Lab R&D Chuyên Sâu VINACOS' ),
			'cleanroom_fac'   => spl_ensure_about_static_attachment( 'static/img/vinacos/cleanroom-factory.jpg', 'Quy Trình Sản Xuất Khép Kín VINACOS' ),
		];

		foreach ( $about_ids as $post_id ) {
			$post_obj = get_post( $post_id );
			if ( ! $post_obj instanceof WP_Post || 'page' !== $post_obj->post_type ) {
				continue;
			}

			spl_sync_single_about_page_acf( $post_id, $media_map );
		}

		update_option( 'spl_about_acf_synced_v2', '1', true );
	} finally {
		if ( $had_pll_filter ) {
			add_filter( 'acf/update_value', [ Dispatcher::class, 'update' ], 5, 3 );
		}
		delete_transient( 'spl_about_acf_sync_lock' );
	}
}

/**
 * Resolve positive attachment ID from mixed ACF image value, or return fallback ID.
 */
function spl_resolve_about_attachment_id( mixed $value, int $fallback_id = 0 ): int {
	if ( is_numeric( $value ) && (int) $value > 0 ) {
		return (int) $value;
	}
	if ( is_array( $value ) && ! empty( $value['ID'] ) && (int) $value['ID'] > 0 ) {
		return (int) $value['ID'];
	}
	return $fallback_id > 0 ? $fallback_id : 0;
}

/**
 * Ensure a static theme image exists in WP Media Library without duplicating existing attachments.
 *
 * @param string $rel_path Relative path inside active theme directory.
 * @param string $title    Human-readable attachment title.
 * @return int Attachment ID or 0 on failure.
 */
function spl_ensure_about_static_attachment( string $rel_path, string $title ): int {
	global $wpdb;

	$abs_path = trailingslashit( get_template_directory() ) . ltrim( $rel_path, '/' );
	if ( ! is_file( $abs_path ) ) {
		return 0;
	}

	$basename = wp_basename( $abs_path );

	// Reuse existing attachment if already imported previously.
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

	if ( $existing_id > 0 && wp_get_attachment_url( $existing_id ) ) {
		return $existing_id;
	}

	$upload = wp_upload_dir();
	if ( ! empty( $upload['error'] ) || empty( $upload['path'] ) ) {
		return 0;
	}

	wp_mkdir_p( $upload['path'] );
	$target_filename = wp_unique_filename( $upload['path'], $basename );
	$target_path     = trailingslashit( $upload['path'] ) . $target_filename;

	if ( ! @copy( $abs_path, $target_path ) ) {
		return 0;
	}

	$filetype = wp_check_filetype( $target_filename, null );
	$mime     = ! empty( $filetype['type'] ) ? $filetype['type'] : 'image/jpeg';

	$attach_id = wp_insert_attachment(
		[
			'guid'           => trailingslashit( $upload['url'] ) . $target_filename,
			'post_mime_type' => $mime,
			'post_title'     => $title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		],
		$target_path,
		0
	);

	if ( is_wp_error( $attach_id ) || (int) $attach_id <= 0 ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	$attach_data = wp_generate_attachment_metadata( (int) $attach_id, $target_path );
	if ( is_array( $attach_data ) ) {
		wp_update_attachment_metadata( (int) $attach_id, $attach_data );
	}

	if ( function_exists( 'pll_set_post_language' ) && function_exists( 'pll_default_language' ) && function_exists( 'pll_get_post_language' ) ) {
		if ( ! pll_get_post_language( (int) $attach_id ) ) {
			pll_set_post_language( (int) $attach_id, (string) pll_default_language() );
		}
	}

	return (int) $attach_id;
}

/**
 * Non-destructively sync a single About page's `about_sections` Flexible Content.
 *
 * @param int              $post_id   About page post ID.
 * @param array<string,int> $media_map Imported static asset attachment IDs.
 */
function spl_sync_single_about_page_acf( int $post_id, array $media_map ): void {
	$lang  = function_exists( 'pll_get_post_language' ) ? (string) pll_get_post_language( $post_id, 'slug' ) : '';
	$is_en = ( 'en' === $lang ) || ( 1052 === $post_id );

	$current_rows = get_field( 'about_sections', $post_id );
	if ( ! is_array( $current_rows ) ) {
		$current_rows = [];
	}

	// Index first occurrence of each existing layout so user customizations are preserved.
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

	// 1. Banner Đầu Trang (about_hero) — strictly preserve existing banner_image & title
	$old_hero   = $by_layout['about_hero'] ?? $by_layout['hero'] ?? [];
	$hero_img   = spl_resolve_about_attachment_id( $old_hero['banner_image'] ?? 0, $media_map['banner_about'] ?? 0 );
	$hero_mobi  = spl_resolve_about_attachment_id( $old_hero['banner_image_mobile'] ?? 0, 0 );
	$hero_title = ! empty( $old_hero['title'] )
		? (string) $old_hero['title']
		: ( $is_en ? 'PARTNER MINDSET' : 'TÂM THẾ CỘNG SỰ' );

	$row_hero = [
		'acf_fc_layout'       => 'about_hero',
		'disable'             => ! empty( $old_hero['disable'] ) ? 1 : 0,
		'banner_image'        => $hero_img > 0 ? $hero_img : '',
		'banner_image_mobile' => $hero_mobi > 0 ? $hero_mobi : '',
		'title'               => $hero_title,
	];

	// 2. Giới Thiệu — Nghiên Cứu Sản Xuất Mỹ Phẩm (about_story)
	$old_story   = $by_layout['about_story'] ?? [];
	$story_title = ! empty( $old_story['title'] )
		? (string) $old_story['title']
		: ( $is_en ? 'COSMETICS R&D & <br/> MANUFACTURING' : 'NGHIÊN CỨU SẢN XUẤT <br/> MỸ PHẨM' );
	$story_title = str_replace(
		[
			'NGHIÊN CỨU <br/> SẢN XUẤT <br/> MỸ PHẨM',
			'COSMETICS <br/> R&D & <br/> MANUFACTURING',
		],
		[
			'NGHIÊN CỨU SẢN XUẤT <br/> MỸ PHẨM',
			'COSMETICS R&D & <br/> MANUFACTURING',
		],
		$story_title
	);
	$story_img = spl_resolve_about_attachment_id( $old_story['image'] ?? 0, $media_map['story_shape'] ?? 0 );

	$default_headline = $is_en
		? 'VINACOS – Driving a Clean Beauty Era from Vietnamese Botanical Resources.'
		: 'VINACOS – Hành động vì một kỷ nguyên mỹ phẩm sạch từ nguồn lực Việt.';
	$default_story_blocks = $is_en ? [
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

	$row_story = [
		'acf_fc_layout' => 'about_story',
		'disable'       => ! empty( $old_story['disable'] ) ? 1 : 0,
		'title'         => $story_title,
		'image'         => $story_img > 0 ? $story_img : '',
		'headline'      => ! empty( $old_story['headline'] ) ? (string) $old_story['headline'] : $default_headline,
		'story_blocks'  => ( ! empty( $old_story['story_blocks'] ) && is_array( $old_story['story_blocks'] ) )
			? $old_story['story_blocks']
			: $default_story_blocks,
	];

	// 3. Thông Điệp Tâm Thế Cộng Sự (about_message) — strictly preserve custom images (1295, 1281) & texts
	$old_msg      = $by_layout['about_message'] ?? [];
	$msg_img      = spl_resolve_about_attachment_id( $old_msg['image'] ?? 0, $media_map['msg_bg'] ?? 0 );
	$msg_img_mobi = spl_resolve_about_attachment_id( $old_msg['image_mobile'] ?? 0, 0 );

	$row_message = [
		'acf_fc_layout' => 'about_message',
		'disable'       => ! empty( $old_msg['disable'] ) ? 1 : 0,
		'subtitle'      => ! empty( $old_msg['subtitle'] )
			? (string) $old_msg['subtitle']
			: ( $is_en ? 'Your Great Company' : 'Công ty tuyệt vời của bạn' ),
		'title'         => ! empty( $old_msg['title'] )
			? (string) $old_msg['title']
			: ( $is_en
				? 'The journey is still long, but we already have <strong>many achievements</strong> worth celebrating.'
				: 'Chặng đường vẫn còn dài, nhưng chúng ta đã có <strong>rất nhiều thành tựu</strong> đáng để ăn mừng.' ),
		'image'         => $msg_img > 0 ? $msg_img : '',
		'image_mobile'  => $msg_img_mobi > 0 ? $msg_img_mobi : '',
	];

	// 4. Lời Hứa Của BB VINACOS (about_promises)
	$old_promises     = $by_layout['about_promises'] ?? [];
	$default_promises = [
		[
			'icon'  => ( $media_map['icon_hop_tac'] ?? 0 ) ?: '',
			'title' => $is_en ? 'Collaboration' : 'Sự hợp tác',
			'desc'  => $is_en
				? 'Describe what this value represents within the team and across the company.'
				: 'Hãy mô tả giá trị đó đại diện cho điều gì trong nhóm và trong công ty.',
		],
		[
			'icon'  => ( $media_map['icon_trung_thuc'] ?? 0 ) ?: '',
			'title' => $is_en ? 'Integrity' : 'Sự trung thực',
			'desc'  => $is_en
				? 'Describe what this value represents within the team and across the company.'
				: 'Hãy mô tả giá trị đó đại diện cho điều gì trong nhóm và trong công ty.',
		],
		[
			'icon'  => ( $media_map['icon_xuat_sac'] ?? 0 ) ?: '',
			'title' => $is_en ? 'Excellence' : 'Sự xuất sắc',
			'desc'  => $is_en
				? 'Describe what this value represents within the team and across the company.'
				: 'Hãy mô tả giá trị đó đại diện cho điều gì trong nhóm và trong công ty.',
		],
	];

	$row_promises = [
		'acf_fc_layout' => 'about_promises',
		'disable'       => ! empty( $old_promises['disable'] ) ? 1 : 0,
		'title'         => ! empty( $old_promises['title'] )
			? (string) $old_promises['title']
			: ( $is_en ? 'VINACOS PROMISES' : 'LỜI HỨA CỦA BB VINACOS' ),
		'promise_items' => ( ! empty( $old_promises['promise_items'] ) && is_array( $old_promises['promise_items'] ) )
			? $old_promises['promise_items']
			: $default_promises,
	];

	// 5. Sức Mạnh Tập Thể (about_team)
	$old_team    = $by_layout['about_team'] ?? [];
	$seed_titles = [ 'Sức mạnh tập thể & Đội ngũ chuyên gia', 'Collective Strength & Expert Team' ];
	$team_title  = ( ! empty( $old_team['title'] ) && ! in_array( (string) $old_team['title'], $seed_titles, true ) )
		? (string) $old_team['title']
		: ( $is_en ? 'Collective Strength & Team' : 'Sức mạnh tập thể' );
	$team_banner = spl_resolve_about_attachment_id( $old_team['banner_image'] ?? 0, $media_map['team_banner'] ?? 0 );

	$default_team_items = $is_en ? [
		[
			'image' => ( $media_map['ceo_founder'] ?? 0 ) ?: '',
			'name'  => 'EXECUTIVE BOARD & LEADERSHIP',
			'desc'  => 'Guiding vision, steering strategy, and fostering a culture of dedication across the organization.',
		],
		[
			'image' => ( $media_map['team_rd'] ?? 0 ) ?: '',
			'name'  => 'R&D RESEARCH & DEVELOPMENT',
			'desc'  => 'Formulation architects decoding botanical active efficacy through scientific rigor and clinical testing.',
		],
		[
			'image' => ( $media_map['team_production'] ?? 0 ) ?: '',
			'name'  => 'PRODUCTION & MANUFACTURING',
			'desc'  => 'Disciplined manufacturing operations adhering to strict cGMP cleanroom protocols and automation.',
		],
		[
			'image' => ( $media_map['team_qa'] ?? 0 ) ?: '',
			'name'  => 'QUALITY ASSURANCE & CONTROL (QA/QC)',
			'desc'  => 'Ensuring 100% compliance and stability from raw material intake to finished goods.',
		],
		[
			'image' => ( $media_map['team_office'] ?? 0 ) ?: '',
			'name'  => 'OEM/ODM BUSINESS DEVELOPMENT',
			'desc'  => 'Trusted advisors actively listening and turning product concepts into commercial reality.',
		],
		[
			'image' => ( $media_map['team_office'] ?? 0 ) ?: '',
			'name'  => 'MARKETING & COMMUNICATIONS',
			'desc'  => 'Storytellers connecting scientific formulation value with brand identity and consumers.',
		],
		[
			'image' => ( $media_map['team_office'] ?? 0 ) ?: '',
			'name'  => 'HUMAN RESOURCES & ADMINISTRATION',
			'desc'  => 'Fostering an empowering workplace to attract and develop top industry talent.',
		],
		[
			'image' => ( $media_map['research_proc'] ?? 0 ) ?: '',
			'name'  => 'PROCUREMENT & SUPPLY CHAIN',
			'desc'  => 'Selecting certified global raw ingredients with full COA and international traceability.',
		],
	] : [
		[
			'image' => ( $media_map['ceo_founder'] ?? 0 ) ?: '',
			'name'  => 'BAN GIÁM ĐỐC',
			'desc'  => 'Định hướng tầm nhìn, dẫn dắt tương lai. Dẫn dắt chiến lược phát triển và đảm bảo VINACOS luôn đi đúng hướng, truyền cảm hứng và xây dựng văn hóa tận tâm cho toàn bộ đội ngũ.',
		],
		[
			'image' => ( $media_map['team_rd'] ?? 0 ) ?: '',
			'name'  => 'R&D PHÒNG NGHIÊN CỨU & PHÁT TRIỂN',
			'desc'  => 'Những kiến trúc sư công thức, mã hóa vẻ đẹp Việt bằng nghiên cứu và khoa học. Đội ngũ chuyên gia tâm huyết thử nghiệm, chuẩn hóa và tạo nên các công thức mỹ phẩm độc quyền.',
		],
		[
			'image' => ( $media_map['team_production'] ?? 0 ) ?: '',
			'name'  => 'BỘ PHẬN SẢN XUẤT',
			'desc'  => 'Kỷ luật thép trong từng thao tác, đảm bảo sự đồng nhất 100%. Với quy trình nghiêm ngặt chuẩn GMP và thiết bị hiện đại, đội ngũ sản xuất biến công thức thành hiện thực.',
		],
		[
			'image' => ( $media_map['team_qa'] ?? 0 ) ?: '',
			'name'  => 'QUẢN LÝ CHẤT LƯỢNG (QA/QC)',
			'desc'  => 'Người gác cổng cuối cùng, đảm bảo chất lượng đạt chuẩn tuyệt đối. Từ nguyên liệu đầu vào đến thành phẩm, kiểm soát nghiêm ngặt để mọi tiêu chuẩn được tuân thủ.',
		],
		[
			'image' => ( $media_map['team_office'] ?? 0 ) ?: '',
			'name'  => 'KINH DOANH & TƯ VẤN OEM/ODM',
			'desc'  => 'Những người đồng hành đầu tiên, lắng nghe và biến ý tưởng thành dự án cụ thể. Là cầu nối tin cậy giữa VINACOS và các thương hiệu đối tác.',
		],
		[
			'image' => ( $media_map['team_office'] ?? 0 ) ?: '',
			'name'  => 'MARKETING & TRUYỀN THÔNG',
			'desc'  => 'Người kể chuyện chính trực, đưa giá trị thực đến tay người dùng. Biến giá trị khoa học thành thông điệp cảm xúc, kết nối thương hiệu với khách hàng.',
		],
		[
			'image' => ( $media_map['team_office'] ?? 0 ) ?: '',
			'name'  => 'NHÂN SỰ & QUẢN TRỊ',
			'desc'  => 'Người xây dựng đội ngũ, tạo môi trường để mỗi thành viên phát huy tối đa năng lực. Thu hút và phát triển nguồn nhân lực trình độ cao cho VINACOS.',
		],
		[
			'image' => ( $media_map['research_proc'] ?? 0 ) ?: '',
			'name'  => 'MUA HÀNG & CHUỖI CUNG ỨNG',
			'desc'  => 'Người quản trị nguồn lực, chọn lọc nguyên liệu nhập khẩu chính ngạch chuẩn COA, tạo nền tảng vững chắc cho sứ mệnh mỹ phẩm sạch.',
		],
	];

	$existing_team_has_imgs = false;
	if ( ! empty( $old_team['team_items'] ) && is_array( $old_team['team_items'] ) ) {
		foreach ( $old_team['team_items'] as $ti ) {
			if ( ! empty( $ti['image'] ) ) {
				$existing_team_has_imgs = true;
				break;
			}
		}
	}

	$row_team = [
		'acf_fc_layout' => 'about_team',
		'disable'       => ! empty( $old_team['disable'] ) ? 1 : 0,
		'title'         => $team_title,
		'banner_image'  => $team_banner > 0 ? $team_banner : '',
		'team_items'    => $existing_team_has_imgs ? $old_team['team_items'] : $default_team_items,
	];

	// 6. Nhà Máy & Quy Trình Sản Xuất (about_cta)
	$old_cta         = $by_layout['about_cta'] ?? [];
	$cta_seed_titles = [ 'Nhà máy & Quy trình sản xuất cGMP', 'cGMP & FDA Certified Cleanroom Plant' ];
	$cta_title       = ( ! empty( $old_cta['title'] ) && ! in_array( (string) $old_cta['title'], $cta_seed_titles, true ) )
		? (string) $old_cta['title']
		: ( $is_en ? 'International cGMP/FDA Cleanroom Facilities' : 'Nhà máy & Quy trình sản xuất chuẩn quốc tế' );

	$default_factory_items = $is_en ? [
		[
			'image' => ( $media_map['factory_ovw'] ?? 0 ) ?: '',
			'title' => 'cGMP & FDA Certified Cleanroom Plant',
			'desc'  => 'High-capacity automated filling & compounding lines capable of producing millions of units annually.',
		],
		[
			'image' => ( $media_map['rd_lab_main'] ?? 0 ) ?: '',
			'title' => 'Advanced Analytical R&D Laboratory',
			'desc'  => 'High-tech analytical testing instruments for active compound verification & strict safety stability testing.',
		],
		[
			'image' => ( $media_map['cleanroom_fac'] ?? 0 ) ?: '',
			'title' => 'Turnkey End-to-End Manufacturing Workflow',
			'desc'  => 'From formula engineering, sample trial runs, stability testing to custom packaging & regulatory filing.',
		],
	] : [
		[
			'image' => ( $media_map['factory_ovw'] ?? 0 ) ?: '',
			'title' => 'Nhà máy sản xuất chuẩn GMP & FDA',
			'desc'  => 'Hệ thống phòng sạch hiện đại, tự động hóa cao, đáp ứng công suất hàng triệu sản phẩm mỗi năm.',
		],
		[
			'image' => ( $media_map['rd_lab_main'] ?? 0 ) ?: '',
			'title' => 'Phòng Lab R&D chuyên sâu',
			'desc'  => 'Trang thiết bị kiểm nghiệm công nghệ cao, phân tích hoạt chất và đo lường chỉ số an toàn nghiêm ngặt.',
		],
		[
			'image' => ( $media_map['cleanroom_fac'] ?? 0 ) ?: '',
			'title' => 'Quy trình sản xuất khép kín A-Z',
			'desc'  => 'Từ nghiên cứu công thức, kiểm định mẫu thử, thử nghiệm độ ổn định đến đóng gói và bàn giao hồ sơ công bố.',
		],
	];

	$row_cta = [
		'acf_fc_layout' => 'about_cta',
		'disable'       => ! empty( $old_cta['disable'] ) ? 1 : 0,
		'title'         => $cta_title,
		'factory_items' => ( ! empty( $old_cta['factory_items'] ) && is_array( $old_cta['factory_items'] ) )
			? $old_cta['factory_items']
			: $default_factory_items,
	];

	$synced_rows = [
		$row_hero,
		$row_story,
		$row_message,
		$row_promises,
		$row_team,
		$row_cta,
	];

	update_field( 'field_vinacos_about_fc', $synced_rows, $post_id );
}
