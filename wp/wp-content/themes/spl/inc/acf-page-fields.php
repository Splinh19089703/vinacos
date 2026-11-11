<?php
/**
 * Register all VINACOS Page ACF Flexible Content via PHP Code
 *
 * Covers:
 * 1. Trang Chủ (Home Page)
 * 2. Tâm Thế Cộng Sự (About Page)
 * 3. Hệ Thống R&D / Hợp Tác OEM-ODM (Cooperation Page)
 *
 * @package SPL
 */

defined( 'ABSPATH' ) || exit;

add_action( 'acf/init', 'spl_register_all_vinacos_page_acf_fields', 10 );
function spl_register_all_vinacos_page_acf_fields(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// -------------------------------------------------------------
	// 1. TRANG CHỦ (HOMEPAGE)
	// -------------------------------------------------------------
	acf_add_local_field_group( [
		'key'                   => 'group_vinacos_home_page',
		'title'                 => __( 'VINACOS - Quản Trị Nội Dung Trang Chủ', 'spl' ),
		'fields'                => [
			[
				'key'          => 'field_vinacos_home_fc',
				'label'        => __( 'Nội dung trang chủ (Flexible Content)', 'spl' ),
				'name'         => 'home_sections',
				'type'         => 'flexible_content',
				'instructions' => __( 'Thêm, sửa, xóa hoặc kéo thả sắp xếp các block hiển thị trên Trang Chủ', 'spl' ),
				'button_label' => __( 'Thêm Block nội dung', 'spl' ),
				'layouts'      => [
					// 1. Hero Banner Slider
					'layout_hero_slider' => [
						'key'        => 'layout_hero_slider',
						'name'       => 'hero_slider',
						'label'      => __( '1. Hero Banner Slider', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'          => 'field_hero_disable',
								'label'        => __( 'Ẩn section này', 'spl' ),
								'name'         => 'disable',
								'type'         => 'true_false',
								'ui'           => 1,
								'default_value'=> 0,
							],
							[
								'key'          => 'field_hero_slides',
								'label'        => __( 'Danh sách Slide Banner', 'spl' ),
								'name'         => 'slides',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm Slide', 'spl' ),
								'sub_fields'   => [
									[
										'key'           => 'field_slide_bg_image',
										'label'         => __( 'Ảnh banner (Desktop)', 'spl' ),
										'name'          => 'bg_image',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'medium',
										'wrapper'       => [ 'width' => '33' ],
									],
									[
										'key'           => 'field_slide_bg_image_mobile',
										'label'         => __( 'Ảnh banner (Mobile)', 'spl' ),
										'name'          => 'bg_image_mobile',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'medium',
										'wrapper'       => [ 'width' => '33' ],
									],
									[
										'key'     => 'field_slide_title',
										'label'   => __( 'Tiêu đề Slide (Mỗi dòng cách nhau Enter)', 'spl' ),
										'name'    => 'title',
										'type'    => 'textarea',
										'rows'    => 3,
										'wrapper' => [ 'width' => '34' ],
									],
									[
										'key'     => 'field_slide_desc',
										'label'   => __( 'Mô tả ngắn', 'spl' ),
										'name'    => 'description',
										'type'    => 'textarea',
										'rows'    => 2,
										'wrapper' => [ 'width' => '50' ],
									],
									[
										'key'           => 'field_slide_link',
										'label'         => __( 'Nút bấm / Liên kết', 'spl' ),
										'name'          => 'link',
										'type'          => 'link',
										'return_format' => 'array',
										'wrapper'       => [ 'width' => '50' ],
									],
								],
							],
						],
					],

					// 2. About Section
					'layout_about_section' => [
						'key'        => 'layout_about_section',
						'name'       => 'about_section',
						'label'      => __( '2. Giới thiệu (Tâm Thế Cộng Sự)', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'          => 'field_about_disable',
								'label'        => __( 'Ẩn section này', 'spl' ),
								'name'         => 'disable',
								'type'         => 'true_false',
								'ui'           => 1,
								'default_value'=> 0,
							],
							[
								'key'     => 'field_about_title',
								'label'   => __( 'Tiêu đề chính', 'spl' ),
								'name'    => 'title',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'           => 'field_about_image',
								'label'         => __( 'Hình ảnh minh họa', 'spl' ),
								'name'          => 'image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '50' ],
							],
							[
								'key'          => 'field_about_content',
								'label'        => __( 'Nội dung chi tiết (Tầm nhìn & Sứ mệnh)', 'spl' ),
								'name'         => 'content',
								'type'         => 'wysiwyg',
								'toolbar'      => 'full',
								'media_upload' => 1,
							],
							[
								'key'     => 'field_about_btn_text',
								'label'   => __( 'Chữ trên nút', 'spl' ),
								'name'    => 'btn_text',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'     => 'field_about_btn_link',
								'label'   => __( 'Liên kết nút', 'spl' ),
								'name'    => 'btn_link',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
						],
					],

					// 3. Brand Banner
					'layout_brand_banner' => [
						'key'        => 'layout_brand_banner',
						'name'       => 'brand_banner',
						'label'      => __( '3. Banner Thương Hiệu (Brand Banner)', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'          => 'field_brand_banner_disable',
								'label'        => __( 'Ẩn section này', 'spl' ),
								'name'         => 'disable',
								'type'         => 'true_false',
								'ui'           => 1,
								'default_value'=> 0,
							],
							[
								'key'           => 'field_brand_banner_img',
								'label'         => __( 'Ảnh banner thương hiệu', 'spl' ),
								'name'          => 'image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'large',
							],
						],
					],

					// 4. R&D System
					'layout_rd_system' => [
						'key'        => 'layout_rd_system',
						'name'       => 'rd_system',
						'label'      => __( '4. Hệ thống R&D (Năng lực & Công trình)', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'          => 'field_rd_disable',
								'label'        => __( 'Ẩn section này', 'spl' ),
								'name'         => 'disable',
								'type'         => 'true_false',
								'ui'           => 1,
								'default_value'=> 0,
							],
							[
								'key'   => 'field_rd_title',
								'label' => __( 'Tiêu đề khối R&D', 'spl' ),
								'name'  => 'title',
								'type'  => 'text',
							],
							[
								'key'          => 'field_rd_items',
								'label'        => __( 'Danh sách thẻ R&D', 'spl' ),
								'name'         => 'items',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm thẻ R&D', 'spl' ),
								'sub_fields'   => [
									[
										'key'     => 'field_rd_item_label',
										'label'   => __( 'Nhãn nhỏ', 'spl' ),
										'name'    => 'label',
										'type'    => 'text',
										'wrapper' => [ 'width' => '50' ],
									],
									[
										'key'     => 'field_rd_item_title',
										'label'   => __( 'Tiêu đề thẻ', 'spl' ),
										'name'    => 'title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '50' ],
									],
									[
										'key'     => 'field_rd_item_desc',
										'label'   => __( 'Mô tả chi tiết', 'spl' ),
										'name'    => 'desc',
										'type'    => 'textarea',
										'rows'    => 3,
									],
									[
										'key'           => 'field_rd_item_img',
										'label'         => __( 'Ảnh minh họa (Desktop)', 'spl' ),
										'name'          => 'image',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'medium',
										'wrapper'       => [ 'width' => '50' ],
									],
									[
										'key'           => 'field_rd_item_img_mobile',
										'label'         => __( 'Ảnh minh họa (Mobile)', 'spl' ),
										'name'          => 'image_mobile',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'medium',
										'wrapper'       => [ 'width' => '50' ],
									],
									[
										'key'     => 'field_rd_item_btn_text',
										'label'   => __( 'Chữ nút', 'spl' ),
										'name'    => 'btn_text',
										'type'    => 'text',
										'wrapper' => [ 'width' => '25' ],
									],
									[
										'key'     => 'field_rd_item_btn_link',
										'label'   => __( 'Link nút', 'spl' ),
										'name'    => 'btn_link',
										'type'    => 'text',
										'wrapper' => [ 'width' => '25' ],
									],
								],
							],
						],
					],

					// 5. Key Numbers
					'layout_key_numbers' => [
						'key'        => 'layout_key_numbers',
						'name'       => 'key_numbers',
						'label'      => __( '5. Con số nổi bật (Key Numbers)', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'          => 'field_numbers_disable',
								'label'        => __( 'Ẩn section này', 'spl' ),
								'name'         => 'disable',
								'type'         => 'true_false',
								'ui'           => 1,
								'default_value'=> 0,
							],
							[
								'key'   => 'field_numbers_title',
								'label' => __( 'Tiêu đề khối', 'spl' ),
								'name'  => 'title',
								'type'  => 'text',
							],
							[
								'key'           => 'field_numbers_bg',
								'label'         => __( 'Ảnh nền (Background)', 'spl' ),
								'name'          => 'bg_image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '50' ],
							],
							[
								'key'           => 'field_numbers_fig',
								'label'         => __( 'Ảnh đồ họa / Người mẫu giữa', 'spl' ),
								'name'          => 'figure_image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '50' ],
							],
							[
								'key'          => 'field_numbers_items',
								'label'        => __( 'Danh sách con số', 'spl' ),
								'name'         => 'items',
								'type'         => 'repeater',
								'layout'       => 'table',
								'button_label' => __( 'Thêm con số', 'spl' ),
								'sub_fields'   => [
									[
										'key'     => 'field_num_count',
										'label'   => __( 'Số lượng', 'spl' ),
										'name'    => 'count',
										'type'    => 'number',
										'wrapper' => [ 'width' => '20' ],
									],
									[
										'key'     => 'field_num_suffix',
										'label'   => __( 'Ký tự sau (%, +)', 'spl' ),
										'name'    => 'suffix',
										'type'    => 'text',
										'wrapper' => [ 'width' => '20' ],
									],
									[
										'key'     => 'field_num_title',
										'label'   => __( 'Mô tả con số', 'spl' ),
										'name'    => 'title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '60' ],
									],
								],
							],
						],
					],

					// 6. Product Showcase
					'layout_product_showcase' => [
						'key'        => 'layout_product_showcase',
						'name'       => 'product_showcase',
						'label'      => __( '6. Danh mục sản phẩm tiêu biểu', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'          => 'field_prod_disable',
								'label'        => __( 'Ẩn section này', 'spl' ),
								'name'         => 'disable',
								'type'         => 'true_false',
								'ui'           => 1,
								'default_value'=> 0,
							],
							[
								'key'   => 'field_prod_title',
								'label' => __( 'Tiêu đề khối sản phẩm', 'spl' ),
								'name'  => 'title',
								'type'  => 'text',
							],
							[
								'key'          => 'field_prod_items',
								'label'        => __( 'Danh sách sản phẩm tiêu biểu', 'spl' ),
								'name'         => 'items',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm sản phẩm', 'spl' ),
								'sub_fields'   => [
									[
										'key'     => 'field_prod_item_title',
										'label'   => __( 'Tên sản phẩm / Công thức', 'spl' ),
										'name'    => 'title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '50' ],
									],
									[
										'key'           => 'field_prod_item_img',
										'label'         => __( 'Ảnh sản phẩm', 'spl' ),
										'name'          => 'image',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'medium',
										'wrapper'       => [ 'width' => '50' ],
									],
									[
										'key'     => 'field_prod_item_desc',
										'label'   => __( 'Mô tả công thức', 'spl' ),
										'name'    => 'description',
										'type'    => 'textarea',
										'rows'    => 3,
									],
									[
										'key'     => 'field_prod_item_btn_text',
										'label'   => __( 'Chữ nút', 'spl' ),
										'name'    => 'btn_text',
										'type'    => 'text',
										'wrapper' => [ 'width' => '50' ],
									],
									[
										'key'     => 'field_prod_item_btn_link',
										'label'   => __( 'Link nút', 'spl' ),
										'name'    => 'btn_link',
										'type'    => 'text',
										'wrapper' => [ 'width' => '50' ],
									],
								],
							],
						],
					],

					// 7. Partners Section
					'layout_partners_section' => [
						'key'        => 'layout_partners_section',
						'name'       => 'partners_section',
						'label'      => __( '7. Đối tác (Nguyên liệu & Nghiên cứu)', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'          => 'field_partners_disable',
								'label'        => __( 'Ẩn section này', 'spl' ),
								'name'         => 'disable',
								'type'         => 'true_false',
								'ui'           => 1,
								'default_value'=> 0,
							],
							[
								'key'     => 'field_partners_watermark',
								'label'   => __( 'Chữ mờ nền (Watermark)', 'spl' ),
								'name'    => 'watermark',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'     => 'field_partners_left_title',
								'label'   => __( 'Tiêu đề bên trái', 'spl' ),
								'name'    => 'left_title',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'     => 'field_partners_right_title',
								'label'   => __( 'Tiêu đề bên phải', 'spl' ),
								'name'    => 'right_title',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
						],
					],

					// 8. News Section
					'layout_news_section' => [
						'key'        => 'layout_news_section',
						'name'       => 'news_section',
						'label'      => __( '8. Tin tức & Hoạt động', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'          => 'field_news_disable',
								'label'        => __( 'Ẩn section này', 'spl' ),
								'name'         => 'disable',
								'type'         => 'true_false',
								'ui'           => 1,
								'default_value'=> 0,
							],
							[
								'key'   => 'field_news_title',
								'label' => __( 'Tiêu đề khối tin tức', 'spl' ),
								'name'  => 'title',
								'type'  => 'text',
							],
						],
					],

					// 9. Consult Modal
					'layout_consult_modal' => [
						'key'        => 'layout_consult_modal',
						'name'       => 'consult_modal',
						'label'      => __( '9. Khung đăng ký nhận tư vấn', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'          => 'field_consult_disable',
								'label'        => __( 'Ẩn section này', 'spl' ),
								'name'         => 'disable',
								'type'         => 'true_false',
								'ui'           => 1,
								'default_value'=> 0,
							],
							[
								'key'     => 'field_consult_title',
								'label'   => __( 'Tiêu đề form tư vấn', 'spl' ),
								'name'    => 'title',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'           => 'field_consult_img',
								'label'         => __( 'Ảnh banner popup', 'spl' ),
								'name'          => 'image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '50' ],
							],
						],
					],
				],
			],
		],
		'location'              => [
			[
				[
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'templates/template-page-home.php',
				],
			],
			[
				[
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				],
			],
			[
				[
					'param'    => 'post',
					'operator' => '==',
					'value'    => '10',
				],
			],
			[
				[
					'param'    => 'post',
					'operator' => '==',
					'value'    => '1121',
				],
			],
		],
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	] );

	// -------------------------------------------------------------
	// 2. VỀ CHÚNG TÔI / TÂM THẾ CỘNG SỰ (ABOUT PAGE)
	// -------------------------------------------------------------
	acf_add_local_field_group( [
		'key'                   => 'group_vinacos_about_page',
		'title'                 => __( 'VINACOS - Quản Trị Trang Về Chúng Tôi (Tâm Thế Cộng Sự)', 'spl' ),
		'fields'                => [
			[
				'key'          => 'field_vinacos_about_fc',
				'label'        => __( 'Các Section Trang Về Chúng Tôi', 'spl' ),
				'name'         => 'about_sections',
				'type'         => 'flexible_content',
				'instructions' => __( 'Chỉnh sửa trực tiếp nội dung, hình ảnh hoặc ẩn/hiện, kéo thả sắp xếp các section hiển thị trên trang Về Chúng Tôi.', 'spl' ),
				'button_label' => __( 'Thêm Block Giới Thiệu', 'spl' ),
				'layouts'      => [
					// 1. Hero Banner
					'layout_about_hero' => [
						'key'        => 'layout_about_hero',
						'name'       => 'about_hero',
						'label'      => __( '1. Banner Đầu Trang', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_abh_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'           => 'field_abh_banner',
								'label'         => __( 'Ảnh banner đầu trang (Desktop)', 'spl' ),
								'name'          => 'banner_image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'large',
								'wrapper'       => [ 'width' => '40' ],
							],
							[
								'key'           => 'field_abh_banner_mobile',
								'label'         => __( 'Ảnh banner Mobile (Tùy chọn)', 'spl' ),
								'name'          => 'banner_image_mobile',
								'type'          => 'image',
								'instructions'  => __( 'Để trống sẽ tự động dùng ảnh Desktop', 'spl' ),
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '30' ],
							],
							[
								'key'          => 'field_abh_title',
								'label'        => __( 'Tiêu đề / Alt ảnh Banner', 'spl' ),
								'name'         => 'title',
								'type'         => 'text',
								'instructions' => __( 'Mô tả alt cho ảnh banner (SEO)', 'spl' ),
								'wrapper'      => [ 'width' => '30' ],
							],
						],
					],

					// 2. Story (Nghiên cứu sản xuất mỹ phẩm)
					'layout_about_story' => [
						'key'        => 'layout_about_story',
						'name'       => 'about_story',
						'label'      => __( '2. Giới Thiệu — Nghiên Cứu Sản Xuất Mỹ Phẩm', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_abs_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'          => 'field_abs_title',
								'label'        => __( 'Tiêu đề khung bên trái', 'spl' ),
								'name'         => 'title',
								'type'         => 'text',
								'instructions' => __( 'Dùng <br/> để ngắt dòng trên màn hình lớn (VD: NGHIÊN CỨU SẢN XUẤT <br/> MỸ PHẨM)', 'spl' ),
								'wrapper'      => [ 'width' => '55' ],
							],
							[
								'key'           => 'field_abs_image',
								'label'         => __( 'Ảnh nền khung bên trái', 'spl' ),
								'name'          => 'image',
								'type'          => 'image',
								'instructions'  => __( 'Để trống sẽ dùng khung xanh mặc định', 'spl' ),
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '45' ],
							],
							[
								'key'          => 'field_abs_headline',
								'label'        => __( 'Câu dẫn mở đầu (Headline in đậm)', 'spl' ),
								'name'         => 'headline',
								'type'         => 'textarea',
								'rows'         => 2,
								'instructions' => __( 'Dòng chữ tiêu đề nổi bật phía trên cột nội dung bên phải', 'spl' ),
							],
							[
								'key'          => 'field_abs_blocks',
								'label'        => __( 'Các mục nội dung bên phải (Dẫn đầu, Thấu hiểu...)', 'spl' ),
								'name'         => 'story_blocks',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm mục nội dung', 'spl' ),
								'sub_fields'   => [
									[
										'key'     => 'field_abs_block_title',
										'label'   => __( 'Tiêu đề mục (VD: Dẫn đầu)', 'spl' ),
										'name'    => 'block_title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '30' ],
									],
									[
										'key'          => 'field_abs_block_desc',
										'label'        => __( 'Nội dung các đoạn văn', 'spl' ),
										'name'         => 'block_desc',
										'type'         => 'textarea',
										'rows'         => 4,
										'instructions' => __( 'Xuống dòng (Enter) để tách thành các đoạn văn riêng', 'spl' ),
										'wrapper'      => [ 'width' => '70' ],
									],
								],
							],
						],
					],

					// 3. Message (Tâm thế cộng sự — Slide 18)
					'layout_about_message' => [
						'key'        => 'layout_about_message',
						'name'       => 'about_message',
						'label'      => __( '3. Thông Điệp Tâm Thế Cộng Sự', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_abm_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'          => 'field_abm_subtitle',
								'label'        => __( 'Tiêu đề phụ / Badge nhỏ (Góc trên)', 'spl' ),
								'name'         => 'subtitle',
								'type'         => 'text',
								'instructions' => __( 'Dòng chữ nhỏ kế bên icon (VD: Công ty tuyệt vời của bạn)', 'spl' ),
								'wrapper'      => [ 'width' => '40' ],
							],
							[
								'key'          => 'field_abm_title',
								'label'        => __( 'Câu thông điệp chính (Trích dẫn)', 'spl' ),
								'name'         => 'title',
								'type'         => 'textarea',
								'rows'         => 3,
								'instructions' => __( 'Dùng thẻ <strong>từ_cần_in_đậm</strong> để nhấn mạnh, <br> hoặc xuống dòng để ngắt dòng', 'spl' ),
								'wrapper'      => [ 'width' => '60' ],
							],
							[
								'key'           => 'field_abm_image',
								'label'         => __( 'Ảnh nền Desktop', 'spl' ),
								'name'          => 'image',
								'type'          => 'image',
								'instructions'  => __( 'Ảnh nền toàn khối trên máy tính', 'spl' ),
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '50' ],
							],
							[
								'key'           => 'field_abm_image_mobile',
								'label'         => __( 'Ảnh hiển thị Mobile', 'spl' ),
								'name'          => 'image_mobile',
								'type'          => 'image',
								'instructions'  => __( 'Ảnh riêng cho điện thoại / máy tính bảng (để trống sẽ dùng ảnh Desktop)', 'spl' ),
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '50' ],
							],
						],
					],

					// 4. Promises (Lời hứa của BB VINACOS)
					'layout_about_promises' => [
						'key'        => 'layout_about_promises',
						'name'       => 'about_promises',
						'label'      => __( '4. Lời Hứa Của BB VINACOS', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_ab_prom_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'   => 'field_ab_prom_title',
								'label' => __( 'Tiêu đề khối', 'spl' ),
								'name'  => 'title',
								'type'  => 'text',
							],
							[
								'key'          => 'field_ab_prom_items',
								'label'        => __( 'Danh sách giá trị / Lời hứa', 'spl' ),
								'name'         => 'promise_items',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm giá trị', 'spl' ),
								'sub_fields'   => [
									[
										'key'           => 'field_ab_prom_icon',
										'label'         => __( 'Icon (60x60)', 'spl' ),
										'name'          => 'icon',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'thumbnail',
										'wrapper'       => [ 'width' => '25' ],
									],
									[
										'key'     => 'field_ab_prom_item_title',
										'label'   => __( 'Tên giá trị (Tag)', 'spl' ),
										'name'    => 'title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '30' ],
									],
									[
										'key'     => 'field_ab_prom_item_desc',
										'label'   => __( 'Mô tả chi tiết', 'spl' ),
										'name'    => 'desc',
										'type'    => 'textarea',
										'rows'    => 3,
										'wrapper' => [ 'width' => '45' ],
									],
								],
							],
						],
					],

					// 5. Team (Sức mạnh tập thể)
					'layout_about_team' => [
						'key'        => 'layout_about_team',
						'name'       => 'about_team',
						'label'      => __( '5. Sức Mạnh Tập Thể (Đội Ngũ & Phòng Ban)', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_ab_team_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'     => 'field_ab_team_title',
								'label'   => __( 'Tiêu đề khối', 'spl' ),
								'name'    => 'title',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'           => 'field_ab_team_banner',
								'label'         => __( 'Ảnh tập thể lớn phía trên', 'spl' ),
								'name'          => 'banner_image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '50' ],
							],
							[
								'key'          => 'field_ab_team_items',
								'label'        => __( 'Danh sách phòng ban / Bộ phận (Slider)', 'spl' ),
								'name'         => 'team_items',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm phòng ban', 'spl' ),
								'sub_fields'   => [
									[
										'key'           => 'field_team_image',
										'label'         => __( 'Ảnh phòng ban', 'spl' ),
										'name'          => 'image',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'medium',
										'wrapper'       => [ 'width' => '30' ],
									],
									[
										'key'     => 'field_team_name',
										'label'   => __( 'Tên phòng ban / Bộ phận', 'spl' ),
										'name'    => 'name',
										'type'    => 'text',
										'wrapper' => [ 'width' => '70' ],
									],
									[
										'key'   => 'field_team_desc',
										'label' => __( 'Mô tả vai trò / nhiệm vụ', 'spl' ),
										'name'  => 'desc',
										'type'  => 'textarea',
										'rows'  => 3,
									],
								],
							],
						],
					],

					// 6. CTA / Factory & Process (Nhà máy & Quy trình sản xuất)
					'layout_about_cta' => [
						'key'        => 'layout_about_cta',
						'name'       => 'about_cta',
						'label'      => __( '6. Nhà Máy & Quy Trình Sản Xuất', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_ab_cta_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'   => 'field_ab_cta_title',
								'label' => __( 'Tiêu đề khối', 'spl' ),
								'name'  => 'title',
								'type'  => 'text',
							],
							[
								'key'          => 'field_ab_cta_items',
								'label'        => __( 'Danh sách Slide Nhà máy & Quy trình', 'spl' ),
								'name'         => 'factory_items',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm slide nhà máy / quy trình', 'spl' ),
								'sub_fields'   => [
									[
										'key'           => 'field_ab_cta_item_img',
										'label'         => __( 'Ảnh slide (1376x768)', 'spl' ),
										'name'          => 'image',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'medium',
										'wrapper'       => [ 'width' => '35' ],
									],
									[
										'key'     => 'field_ab_cta_item_title',
										'label'   => __( 'Tiêu đề slide', 'spl' ),
										'name'    => 'title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '65' ],
									],
									[
										'key'   => 'field_ab_cta_item_desc',
										'label' => __( 'Mô tả chi tiết', 'spl' ),
										'name'  => 'desc',
										'type'  => 'textarea',
										'rows'  => 2,
									],
								],
							],
						],
					],

					// 7. [Tùy chọn] Timeline
					'layout_about_timeline' => [
						'key'        => 'layout_about_timeline',
						'name'       => 'about_timeline',
						'label'      => __( '7. [Tùy chọn] Timeline Từng Mốc Dấu Ấn', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_abtl_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'   => 'field_abtl_title',
								'label' => __( 'Tiêu đề khối Timeline', 'spl' ),
								'name'  => 'title',
								'type'  => 'text',
							],
							[
								'key'          => 'field_abtl_items',
								'label'        => __( 'Các mốc thời gian', 'spl' ),
								'name'         => 'timeline_items',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm mốc', 'spl' ),
								'sub_fields'   => [
									[
										'key'     => 'field_tl_year',
										'label'   => __( 'Năm / Giai đoạn', 'spl' ),
										'name'    => 'year',
										'type'    => 'text',
										'wrapper' => [ 'width' => '25' ],
									],
									[
										'key'     => 'field_tl_title',
										'label'   => __( 'Tiêu đề mốc', 'spl' ),
										'name'    => 'title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '45' ],
									],
									[
										'key'           => 'field_tl_image',
										'label'         => __( 'Ảnh minh họa', 'spl' ),
										'name'          => 'image',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'medium',
										'wrapper'       => [ 'width' => '30' ],
									],
									[
										'key'   => 'field_tl_desc',
										'label' => __( 'Mô tả chi tiết', 'spl' ),
										'name'  => 'desc',
										'type'  => 'textarea',
										'rows'  => 2,
									],
								],
							],
						],
					],

					// 8. [Tùy chọn] Mission & Vision
					'layout_about_mission' => [
						'key'        => 'layout_about_mission',
						'name'       => 'about_mission',
						'label'      => __( '8. [Tùy chọn] Tầm Nhìn & Sứ Mệnh', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_abm_vis_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'     => 'field_abm_sec_title',
								'label'   => __( 'Tiêu đề khối', 'spl' ),
								'name'    => 'title',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'           => 'field_abm_vis_img',
								'label'         => __( 'Ảnh Tầm nhìn & Sứ mệnh', 'spl' ),
								'name'          => 'image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '50' ],
							],
							[
								'key'     => 'field_abm_vis_title',
								'label'   => __( 'Tiêu đề Tầm nhìn (Tùy chọn)', 'spl' ),
								'name'    => 'vision_title',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'     => 'field_abm_vis_desc',
								'label'   => __( 'Nội dung Tầm nhìn (Tùy chọn)', 'spl' ),
								'name'    => 'vision_desc',
								'type'    => 'textarea',
								'rows'    => 3,
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'     => 'field_abm_mis_title',
								'label'   => __( 'Tiêu đề Sứ mệnh (Tùy chọn)', 'spl' ),
								'name'    => 'mission_title',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'     => 'field_abm_mis_desc',
								'label'   => __( 'Nội dung Sứ mệnh (Tùy chọn)', 'spl' ),
								'name'    => 'mission_desc',
								'type'    => 'textarea',
								'rows'    => 3,
								'wrapper' => [ 'width' => '50' ],
							],
						],
					],

					// 9. [Tùy chọn] Stats
					'layout_about_stats' => [
						'key'        => 'layout_about_stats',
						'name'       => 'about_stats',
						'label'      => __( '9. [Tùy chọn] Những Con Số Biết Nói', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_ab_stat_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'     => 'field_ab_stat_title',
								'label'   => __( 'Tiêu đề khối', 'spl' ),
								'name'    => 'title',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'           => 'field_ab_stat_img',
								'label'         => __( 'Ảnh trung tâm', 'spl' ),
								'name'          => 'center_image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '50' ],
							],
							[
								'key'          => 'field_ab_stat_items',
								'label'        => __( 'Danh sách con số', 'spl' ),
								'name'         => 'items',
								'type'         => 'repeater',
								'layout'       => 'table',
								'button_label' => __( 'Thêm con số', 'spl' ),
								'sub_fields'   => [
									[
										'key'     => 'field_ab_num_count',
										'label'   => __( 'Số lượng', 'spl' ),
										'name'    => 'count',
										'type'    => 'text',
										'wrapper' => [ 'width' => '20' ],
									],
									[
										'key'     => 'field_ab_num_suffix',
										'label'   => __( 'Ký tự sau (%, +)', 'spl' ),
										'name'    => 'suffix',
										'type'    => 'text',
										'wrapper' => [ 'width' => '20' ],
									],
									[
										'key'     => 'field_ab_num_title',
										'label'   => __( 'Mô tả con số', 'spl' ),
										'name'    => 'title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '60' ],
									],
								],
							],
						],
					],
				],
			],
		],
		'location'              => [
			[
				[
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'templates/template-page-about.php',
				],
			],
			[
				[
					'param'    => 'post',
					'operator' => '==',
					'value'    => '942',
				],
			],
			[
				[
					'param'    => 'post',
					'operator' => '==',
					'value'    => '1052',
				],
			],
			[
				[
					'param'    => 'post',
					'operator' => '==',
					'value'    => '936',
				],
			],
		],
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	] );

	// -------------------------------------------------------------
	// 3. HỆ THỐNG R&D (COOPERATION / R&D SYSTEM PAGE)
	// -------------------------------------------------------------
	acf_add_local_field_group( [
		'key'                   => 'group_vinacos_cooperation_page',
		'title'                 => __( 'VINACOS - Quản Trị Trang Hệ Thống R&D', 'spl' ),
		'fields'                => [
			[
				'key'          => 'field_vinacos_coop_fc',
				'label'        => __( 'Nội dung trang Hệ Thống R&D (1:1 Giao Diện Thực Tế)', 'spl' ),
				'name'         => 'cooperation_sections',
				'type'         => 'flexible_content',
				'instructions' => __( 'Quản trị toàn bộ 5 phần hiển thị thực tế trên trang Hệ Thống R&D (/oem-odm-gia-cong-unila-viet-nam/)', 'spl' ),
				'button_label' => __( 'Thêm Block R&D', 'spl' ),
				'layouts'      => [
					// 1. Hero Banner & Breadcrumb
					'layout_coop_hero' => [
						'key'        => 'layout_coop_hero',
						'name'       => 'cooperation_hero',
						'label'      => __( '1. Banner Đầu Trang & Đường Dẫn (Breadcrumb)', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_coop_h_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'           => 'field_coop_h_banner',
								'label'         => __( 'Ảnh banner đầu trang (Desktop)', 'spl' ),
								'name'          => 'banner_image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '34' ],
							],
							[
								'key'           => 'field_coop_h_banner_mobile',
								'label'         => __( 'Ảnh banner Mobile (Tùy chọn)', 'spl' ),
								'name'          => 'banner_image_mobile',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '33' ],
							],
							[
								'key'     => 'field_coop_h_breadcrumb',
								'label'   => __( 'Nhãn đường dẫn (Breadcrumb)', 'spl' ),
								'name'    => 'breadcrumb_label',
								'type'    => 'text',
								'wrapper' => [ 'width' => '33' ],
							],
						],
					],

					// 2. Sáng tạo & Sáng chế (.oem-1-section)
					'layout_coop_benefits' => [
						'key'        => 'layout_coop_benefits',
						'name'       => 'cooperation_benefits',
						'label'      => __( '2. Sáng Tạo & Sáng Chế (Ấn Phẩm / Bài Báo R&D)', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_coop_b_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'   => 'field_coop_b_heading',
								'label' => __( 'Tiêu đề chính (H1)', 'spl' ),
								'name'  => 'section_heading',
								'type'  => 'text',
							],
							[
								'key'          => 'field_coop_b_intro',
								'label'        => __( 'Nội dung giới thiệu bên dưới tiêu đề', 'spl' ),
								'name'         => 'intro_content',
								'type'         => 'wysiwyg',
								'toolbar'      => 'full',
								'media_upload' => 0,
							],
							[
								'key'          => 'field_coop_b_items',
								'label'        => __( 'Danh sách ảnh bài báo / ấn phẩm R&D (3 khung dọc)', 'spl' ),
								'name'         => 'benefit_items',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm ảnh ấn phẩm R&D', 'spl' ),
								'sub_fields'   => [
									[
										'key'           => 'field_ben_img',
										'label'         => __( 'Ảnh minh họa ấn phẩm', 'spl' ),
										'name'          => 'image',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'medium',
										'wrapper'       => [ 'width' => '40' ],
									],
									[
										'key'     => 'field_ben_title',
										'label'   => __( 'Tiêu đề / Chú thích ảnh (Alt)', 'spl' ),
										'name'    => 'title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '60' ],
									],
								],
							],
						],
					],

					// 3. Nghiên cứu nền chất (.oem-2-section)
					'layout_coop_formulation' => [
						'key'        => 'layout_coop_formulation',
						'name'       => 'cooperation_formulation',
						'label'      => __( '3. Nghiên Cứu Nền Chất (Accordion & Ảnh Phải)', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_coop_fm_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'     => 'field_coop_fm_title',
								'label'   => __( 'Tiêu đề khối', 'spl' ),
								'name'    => 'title',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'           => 'field_coop_fm_img',
								'label'         => __( 'Ảnh minh họa bên phải (640×888)', 'spl' ),
								'name'          => 'image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '50' ],
							],
							[
								'key'          => 'field_coop_fm_items',
								'label'        => __( 'Danh sách nền chất (Accordion đóng/mở)', 'spl' ),
								'name'         => 'accordion_items',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm nền chất', 'spl' ),
								'sub_fields'   => [
									[
										'key'     => 'field_coop_fm_item_head',
										'label'   => __( 'Tiêu đề thanh đóng/mở (In hoa)', 'spl' ),
										'name'    => 'heading',
										'type'    => 'text',
										'wrapper' => [ 'width' => '50' ],
									],
									[
										'key'     => 'field_coop_fm_item_sub',
										'label'   => __( 'Tiêu đề phụ bên trong', 'spl' ),
										'name'    => 'sub_title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '50' ],
									],
									[
										'key'   => 'field_coop_fm_item_desc',
										'label' => __( 'Nội dung mô tả chi tiết', 'spl' ),
										'name'  => 'desc',
										'type'  => 'textarea',
										'rows'  => 3,
									],
								],
							],
						],
					],

					// 4. Đối tác (.oem-3-section)
					'layout_coop_partners' => [
						'key'        => 'layout_coop_partners',
						'name'       => 'cooperation_partners',
						'label'      => __( '4. Đối Tác Nghiên Cứu & Nguyên Liệu', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_coop_pt_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'   => 'field_coop_pt_title',
								'label' => __( 'Tiêu đề khối', 'spl' ),
								'name'  => 'title',
								'type'  => 'text',
							],
							[
								'key'          => 'field_coop_pt_desc',
								'label'        => __( 'Nội dung giới thiệu đối tác', 'spl' ),
								'name'         => 'desc',
								'type'         => 'wysiwyg',
								'toolbar'      => 'full',
								'media_upload' => 0,
							],
							[
								'key'          => 'field_coop_pt_items',
								'label'        => __( 'Danh sách ảnh slide đối tác (680×454)', 'spl' ),
								'name'         => 'partner_items',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm ảnh đối tác', 'spl' ),
								'sub_fields'   => [
									[
										'key'           => 'field_coop_pt_item_img',
										'label'         => __( 'Ảnh đối tác', 'spl' ),
										'name'          => 'image',
										'type'          => 'image',
										'return_format' => 'id',
										'preview_size'  => 'medium',
										'wrapper'       => [ 'width' => '40' ],
									],
									[
										'key'     => 'field_coop_pt_item_title',
										'label'   => __( 'Chú thích ảnh (Alt)', 'spl' ),
										'name'    => 'title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '60' ],
									],
								],
							],
						],
					],

					// 5. Quy trình nghiên cứu và sản xuất (.oem-5-section)
					'layout_coop_process' => [
						'key'        => 'layout_coop_process',
						'name'       => 'cooperation_process',
						'label'      => __( '5. Quy Trình Nghiên Cứu Và Sản Xuất (Ảnh Lab & 3 Định Hướng)', 'spl' ),
						'display'    => 'block',
						'sub_fields' => [
							[
								'key'           => 'field_coop_p_disable',
								'label'         => __( 'Ẩn section này', 'spl' ),
								'name'          => 'disable',
								'type'          => 'true_false',
								'ui'            => 1,
								'default_value' => 0,
							],
							[
								'key'     => 'field_coop_p_heading',
								'label'   => __( 'Tiêu đề khối', 'spl' ),
								'name'    => 'section_heading',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'     => 'field_coop_p_subtitle',
								'label'   => __( 'Mô tả phụ dưới tiêu đề (Tùy chọn)', 'spl' ),
								'name'    => 'subtitle',
								'type'    => 'text',
								'wrapper' => [ 'width' => '50' ],
							],
							[
								'key'           => 'field_coop_p_lab_img',
								'label'         => __( 'Ảnh phòng Lab bên trái (400×533)', 'spl' ),
								'name'          => 'lab_image',
								'type'          => 'image',
								'return_format' => 'id',
								'preview_size'  => 'medium',
								'wrapper'       => [ 'width' => '35' ],
							],
							[
								'key'          => 'field_coop_p_intro',
								'label'        => __( 'Đoạn giới thiệu bên cạnh ảnh phòng Lab', 'spl' ),
								'name'         => 'intro_desc',
								'type'         => 'wysiwyg',
								'toolbar'      => 'full',
								'media_upload' => 0,
								'wrapper'      => [ 'width' => '65' ],
							],
							[
								'key'          => 'field_coop_p_pillars',
								'label'        => __( '3 Hướng Phát Triển Công Thức (01, 02, 03)', 'spl' ),
								'name'         => 'rd_pillars',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => __( 'Thêm hướng phát triển', 'spl' ),
								'sub_fields'   => [
									[
										'key'     => 'field_coop_pil_title',
										'label'   => __( 'Tiêu đề (VD: 01 - Từ sản phẩm có sẵn)', 'spl' ),
										'name'    => 'title',
										'type'    => 'text',
										'wrapper' => [ 'width' => '50' ],
									],
									[
										'key'     => 'field_coop_pil_sub',
										'label'   => __( 'Mô tả đối tượng (chữ xanh)', 'spl' ),
										'name'    => 'subtitle',
										'type'    => 'text',
										'wrapper' => [ 'width' => '50' ],
									],
									[
										'key'   => 'field_coop_pil_desc',
										'label' => __( 'Nội dung chi tiết', 'spl' ),
										'name'  => 'desc',
										'type'  => 'textarea',
										'rows'  => 3,
									],
								],
							],
						],
					],
				],
			],
		],
		'location'              => [
			[
				[
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'templates/template-page-cooperation.php',
				],
			],
			[
				[
					'param'    => 'post',
					'operator' => '==',
					'value'    => '944',
				],
			],
			[
				[
					'param'    => 'post',
					'operator' => '==',
					'value'    => '926',
				],
			],
		],
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	] );
}

if ( is_file( __DIR__ . '/acf-about-sync.php' ) ) {
	require_once __DIR__ . '/acf-about-sync.php';
}
if ( is_file( __DIR__ . '/acf-cooperation-sync.php' ) ) {
	require_once __DIR__ . '/acf-cooperation-sync.php';
}
