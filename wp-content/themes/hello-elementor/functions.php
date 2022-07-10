<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '2.5.0' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_load_textdomain', [ true ], '2.0', 'hello_elementor_load_textdomain' );
		if ( apply_filters( 'hello_elementor_load_textdomain', $hook_result ) ) {
			load_theme_textdomain( 'hello-elementor', get_template_directory() . '/languages' );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_register_menus', [ true ], '2.0', 'hello_elementor_register_menus' );
		if ( apply_filters( 'hello_elementor_register_menus', $hook_result ) ) {
			register_nav_menus( [ 'menu-1' => __( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => __( 'Footer', 'hello-elementor' ) ] );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_add_theme_support', [ true ], '2.0', 'hello_elementor_add_theme_support' );
		if ( apply_filters( 'hello_elementor_add_theme_support', $hook_result ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			$hook_result = apply_filters_deprecated( 'elementor_hello_theme_add_woocommerce_support', [ true ], '2.0', 'hello_elementor_add_woocommerce_support' );
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', $hook_result ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$enqueue_basic_style = apply_filters_deprecated( 'elementor_hello_theme_enqueue_style', [ true ], '2.0', 'hello_elementor_enqueue_style' );
		$min_suffix          = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', $enqueue_basic_style ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_register_elementor_locations', [ true ], '2.0', 'hello_elementor_register_elementor_locations' );
		if ( apply_filters( 'hello_elementor_register_elementor_locations', $hook_result ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

/**
 * If Elementor is installed and active, we can load the Elementor-specific Settings & Features
*/

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';

/**
 * Include customizer registration functions
*/
function hello_register_customizer_functions() {
	if ( hello_header_footer_experiment_active() && is_customize_preview() ) {
		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_register_customizer_functions' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * Wrapper function to deal with backwards compatibility.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	}
}

// =========================================================
// 	php personnalisé -- php personnalisé -- php personnalisé
// ========================================================= 
// rajout code php de sortie de page de logout
add_action('check_admin_referer', 'logout_without_confirm', 10, 2);
function logout_without_confirm($action, $result) {
	if ($action == "log-out" && !isset($_GET['_wpnonce'])) { 
		$location = get_permalink(2341);
		wp_logout();
		header("Location: $location");
		die; 
	}
}

/*
* On utilise une fonction pour créer notre custom post type 'Sorties en Breizh'
*/

function wpm_custom_post_type() {

	// On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
	$labels = array(
		// Le nom au pluriel
		'name'                => _x( 'Sorties en Breizh', 'Post Type General Name'),
		// Le nom au singulier
		'singular_name'       => _x( 'Sortie en Breizh', 'Post Type Singular Name'),
		// Le libellé affiché dans le menu
		'menu_name'           => __( 'Sorties en Breizh'),
		// Les différents libellés de l'administration
		'all_items'           => __( 'Toutes les sorties en Breizh'),
		'view_item'           => __( 'Voir les sorties en Breizh'),
		'add_new_item'        => __( 'Ajouter une nouvelle sortie en Breizh'),
		'add_new'             => __( 'Ajouter'),
		'edit_item'           => __( 'Editer la sorties en Breizh'),
		'update_item'         => __( 'Modifier la sortie en Breizh'),
		'search_items'        => __( 'Rechercher une sortie en Breizh'),
		'not_found'           => __( 'Non trouvée'),
		'not_found_in_trash'  => __( 'Non trouvée dans la corbeille'),
	);
	
	// On peut définir ici d'autres options pour notre custom post type
	
	$args = array(
		'label'               => __( 'Sorties en Breizh'),
		'description'         => __( 'Tous sur sorties en Breizh'),
		'labels'              => $labels,
		'menu_icon'           => ('http://localhost/fanzine-de-breizh-wp/wp-content/uploads/2022/07/map-22x22-1.png'),
		
		// On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		/* 
		* Différentes options supplémentaires
		*/	
		'show_in_rest' => true,
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => true,
		'rewrite'			  => array( 'slug' => 'sorties-en-breizh'),

	);
	
	// On enregistre notre custom post type qu'on nomme ici "sortiesenbreizh" et ses arguments
	register_post_type( 'sortiesenbreizh', $args );

}

add_action( 'init', 'wpm_custom_post_type', 0 );


add_action( 'init', 'wpm_add_taxonomies', 0 );

//On crée 3 taxonomies personnalisées: Départements, Points d'intérêts et Thèmes.

function wpm_add_taxonomies() {
	
	// Taxonomie Départements

	// On déclare ici les différentes dénominations de notre taxonomie qui seront affichées et utilisées dans l'administration de WordPress
	$labels_departement = array(
		'name'              			=> _x( 'Départements', 'taxonomy general name'),
		'singular_name'     			=> _x( 'Département', 'taxonomy singular name'),
		'search_items'      			=> __( 'Chercher un département'),
		'all_items'        				=> __( 'Toutes les départements'),
		'edit_item'         			=> __( 'Editer le département'),
		'update_item'       			=> __( 'Mettre à jour le département'),
		'add_new_item'     				=> __( 'Ajouter un nouveau département'),
		'new_item_name'     			=> __( 'Valeur du nouveau département'),
		'separate_items_with_commas'	=> __( 'Séparer les départements avec une virgule'),
		'menu_name'         			=> __( 'Départements'),
	);

	$args_departement = array(
	// Si 'hierarchical' est défini à false, notre taxonomie se comportera comme une étiquette standard
		'hierarchical'      => true,
		'labels'            => $labels_departement,
		'show_ui'           => true,
		'show_in_rest' 		=> true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'departements' ),
	);

	register_taxonomy( 'departements', 'sortiesenbreizh', $args_departement );

	// Taxonomie Points d'intérêts
	
	$labels_pointsdinterets = array(
		'name'                       => _x( 'Points d\'intérêts', 'taxonomy general name'),
		'singular_name'              => _x( 'Point d\'intérêt', 'taxonomy singular name'),
		'search_items'               => __( 'Rechercher un point d\'intérêt'),
		'popular_items'              => __( 'Points d\'intérêts populaires'),
		'all_items'                  => __( 'Tous les points d\'intérêts'),
		'edit_item'                  => __( 'Editer un point d\'intérêt'),
		'update_item'                => __( 'Mettre à jour un point d\'intérêt'),
		'add_new_item'               => __( 'Ajouter un nouveau point d\'intérêt'),
		'new_item_name'              => __( 'Nom du nouveau point d\'intérêt'),
		'separate_items_with_commas' => __( 'Séparer les points d\'intérêts avec une virgule'),
		'add_or_remove_items'        => __( 'Ajouter ou supprimer un point d\'intérêt'),
		'choose_from_most_used'      => __( 'Choisir parmi les plus utilisés'),
		'not_found'                  => __( 'Pas de points d\'intérêts trouvés'),
		'menu_name'                  => __( 'Points d\'intérêts'),
	);

	$args_pointsdinterets = array(
		'hierarchical'          => true,
		'labels'                => $labels_pointsdinterets,
		'show_ui'               => true,
		'show_in_rest'			=> true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'points-d-interets' ),
	);

	register_taxonomy( 'pointsdinterets', 'sortiesenbreizh', $args_pointsdinterets );
	
	// Thèmes de sorties en Breizh

	$labels_theme_sorties = array(
		'name'                       => _x( 'Thèmes de sorties', 'taxonomy general name'),
		'singular_name'              => _x( 'Thème de sortie', 'taxonomy singular name'),
		'search_items'               => __( 'Rechercher un thème'),
		'popular_items'              => __( 'Thème populaires'),
		'all_items'                  => __( 'Tous les thèmes'),
		'edit_item'                  => __( 'Editer un thème'),
		'update_item'                => __( 'Mettre à jour un thème'),
		'add_new_item'               => __( 'Ajouter un nouveau thème'),
		'new_item_name'              => __( 'Nom du nouveau thème'),
		'add_or_remove_items'        => __( 'Ajouter ou supprimer un thème'),
		'choose_from_most_used'      => __( 'Choisir parmi les thèmes les plus utilisées'),
		'not_found'                  => __( 'Pas de thèmes trouvés'),
		'menu_name'                  => __( 'Thèmes de sorties'),
	);

	$args_theme_sorties = array(
	// Si 'hierarchical' est défini à true, notre taxonomie se comportera comme une catégorie standard
		'hierarchical'          => true,
		'labels'                => $labels_theme_sorties,
		'show_ui'               => true,
		'show_in_rest'			=> true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'themes-de-sorties' ),
	);

	register_taxonomy( 'themesdesortie', 'sortiesenbreizh', $args_theme_sorties );
}