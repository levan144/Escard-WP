<?php

function cptui_register_my_cpts_companies() {

	/**
	 * Post Type: Companies.
	 */

	$labels = [
		"name" => __( "Companies", "ljaddons" ),
		"singular_name" => __( "Company", "ljaddons" ),
		"menu_name" => __( "My Companies", "ljaddons" ),
		"all_items" => __( "All Companies", "ljaddons" ),
		"add_new" => __( "Add new", "ljaddons" ),
		"add_new_item" => __( "Add new Company", "ljaddons" ),
		"edit_item" => __( "Edit Company", "ljaddons" ),
		"new_item" => __( "New Company", "ljaddons" ),
		"view_item" => __( "View Company", "ljaddons" ),
		"view_items" => __( "View Companies", "ljaddons" ),
		"search_items" => __( "Search Companies", "ljaddons" ),
		"not_found" => __( "No Companies found", "ljaddons" ),
		"not_found_in_trash" => __( "No Companies found in trash", "ljaddons" ),
		"parent" => __( "Parent Company:", "ljaddons" ),
		"featured_image" => __( "Featured image for this Company", "ljaddons" ),
		"set_featured_image" => __( "Set featured image for this Company", "ljaddons" ),
		"remove_featured_image" => __( "Remove featured image for this Company", "ljaddons" ),
		"use_featured_image" => __( "Use as featured image for this Company", "ljaddons" ),
		"archives" => __( "Company archives", "ljaddons" ),
		"insert_into_item" => __( "Insert into Company", "ljaddons" ),
		"uploaded_to_this_item" => __( "Upload to this Company", "ljaddons" ),
		"filter_items_list" => __( "Filter Companies list", "ljaddons" ),
		"items_list_navigation" => __( "Companies list navigation", "ljaddons" ),
		"items_list" => __( "Companies list", "ljaddons" ),
		"attributes" => __( "Companies attributes", "ljaddons" ),
		"name_admin_bar" => __( "Company", "ljaddons" ),
		"item_published" => __( "Company published", "ljaddons" ),
		"item_published_privately" => __( "Company published privately.", "ljaddons" ),
		"item_reverted_to_draft" => __( "Company reverted to draft.", "ljaddons" ),
		"item_scheduled" => __( "Company scheduled", "ljaddons" ),
		"item_updated" => __( "Company updated.", "ljaddons" ),
		"parent_item_colon" => __( "Parent Company:", "ljaddons" ),
	];

	$args = [
		"label" => __( "Companies", "ljaddons" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "companies", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-building",
		"supports" => [ "title", "thumbnail", "editor"],
		"show_in_graphql" => false,
	];

	register_post_type( "companies", $args );
}



function cptui_register_my_taxes_departments() {

	/**
	 * Taxonomy: Departments.
	 */

	$labels = [
		"name" => __( "Departments", "ljaddons" ),
		"singular_name" => __( "Department", "ljaddons" ),
		"menu_name" => __( "Departments", "ljaddons" ),
		"all_items" => __( "All Departments", "ljaddons" ),
		"edit_item" => __( "Edit Department", "ljaddons" ),
		"view_item" => __( "View Department", "ljaddons" ),
		"update_item" => __( "Update Department name", "ljaddons" ),
		"add_new_item" => __( "Add new Department", "ljaddons" ),
		"new_item_name" => __( "New Department name", "ljaddons" ),
		"parent_item" => __( "Parent Department", "ljaddons" ),
		"parent_item_colon" => __( "Parent Department:", "ljaddons" ),
		"search_items" => __( "Search Departments", "ljaddons" ),
		"popular_items" => __( "Popular Departments", "ljaddons" ),
		"separate_items_with_commas" => __( "Separate Departments with commas", "ljaddons" ),
		"add_or_remove_items" => __( "Add or remove Departments", "ljaddons" ),
		"choose_from_most_used" => __( "Choose from the most used Departments", "ljaddons" ),
		"not_found" => __( "No Departments found", "ljaddons" ),
		"no_terms" => __( "No Departments", "ljaddons" ),
		"items_list_navigation" => __( "Departments list navigation", "ljaddons" ),
		"items_list" => __( "Departments list", "ljaddons" ),
		"back_to_items" => __( "Back to Departments", "ljaddons" ),
		"name_field_description" => __( "The name is how it appears on your site.", "ljaddons" ),
		"parent_field_description" => __( "Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "ljaddons" ),
		"slug_field_description" => __( "The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "ljaddons" ),
		"desc_field_description" => __( "The description is not prominent by default; however, some themes may show it.", "ljaddons" ),
	];

	
	$args = [
		"label" => __( "Departments", "ljaddons" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'departments', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "departments",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => true,
		"sort" => true,
		"show_in_graphql" => false,
	];
	register_taxonomy( "departments", [ "companies" ], $args );
}

$settings = ljaddons_get_settings();
if($settings['companies_enabled']){
    add_action( 'init', 'cptui_register_my_cpts_companies' );
    add_action( 'init', 'cptui_register_my_taxes_departments' );
}
