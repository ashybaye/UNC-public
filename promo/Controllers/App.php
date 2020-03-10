<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class App extends Controller
{
    public function siteName()
    {
      return get_bloginfo('name');
    }

    public static function title()
    {
      if (is_home()) {
          if ($home = get_option('page_for_posts', true)) {
            return get_the_title($home);
          }
          return __('Latest Posts', 'sage');
      }
      if (is_archive()) {

        // Set up the custom page title for the Institutions and Affiliates listing page
        if (get_post_type() == "institution") {
          $title = __('Institutions and Affiliates', 'sage');
        }
        // Remove 'Category:' from the News Archive pages
        else if( is_category() ) {
          $title = single_cat_title( '', false );
        }
        else {
          $title = get_the_archive_title();
        }
          return $title;
      }
      if (is_search()) {
          return sprintf(__('Search Results for %s', 'sage'), get_search_query());
      }
      if (is_404()) {
          return __('Not Found', 'sage');
      }
      return get_the_title();
    }
}
