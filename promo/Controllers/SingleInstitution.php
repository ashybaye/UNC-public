<?php

/**
 * Add Advanced Custom Fields for the Institution content type
 */

namespace App\Controllers;

use Sober\Controller\Controller;

class SingleInstitution extends Controller
{

  public function inst_intro_content() {
    return get_field('inst_intro_content');
  }

  public function inst_brand_color() {
    return get_field('inst_brand_color');
  }

  public function inst_logo() {
    return get_field('inst_logo');
  }

  public function inst_abbreviation() {
    return get_field('inst_abbreviation');
  }

  public function inst_website_url() {
    return get_field('inst_website_url');
  }

  public function inst_facebook_url() {
    return get_field('inst_facebook_url');
  }

  public function inst_twitter_url() {
    return get_field('inst_twitter_url');
  }

  public function inst_instagram_url() {
    return get_field('inst_instagram_url');
  }

  public function inst_linkedin_url() {
    return get_field('inst_linkedin_url');
  }

  public function inst_youtube_url() {
    return get_field('inst_youtube_url');
  }

  public function inst_snapchat_url() {
    return get_field('inst_snapchat_url');
  }

  public function inst_section_2() {
    return get_field('inst_section_2');
  }

  public function inst_section_3() {
    return get_field('inst_section_3');
  }

  public function inst_performance_assessment() {
    return get_field('inst_performance_assessment');
  }

  public function inst_virtual_tour() {
    return get_field('inst_virtual_tour');
  }

  public function inst_video() {
    return get_field('inst_video');
  }

  public function inst_features() {
    $inst_features_array = [];
    for ($i = 1; $i <= 3; ++$i) {
      $feature_img = 'inst_feature_' . $i . '_image';
      $feature_title = 'inst_feature_' . $i . '_title';
      $feature_description = 'inst_feature_' . $i . '_description';
      $feature_link = 'inst_feature_' . $i . '_link';

      $feature_obj = (object) [
        'img' => get_field($feature_img),
        'title' => get_field($feature_title),
        'description' => get_field($feature_description),
        'link' => get_field($feature_link)
      ];

      if ($feature_obj->title != "") {
        array_push($inst_features_array, $feature_obj);
      }

    }

    return $inst_features_array;
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
