<?php
/**
 * Author : trBookmark
 * General Settings.
 */

/************************************
ヘッダの軽量化
************************************/
// rest_output_link_wp_head: RESTAPIリンクタグをページヘッダーに出力します。
remove_action( 'wp_head', 'rest_output_link_wp_head');
// _wp_render_title_tag: テーマがタイトルタグをサポートしているかどうかに関係なく、コンテンツとともにタイトルタグを表示します。
remove_action( 'wp_head', '_wp_render_title_tag');
// feed_links: 一般的なフィードへのリンクを表示します。
remove_action( 'wp_head', 'feed_links', 2);
// feed_links_extra: カテゴリフィードなどの追加フィードへのリンクを表示します。
remove_action( 'wp_head', 'feed_links_extra', 3);
// rsd_link: ReallySimpleDiscoveryサービスエンドポイントへのリンクを表示します。
remove_action( 'wp_head', 'rsd_link' );
// wlwmanifest_link: WindowsLiveWriterマニフェストファイルへのリンクを表示します。
remove_action( 'wp_head', 'wlwmanifest_link' );
// locale_stylesheet: ローカライズされたスタイルシートのリンク要素を表示します。
remove_action( 'wp_head', 'locale_stylesheet' );
// print_emoji_detection_script: インライン絵文字検出スクリプトがまだ印刷されていない場合は、印刷します。
remove_action( 'wp_head', 'print_emoji_detection_script', 7);
// print_emoji_styles: 重要な絵文字関連のスタイルを印刷します。
remove_action( 'wp_print_styles', 'print_emoji_styles');
// wp_print_styles: $handlesキューにある表示スタイル。
remove_action( 'wp_head', 'wp_print_styles');
// wp_print_head_scripts: フッターのためにキューに入れられたスクリプトを延期します。
// これらのスクリプトを印刷するために、フッターでwp_print_footer_scripts（）が呼び出されます。
remove_action( 'wp_head', 'wp_print_head_scripts');
// wp_generator: wp_headフックで生成されたXHTMLジェネレーターを表示します。
// WordPressのバージョン情報など。
remove_action( 'wp_head', 'wp_generator' );
// wp_shortlink_wp_head: 現在のページにショートリンクが定義されている場合は、rel=shortlinkをヘッドに挿入します。
remove_action( 'wp_head', 'wp_shortlink_wp_head');
// wp_custom_css_cb: カスタムCSSスタイル要素をレンダリングします。
remove_action( 'wp_head', 'wp_custom_css_cb');
// adjacent_posts_rel_link_wp_head: 単一の投稿ページの現在の投稿に隣接する投稿のリレーショナルリンクを表示します。
// rel=prevとrel=nextのリンクタグ
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

// ヘッダから削除しないものメモ
// wp_robots: 必要に応じてロボットのメタタグを表示します。
// remove_action( 'wp_head', 'wp_robots');
// rel_canonical: 特異なクエリに対してrel=canonicalを出力します。
// remove_action( 'wp_head', 'rel_canonical' );
// wp_site_icon: サイトアイコンのメタタグを表示します。
// remove_action( 'wp_head', 'wp_site_icon');

/************************************
自動更新
************************************/
// WordPressをGitで管理していると自動更新が無効化される
add_filter( 'automatic_updates_is_vcs_checkout', '__return_false', 1 );
// メジャーアップデートの無効化
add_filter( 'allow_major_auto_core_updates', '__return_false' );
// マイナーアップデートの有効化
add_filter( 'allow_minor_auto_core_updates', '__return_true' );
// 開発版アップデートの無効化
add_filter( 'allow_dev_auto_core_updates', '__return_false' );
// プラグインの自動更新を有効化
add_filter( 'auto_update_plugin', '__return_true' );
// 翻訳の更新
add_filter( 'auto_update_translation', '__return_true' );


/************************************
その他の軽量化
************************************/
// wp_resource_hints: Webサイトへの事前フェッチ、事前レンダリング、および事前接続のためのリソースヒントをブラウザに出力します。
// DNSプリフェッチ削除
add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );
add_filter( 'wp_resource_hints', 'remove_dns_prefetch', 10, 2 );
function remove_dns_prefetch( $hints, $relation_type ) {
  if ( 'dns-prefetch' === $relation_type ) {
    return array_diff( wp_dependencies_unique_hosts(), $hints );
  }
  return $hints;
}

// css
add_action( 'wp_enqueue_scripts',
  function (){
    /*
    wp_enqueue_style(
      string $handle,
      string $src,
      string[] $deps = array(),
      string|bool|null $ver = false,
      string $media
    )
      */
    // google fonts を読み込む場合
    // wp_enqueue_style('googleapis', 'https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;1,700&family=M+PLUS+1+Code:wght@300..700&display=swap', array(), false, 'all');

    // メインのスタイルシート
    wp_enqueue_style('main-style', get_template_directory_uri() . '/css/style.min.css', array(), false, 'all');

    // デフォルトのcss等を削除
    if (!is_user_logged_in()) {
      // ログイン以外
      wp_deregister_style('dashicons');
      wp_dequeue_style('admin-bar');
    }

    // 特定ページで特定 CSS を読み込む
    // portfolio archive, contact page に Contact Form 7 用 CSS
    if( is_archive( 'portfolio' ) || is_page('contact')){
      wp_enqueue_style( 'form-style', get_template_directory_uri() . '/css/style_form.min.css' );
    }

    // // 例）CSSの読み込み停止(wp_enqueue_styleの取り消し)
    // // 登録ハンドル名はid属性から-cssを取ったもの
    // wp_dequeue_style( 'global-styles' );
    // // Gutenberg用CSSを削除
    // wp_dequeue_style('wp-block-library');
  }
);

// // bodyにページに応じたclassを付与
// add_filter( 'body_class',
//   function( $classes ){
//     if( is_front_page() ){
//       // サイトフロントページ
//       $classes[] = 'class名';
//     }elseif( is_home() ){
//       // ブログホームページ（投稿記事一覧）
//       $classes[] = 'class名';
//     }elseif( is_archive() ){
//       // アーカイブ
//       $classes[] = 'class名';
//     }elseif( is_category() ){
//       // カテゴリ（カテゴリidを渡せば特定カテゴリ、array可）
//       $classes[] = 'class名';
//     }elseif( is_tag() ){
//       // タグ（タグidを渡せば特定タグ、array可）
//       $classes[] = 'class名';
//     }elseif( is_404() ){
//       // 404
//       $classes[] = 'class名';
//     }elseif( is_page() ){
//       // 固定ページ（page_idを渡せば特定ページ、array可）
//       $classes[] = 'class名';
//     }elseif( is_single() ){
//       // 記事（post_idを渡せば特定記事、array可）
//       $classes[] = 'class名';
//     }elseif( is_singular() ){
//       // 投稿、固定、添付ファイル
//       $classes[] = 'class名';
//     }elseif( wp_is_mobile() ){
//       // モバイル表示（タブレット含む）
//       $classes[] = 'class名';
//     }
//     return $classes;
//   }
// );

/*
 * REST APIを無効化
add_filter( 'rest_authentication_errors',
  function (){
    return new WP_Error( 'disabled', __( 'REST API is disabled.' ), array( 'status' => rest_authorization_required_code() ) );
  }
);
 */

/************************************
不要なウィジェットを表示しない
************************************/
add_action('widgets_init',
  function(){
    unregister_widget('WP_Widget_Pages');   //固定ページ
    unregister_widget('WP_Widget_Links');   //リンク集
    unregister_widget('WP_Widget_Search');  //サイト内検索フォーム
    unregister_widget('WP_Widget_Archives');//月別アーカイブ
    unregister_widget('WP_Widget_Meta');    //メタ情報
    unregister_widget('WP_Widget_Calendar');//カレンダー
    unregister_widget('WP_Widget_Text');        //任意のテキストとHTML
    unregister_widget('WP_Widget_Categories');  //カテゴリー
    unregister_widget('WP_Widget_Recent_Posts');    //最近の投稿
    unregister_widget('WP_Widget_Recent_Comments'); //最近のコメント
    unregister_widget('WP_Widget_RSS');     //RSSフィード
    unregister_widget('WP_Widget_Tag_Cloud');   //タグクラウド
    unregister_widget('WP_Nav_Menu_Widget');    //ナビゲーションメニュー
    unregister_widget('Twenty_Fourteen_Ephemera_Widget'); //Twenty Fourteen 短冊
    unregister_widget("WP_Widget_Media_Image"); //画像
    unregister_widget("WP_Widget_Media_Audio"); //音声
    unregister_widget("WP_Widget_Media_Gallery"); //ギャラリー
    unregister_widget("WP_Widget_Custom_HTML"); //カスタムHTML
  }
);

/************************************
抜粋の文字数を変更
************************************/
add_filter('excerpt_length',
  function ($length){
    return 60;
  }
,999);

/************************************
thumbnail（アイキャッチ）の有効化
************************************/
add_theme_support('post-thumbnails');

/************************************
タイトルタグ
************************************/
// タイトルタグ自動的挿入の有効化
add_theme_support( 'title-tag' );

// タイトル文字内のセパレーターを変更
add_filter( 'document_title_separator',
  function ( $sep ){
    $sep = ' | ';
    return $sep;
  }
);

// トップのサイトディスクリプションを削除
add_filter( 'document_title_parts',
  function ( $results ){
    if( is_home() || is_front_page() ){
      $results['tagline'] = '';
    }
    return $results;
  }
, 11 );

/***********************************************
headにOGPタグを追加
***********************************************/
add_action('wp_head',
  function () {
    $meta_list = '';
    $description = '';
    $canonical = '';
    $ogp_title = '';
    $ogp_descr = '';
    $ogp_url = '';
    $ogp_img = '';
    $ogp_type = ( is_front_page() || is_home() ) ? 'website' : 'article';

    if( is_singular() ) {
      // 記事または固定ページ
      global $post;
      setup_postdata($post);
      $ogp_title = $post->post_title;
      $ogp_descr = wp_strip_all_tags(get_the_excerpt());
      $ogp_url = get_permalink();
      wp_reset_postdata();
      $description = $ogp_descr;
    }elseif( is_category() ){
      $description = wp_strip_all_tags(category_description());
    }elseif( is_tag() ){
      $description = wp_strip_all_tags(tag_description());
    }elseif( is_front_page() || is_home() ) {
      // トップページ
      $ogp_title = get_bloginfo('name');
      $ogp_descr = get_bloginfo('description');
      $ogp_url = get_bloginfo('url'); // サイトのURL, home_url()では、delete_domain_attachment_url によりルート相対パスになる
      $description = get_bloginfo('description');
      $canonical = $ogp_url;
    }

    if ( is_singular() && has_post_thumbnail() ) {
      $ps_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
      $ogp_img = $ps_thumb[0];
    } else {
      // デフォルト画像はテーマのサムネイルにする
      $ogp_img = get_template_directory_uri() . '/screenshot.png';
    }

    if (!empty($canonical))   $meta_list .= '<link rel="canonical" href="'.esc_attr($canonical).'">'."\n";
    if (!empty($ogp_title))   $meta_list .= '<meta property="og:title" content="'.esc_attr($ogp_title).'">' . "\n";
    if (!empty($ogp_title))   $meta_list .= '<meta property="og:site_name" content="'.esc_attr(get_bloginfo('name')).'">' . "\n";
    if (!empty($ogp_title))   $meta_list .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    if (!empty($ogp_title))   $meta_list .= '<meta property="og:locale" content="ja_JP">' . "\n";
    if (!empty($ogp_descr))   $meta_list .= '<meta property="og:description" content="'.esc_attr($ogp_descr).'">' . "\n";
    if (!empty($ogp_type))    $meta_list .= '<meta property="og:type" content="'.$ogp_type.'">' . "\n";
    if (!empty($ogp_url))     $meta_list .= '<meta property="og:url" content="'.esc_url($ogp_url).'">' . "\n";
    if (!empty($ogp_img))     $meta_list .= '<meta property="og:image" content="'.esc_url($ogp_img).'">' . "\n";
    if (!empty($description)) $meta_list .= '<meta property="description" content="'.esc_attr($description).'">' . "\n";

    echo $meta_list;
  }
);

/*
 * Contact Form 7 の JavaScript と CSS を無効化
 */
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );


/*
 * 必要な固定ページを作成
 * About, Contact
 */
add_action('after_setup_theme',
  function () {
    $pages_array[] = array('title'=>'About', 'name'=>'about', 'parent'=>'');
    $pages_array[] = array('title'=>'Contact', 'name'=>'contact', 'parent'=>'');
    foreach ($pages_array as $val) {
      // parent
      if(!empty($val['parent'])){
        $parent_id = get_page_by_path($val['parent']);
        $parent_id = $parent_id->ID;
        $page_slug = $val['parent'] . "/" . $val['name'];
      }else{
        $parent_id = "";
        $page_slug =$val['name'];
      }
      if ( empty(get_page_by_path( $page_slug ))) {
        // なければ作成
        $insert_id = wp_insert_post(
          array(
            'post_title'   => $val['title'],
            'post_name'    => $val['name'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_parent'  => $parent_id,
            'post_content' => '',
          )
        );
      }
    }
  }
);
