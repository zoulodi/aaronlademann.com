<?php

/*-----------------------------------------------------------------------------------

	Plugin Name: Custom Latest Tweets
	Plugin URI: http://www.premiumpixels.com
	Description: A widget that displays your latest tweets
	Version: 1.0
	Author: Orman Clark
	Author URI: http://www.premiumpixels.com

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/*	Twitter widget function
/*-----------------------------------------------------------------------------------*/

function tz_parse_cache_feed($usernames, $limit) {
	$username_for_feed = str_replace(" ", "+OR+from%3A", $usernames);
	$feed = "http://search.twitter.com/search.atom?q=from%3A" . $username_for_feed . "&rpp=" . $limit;
	$usernames_for_file = str_replace(" ", "-", $usernames);
	$cache_file = dirname(__FILE__).'/cache/' . $usernames_for_file . '-twitter-cache';
	$last = filemtime($cache_file);
	$now = time();
	$interval = 600; // ten minutes
	// check the cache file
	if ( !$last || (( $now - $last ) > $interval) ) {
		// cache file doesn't exist, or is old, so refresh it
		$cache_rss = file_get_contents($feed);
		if (!$cache_rss) {
			// we didn't get anything back from twitter
			echo "<!-- ERROR: Twitter feed was blank! Using cache file. -->";
		} else {
			// we got good results from twitter
			echo "<!-- SUCCESS: Twitter feed used to update cache file -->";
			$cache_static = fopen($cache_file, 'wb');
			fwrite($cache_static, serialize($cache_rss));
			fclose($cache_static);
		}
		// read from the cache file
		$rss = @unserialize(file_get_contents($cache_file));
	}
	else {
		// cache file is fresh enough, so read from it
		echo "<!-- SUCCESS: Cache file was recent enough to read from -->";
		$rss = @unserialize(file_get_contents($cache_file));
	}
	// clean up and output the twitter feed
	$feed = str_replace("&amp;", "&", $rss);
	$feed = str_replace("&lt;", "<", $feed);
	$feed = str_replace("&gt;", ">", $feed);
	$clean = explode("<entry>", $feed);
	$clean = str_replace("&quot;", "'", $clean);
	$clean = str_replace("&apos;", "'", $clean);
	$amount = count($clean) - 1;
	
	if ($amount) { // are there any tweets?
		
	?>
    <div id="twitter_div" class="clearfix">
          <ul id="twitter_update_list"> 
    <?php
		for ($i = 1; $i <= $amount; $i++) {
			$entry_close = explode("</entry>", $clean[$i]);
			$clean_content_1 = explode("<content type=\"html\">", $entry_close[0]);
			$clean_content = explode("</content>", $clean_content_1[1]);
			$clean_name_2 = explode("<name>", $entry_close[0]);
			$clean_name_1 = explode("(", $clean_name_2[1]);
			$clean_name = explode(")</name>", $clean_name_1[1]);
			$clean_user = explode(" (", $clean_name_2[1]);
			$clean_lower_user = strtolower($clean_user[0]);
			$clean_uri_1 = explode("<uri>", $entry_close[0]);
			$clean_uri = explode("</uri>", $clean_uri_1[1]);
			$clean_time_1 = explode("<published>", $entry_close[0]);
			$clean_time = explode("</published>", $clean_time_1[1]);
			$unix_time = strtotime($clean_time[0]);
			$pretty_time = relativeTime($unix_time);
			?>

                    <li><span><?php echo $clean_content[0]; ?></span> <small><a href="<?php echo $clean_uri[0]; ?>"><?php echo $pretty_time; ?></a></small></li>
			<?php
		}
		
		?>
    	</ul>
        
    </div>
    <?php
	}
	else 
	{
		?>
        <div id="twitter_div" class="clearfix">
            <ul id="twitter_update_list">
                <li><span>Twitter should be here, but it's not. Get over it.</span></li>
            </ul>
        </div>
        <?php
	}
}


// Add function to widgets_init that'll load our widget
add_action( 'widgets_init', 'tz_tweets_widgets' );

// Register widget
function tz_tweets_widgets() {
	register_widget( 'tz_Tweet_Widget' );
}

// Widget class
class tz_tweet_widget extends WP_Widget {


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/
	
function tz_Tweet_Widget() {

	// Widget settings
	$widget_ops = array(
		'classname' => 'tz_tweet_widget',
		'description' => __('A widget that displays your latest tweets.', 'framework')
	);

	// Widget control settings
	/*$control_ops = array(
		'width' => 300,
		'height' => 350,
		'id_base' => 'tz_tweet_widget'
	);*/

	// Create the widget
	$this->WP_Widget( 'tz_tweet_widget', __('Custom Latest Tweets','framework'), $widget_ops, $control_ops );
	
}


/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/
	
function widget( $args, $instance ) {
	extract( $args );

	// Our variables from the widget settings
	$title = apply_filters('widget_title', $instance['title'] );
	$username = $instance['username'];
	$postcount = $instance['postcount'];
	$tweettext = $instance['tweettext'];

	// Before widget (defined by theme functions file)
	echo $before_widget;

	// Display the widget title if one was input
	if ( $title )
		echo $before_title . $title . $after_title;

	// Display Latest Tweets
	 tz_parse_cache_feed($username, $postcount);
	 
	 echo '<a href="http://twitter.com/'.$username.'" id="twitter-link">'.$tweettext.'</a>';

	// After widget (defined by theme functions file)
	echo $after_widget;
	
}


/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/
	
function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	// Strip tags to remove HTML (important for text inputs)
	$instance['title'] = strip_tags( $new_instance['title'] );
	$instance['username'] = strip_tags( $new_instance['username'] );
	$instance['postcount'] = strip_tags( $new_instance['postcount'] );
	$instance['tweettext'] = strip_tags( $new_instance['tweettext'] );

	// No need to strip tags

	return $instance;
}


/*-----------------------------------------------------------------------------------*/
/*	Widget Settings (Displays the widget settings controls on the widget panel)
/*-----------------------------------------------------------------------------------*/
	 
function form( $instance ) {

	// Set up some default widget settings
	$defaults = array(
	'title' => 'Latest Tweets',
	'username' => 'ormanclark',
	'postcount' => '5',
	'tweettext' => 'Follow on Twitter',
	);
	
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	<!-- Widget Title: Text Input -->
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'framework') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>

	<!-- Username: Text Input -->
	<p>
		<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Twitter Username e.g. ormanclark', 'framework') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" />
	</p>
	
	<!-- Postcount: Text Input -->
	<p>
		<label for="<?php echo $this->get_field_id( 'postcount' ); ?>"><?php _e('Number of tweets (max 20)', 'framework') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" value="<?php echo $instance['postcount']; ?>" />
	</p>
	
	<!-- Tweettext: Text Input -->
	<p>
		<label for="<?php echo $this->get_field_id( 'tweettext' ); ?>"><?php _e('Follow Text e.g. Follow on Twitter', 'framework') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tweettext' ); ?>" name="<?php echo $this->get_field_name( 'tweettext' ); ?>" value="<?php echo $instance['tweettext']; ?>" />
	</p>
		
	<?php
	}

}
?>