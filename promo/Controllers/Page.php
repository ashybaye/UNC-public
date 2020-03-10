<?php

/**
 * Add Advanced Custom Fields for Page
 */

namespace App\Controllers;

use Sober\Controller\Controller;

class Page extends Controller
{

  /**
   * Select the sidebar promos for a page based on the related field
   *
   * @return array()
   */
  public function page_promos() {
    $promo_array = Array();

    for ($i = 1; $i < 6; ++$i) {
      $this_promo_field = 'page_promo_' . $i;

      $widget = get_field($this_promo_field);
      if ( $widget ) {
        setup_postdata($widget);
          $widget_obj = (object) [
            'header' => get_field('sidebar_widget_header', $widget->ID),
            'content' => get_field('sidebar_widget_text', $widget->ID),
            'format' => get_field('sidebar_widget_format', $widget->ID)->slug
          ];
          $promo_array[$i] = $widget_obj;
        wp_reset_postdata();
      }
      else {
        $promo_array[$i] = NULL;
      }
    }

    return $promo_array;
  }


  /**
   * Check if post or page is in a menu
   *
   * @param $menu menu name, id, or slug
   * @param $object_id int post object id of page
   * @return bool true if object is in menu
   */
  // public function page_in_menu( $menu = null,
  // $object_id = null ) {

  public function page_menu() {

    // get all menu locations
    $locations = get_nav_menu_locations();

    // get the current post/page ID
    GLOBAL $post;
    $object_id = get_queried_object_id();

    // loop through all menu locations to find where this page is first listed
    foreach( $locations as $location_name => $location_id ) {

      // get the menu based on location
      $menu = wp_get_nav_menu_name($location_name);
      // get menu object
      $menu_object = wp_get_nav_menu_items( esc_attr( $menu ) );

      // get the object_id field out of the menu object
      $menu_items = wp_list_pluck( $menu_object, 'object_id' );

      // test if the post/page is in the menu
      // return the menu location if found
      if ( in_array( $object_id, $menu_items ) ) {
        return $location_name;
      }
    }

    return false;
  }

    /**
   * Get the latest featured News Story to display on the All System News Listing Page
   * (/news/)
   *
   * @return data array with the featured story
   */
  public function news_featured_story() {

    $this_page = basename(get_permalink());
    if ($this_page == 'news') {

      // get the latest featured published news story
      $sticky = get_option( 'sticky_posts' ); // all sticky posts
      $args = array(
        'post_type' => 'post',
        'posts_per_page' => 1,
        'post__in' => $sticky,
        'ignore_sticky_posts' => 1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
      );

      $featured_stories = get_posts( $args );
      setup_postdata($featured_stories);
      $featured_story = $featured_stories[0];

      // get the featured image
      $featured_story->news_featured_video = get_field('news_featured_video', $featured_story->ID);

      // get the featured video if it exists

      wp_reset_postdata();
      return $featured_story;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Get all tags
   *
   * @return array of terms
   */
  public function news_terms() {
    $terms = get_terms( 'post_tag', 'orderby=name' );
    if ($terms) {
      return $terms;
    }
    else {
      return NULL;
    }
  }

  /**
   * Get all categories
   *
   * @return array of categories
   */
  public function news_categories() {
    $categories = get_categories( 'category', 'orderby=name' );
    if ($categories) {
      return $categories;
    }
    else {
      return NULL;
    }
  }

  /**
   * Get all Institutions referenced in any News posts
   *
   * @return array()
   */
  public function news_institutions() {
    $args = array(
      'post_type' => 'institution',
      'post_status' => 'publish',
      'orderby' => 'name',
      'order'   => 'ASC',
      'posts_per_page' => -1,
    );

    $all_institutions = get_posts( $args );
    $inst_array = [];

    foreach ($all_institutions as $inst) {
      $inst_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_query'	=> array(
          array(
            'key' => 'news_institution',
            'compare' => 'LIKE',
            'value' => $inst->ID,
          )
        ),
      );
      $post_linked = get_posts( $inst_args );
      if ($post_linked) {
        $this_inst_content = (object) [
          'id' => $inst->ID,
          'name' => $inst->post_title,
        ];
        array_push($inst_array, $this_inst_content);
      }
    }
    wp_reset_postdata();

    if ($all_institutions) {
      return $inst_array;
    }
    else {
      return NULL;
    }
  }

  public function system_news() {
    $this_page = basename(get_permalink());
    if ($this_page == 'news') {

      $news_items = [];
      $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order'   => 'DESC',
        'posts_per_page'=> 6,
        'category_name' => 'system-news',
        'meta_value'	=> TRUE,
      );

      $news_posts = get_posts( $args );

      if ($news_posts) {

        foreach ($news_posts as $news_post) {
          if ($news_post) {
            setup_postdata($news_post);

            // If a news story has a tag and associated icon, get the URL of the icon
            // If s news story does not have a tag, return empty URL for the icon
            if ( isset(get_field('news_tags',$news_post)[0]) ) {
              $tag_id = 'category_' . get_field('news_tags',$news_post)[0]->term_id;
              $icon_url = get_field('tag_icon', $tag_id)['url'];
            }
            else {
              $icon_url = '';
            }

            $this_news_content = (object) [
              'id' => apply_filters('the_title', $news_post->ID),
              'title' => apply_filters('the_title', $news_post->post_title),
              'link' => get_the_permalink($news_post->ID),
              'icon' => $icon_url,
            ];
            array_push($news_items, $this_news_content);
            wp_reset_postdata();
          }
        }
      }
      return $news_items;
    }
    else
      return FALSE;
  }

}
