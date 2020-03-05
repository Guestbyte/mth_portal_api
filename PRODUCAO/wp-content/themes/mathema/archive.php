<?php /* Template Name: Blog */ ?>
<?php get_header(); ?>
<!-- CATEGORIA -->
<section class="topo-blog">
    <div class="inner">
        <!-- PEGA A IMAGEM DESTACADA -->
        <?php $pageQuery = new WP_Query(array('pagename' => 'blog')); $pageQuery->the_post(); ?>
        <?php the_post_thumbnail( 'topo-blog', array('class' => 'topo-blog-img') ) ?>
        <?php wp_reset_postdata(); ?>      
        <!-- BOX SLIDE BLOG -->
        <div class="box-slide-blog">
            <!-- PEGA A CATEGORIA DO POST -->
            <?php
                $category = get_category( get_query_var( 'cat' ) );
                $catName = $category->name;
            ?>       
            <!-- breadcrumb -->
            <div class="breadcrumbs">
                <span><a href="<?php echo bloginfo('url'); ?>"><span>Home</span></a></span>
                <span class="separador-breadcrumb"> › </span>
                <span><a href="<?php echo bloginfo('url'); ?>/blog"><span>Blog</span></a></span>
                <span class="separador-breadcrumb"> › </span>
                <span class="current-item"><?php echo $catName; ?></span>                
            </div>           
            <h1><?php echo $catName; ?></h1>       
        </div>       
    </div>        
</section>
<!-- LISTA POSTS -->
<section class="blog-home">
    <div class="inner">       
        <div class="categorias">
            <?php $pageQuery = new WP_Query(array('pagename' => 'blog')); $pageQuery->the_post(); ?>            
            <h6><?php the_field('titulo_lista_de_categorias'); ?></h6>
            <?php wp_reset_postdata(); ?>
            <!-- PEGA O CAMPO TÍTULO DAS CATEGORIAS DA PÁGINA BLOG -->            
            <ul class="list-categorias">
                <li><a href="<?php echo bloginfo('url'); ?>/blog"><span>Todas categorias</span></a></li>
                <?php wp_list_categories( array('orderby' => 'id','hide_empty' => 0,'title_li' => '') ); ?>               
            </ul>            
        </div>       
        <div class="clearfix"></div>
        <div class="blog-list">            
        <?php       
        // The Loop
        if ( have_posts() ) {          
            while ( have_posts() ) {              
                the_post(); ?>                 
                <div class="post-blog">
                    <!-- imagem e categorias -->
                    <div class="post-blog-img-container">
                        <div class="categorias-post"><?php echo get_the_category_list(); ?></div>
                        <a class="post-blog-img-link" href="<?php the_permalink(); ?>" <?php thumbnail_bg('home'); ?>></a> 
                    </div>                 
                    <!-- informaçoes  -->
                    <div class="post-blog-inner">                    
                        <a class="post-blog-link" href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                        <div class="data"><?php the_time('j \d\e F \d\e Y') ?></div>
                        <p><?php echo excerpt(40); ?></p>
                    </div>               
                </div>
                <?php 
                }   
            }           
            ?>            
        </div>
        <!-- PAGINAÇÃO -->
        <?php wordpress_pagination(); ?>     
    </div>
</section>
<!-- FOOTER -->
<?php get_footer(); ?>