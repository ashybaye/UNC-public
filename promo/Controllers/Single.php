<?php

/**
 * Add Advanced Custom Fields for the News content type (Single controller)
 */

namespace App\Controllers;

use Sober\Controller\Controller;

class Single extends Controller
{

  public function news_promo() {
    $widget = get_field('news_promo');
    if ( $widget ) {
      setup_postdata($widget);
        $widget_array = (object) [
          'header' => get_field('sidebar_widget_header', $widget->ID),
          'content' => get_field('sidebar_widget_text', $widget->ID),
          'format' => get_field('sidebar_widget_format', $widget->ID)->slug
        ];
      wp_reset_postdata();
    }
    else {
      $widget_array = NULL;
    }
    return $widget_array;
  }

  public function news_featured_video() {
    return get_field('news_featured_video');
  }

  public function news_release_date() {
    return get_field('news_release_date');
  }

  public function news_original_story_link() {
    return get_field('news_original_story_link');
  }

  public function news_tags() {
    $news_tags = get_field('news_tags');
    $news_tag_array = [];

    if ($news_tags) {
      setup_postdata($news_tags);
      foreach ($news_tags as $news_tag) {
        $tags_obj = (object) [
          'slug' => $news_tag->slug,
          'name' => $news_tag->name
        ];
        array_push($news_tag_array, $tags_obj);
      }
      wp_reset_postdata();
    }
    return $news_tag_array;
  }

  public function related_news() {
    $related_posts = [];
    $news_tags = get_field('news_tags');
    $tag_id_array = array();

    if ($news_tags) {

      foreach ($news_tags as $news_tag) {
        $tag_id_array[] = $news_tag->term_id;
      }

      $args = array(

        'post_type' => 'post',
        'post_status' => 'publish',
        'field' => 'id',
        'orderby' => 'date',
        'order'   => 'DESC',
        'posts_per_page'=> 6,
        'category_name' => 'system-news',
        'post__not_in' => array(get_the_ID()),
        'tax_query' => array(
          'relation' => 'AND',
          array(
            'taxonomy' => 'post_tag',
            'field'    => 'term_id',
            'terms'    => $tag_id_array,
          )
        )
      );

      $related_posts = get_posts( $args );
    }
    return $related_posts;
  }
}
