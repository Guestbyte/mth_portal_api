<!--HEADER-->
<?php get_header(); ?>
<!--LOOP WOOCOMMERCE-->
<section class="conteudo-padrao">
    <div class="topo-woo"></div>   
    <div class="inner">    
    <?php if (have_posts()) : while (have_posts()): the_post(); ?>     
        <?php the_content(); ?>               
    <?php endwhile; endif;?>
    <div class="clearfix"></div>
    </div>
</section>
<!-- RESPONSIVE TABS -->
<script type="text/javascript">
$(document).ready(function () {
    $("section.conteudo-padrao .inner").css("min-height", $(window).height() - $("footer").height() - $("header").height() );        
});
</script>    
<!--FOOTER-->
<?php get_footer();?>
