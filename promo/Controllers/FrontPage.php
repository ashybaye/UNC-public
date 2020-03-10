<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class FrontPage extends Controller
{

  public function homepage_banner_content() {
    return get_field('homepage_banner_content');
  }

  public function homepage_overlay_opacity() {
    return get_field('homepage_overlay_opacity');
  }

  public function homepage_cta_1() {
    return get_field('homepage_cta_1');
  }

  public function homepage_cta_2() {
    return get_field('homepage_cta_2');
  }

  public function homepage_cta_3() {
    return get_field('homepage_cta_3');
  }

  public function homepage_promo_header() {
    return get_field('homepage_promo_header');
  }

  public function homepage_promo_title() {
    return get_field('homepage_promo_title');
  }

  public function homepage_promo_content() {
    return get_field('homepage_promo_content');
  }

  public function homepage_promo_link() {
    return get_field('homepage_promo_link');
  }

  public function homepage_system_news() {
    $homepage_news_items = [];
    $args = array(
      'post_type' => 'post',
      'post_status' => 'publish',
      'orderby' => 'date',
      'order'   => 'DESC',
      'posts_per_page'=> 3,
      'category_name' => 'system-news',
      'meta_key'		=> 'news_show_on_homepage',
      'meta_value'	=> TRUE,
    );

    $news_posts = get_posts( $args );

    if ($news_posts) {

      foreach ($news_posts as $news_post) {
        if ($news_post) {
          setup_postdata($news_post);
          $tag_id = 'category_' . get_field('news_tags',$news_post)[0]->term_id;
          $this_news_content = (object) [
            'id' => apply_filters('the_title', $news_post->ID),
            'title' => apply_filters('the_title', $news_post->post_title),
            'link' => get_the_permalink($news_post->ID),
            'icon' => get_field('tag_icon', $tag_id)['url'],
          ];
          array_push($homepage_news_items, $this_news_content);
          wp_reset_postdata();
        }

      }
    }
    return $homepage_news_items;
  }

  public function homepage_institutional_news() {
    $homepage_news_items = [];
    $args = array(
      'post_type' => 'post',
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
      'posts_per_page'=> 6,
      'category_name' => 'institutional-news',
      'meta_key' => 'news_show_on_homepage',
      'meta_value' => TRUE,
    );

    $news_posts = get_posts( $args );

    if ($news_posts) {

      foreach ($news_posts as $news_post) {
        if ($news_post) {
          setup_postdata($news_post);

          // If a news story has a tag, get the tag name
          // If s news story does not have a tag, return empty string
          if ( isset(get_field('news_tags',$news_post)[0]) ) {
            $tag_name = get_field('news_tags',$news_post->ID)[0]->name;
          }
          else {
            $tag_name = '';
          }

          $this_news_content = (object) [
            'id' => apply_filters('the_title', $news_post->ID),
            'title' => apply_filters('the_title', $news_post->post_title),
            'link' => get_the_permalink($news_post->ID),
            'image_url' => wp_get_attachment_url(get_post_thumbnail_id($news_post->ID)),
            'tag' => $tag_name
          ];
          array_push($homepage_news_items, $this_news_content);
          wp_reset_postdata();
        }

      }
    }

    return $homepage_news_items;
  }

  public function institutions() {

    $args = array(
      'post_type' => 'institution',
    );
    $institutions = get_posts([
      'post_type' => 'institution',
      'posts_per_page' => '-1',
      'post_status' => 'publish',
      'orderby' => 'menu_order',
    ]);

    return $institutions;
  }

}
