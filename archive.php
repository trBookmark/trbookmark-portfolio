<?php
/**
 * Author : trBookmark
 * Template Name: アーカイブ
 * post type によってテンプレートを分岐
 */
$post_type = get_query_var( 'post_type' );

// 以下フォールバック
get_header();
?>
  <main class="main">
    <div class="post_sections" id="sites-section">
      <article class="post_sections-inner">
        <h1 id="sites"><? echo esc_html($post_type) ?> Archive</h1>
        <div class="sites_grid">
          <?php
          if(have_posts()): while(have_posts()): the_post();
          $post_meta = get_post_meta($postID);
          ?>
          <section class="sites_grid-section">
            <h2 class="sites_grid-site-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <figure class="sites_grid-figure">
              <picture><?php if(has_post_thumbnail()) the_post_thumbnail( 'medium_large', array('class' => 'sites_grid-img') ); ?></picture>
            </figure>
          </section>
          <?php endwhile;endif; wp_reset_query(); ?>
        </div>
      </article>
    </div>
  </main>
<?php get_footer(); ?>
