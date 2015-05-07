<?php
/**
 * @package WordPress
 * @subpackage CoreOne
 * @since CoreOne 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function the_breadcrumb() {
    global $post;
    echo '<ol class="breadcrumb">';
    if (!is_home()) {
        echo '<li><a href="';
        echo get_option('home');
        echo '">';
        echo 'Ana Sayfa';
        echo '</a></li>';
        if (is_category()) {
            echo '<li class="active">';
            the_category(' </li><li> ');
		} elseif (is_single()) {
			echo '</li><li class="active">';
			the_title();
			echo '</li>';
		} elseif (is_page()) {
            if($post->post_parent){
                $anc = get_post_ancestors( $post->ID );
                $title = get_the_title();
                foreach ( $anc as $ancestor ) {
                    $output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li>';
                }
                echo $output;
                echo '<li class="active" title="'.$title.'"> '.$title.'</li>';
            } else {
                echo '<li class="active"> ';
                echo the_title();
                echo '</li>';
            }
        }
    }
    elseif (is_tag()) {single_tag_title();}
    elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}
    elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}
    elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}
    elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}
    elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
    elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
    echo '</ol>';
}

//Taxonomy Breadcrumb
function be_taxonomy_breadcrumb() {
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	$parent = $term->parent;
	while ($parent):
		$parents[] = $parent;
		$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
		$parent = $new_parent->parent;
	endwhile;
	if(!empty($parents)):
		$parents = array_reverse($parents);
		foreach ($parents as $parent):
			$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
			$url = get_bloginfo('url').'/'.$item->taxonomy.'/'.$item->slug;
			echo '<li class="active"><a href="'.$url.'">'.$item->name.'</a></li>';
		endforeach;
	endif;
	echo '<li>'.$term->name.'</li>';
}