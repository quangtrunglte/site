<?php
/**
 * User Profile Page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$GLOBALS['profile'] = 'active';
$profile_id = get_query_var( 'profile_id' );
if ( $profile_id ) {
	$this_user = get_user_by( 'login',$profile_id );
} else {
	$this_user = wp_get_current_user();
}
if ( ! $this_user->ID ) {
	wp_redirect( site_url() );
}
?>
<?php get_header(); ?>
<?php $GLOBALS['hide'] = 'hide'; ?>
<?php get_template_part( 'template-parts/king-profile-header' ); ?>

<div id="primary" class="profile-content-area">
	<main id="main" class="profile-site-main">
		<div class="king-profile-content king-grid">

			<div class="king-user-posts">
				<div class="row">
					<?php if ( get_the_author_meta( 'description',$this_user->ID ) ) : ?>
							<article class="post king-profile-sidebar">
							<h5 class="king-king-widget-title"><span><?php echo esc_html_e( 'About', 'king' ); ?></span></h5>
							<div class="king-about">
								<?php echo nl2br( get_the_author_meta( 'description',$this_user->ID ) ); ?>
							</div>

						</article>
					<?php endif; ?>	                        
					<?php
					$paged = isset( $_GET['page'] ) ? $_GET['page'] : 0;
					if ( get_field( 'length_of_posts_in_profile', 'options' ) ) {
						$length_user_posts = get_field( 'length_of_posts_in_profile', 'option' );
					} else {
						$length_user_posts = '8';
					}
					$the_query = new WP_Query( array( 'posts_per_page' => $length_user_posts, 'post_type' => 'post', 'author' => $this_user->ID, 'paged' => $paged ) );

					if ( $the_query->have_posts() ) :

						while ( $the_query->have_posts() ) :
							$the_query->the_post();
							get_template_part( 'template-parts/content', get_post_format() );
						endwhile;
						wp_reset_postdata();

						else : ?>
						<div class="no-follower"><i class="fa fa-slack fa-2x" aria-hidden="true"></i><?php esc_html_e( 'Sorry, no posts were found', 'king' ); ?> </div>
					<?php endif; ?>							
				</div>
				<div class="king-pagination">
					<?php
					$format = '?page=%#%';
					if ( $profile_id ) {
						$url = site_url() . '/' . $GLOBALS['king_account'] . '/' . $profile_id . '%_%';
					} else {
						$url = site_url() . '/' . $GLOBALS['king_account'] . '/%_%';
					}
							$big = 999999999; // need an unlikely integer.
							echo paginate_links( array(
								'base' => $url,
								'format' => $format,
								'current' => max( 1, $paged ),
								'total' => $the_query->max_num_pages,
								'prev_next'    => true,
								'prev_text'          => '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
								'next_text'          => '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
							) );
								?>
							</div>
						</div>


					</div>

				</main><!-- #main -->
			</div><!-- #primary -->
<?php get_footer(); ?>
