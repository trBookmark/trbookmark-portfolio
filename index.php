<?php
/**
 * Author : trBookmark
 * Template Name: Index (Default Template)
 * Template for the default display.
 */

if( is_page('contact')){
  // Contact Form 7 の JavaScript と CSS を有効化
  if ( function_exists( 'wpcf7_enqueue_scripts' ) ) wpcf7_enqueue_scripts();
  if ( function_exists( 'wpcf7_enqueue_styles' ) ) wpcf7_enqueue_styles();
}
get_header('', ['body-class' => 'index_page']); // body に class を付与
?>
  <main class="main">
    <div class="post_sections">
      <?php
        if ( have_posts() ) :
        while ( have_posts() ) : the_post();
      ?>
      <article class="post_sections-inner">
        <div class="sites_grid">
          <section class="sites_grid-section js-fadein">
            <h1 class="sites_grid-site-title"><?php the_title(); ?></h1>
            <div class="sites_grid-content"><?php the_content(); ?></div>
            <?php the_posts_pagination(); ?>
          </section>
        </div>
      </article>
      <?php
        endwhile;
      ?>
    </div>
    <?php
    endif;
    ?>
  </main>

<? get_footer(); ?>
