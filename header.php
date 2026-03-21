<?
/**
 * Author : trBookmark
 * Template Name: Header (Default Template)
 * Template for the header.
 */
?>
<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="googlebot" content="notranslate, noimageindex">
<meta name="bingbot" content="noodp">
<?php wp_head(); ?>
</head>
<body<?
if (isset($args['body-class'])) echo ' class="' . $args['body-class'] . '"';
?>>
