<?php
/**
 * Header — 100% Unila HTML structure with Mega Menu & SVG icons.
 *
 * @package SPL
 */

defined( 'ABSPATH' ) || exit;

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100;200;300;400;500;600;700;800;900&family=Playball&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<?php
$body_extra = 'wp-custom-logo';
if ( ! is_shop() && ! is_product() && ! is_tax( 'product_cat' ) && ! is_post_type_archive( 'product' ) && ! is_archive() && ! is_single() && ( is_front_page() || ( is_page() && ( is_page_template( 'templates/template-page-home.php' ) || in_array( (int) get_the_ID(), [ 10, 1121 ], true ) ) ) ) ) {
	$body_extra .= ' home page-template page-template-pages page-template-page-home page-template-pagespage-home-php';
}
?>
<body <?php body_class( $body_extra ); ?>>
<?php wp_body_open(); ?>

<?php
use SPL\Core\Helper;

$logo_url   = '';
$logo_field = Helper::getField( 'logo', 'option' );
if ( is_array( $logo_field ) && ! empty( $logo_field['url'] ) ) {
	$logo_url = $logo_field['url'];
} elseif ( is_array( $logo_field ) && ! empty( $logo_field['id'] ) ) {
	$logo_url = wp_get_attachment_image_url( (int) $logo_field['id'], 'full' );
} elseif ( is_numeric( $logo_field ) && (int) $logo_field > 0 ) {
	$logo_url = wp_get_attachment_image_url( (int) $logo_field, 'full' );
} elseif ( is_string( $logo_field ) && ! empty( $logo_field ) && ! is_numeric( $logo_field ) ) {
	$logo_url = $logo_field;
}

if ( empty( $logo_url ) ) {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$logo_url = wp_get_attachment_image_url( (int) $custom_logo_id, 'full' );
	}
}

if ( empty( $logo_url ) || stripos( $logo_url, 'Logo-tong-hop' ) !== false || stripos( $logo_url, 'dailyxedien' ) !== false || stripos( $logo_url, '2026/07/logo-vinacos.png' ) !== false ) {
	$logo_url = get_template_directory_uri() . '/static/img/logo.png';
}
?>
<header>
	<div class="header-wrap">
		<div class="header-logo">
			<p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link">
					<img decoding="async" class="alignnone size-full wp-image-81 site-logo-img" src="<?php echo esc_url( $logo_url ); ?>" alt="B&B VINACOS" />
				</a>
			</p>
		</div>
		<div class="header-center">
			<nav class="navbar-nav" id="toggleMenu">
				<?php
				$is_en = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();

				// 1. Detect assigned menu (check main-nav, primary, main_menu, or specific slugs)
				$locations  = get_nav_menu_locations();
				$menu_id    = $locations['main-nav'] ?? $locations['primary'] ?? $locations['main_menu'] ?? 0;
				if ( ! $menu_id ) {
					$all_menus = wp_get_nav_menus();
					foreach ( $all_menus as $m ) {
						if ( $m->slug === 'vinacos-primary-menu' || $m->slug === 'primary-menu' || $m->slug === 'menu-chinh' ) {
							$menu_id = $m->term_id;
							break;
						}
					}
				}
				$menu_items = $menu_id ? wp_get_nav_menu_items( $menu_id ) : false;

				// 2. Base pages & default labels from WordPress database with Polylang resolution
				$about_id   = ( $is_en && function_exists( 'pll_get_post' ) ) ? ( pll_get_post( 942, 'en' ) ?: 1052 ) : 942;
				$about_page = get_post( $about_id );
				$about_url  = $about_page ? get_permalink( $about_page ) : ( $is_en ? home_url( '/en/partner-mindset-about/' ) : home_url( '/ve-chung-toi/' ) );
				$about_label = $is_en ? 'About Us' : ( $about_page ? get_the_title( $about_page ) : 'Về chúng tôi' );

				$shop_id    = ( $is_en && function_exists( 'pll_get_post' ) ) ? ( pll_get_post( 943, 'en' ) ?: 1123 ) : 943;
				$shop_page  = get_post( $shop_id );
				$shop_url   = $shop_page ? get_permalink( $shop_page ) : ( $is_en ? home_url( '/en/products/' ) : home_url( '/san-pham-gia-cong-unila-viet-nam/' ) );
				$shop_label = $is_en ? 'Products' : ( $shop_page ? get_the_title( $shop_page ) : 'Sản phẩm' );

				$oem_id     = ( $is_en && function_exists( 'pll_get_post' ) ) ? ( pll_get_post( 944, 'en' ) ?: 1122 ) : 944;
				$oem_page   = get_post( $oem_id );
				$oem_url    = $oem_page ? get_permalink( $oem_page ) : ( $is_en ? home_url( '/en/rd-system-oem-odm/' ) : home_url( '/oem-odm-gia-cong-unila-viet-nam/' ) );
				$oem_label  = $is_en ? 'R&D System & OEM/ODM' : ( $oem_page ? get_the_title( $oem_page ) : 'HỆ THỐNG R&D' );

				$news_id    = ( $is_en && function_exists( 'pll_get_post' ) ) ? ( pll_get_post( 928, 'en' ) ?: 1124 ) : 928;
				$news_page  = get_post( $news_id );
				$news_url   = $news_page ? get_permalink( $news_page ) : ( $is_en ? home_url( '/en/news/' ) : home_url( '/tin-tuc/' ) );
				$news_label = $is_en ? 'News' : ( $news_page ? get_the_title( $news_page ) : 'Tin tức' );

				$contact_id   = ( $is_en && function_exists( 'pll_get_post' ) ) ? ( pll_get_post( 937, 'en' ) ?: 1056 ) : 937;
				$contact_page = get_post( $contact_id );
				$contact_url  = $contact_page ? get_permalink( $contact_page ) : ( $is_en ? home_url( '/en/contact-us/' ) : home_url( '/lien-he/' ) );
				$contact_label = $is_en ? 'Contact Us' : ( $contact_page ? get_the_title( $contact_page ) : 'Liên hệ' );

				// 3. Override labels & URLs directly from WordPress Menu (only in VI mode to avoid overwriting EN)
				if ( ! empty( $menu_items ) && ! $is_en ) {
					foreach ( $menu_items as $mi ) {
						$mi_title = $mi->title;
						$mi_url   = $mi->url;
						if ( (int) $mi->object_id === 942 || stripos( $mi_url, 've-chung-toi' ) !== false || stripos( $mi_url, 'tam-the' ) !== false || stripos( $mi_url, 'about' ) !== false ) {
							$about_label = $mi_title;
							$about_url   = $mi_url;
						} elseif ( (int) $mi->object_id === 943 || stripos( $mi_url, 'san-pham' ) !== false || stripos( $mi_url, 'product' ) !== false ) {
							$shop_label  = $mi_title;
							$shop_url    = $mi_url;
						} elseif ( (int) $mi->object_id === 944 || stripos( $mi_url, 'oem' ) !== false || stripos( $mi_url, 'rd-system' ) !== false ) {
							$oem_label   = $mi_title;
							$oem_url     = $mi_url;
						} elseif ( (int) $mi->object_id === 928 || stripos( $mi_url, 'tin-tuc' ) !== false || stripos( $mi_url, 'news' ) !== false ) {
							$news_label  = $mi_title;
							$news_url    = $mi_url;
						} elseif ( (int) $mi->object_id === 937 || stripos( $mi_url, 'lien-he' ) !== false || stripos( $mi_url, 'contact' ) !== false ) {
							$contact_label = $mi_title;
							$contact_url   = $mi_url;
						}
					}
				}
				?>
				<ul id="primary-menu" class="main-menu">
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="<?php echo esc_url( $about_url ); ?>"><?php echo esc_html( $about_label ); ?></a>
					</li>
					<li class="menu-item menu-item-type-taxonomy menu-item-object-products menu-item-has-children">
						<a href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html( $shop_label ); ?></a>
						<div class="mega-menu">
							<div class="container">
								<ul class="sub-menu">
									<?php
									$mega_cats = $is_en ? array(
										array( 'slug' => 'serums', 'title' => 'Serums' ),
										array( 'slug' => 'creams', 'title' => 'Creams' ),
										array( 'slug' => 'gels-cleansers', 'title' => 'Gels & Cleansers' ),
									) : array(
										array( 'slug' => 'serum', 'title' => 'Serum' ),
										array( 'slug' => 'kem', 'title' => 'Kem' ),
										array( 'slug' => 'gel', 'title' => 'Gel' ),
									);

									foreach ( $mega_cats as $mc ) :
										$term = get_term_by( 'slug', $mc['slug'], 'product_cat' );
										$term_url = $term ? get_term_link( $term ) : $shop_url;
										
										$prods = get_posts( array(
											'post_type'      => 'product',
											'posts_per_page' => 20,
											'orderby'        => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ),
											'lang'           => $is_en ? 'en' : 'vi',
											'tax_query'      => array(
												array(
													'taxonomy' => 'product_cat',
													'field'    => 'slug',
													'terms'    => $mc['slug'],
												),
											),
										) );
										?>
										<li class="menu-item menu-item-has-children">
											<a href="<?php echo esc_url( $term_url ); ?>"><?php echo esc_html( $mc['title'] ); ?></a>
											<div class="mega-wrap mega-wrap--products">
												<ul class="sub-menu">
													<?php if ( ! empty( $prods ) ) : ?>
														<?php foreach ( $prods as $p ) : ?>
															<li><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></li>
														<?php endforeach; ?>
													<?php else : ?>
														<li><a href="<?php echo esc_url( $term_url ); ?>"><?= $is_en ? 'View All' : 'Xem tất cả' ?></a></li>
													<?php endif; ?>
												</ul>
											</div>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="<?php echo esc_url( $oem_url ); ?>"><?php echo esc_html( $oem_label ); ?></a>
					</li>
					<li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-has-children">
						<a href="<?php echo esc_url( $news_url ); ?>"><?php echo esc_html( $news_label ); ?></a>
						<div class="mega-menu">
							<div class="container">
								<ul class="sub-menu">
									<?php
									$news_cats = $is_en ? array(
										array( 'slug' => 'news-industry-trends', 'title' => 'News & Market Trends' ),
										array( 'slug' => 'beauty-skincare-blog', 'title' => 'Beauty Blog' ),
										array( 'slug' => 'oem-odm-insights', 'title' => 'OEM/ODM Insights' ),
									) : array(
										array( 'slug' => 'tin-tuc', 'title' => 'Tin Tức & Thị Trường' ),
										array( 'slug' => 'blog', 'title' => 'Blog Làm Đẹp' ),
										array( 'slug' => 'dich-vu-xe-dien', 'title' => 'Dịch Vụ Gia Công' ),
									);

									foreach ( $news_cats as $nc ) :
										$cat = get_category_by_slug( $nc['slug'] );
										$cat_url = $cat ? get_category_link( $cat ) : $news_url;
										
										$posts = get_posts( array(
											'post_type'      => 'post',
											'posts_per_page' => 4,
											'lang'           => $is_en ? 'en' : 'vi',
											'category_name'  => $nc['slug'],
										) );
										
										$preview_img = ! empty( $posts ) ? get_the_post_thumbnail_url( $posts[0]->ID, 'medium' ) : get_template_directory_uri() . '/static/img/logo.png';
										?>
										<li class="menu-item menu-item-has-children">
											<a href="<?php echo esc_url( $cat_url ); ?>"><?php echo esc_html( $nc['title'] ); ?></a>
											<div class="mega-wrap">
												<ul class="sub-menu">
													<?php foreach ( $posts as $ps ) : ?>
														<li><a href="<?php echo esc_url( get_permalink( $ps ) ); ?>"><?php echo esc_html( get_the_title( $ps ) ); ?></a></li>
													<?php endforeach; ?>
												</ul>
												<div class="walker-preview img-cover"><img src="<?php echo esc_url( $preview_img ); ?>" alt="<?php echo esc_attr( $nc['title'] ); ?>"></div>
											</div>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( $contact_label ); ?></a>
					</li>
				</ul>
			</nav>
		</div>
		<div class="header-right">
			<div class="button-search"><?= spl_icon( 'search', '', 18 ) ?></div>
			<div class="button-cart"><?= spl_icon( 'cart', '', 18 ) ?></div>
			<div class="button-language">
				<?php
				if ( function_exists( 'pll_the_languages' ) ) {
					get_template_part( 'template-parts/blocks/language-switcher' );
				} else {
				?>
				<div class="wpml-ls-statics-shortcode_actions wpml-ls wpml-ls-legacy-dropdown-click js-wpml-ls-legacy-dropdown-click">
					<ul>
						<li class="wpml-ls-slot-shortcode_actions wpml-ls-item wpml-ls-item-vi wpml-ls-current-language wpml-ls-first-item wpml-ls-last-item wpml-ls-item-legacy-dropdown-click">
							<a href="#" class="js-wpml-ls-item-toggle wpml-ls-item-toggle">
								<span class="wpml-ls-native">VN</span>
							</a>
						</li>
					</ul>
				</div>
				<?php } ?>
			</div>
			<button type="button" id="buttonMenu" aria-controls="toggleMenu" data-target="#toggleMenu">
				<span class="line"></span>
				<span class="line"></span>
				<span class="line"></span>
			</button>
		</div>
	</div>
</header>

<div class="mobile-wrap">
	<i class="close-mobile">&times;</i>
	<div class="navbar-nav-list"></div>
</div>
<div class="backdrop backdrop-mobile"></div>

<main>
