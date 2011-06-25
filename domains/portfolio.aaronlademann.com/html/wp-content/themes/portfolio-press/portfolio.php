<?php
/*
Template Name: Portfolio Plugin Page Template
*/

get_header(); ?>

		<div id="container">
			<div id="content" role="main">
			
			<?php global $wp_query;
			$paged = ( $wp_query->query_vars['page'] ) ? $wp_query->query_vars['page'] : 1;
			$args = array(
				'post_type'					=> 'portfolio',
				'post_status'				=> 'publish',
				'orderby'						=> 'menu_order',
				'caller_get_posts'  => 1,
				'posts_per_page'		=> 5,
				'paged'							=> $paged 
				);

			query_posts( $args );
			
			while ( have_posts() ) : the_post(); ?>
			<div class="portfolio_content">
			<div class="item_title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></div>
			<div class="entry">
			<?php global $post; $meta_values = get_post_custom($post->ID);
			
			$thumb			= array();
			$images			= array();
			$upload_dir = wp_upload_dir();
			$image_alt	= "";
			$thumb_url	=	"";
			$featured_image_url = "";

			if( array_key_exists( '_thumbnail_id', $meta_values ) ) {
				$thumb			= wp_get_attachment_metadata( $meta_values['_thumbnail_id'][0] );
				$thumb_url	= $upload_dir["baseurl"] ."/". substr($thumb['file'], 0, 8) . $thumb['sizes']['medium']['file'];
				$featured_image_url = $upload_dir["baseurl"] ."/". $thumb["file"];
			}
			
			$post_attachments = get_posts( 'post_type=attachment&post_parent='. $post->ID .'&numberposts=1' );
			if( count( $thumb ) == 0 ) {
				if( count( $post_attachments ) > 0 ) {
					$metadata		= wp_get_attachment_metadata( $post_attachments[0]->ID );
					$thumb_url	= ( isset( $metadata['sizes']["medium"]['file'] ) ? $upload_dir["baseurl"] ."/". substr($thumb['file'], 0, 8) . $metadata['sizes']["medium"]['file'] : $post_attachments[0]->guid );
					$featured_image_url = $upload_dir["baseurl"] ."/". $metadata["file"];
					$image_alt					= get_post_custom( $post_attachments[0]->ID );
					$image_alt					= $image_alt["_wp_attachment_image_alt"][0];
				}
				else {
					$thumb_url					= "";
					$featured_image_url = "";
					$image_alt					= "";
				}
			}

			echo '<p><a class="lightbox" rel="lightbox" href="'. $featured_image_url .'"><img src="'. $thumb_url .'" width="240" alt="'. $image_alt .'" /></a></p>';
			echo '<p><span class="lable">Date of completion</span>: '. $meta_values["_prtf_date_compl"][0] .'</p>';
			$user_id = get_current_user_id();
			if ( $user_id == 0 ) {
				echo '<p><span class="lable">Link</span>: '. $meta_values["_prtf_link"][0] .'</p>';
			}
			else {
				if( parse_url( $meta_values["_prtf_link"][0] ) !== false )
					echo '<p><span class="lable">Link</span>: <a href="'. $meta_values["_prtf_link"][0] .'">'. $meta_values["_prtf_link"][0] .'</a></p>';
				else
					echo '<p><span class="lable">Link</span>: '. $meta_values["_prtf_link"][0] .'</p>';
			}
			echo '<p><span class="lable">Short description</span>: '. $meta_values["_prtf_short_descr"][0] .'</p>'; ?>
			</div>
			<div class="read_more"><a href="<?php the_permalink(); ?>" rel="bookmark">Read more >></a></div>
			</div>
			<?php $tags = wp_get_object_terms( $post->ID, 'post_tag' ) ;			
			if ( $tags ) {
				if( count( $tags ) > 0 ) {
					$content = "";
					$content .= '<div class="portfolio_terms">Technologies: ';
					foreach ( $tags as $tag ) {
						$content .= '<a href="'. get_tag_link( $tag->term_id ). '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a>, ';
					}
					$content = substr( $content, 0, strlen( $content ) -2 );
					$content .= '</div>';
					echo $content;
				}
			}
			endwhile; ?>
			
			<script type="text/javascript">
			var base_url = "<?php echo WP_PLUGIN_URL .'/portfolio'; ?>";
			jQuery(document).ready(function(){
					jQuery('a[rel="lightbox"]').colorbox({transition:'fade'});
				});
			</script>
			</div><!-- #content -->

			<?php portfolio_pagination(); ?>

		</div><!-- #container -->
		<div id="jquery-overlay"></div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>