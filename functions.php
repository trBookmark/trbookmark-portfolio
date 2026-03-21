<?php
/**
 * Author : trBookmark
 * trBookmark's Theme functions and definitions.
 */

// wpディレクトリへの直接アクセス禁止
if (!defined('ABSPATH')) {
  exit;
}

// カスタム投稿タイプ用変数
global $custom_fields;


// URLをルート相対パスに変更
function delete_domain_attachment_url( $url ) {
  if ( preg_match( '/^http(s)?:\/\/[^\/\s]+(.*)$/', $url, $match ) ) {
    $url = $match[2];
  }
  return $url;
}
// 画像src
add_filter('wp_get_attachment_url', 'delete_domain_attachment_url');
// 添付データのパーマリンクURL
add_filter('attachment_link', 'delete_domain_attachment_url');
// 添付ファイルへのリンクを示すHTMLテキスト
add_filter('wp_get_attachment_link', 'delete_domain_attachment_url');
// tag_link
add_filter('tag_link', 'delete_domain_attachment_url');
// パーマリンク
add_filter( 'the_permalink',
  function ($url) {
    return delete_domain_attachment_url($url);
  }
);
// それ以外は wp_make_link_relative(); を使用
// wp_make_link_relative( string $link ): string
// $link string required Full URL path.
// httpまたはhttpsプロトコルとドメインを削除。
// パスの先頭に '/' を残す、Webルートベースからのリンク

// カスタム投稿タイプ設定
locate_template('inc/functions.custom.php', true);
// 共通設定
locate_template('inc/functions.common.php', true);
// 管理画面設定
locate_template('inc/functions.admin.php', true);


/*
 * init: WordPressの読み込みが完了した後、ヘッダーが送信される前に発生.
 */

// 投稿画面でタグ一覧を表示
add_action( 'init', 'show_taxonomy_post_editer', 1 );
function show_taxonomy_post_editer() {
  $tag_slug_args = get_taxonomy('post_tag');
  $tag_slug_args->hierarchical = true;
  $tag_slug_args->meta_box_cb = 'post_categories_meta_box';
  register_taxonomy('post_tag', 'post', (array) $tag_slug_args);
}

// 軽量化
add_action( 'init', 'register_wp_header', 1 );
function register_wp_header() {
  // wp_print_styles: $handlesキューにある表示スタイル。
  // print_emoji_detection_script: インライン絵文字検出スクリプトがまだ印刷されていない場合は、印刷します。
  remove_action( 'wp_head', 'print_emoji_detection_script', 7);
  remove_action( 'wp_head', 'wp_print_styles');
  remove_action( 'wp_print_styles', 'print_emoji_styles');
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script');
  remove_action( 'admin_print_styles', 'print_emoji_styles');
  remove_filter( 'the_content_feed', 'wp_staticize_emoji');
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji');
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email');
  remove_filter( 'the_content', 'wpautop'); // 本文の<p></p>削除
}

// エディターの不要パーツを隠す：カスタム投稿タイプ用（functions.custom.phpにて設定）
add_action('init','remove_editor_supports_custom');
// エディターの不要パーツを隠す：一般用
add_action('init','remove_editor_supports');

// カスタム投稿タイプを設定
add_action( 'init', 'register_custom_posttypes', 1 );
