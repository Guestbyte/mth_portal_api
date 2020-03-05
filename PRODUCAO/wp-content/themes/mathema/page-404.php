<!--HEADER-->
<?php get_header(); ?>
<!--LOOP WOOCOMMERCE-->
<section class="conteudo-padrao">
<div class="topo-woo"></div>         
    <div class="inner">
    <h1>ESSA PÁGNA AINDA NÃO FOI CRIADA</h1>    
    <?php if (have_posts()) : while (have_posts()): the_post(); ?>     
        <?php the_content(); ?>               
    <?php endwhile; endif;?>
    <div class="clearfix"></div>
    </div>
</section>
<!--FOOTER-->
<?php get_footer();?>
