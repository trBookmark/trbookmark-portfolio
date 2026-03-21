<?php
/**
 * Author : trBookmark
 * Template Name: Top Page (Default Template)
 * Template for the Top Page.
 */
get_header('', ['body-class' => 'index_page']); // body に class を付与
?>
  <main class="main">
    <div class="post_sections">
      <h1 class="post_sections-inner"><? bloginfo('name') ?></h1>
    </div>
  </main>
<?php get_footer(); ?>
