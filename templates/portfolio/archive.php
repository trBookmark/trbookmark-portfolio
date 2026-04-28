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

// 固定ページabout、contactを取得
$page_id = get_page_by_path('about');
$about = $page_id ? get_post( $page_id ) : '';
$page_id = get_page_by_path('contact');
$contact = $page_id ? get_post( $page_id ) : '';
?>
  <main class="main">
    <div class="post_sections" id="title-section">
      <h1 class="post_sections-inner">trBookmark's Portfolio</h1>
      <div class="scroll-down"><a href="#sites-section">Scroll</a></div>
    </div>
    <article class="post_sections" id="sites-section">
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
            <?php if(has_post_thumbnail()) the_post_thumbnail( 'medium_large', array('class' => 'sites_grid-img') ); ?>
            <figcaption class="sites_grid-caption"><?echo get_post_meta(get_the_ID(), 'portfolio-caption', true); ?></figcaption>
          </figure>
        </section>
        <?php endwhile;endif; wp_reset_query(); ?>
      </div>
      <?php if (!empty($about -> post_content)): ?>
      <div class="scroll-down"><a href="#about-section">Scroll</a></div>
      <?php elseif(!empty($contact -> post_content)): ?>
      <div class="scroll-down"><a href="#contact-section">Scroll</a></div>
      <?php endif; ?>
    </article>
    <?php if (!empty($about -> post_content)): ?>
    <article class="post_sections" id="about-section">
      <section class="post_sections-about_content">
        <h2 id="about" class="article-title"><?php echo $about -> post_title; ?></h2>
        <?php echo $about -> post_content; ?>
      </section>
      <?php if(!empty($contact -> post_content)): ?>
          <div class="scroll-down"><a href="#contact-section">Scroll</a></div>
      <?php endif; ?>
    </article>
    <?php endif; ?>
    <?php if (!empty($contact -> post_content)): ?>
    <article class="post_sections" id="contact-section">
      <section class="post_sections-contact_content">
        <h2 id="contact" class="article-title"><?php echo $contact -> post_title; ?></h2>
        <?php echo do_shortcode($contact -> post_content); ?>
      </section>
    </article>
    <?php endif; ?>
    <?php
      $count = 0;
      if(have_posts()): while(have_posts()): the_post();
      $count++;
    ?>
    <dialog class="sites_grid-description" id="modal-<? echo $count ?>" aria-labelledby="modal-<? echo $count ?>-title" aria-modal="true">
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
