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
            <!-- PEGAS AS INFORMAÇÕES DO AUTHOR    -->
            <?php
            $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
            ?>            
            <div class="autor-item">
                <?php echo get_wp_user_avatar($user_id, 80, 'left'); ?>
                <div class="autor-item-info">
                    <h1><?php echo $curauth->display_name; ?></h1>									
                </div>
                <div class="autor-item-txt"><p><?php echo $curauth->description; ?></p>  </div>
            </div>                      
        </div>       
    </div>        
</section>
<!-- LISTA POSTS -->
<section class="blog-home">
    <div class="inner">       
        <div class="categorias">           
            <h6>Todas as publicações de <?php echo $curauth->display_name; ?></h6>            
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