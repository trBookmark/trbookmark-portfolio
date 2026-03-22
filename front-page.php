<?php
/**
 * Author : trBookmark
 * Template Name: Top Page (Default Template)
 * Template for the Top Page.
 */
get_header('', ['body-class' => 'front_page']); // body に class を付与
?>
  <main class="main">
    <section class="post_sections">
      <div  class="post_sections-inner">
        <h1><? bloginfo('name') ?></h1>
        <a href="/portfolio">Portfolio</a>
      </div>
    </section>
  </main>
<?php get_footer(); ?>
