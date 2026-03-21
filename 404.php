<?php
/*
 * Author : trBookmark
 * Template Name: 404 Not Found (Default Template)
 * A Template for the 404 not found page.
 */
get_header('', ['body-class' => 'index_page']); // body に class を付与
?>

  <main class="main">
    <div class="post_sections">
      <article class="post_sections-inner">
        <div class="sites_grid">
          <section class="sites_grid-section js-fadein">
            <h1 class="sites_grid-site-title">Oops!</h1>
            <div class="error-404">
              <p>お探しのページを見つけられませんでした。<br>
                移動、または削除された可能性があります。</p>
              <p><a href="<?php echo esc_url(home_url('/')); ?>">トップへ戻る</a></p>
            </div>
          </section>
        </div>
        </article>
    </div>
  </main>

<? get_footer(); ?>
