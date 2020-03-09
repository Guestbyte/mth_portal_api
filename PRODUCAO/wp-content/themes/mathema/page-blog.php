<?php /* Template Name: Blog */ ?>
<?php get_header(); ?>
<!-- BLOG -->
<section class="topo-blog">
    <div class="inner">
        <div class="slide-blog owl-emenda">
            <div id="owl-box-cat" class="owl-carousel owl-theme">                
            <?php
            // WP_Query arguments
            $blogSliArgs = array(
                'post_type'      => array( 'post' ),
                'posts_per_page'   => '3',                                        
            );
            // The Query       
            $blogSliQuery = new WP_Query( $blogSliArgs );
            // The Loop                
            if ( $blogSliQuery->have_posts() ) {               
                while ( $blogSliQuery->have_posts() ) {                  
                    $blogSliQuery->the_post();
                    ?>
                    <!--  POST BLOG ITEM   -->
                    <div class="slide-blog-item">           
                        <!-- BOX SLIDE BLOG -->
                        <div class="box-slide-blog">           

                            <div class="box-cat-produto-item">
                                <!-- exibe a categoria primária marcada (plugin Yoast SEO)-->
                                <?php get_primary_category(); ?>                        
                                <a href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
                                <a class="leia-mais-blog" href="">Leia mais</a>                        
                            </div>

                        </div>            
                        <div class="topo-blog-img" <?php thumbnail_bg('topo-blog'); ?>></div>                        
                    </div> 
                    <?php 
                }   
            } 
            /* Restore original Post Data */
            wp_reset_postdata();
            ?>            
            </div>
        </div>      
    </div>        
</section>
<!-- LISTA POSTS -->
<section class="blog-home">
    <div class="inner">       
        <div class="categorias">
            <h6><?php the_field('titulo_lista_de_categorias'); ?></h6>            
            <ul class="list-categorias">
                <?php wp_list_categories( array('orderby' => 'id','hide_empty' => 1,'title_li' => '') ); ?>               
            </ul>            
        </div>       
        <div class="clearfix"></div>
        <div class="blog-list">            
        <?php
        // WP_Query arguments
        $args = array(
            'post_type'        => array( 'post' ),                   
            'posts_per_page'   => '5',
            'paged'            => $paged,           
        );
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        // The Query       
        $wp_query = new WP_Query( $args );
        // The Loop
        if ( $wp_query->have_posts() ) {          
            while ( $wp_query->have_posts() ) {              
            $wp_query->the_post(); ?>                 
                <div class="post-blog">
                    <!-- imagem e categorias -->
                    <div class="post-blog-img-container">
                        <div class="categorias-post"><?php echo get_the_category_list(); ?></div>
                        <a class="post-blog-img-link" href="<?php the_permalink(); ?>" <?php thumbnail_bg('home'); ?>></a> 
                    </div>                 
                    <!-- informaçoes  -->
                    <div class="post-blog-inner">                    
                        <a class="post-blog-link" href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
                        <div class="data"><?php the_time('j \d\e F \d\e Y') ?></div>
                        <p><?php echo excerpt(40); ?></p>
                    </div>               
                </div>
                <?php 
                }   
            } 
            /* Restore original Post Data */
            wp_reset_postdata();
            ?>            
        </div>
        <!-- PAGINAÇÃO -->
        <?php wordpress_pagination(); ?>     
    </div>
</section>





<!-- OWL CAROUSEL -->
<script>
$(document).ready(function() {
    var owl9 = $('#owl-box-cat');
    owl9.owlCarousel({
        autoplay: true, 
        autoplayHoverPause: true,     
        items: 1,
        margin: 0,
        nav: true,
        dots: true,
        loop: true,
        thumbs: false,       
    })
})
</script>
<!-- FOOTER -->
<?php get_footer(); ?>