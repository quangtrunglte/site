<?php
/**
 * Profile Header Theme Part.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php $GLOBALS['hide'] = 'hide'; ?>
<?php get_template_part( 'template-parts/king-header-nav' ); ?>
<?php
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

<?php if ( get_field( 'cover_image','user_' . $this_user->ID ) ) {
	$coverimage = get_field( 'cover_image','user_' . $this_user->ID );
	$cover = $coverimage['url'];
} elseif ( get_field( 'default_cover', 'options' ) ) {
	$coverimage = get_field( 'default_cover', 'options' );
	$cover = $coverimage['url'];
} else {
	$cover = '';
} ?>

<div class="king-profile-top" id="nocover" <?php if ( ! empty( $cover ) ) : ?> style="background-image: url('<?php echo esc_url( $cover ); ?>');" <?php endif; ?> >
	<div class="king-profile-head">
		<div class="king-profile-user">	
			<?php if ( ! $profile_id ) : ?>
				<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $GLOBALS['king_edit'] ); ?>" class="edit-profile"><i class="fa fa-cog fa-2x" aria-hidden="true"></i></a>
			<?php endif; ?>
			<?php if ( $profile_id && ( $this_user->ID !== get_current_user_id() ) && get_field( 'enable_private_messages', 'options' ) && is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_prvtmsg'] . '/' . $this_user->user_login ); ?>" class="edit-profile"><i class="fa fa-envelope fa-2x" aria-hidden="true"></i></a>
			<?php endif; ?>			
			<?php if ( get_field( 'verified_account','user_' . $this_user->ID ) ) {
				$verified = 'verified';
			} else {
				$verified = '';
			}
			?>
			<div class="king-profile-avatar <?php echo esc_attr( $verified ); ?>">
				<?php if ( get_field( 'verified_account','user_' . $this_user->ID ) ) : ?>
					<span class="verified_account" title="<?php echo esc_html_e( 'verified account', 'king' ); ?>">
						<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i>
					</span>
				<?php endif; ?>    
				<?php if ( get_field( 'author_image','user_' . $this_user->ID ) ) : $image = get_field( 'author_image','user_' . $this_user->ID ); ?>
					<img src="<?php  echo esc_url( $image['sizes']['thumbnail'] ); ?>" alt=""/>
				<?php else : ?>
					<span class="no-avatar"></span>  
				<?php endif; ?>

			</div>
		<?php if ( get_field( 'enable_user_points', 'options' ) ) : ?>
			<div class="king-points" title="<?php echo esc_html_e( 'Points','king' ); ?>"><i class="fa fa-star" aria-hidden="true"></i> <?php echo king_user_points( $this_user->ID ); ?></div>
		<?php endif; ?>				
		</div>		
		<div class="king-profile-info">	
			<h4><?php echo esc_attr( $this_user->data->display_name ); ?></h4>				
			<?php echo wp_kses_post( get_the_author_meta( 'first_name',$this_user->ID ) ); ?> 
			<?php echo wp_kses_post( get_the_author_meta( 'last_name',$this_user->ID ) ); ?>
		</div>		
		<div class="king-profile-social">
			<ul>
				<?php if ( get_field( 'profile_facebook', 'user_' . $this_user->ID ) ) : ?>
					<li class="fb"><a href="<?php the_field( 'profile_facebook', 'user_' . $this_user->ID ); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_twitter', 'user_' . $this_user->ID ) ) : ?>
					<li class="twi"><a href="<?php the_field( 'profile_twitter', 'user_' . $this_user->ID ); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_google', 'user_' . $this_user->ID ) ) : ?>
					<li class="g"><a href="<?php the_field( 'profile_google', 'user_' . $this_user->ID ); ?>" target="_blank"><i class="fa fa-google-plus"></i> </a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_linkedin', 'user_' . $this_user->ID ) ) : ?>
					<li class="ln"><a href="<?php the_field( 'profile_linkedin', 'user_' . $this_user->ID ); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
				<?php endif; ?>            
			</ul>
		</div>

		<?php if ( ( $profile_id ) && ( is_user_logged_in() ) ) : ?>
			<?php
			$current_user = wp_get_current_user();
			if ( $this_user->ID !== $current_user->ID ) {
				echo king_get_simple_follows_button( $this_user->ID );
			}
			?>
		<?php endif; ?>

		<div class="profile-stats">
			<span class="profile-stats-num">

				<i><?php echo esc_attr( count_user_posts( $this_user->ID ) ); ?></i>
				<?php echo esc_html_e( 'Posts','king' ); ?>
			</span><!-- posts -->
			<span class="profile-stats-num">
				<i>
					<?php
					$likes = get_user_meta( $this_user->ID, 'wp__user_like_count', true );
					if ( ! empty( $likes ) ) {
						echo esc_attr( $likes );
					} else {
						echo '0';
					}

					?>                            
				</i>                
				<?php echo esc_html_e( 'Likes','king' ); ?>                    
			</span><!-- likes -->
			<span class="profile-stats-num">
				<i><?php
					$comment_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS total FROM $wpdb->comments WHERE comment_approved = 1 AND user_id = %s", $this_user->ID ) );
					echo esc_attr( $comment_count ); ?></i>
					<?php echo esc_html_e( 'Comments','king' ); ?>
				</span><!-- comments -->
				<span class="profile-stats-num">

					<i>
						<?php
						$following = get_user_meta( $this_user->ID, 'wp__user_follow_count', true );
						if ( ! empty( $following ) ) {
							echo esc_attr( $following );
						} else {
							echo '0';
						}

						?>                            
					</i>
					<?php echo esc_html_e( 'Following','king' ); ?>
				</span><!-- following -->
				<span class="profile-stats-num">
					<i>
						<?php
						$followers = get_user_meta( $this_user->ID, 'wp__post_follow_count', true );
						if ( ! empty( $followers ) ) {
							echo esc_attr( $followers );
						} else {
							echo '0';
						}

						?>                            
					</i>
					<?php echo esc_html_e("Followers","king"); ?>
				</span><!-- followers -->
			</div>   


			<div class="king-profile-links">
				<?php if ( ! $profile_id ) : ?>
					
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] ); ?>" class="my-posts <?php echo esc_attr( $GLOBALS['profile'] ); ?>"><?php echo esc_html_e( 'My Posts','king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_likes'] ); ?>" class="my-likes <?php echo esc_attr( $GLOBALS['likes'] ); ?>"><?php echo esc_html_e( 'Liked','king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_followers'] ); ?>" class="followers <?php echo esc_attr( $GLOBALS['followers'] ); ?>"><?php echo esc_html_e( 'My followers','king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_following'] ); ?>" class="following <?php echo esc_attr( $GLOBALS['following'] ); ?>"><?php echo esc_html_e( 'Following','king' ); ?></a>
				<?php else : ?>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $this_user->data->user_login ); ?>" class="my-posts <?php echo esc_attr( $GLOBALS['profile'] ); ?>"><?php echo esc_attr( $this_user->data->display_name ) . ' ' . esc_html__( 'posts','king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_likes'] . '/' . $this_user->data->user_login ); ?>" class="my-likes <?php echo esc_attr( $GLOBALS['likes'] ); ?>"><?php echo esc_attr( $this_user->data->display_name ) . ' ' . esc_html__( 'likes','king' ); ?></a>      
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_followers'] . '/' . $this_user->data->user_login ); ?>" class="followers <?php echo esc_attr( $GLOBALS['followers'] ); ?>"><?php echo esc_attr( $this_user->data->display_name ) . ' ' . esc_html__( 'Followers','king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_following'] . '/' . $this_user->data->user_login ); ?>" class="following <?php echo esc_attr( $GLOBALS['following'] ); ?>"><?php echo esc_attr( $this_user->data->display_name ) . ' ' . esc_html__( 'Following','king' ); ?></a> 
				<?php endif; ?>
			</div>
		</div>
</div>