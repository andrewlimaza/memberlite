<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Memberlite
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action('before_page'); ?>
<div id="page" class="hfeed site">
	<?php do_action('before_mobile_nav'); ?>	
	<?php 
		if(is_active_sidebar('sidebar-5') || has_nav_menu('primary'))
		{
			//show the mobile menu widget area
			?>
			<nav id="mobile-navigation" role="navigation">
			<?php
				if(is_active_sidebar('sidebar-5'))
				{
					dynamic_sidebar('sidebar-5');
				}
				elseif(has_nav_menu('primary'))
				{
					$mobile_defaults = array(
						'theme_location' => 'primary',
					);				
					wp_nav_menu($mobile_defaults ); 				
				}
			?>
			</nav>
			<div class="mobile-navigation-bar">
				<button class="menu-toggle"><i class="fa fa-bars"></i></button>
			</div>
			<?php
		}
	?>
	<?php do_action('after_mobile_nav'); ?>
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'memberlite' ); ?></a>
	<?php do_action('before_site_header'); ?>
	<header id="masthead" class="site-header" role="banner">
		<div class="row">
			<?php
				$meta_login = get_theme_mod( 'meta_login', false ); 
				if(!is_page_template( 'templates/interstitial.php' ) && (!empty($meta_login) || has_nav_menu('meta') || is_active_sidebar('sidebar-3')) ) 
					$show_header_right = true;
			?>
			<div class="<?php if(is_page_template( 'templates/interstitial.php') || empty($show_header_right)) { echo 'large-12'; } else { echo 'medium-4'; } ?> columns site-branding">				
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<span class="site-description"><?php bloginfo( 'description' ); ?></span>
			</div><!-- .column4 -->
			<?php
				if(!empty($show_header_right))
				{
					?>
					<div class="medium-8 columns header-right<?php if($meta_login == false) { ?> no-meta-menu<?php } ?>">
					<?php
						if(!empty($meta_login))
						{	
							?>
							<div id="meta-member">
								<aside class="widget">
								<?php 
									global $current_user, $pmpro_pages;
									if($user_ID)
									{ 
										?>				
										<span class="user">Welcome, 
										<?php
											if(!empty($pmpro_pages))
											{
												$account_page = get_post($pmpro_pages['account']);
												?>
												<a href="<?php echo pmpro_url("account"); ?>"><?php echo preg_replace("/\@.*/", "", $current_user->display_name)?></a>
												<?php
											}
											else
											{
												?>
												<a href="<?php echo admin_url("profile.php"); ?>"><?php echo preg_replace("/\@.*/", "", $current_user->display_name)?></a>
												<?php
											}
										?>
										</span>
										<?php
									}									
									$member_menu_defaults = array(
										'theme_location' => 'member',
										'container' => 'nav',
										'container_id' => 'member-navigation',
										'container_class' => 'member-navigation',
										'fallback_cb' => 'memberlite_member_menu_cb',
										'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
									);					
									wp_nav_menu( $member_menu_defaults ); 
								?>
								</aside>
							</div><!-- #meta-member -->
							<?php
						}
											
						$meta_defaults = array(
							'theme_location' => 'meta',
							'container' => 'nav',
							'container_id' => 'meta-navigation',
							'container_class' => 'meta-navigation',
							'fallback_cb' => false
						);
						wp_nav_menu( $meta_defaults );
						
						if(is_dynamic_sidebar('sidebar-3'))
							dynamic_sidebar('sidebar-3');
						?>
					</div><!-- .column8 -->
					<?php 
					}
				?>
		</div><!-- .row -->
	</header><!-- #masthead -->
	<?php do_action('before_site_navigation'); ?>
	<?php if(!is_page_template( 'templates/interstitial.php' )) { ?>
	<nav id="site-navigation">
	<?php
		$primary_defaults = array(
			'theme_location' => 'primary',
			'container' => 'div',
			'container_class' => 'main-navigation row',
			'menu_class' => 'menu large-12 columns',
			'fallback_cb' => false,					
		);				
		wp_nav_menu($primary_defaults); 				
	?>
	</nav><!-- #site-navigation -->
	<?php } ?>
	<?php do_action('before_content'); ?>
	<div id="content" class="site-content">
		<?php do_action('before_masthead'); ?>
		<?php
			$template = get_page_template();
			if( !is_front_page() || (is_front_page() && (basename($template) != 'page.php') || 'posts' == get_option( 'show_on_front' )) && !is_404())
			{
				$post = get_queried_object();
				if(empty($post) && is_archive())
				{
					$page_for_posts_id = get_option('page_for_posts');
					$post = get_page($page_for_posts_id);
				}
				if(!empty($post) && !is_attachment())
				{
					$banner_image_id = memberlite_getBannerImageID($post);
					$banner_image_src = wp_get_attachment_image_src( $banner_image_id, 'full');
					if(!empty($banner_image_src))
					{
						?>
						<div class="masthead-banner" style="background-image: url('<?php echo esc_attr($banner_image_src[0]);?>');">
						<?php
					}
				}
				?>
				<header class="masthead">
					<div class="row">
						<div class="large-12 columns">
							<?php do_action('before_masthead_inner'); ?>
							<?php if(is_page_template( 'templates/interstitial.php' )) { 
								$referrer = $_GET['redirect_to'];
								?>
								<a class="btn" href="<?php echo esc_attr($referrer); ?>"><?php _e( 'No Thanks &raquo;','memberlite'); ?></a>
							<?php } else { ?>
								<?php 
									if(!empty($post))
									{
										$memberlite_banner_hide_breadcrumbs = get_post_meta($post->ID, '_memberlite_banner_hide_breadcrumbs', true);
										if(empty($memberlite_banner_hide_breadcrumbs))
											memberlite_getBreadcrumbs(); 
									}
								?>
							<?php } ?>							
							<?php
								if(!empty($post))
								{
									$memberlite_banner_extra_padding = get_post_meta($post->ID, '_memberlite_banner_extra_padding', true);
								}
								if(!empty($memberlite_banner_extra_padding))
									echo '<div class="masthead-padding">';
							?>
							<?php if(is_search()) { ?>
								<?php memberlite_page_title(); ?>
							<?php } elseif(!empty($post)) { ?>
								<?php								
									$memberlite_banner_right = get_post_meta($post->ID, '_memberlite_banner_right', true);
									if(!empty($memberlite_banner_right))
										echo '<div class="pull-right">' . apply_filters('the_content',$memberlite_banner_right) . '</div>';
								?>
								<?php 
									if(!empty($post))
									{
										$memberlite_banner_hide_title = get_post_meta($post->ID, '_memberlite_banner_hide_title', true);
										if(empty($memberlite_banner_hide_title))
											memberlite_page_title(); 
									}
								?>
								<?php
									$memberlite_banner_desc = get_post_meta($post->ID, '_memberlite_banner_desc', true);
									if(!empty($memberlite_banner_desc))
										echo wpautop(do_shortcode($memberlite_banner_desc));
								?>
							<?php } ?>
							<?php do_action('after_masthead_inner'); ?>
						</div>
					</div><!-- .row -->
				</header><!-- .masthead -->
				<?php
					if(!empty($banner_image_src))
					{
						?>
						</div> <!-- .masthead-banner -->
						<?php
					}
				?>
				<?php if(!is_page_template( 'templates/fluid-width.php' )) { ?>
					<div class="row">
				<?php } ?>
				<?php
			} 
		?>
		<?php do_action('after_masthead'); ?>