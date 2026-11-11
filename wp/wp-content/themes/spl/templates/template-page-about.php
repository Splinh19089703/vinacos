<?php
/**
 * Template Name: Giới Thiệu — 100% Exact Unila HTML layout for /tam-the-cong-su-unila-viet-nam/.
 *
 * @package SPL
 */

use SPL\Core\Helper;

defined( 'ABSPATH' ) || exit;

get_header();

global $post;
$page_id   = $post->ID ?? 0;
$sections  = $page_id ? ( function_exists( 'get_field' ) ? get_field( 'about_sections', $page_id ) : ( class_exists( Helper::class ) ? Helper::getField( 'about_sections', $page_id ) : false ) ) : false;
$is_synced = ( '1' === get_option( 'spl_about_acf_synced_v2' ) );

// Render Hero Banner at top of About page unless explicitly disabled
$hero_section = null;
if ( ! empty( $sections ) && is_array( $sections ) ) {
	foreach ( $sections as $s ) {
		if ( in_array( $s['acf_fc_layout'] ?? '', [ 'about_hero', 'hero' ], true ) ) {
			$hero_section = $s;
			break;
		}
	}
}
if ( empty( $hero_section['disable'] ) ) {
	get_template_part( 'parts/about/hero', null, $hero_section ?? [] );
}

if ( ! empty( $sections ) && is_array( $sections ) ) :
	$cta_rendered = false;
	foreach ( $sections as $section ) :
		if ( ! empty( $section['disable'] ) ) :
			continue;
		endif;

		$layout = $section['acf_fc_layout'] ?? '';
		$part   = str_replace( 'about_', '', $layout );

		// Hero is already handled at top
		if ( 'hero' === $part ) :
			continue;
		endif;

		// Pre-migration compatibility: hide legacy unseeded blocks and map legacy stats -> promises
		if ( ! $is_synced ) :
			if ( in_array( $part, [ 'mission', 'timeline' ], true ) ) :
				continue;
			endif;
			if ( 'stats' === $part ) :
				get_template_part( 'parts/about/promises', null, $section );
				continue;
			endif;
			if ( 'cta' === $part ) :
				if ( $cta_rendered ) :
					continue;
				endif;
				$cta_rendered = true;
			endif;
		endif;

		get_template_part( 'parts/about/' . $part, null, $section );
	endforeach;

else :
	// Render default Unila sections in exact visual sequence
	get_template_part( 'parts/about/story' );
	get_template_part( 'parts/about/message' );
	get_template_part( 'parts/about/promises' );
	get_template_part( 'parts/about/team' );
	get_template_part( 'parts/about/cta' );
endif;

get_footer();
