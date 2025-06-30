<?php
/**
 * Custom Walker for Bulma Navbar
 */

class Bulma_Nav_Walker extends Walker_Nav_Menu {

	// Start Level - wrap with <div class="navbar-start"> or <div class="navbar-end">
	function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<div class=\"navbar-dropdown\">\n";
	}

	// End Level
	function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</div>\n";
	}

	// Start Element
	function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

		// Check if item has children
		$has_children = in_array( 'menu-item-has-children', $classes );

		if ( $depth === 0 ) {
			// Top level items
			if ( $has_children ) {
				$class_names = 'navbar-item has-dropdown is-hoverable';
				$link_class = 'navbar-link';
			} else {
				$class_names = 'navbar-item';
				$link_class = '';
			}
		} else {
			// Dropdown items
			$class_names = 'navbar-item';
			$link_class = '';
		}

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<div' . $id . ' class="' . $class_names . '">';

		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		$link_class_attr = $link_class ? ' class="' . $link_class . '"' : '';

		$args_before = '';
		$args_link_before = '';
		$args_link_after = '';
		$args_after = '';
		
		if ( $args ) {
			$args_before = property_exists( $args, 'before' ) ? $args->before : '';
			$args_link_before = property_exists( $args, 'link_before' ) ? $args->link_before : '';
			$args_link_after = property_exists( $args, 'link_after' ) ? $args->link_after : '';
			$args_after = property_exists( $args, 'after' ) ? $args->after : '';
		}

		$item_output = $args_before;
		$item_output .= '<a' . $attributes . $link_class_attr . '>';
		$item_output .= $args_link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args_link_after;
		$item_output .= '</a>';
		$item_output .= $args_after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	// End Element
	function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</div>\n";
	}
}
