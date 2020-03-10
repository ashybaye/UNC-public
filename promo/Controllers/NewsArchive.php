<?php

/**
 * Add Advanced Custom Fields for Page
 */

namespace App\Controllers;

use Sober\Controller\Controller;

class NewsArchive extends Controller {

  /**
   * Return all published news
   * with optional filters from URL parameters
   *
   * @return array
   */
  public static function get_news() {

    /**
     * Get the page number from the URL
     * If page number is not given, assume it's the first page
     *
     * @param [string] $name
     * @return int
     */
    function get_url_var($name)
    {
      $strURL = $_SERVER['REQUEST_URI'];
      $arrVals = explode("/",$strURL);
      $found = 0;
      foreach ($arrVals as $index => $value)
      {
        if($value == $name) $found = $index;
      }
      if (!empty($found)) {
        $place = $found + 1;
        return $arrVals[$place];
      }
      else {
        return 1;
      }
    }
    $paged = get_url_var('page');

    $news_items = [];
    $args = array(
      'post_type' => 'post',
      'post_status' => 'publish',
      'orderby' => 'date',
      'order'   => 'DESC',
      'paged' => $paged,
      'posts_per_page' => 10,
      'ignore_sticky_posts' => true,
    );

    /**
     * get the GET parameter for the category (category slug), sanitize
     * check if the category object exist
     * if yes, assign the category id and add to query args
     */
    if(isset($_GET['_category']) && $_GET['_category'] != 'all') {
      $arg_category_id = sanitize_text_field( $_GET['_category'] );
      $args['cat'] = $arg_category_id;
      // $arg_category_obj = get_category_by_slug($arg_category);
      // if ($arg_category_obj) {
      //   $arg_category_id = (int)$arg_category_obj->term_id;
      //   // add Category ID to the query args
      //   $args['cat'] = $arg_category_id;
      // }
    }

    /**
     * Get GET parameter for the News Tag
     */
    if( isset($_GET['_tag']) && $_GET['_tag'] != 'all') {
      $arg_tag_id = (int)sanitize_text_field( $_GET['_tag'] );
      $args['tax_query'] = array(
        array(
          'taxonomy' => 'post_tag',
          'field' => 'term_id',
          'terms' => $arg_tag_id,
        ),
      );
    }

    /**
     * get the GET parameter for the year (integer), sanitize
     * if given, pass to the query args
     */
    if (isset($_GET['_year']) && $_GET['_year'] != 'all') {
      $arg_year = sanitize_text_field( $_GET['_year'] );
      $args['year'] = $arg_year;
    }

    /**
     * get the GET parameter for the Institution ACF (as integer), sanitize
     * check if the reference object exist
     * if yes, assign the institution post id and add to the query args
     */
    if( isset($_GET['_inst']) && $_GET['_inst'] != 'all') {
      $arg_institution_id = (int)sanitize_text_field( $_GET['_inst'] );
      $args['meta_query'] = array(
        array(
          'key' => 'news_institution',
          'value' => $arg_institution_id,
          'compare' => 'LIKE'
        ),
      );
    }

    $news_query = new \WP_Query( $args );

    if ( $news_query ) {
      return $news_query;
    }
    else {
      return NULL;
    }
  }
}
