<?php
/**
 * Author : trBookmark
 * Template Name: Portfolio アーカイブ
 * Template for the Archive of Custom post type 'portfolio'.
 */

// Contact Form 7 の JavaScript と CSS を有効化
if ( function_exists( 'wpcf7_enqueue_scripts' ) ) wpcf7_enqueue_scripts();
if ( function_exists( 'wpcf7_enqueue_styles' ) ) wpcf7_enqueue_styles();

get_header('', ['body-class' => 'portfolio-archive']); // body に class を付与
get_template_part( 'templates/portfolio/nav' );
?>
  <main class="main">
    <div class="post_sections" id="title-section">
      <h1 class="post_sections-inner">trBookmark's Portfolio</h1>
      <div class="scroll-down"><a href="#sites-section">Scroll</a></div>
    </div>
    <div class="post_sections" id="sites-section">
      <article class="post_sections-inner">
        <h2 id="sites">Sites</h2>
        <div class="sites_grid">
          <?php
          $count = 0;
          if(have_posts()): while(have_posts()): the_post();
          $count++;
          ?>
          <section class="sites_grid-section js-fadein">
            <h3 class="sites_grid-site-title"><?php the_title(); ?></h3>
            <figure class="sites_grid-figure js-showDialog" data-dialog="modal-<? echo $count ?>">
              <picture><?php if(has_post_thumbnail()) the_post_thumbnail( 'medium_large', array('class' => 'sites_grid-img') ); ?></picture>
              <figcaption class="sites_grid-caption"><?echo get_post_meta(get_the_ID(), 'portfolio-caption', true); ?></figcaption>
            </figure>
          </section>
          <?php endwhile;endif; wp_reset_query(); ?>
        </div>
      </article>
      <div class="scroll-down"><a href="#about-section">Scroll</a></div>
    </div>
    <article class="post_sections" id="about-section">
      <div class="post_sections-inner">
        <?php
        $page_id = get_page_by_path('about');
        $page = get_post( $page_id );
        if (!empty($page -> post_content)){
          echo '<section class="post_sections-about_content">';
          echo '<h2 id="about" class="article-title">'.$page -> post_title.'</h2>';
          echo $page -> post_content;
          echo '</section>';
        }
        $page_id = get_page_by_path('contact');
        $page = get_post( $page_id );
        if (!empty($page -> post_content)){
          echo '<section class="post_sections-contact_content">';
          echo '<h2 id="contact" class="article-title">'.$page -> post_title.'</h2>';
          echo do_shortcode($page -> post_content);
          echo '</section>';
        }
        ?>
      </div>
    </article>
    <?php
    $count = 0;
    if(have_posts()): while(have_posts()): the_post();
    $count++;
    ?>
    <dialog class="sites_grid-description" id="modal-<? echo $count ?>" aria-labelledby="modal-<? echo $count ?>-title" role="dialog" aria-modal="true">
      <div class="sites_grid-description-inner">
        <h3 id="modal-<? echo $count ?>-title"><?php the_title(); ?> 詳細</h3>
        <p><?php echo wp_kses_post(get_the_excerpt()); ?></p>
        <address><a href="<?echo esc_url(get_post_meta(get_the_ID(), 'portfolio-url', true)); ?>"><?echo esc_url(get_post_meta(get_the_ID(), 'portfolio-url', true)); ?></a></address>
        <?php the_content(); ?>
      </div>
    </dialog>
    <?php endwhile;endif; wp_reset_query(); ?>
  </main>
<?php get_footer(); ?>
