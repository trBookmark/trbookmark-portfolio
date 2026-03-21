<?php
/**
 * Author : trBookmark
 * Template Name: 個別投稿
 */

get_header();
?>
  <main class="main">
    <div class="post_sections">
      <article class="post_sections-inner">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="post_sections-content">
          <h2 id="sites"><?php the_title(); ?></h2>
          <figure class="sites_grid-figure js-showDialog" data-dialog="modal-<? echo $count ?>">
            <picture><?php if(has_post_thumbnail()) the_post_thumbnail( 'medium_large', array('class' => 'sites_grid-img') ); ?></picture>
          </figure>
          <div class="sites_grid">
            <?php the_content(); ?>
          </div>
          <?php // 現在の投稿に隣接している前後の投稿を取得
            $prev_post = get_previous_post();  // 前の投稿
            $next_post = get_next_post();  // 次の投稿
            if($prev_post || $next_post):
          ?>
          <nav class="fotter__nav">
            <ul class="fotter__nav-list">
              <?php if($prev_post):?><li class="fotter__nav-listitem"><?php previous_post_link('« %link', '%title', false, ''); ?></li><?php endif; ?>
              <li class="fotter__nav-listitem"><a href="<? echo get_post_type_archive_link( get_post_type() ); ?>">一覧</a></li>
              <?php if($next_post):?><li class="fotter__nav-listitem"><?php next_post_link('%link »', '%title', false, ''); ?></li><?php endif; ?>
            </ul>
          </nav>
        </div><?php endif; ?>
        <?php endwhile; endif; ?>
      </article>
    </div>
  </main>
<?php get_footer(); ?>
