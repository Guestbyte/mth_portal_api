<!--HEADER-->
<?php get_header(); ?>
<!--LOOP-->
<?php if (have_posts()) : while (have_posts()): the_post(); ?>
<!-- BLOG -->
<section class="topo-blog">
    <div class="inner">
        <!-- IMAGEM -->        
        <?php the_post_thumbnail( 'topo-single', array('class' => 'topo-blog-img') ) ?>   
        <!-- BOX SINGLE BLOG -->
        <div class="box-slide-blog">           
            <!-- breadcrumb -->
            <div class="breadcrumbs">
                <span><a href="<?php echo bloginfo('url'); ?>"><span>Home</span></a></span>
                <span class="separador-breadcrumb"> › </span>
                <span><a href="<?php echo bloginfo('url'); ?>/blog"><span>Blog</span></a></span>
                <span class="separador-breadcrumb"> › </span>
                <span class="current-item"><?php get_primary_category(); ?></span>                
            </div>
            <div class="infos">         
                <h1><?php the_title(); ?></h1>
                <span class="autor-post-nome">Por <?php do_action( 'pp_multiple_authors_show_author_box' ); ?></span>
                <span class="post-data">Escrito em: <?php the_time('d/m/Y'); wpb_last_updated_date(); ?></span>               
            </div>       
        </div>                 
    </div>        
</section>
<!-- CONTEÚDO POST -->
<section class="conteudo-single">   
    <div class="inner">
    <!-- adiciona lista de 3 produtos aleatórios -->
	<div id="produtos-aletorios" class="produtos-aletorios">
        <h4>Conheça nossos outros produtos:</h4>
        <?php echo do_shortcode( '[products per_page="3" columns="1" orderby="rand"]' ); ?> 
    </div>
    <div class="compartilhe">
        <span class="titulo-compartilhe">Compartilhe nas suas redes:</span>
        <?php echo do_shortcode( '[addtoany]' ) ?>
    </div>   
    <div class="conteudo-post conteudo-padrao">
        <?php the_content(); ?>               
    </div>		
    <div class="clearfix"></div>
    </div>
</section>
<!-- COMENTÁRIOS -->
<section class="comentarios-container">
    <div class="inner">        
         <?php comments_template(); ?>      
    </div>
</section>
<?php endwhile; endif;?>
<!--FOOTER-->
<?php get_footer();?>
