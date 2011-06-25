<?php
/**
* Outputs a valid xml with all wordpress data you need included portfolio projects.
* Images imported from Media Library are listed in each available (crunched) size
*/

	/**
	* Extract the base path out of the current path
	*/
	function hide_server_url( $path ) {
		$site_url = get_site_url();
		if (substr($path, 0, strlen($site_url)) != $site_url) return $path;
	
		$trimmed = substr($path, strlen($site_url));
		while (substr($trimmed,0,1) == '/') $trimmed = substr($trimmed,1);
		while (substr($trimmed, strlen($trimmed)-1) == '/') $trimmed = substr($trimmed, 0, strlen($trimmed)-1);
	
		return $trimmed;
	}
	
header ("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";

$projects = simple_portfolio_query_projects();

?>
<wordpress>
	<portfolio>
		<projects>
	<?php 
		while (have_posts()) : the_post();
			$image_media = simple_portfolio_media(get_the_ID(), 'image');
			$youtube_media = simple_portfolio_media(get_the_ID(), 'youtube');
			$snippet_media = simple_portfolio_media(get_the_ID(), 'snippet');
			$text_media = simple_portfolio_media(get_the_ID(), 'text');
		
			$categories = '';
			foreach (wp_get_post_terms(get_the_ID(), 'portfolio-categories', array('orderby' => 'id')) as $tax) $categories .= $tax->term_id . ',';
			$categories = rtrim($categories, ',');
		
			$clients = '';
			foreach (wp_get_post_terms(get_the_ID(), 'portfolio-clients', array('orderby' => 'id')) as $tax) $clients .= $tax->term_id . ',';
			$clients = rtrim($clients, ',');
		
			$tags = '';
			foreach (wp_get_post_terms(get_the_ID(), 'portfolio-tags', array('orderby' => 'id')) as $tax) $tags .= $tax->term_id . ',';
			$tags = rtrim($tags, ',');
		
	?>
		<project id="<?php the_ID(); ?>" categories="<?php echo $categories; ?>" clients="<?php echo $clients; ?>" tags="<?php echo $tags; ?>">
			<title><![CDATA[<?php the_title(); ?>]]></title>
			<date_created><![CDATA[<?php echo get_post(get_the_id())->post_date; ?>]]></date_created>
			<date_modified><![CDATA[<?php echo get_post(get_the_id())->post_modified; ?>]]></date_modified>
			<description><![CDATA[<?php echo preg_replace('/<!--more-->/', '', get_post(get_the_id())->post_content); ?>]]></description>
			<media>
					<?php /** images */ ?>
					<?php foreach ($image_media as $image_id): ?>
						<image>
							<?php foreach (array('thumbnail', 'medium', 'large', 'full') as $image_size): $image = wp_get_attachment_image_src($image_id['value'], $image_size); ?>
								<<?php echo $image_size; ?> width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" src="<?php echo $image[0]; ?>" />
							<?php endforeach; ?>
						</image>
					<?php endforeach; ?>
				
					<?php /** youtube */ ?>
					<?php foreach ($youtube_media as $youtube_id): ?>
						<youtube id="<?php echo $youtube_id['value']; ?>" />
					<?php endforeach; ?>
				
					<?php /** snippet */ ?>
					<?php foreach ($snippet_media as $snippet): ?>
						<snippet><![CDATA[<?php echo $snippet['value']; ?>]]></snippet>
					<?php endforeach; ?>
				
					<?php /** text */ ?>
					<?php foreach ($text_media as $text): ?>
						<text><![CDATA[<?php echo $text['value']; ?>]]></text>
					<?php endforeach; ?>
			</media>
			<information>
				<?php foreach (simple_portfolio_info() as $tag=>$info): preg_match('/_([a-zA-Z0-9]+)/', $tag, $tag); ?>
				<<?php echo $tag[1]; ?>><![CDATA[<?php echo $info; ?>]]></<?php echo $tag[1]; ?>>
				<?php endforeach; ?>
			</information>
		</project>
	
	<?php endwhile; ?>
		</projects>
	
		<?php /** clients */ ?>
		<clients>
			<?php $clients = get_terms('portfolio-clients', array('orderby' => 'id')); ?>
			<?php foreach ($clients as $client): ?>
				<client id="<?php echo $client->term_id; ?>" link="<?php echo get_term_link($client->slug, $client->taxonomy); ?>">
					<title><![CDATA[<?php echo $client->name; ?>]]></title>
					<description><![CDATA[<?php echo preg_replace('/<!--more-->/', '', $client->description); ?>]]></description>
				</client>
			<?php endforeach; ?>
		</clients>
	
		<?php /** categories */ ?>
		<categories>
			<?php $cats = get_terms('portfolio-categories', array('orderby' => 'id')); ?>
			<?php foreach ($cats as $cat): ?>
				<category id="<?php echo $cat->term_id; ?>" link="<?php echo get_term_link($cat->slug, $cat->taxonomy); ?>">
					<title><![CDATA[<?php echo $cat->name; ?>]]></title>
					<description><![CDATA[<?php echo preg_replace('/<!--more-->/', '', $cat->description); ?>]]></description>
				</category>
			<?php endforeach; ?>
		</categories>
	
		<?php /** tags */ ?>
		<tags>
			<?php $tags = get_terms('portfolio-tags', array('orderby' => 'id')); ?>
			<?php foreach ($tags as $tag): ?>
				<tag id="<?php echo $tag->term_id; ?>" link="<?php echo get_term_link($tag->slug, $tag->taxonomy); ?>">
					<title><![CDATA[<?php echo $tag->name; ?>]]></title>
					<description><![CDATA[<?php echo preg_replace('/<!--more-->/', '', $tag->description); ?>]]></description>
				</tag>
			<?php endforeach; ?>
		</tags>
	
	</portfolio>
	
<?php if (get_option('use-xml') == '2'): ?>
	<menus>
		<?php
			$menus = wp_get_nav_menus();
			$menu_items = wp_get_nav_menu_items($menus[0]);
			
			$nav = new NavigationMenus( $menu_items );
			$nav->hideServerUrls(get_site_url());
			$menus_recursive = $nav->getRecursiveXml();
			echo $menus_recursive;
		?>
	</menus>
	<pages>
	
		<?php query_posts('post_type=page&orderby=menu_order&post_status=publish'); while (have_posts()) : the_post(); $post = get_post(get_the_id()); ?>
			<page id="<?php the_ID(); ?>" path="<?php echo hide_server_url(get_permalink()); ?>" template="<?php echo get_post_meta($post->ID,'_wp_page_template',true); ?>">
				<title><![CDATA[<?php echo $post->post_title; ?>]]></title>
				<date_created><![CDATA[<?php echo $post->post_date; ?>]]></date_created>
				<date_modified><![CDATA[<?php echo $post->post_modified; ?>]]></date_modified>
				<excerpt><![CDATA[<?php echo $post->post_excerpt; ?>]]></excerpt>
				<content><![CDATA[<?php echo $post->post_content; ?>]]></content>
				<meta>
				<?php foreach (get_post_custom($post->ID) as $key => $value): if (substr($key,0,1) != '_'): ?>
					<value id="<?php echo $key; ?>"><![CDATA[<?php echo $value[0]; ?>]]></value>
				<?php endif; endforeach; ?>
				</meta>
				<featured_image>
					<?php foreach (array('thumbnail', 'medium', 'large', 'full') as $image_size): $image = wp_get_attachment_image_src(get_post_thumbnail_id(), $image_size); ?>
						<<?php echo $image_size; ?> width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" src="<?php echo $image[0]; ?>" />
					<?php endforeach; ?>
				</featured_image>
			</page>
		<?php endwhile; ?>
		
	</pages>
	<links>
	
		<?php foreach (get_bookmarks() as $bookmark): ?>
			<link url="<?php echo $bookmark->link_url; ?>" target="<?php echo $bookmark->link_target; ?>">
				<title><![CDATA[<?php echo $bookmark->link_name; ?>]]></title>
				<description><![CDATA[<?php echo $bookmark->link_description; ?>]]></description>
			</link>
		<?php endforeach; ?>
		
	</links>
	<categories>
	
		<?php $categories = get_categories( array('orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 1, 'child_of' => 0, 'hierarchical' => true, 'depth' => 0, 'current_category' => 0, 'taxonomy' => 'category') );
		foreach ($categories as $category):	?>
			<category path="category/<?php echo $category->slug; ?>">
				<title><![CDATA[<?php echo $category->name; ?>]]></title>
				<description><![CDATA[<?php echo $category->description; ?>]]></description>
				<posts>
				<?php wp_reset_query(); query_posts('post_type=post&orderby=menu_order&post_status=publish&cat=' . $category->cat_ID ); while (have_posts()) : the_post(); $post = get_post(get_the_id()); ?>
					<post id="<?php the_ID(); ?>" path="<?php echo hide_server_url(get_permalink()); ?>">
						<title><![CDATA[<?php echo $post->post_title; ?>]]></title>
						<date_created><![CDATA[<?php echo $post->post_date; ?>]]></date_created>
						<date_modified><![CDATA[<?php echo $post->post_modified; ?>]]></date_modified>
						<excerpt><![CDATA[<?php echo $post->post_excerpt; ?>]]></excerpt>
						<content><![CDATA[<?php echo $post->post_content; ?>]]></content>
						<meta>
						<?php foreach (get_post_custom($post->ID) as $key => $value): if (substr($key,0,1) != '_'): ?>
							<value id="<?php echo $key; ?>"><![CDATA[<?php echo $value[0]; ?>]]></value>
						<?php endif; endforeach; ?>
						</meta>
					</post>
				<?php endwhile; ?>
				</posts>
			</category>
		<?php endforeach;?>
		
	</categories>
<?php endif; ?>

</wordpress>

<?php
	class NavigationMenus {
		
		var $collection;
		
		function __construct( $collection ) {
			$this->collection = $collection;
		}
		
		/**
		* Get a recursive xml formatted menu structure
		*/
		public function getRecursiveXml() {
			$recursiveMenus = $this->getRecursiveMenus();

			$output = "";
			
			foreach ($recursiveMenus as $item) {
				$output .= $this->getXMLformattedMenu( $item );
			}
			
			return $output;
		}
		
		private function getXMLformattedMenu( $item ) {
			$output = "";
			$output .= "<menu url=\"{$item['menu']->url}\">\n";
			$output .= "	<title><![CDATA[{$item['menu']->title}]]></title>\n";
			$output .= "	<description><![CDATA[{$item['menu']->description}]]></description>\n";
			$output .= "	<menus>";
			
			foreach ($item['menus'] as $childItem) {
				$output .= $this->getXMLformattedMenu( $childItem );
			}
			
			$output .= "	</menus>";
			$output .= "</menu>\n";
			
			return $output;
		}
		
		/**
		* Get the recursive menus at object level
		*/
		public function getRecursiveMenus() {
			// parse top level items
			$toplevel = array();
			foreach ($this->collection as $item) {
				if ($item->menu_item_parent == 0) 
					array_push( $toplevel, $this->getMenu($item));
			}
			
			// loop over toplevel and apply children
			foreach ($toplevel as &$item) {
				$this->addChildren( $item );
			}
		
			return $toplevel;
		}
		
		/**
		* Hide the base server url..
		* for example: site is http://www.site.nl/page/2
		* then all menus url will be replaced with page/2
		*/
		public function hideServerUrls( $siteurl ) {
			foreach ($this->collection as &$collectionItem) {
				$path = $collectionItem->url;
				
				if (substr($path, 0, strlen($siteurl)) == $siteurl) {
					$trimmed = substr($path, strlen($siteurl));
					while (substr($trimmed,0,1) == '/') $trimmed = substr($trimmed,1);
					while (substr($trimmed, strlen($trimmed)-1) == '/') $trimmed = substr($trimmed, 0, strlen($trimmed)-1);
					$path = $trimmed;
				}
				
				$collectionItem->url = $path;
			}
		}
		
		private function addChildren( &$item ) {
			foreach ($this->collection as $collectionItem) {
				if ($collectionItem->menu_item_parent == $item["menu"]->ID) {
					$added = $this->getMenu($collectionItem);
					$this->addChildren( $added );
					array_push($item["menus"], $added);
				}
			}
		}
		
		private function getMenu( $item ) {
			return array("menu" => $item, "menus" => array());
		}		
	}
?>
