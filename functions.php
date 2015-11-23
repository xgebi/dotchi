<?php

if (!function_exists("dotchi_init")) {
	function dotchi_init() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		
		/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	 add_theme_support( 'title-tag' );
	}
}
add_action( 'after_setup_theme', 'dotchi_init' );

function dotchi_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dotchi_content_width', 900 );
}
add_action( 'after_setup_theme', 'dotchi_content_width', 0 );

function dotchi_scripts() {
	wp_enqueue_style( 'dotchi-style', get_stylesheet_uri() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dotchi_scripts' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dotchi_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'dotchi' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'dotchi_widgets_init' );

// Custom 'more' for excerpts
function new_excerpt_more($more) {
    global $post;
    // will be changed when I'll implement custom thingies
	return '<a class="moretag" href="'. get_permalink($post->ID) . '">'._e('Read more', 'dotchi') .'</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function dotchi_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>' , 'dotchi'), get_comment_author_link() ); ?>
	</div>
	<?php if ( $comment->comment_approved == '0' ) : ?>
		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'dotchi' ); ?></em>
		<br />
	<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
		<?php
			/* translators: 1: date, 2: time */
			printf( __('%1$s at %2$s', 'dotchi'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' , 'dotchi'), '  ', '' );
		?>
	</div>

	<?php comment_text(); ?>

	<div class="reply">
	<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function dotchi_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'dotchi_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'dotchi_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so dotchi_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so dotchi_categorized_blog should return false.
		return false;
	}
}
