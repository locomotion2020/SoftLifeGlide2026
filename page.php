<?php 
get_header();

global $post;
$post_slug = $post->post_name;

get_template_part( 'templates/partials/page/content', $post_slug );

get_footer(); 
