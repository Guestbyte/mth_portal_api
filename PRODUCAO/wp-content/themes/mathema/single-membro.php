<?php /* Template Name: Equipe */ ?>
<?php get_header(); ?>
<!-- HOME -->
<?php $pageQuery = new WP_Query(array('pagename' => 'o-grupo-mathema/equipe')); $pageQuery->the_post(); ?>
<section class="topo-meia-altura" <?php thumbnail_bg('topo'); ?>>
    <div class="inner">         
    </div>        
</section>
<?php wp_reset_postdata(); ?>
<section class="membro-equipe">
    <div class="inner">
        <div class="membro-equipe-inner">            
             <!-- breadcrumb -->
             <div class="breadcrumbs">
                <span><a href="<?php echo bloginfo('url'); ?>"><span>Home</span></a></span>
                <span class="separador-breadcrumb"> › </span>
                <span><a href="<?php echo bloginfo('url'); ?>/o-grupo-mathema/o-grupo/"><span>O Grupo Mathema</span></a></span>
                <span class="separador-breadcrumb"> › </span>
                <span><a href="<?php echo bloginfo('url'); ?>/o-grupo-mathema/equipe/"><span>Equipe</span></a></span>                              
            </div>              
            <?php if (have_posts()) : while (have_posts()): the_post(); ?>
            <div class="membro-equipe-info">
                <?php the_post_thumbnail( 'membro', array( 'class' => 'img-membro' ) ); ?>               
                <span>Olá, sou</span>
                <h1><?php the_title(); ?></h1>
                <h6><span>cargo:</span> <?php the_field('cargo_membro_equipe'); ?></h6>                
            </div>
            <div class="membro-equipe-txt">
                <?php the_content(); ?>
            </div>
            <div class="clearfix"></div>                                   
            <?php endwhile; endif;?>
        </div>
        <!-- PEGA O AUTOR -->                
        <?php
        if( get_field('id_membro_equipe') ){

            $autorPost = get_field('id_membro_equipe');        
            ?>
            <?php
            //SE ESSE AUTOR TIVER PRODUTOS
            $argsProduct = array(
                'post_type'  => 'product',
                'author'     => $autorPost,
            );        
            $wp_products = get_posts($argsProduct);        
            if ( count($wp_products) ) {
            ?>
            <!-- LISTA PRODUTOS DO AUTOR -->
            <div class="list-itens-membro">
                <h2>Cursos que desenvolvi</h2>           
                <div class="loja-home-slide owl-emenda">
                    <div id="owl-loja" class="owl-carousel owl-theme products">
                        <!--  LOOP DOS PRODUTOS -->      
                        <?php
                        $args = array(
                            'post_type' => 'product',                        
                            'posts_per_page' => 3,
                            'orderby'        => 'rand',
                            'author'         => $autorPost,
                            );
                        $loop = new WP_Query( $args );
                        if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
                        ?>
                        <!--  ITEM PRODUTO -->
                        <div class="item-container">
                        <!--  CARREGA O PRODUTO WOOCOMMERCE -->
                            <?php wc_get_template_part( 'content', 'product' ); ?>
                        </div>
                        <?php 
                        endwhile;endif;            
                        wp_reset_postdata();
                        ?>                
                    </div>
                </div>              
                <a href="#" class="btn-padrao">Ver mais cursos na loja online</a>
            </div>
            <?php }; ?>

            <!-- LISTA POSTS DO AUTOR -->
            <?php
            //SE ESSE AUTOR TIVER POSTS
            $argsPost = array(
                'post_type'  => 'post',
                'author'     => $autorPost,
            );            
            $wp_posts = get_posts($argsPost);        
            if ( count($wp_posts) ) {                
            ?>

            <div class="list-itens-membro">
                <h2>Artigos em que colaborei</h2>           
                <!-- LISTA OS POSTS DESTE USUÁRIO -->                     
                <div class="relatos-ciranda-list">
                <?php
                // WP_Query arguments
                $argsPost = array(
                    'post_type'        => array( 'post' ),
                    'author'           => $autorPost,
                /*  'orderby'        => 'rand',        */    
                    'posts_per_page'   => '3'            
                );        
                // The Query
                $the_query = new WP_Query( $argsPost ); 
                // The Loop
                if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <!-- ITEM -->
                <div class="relato-ciranda-item">
                    <div class="relato-ciranda-item-categorias">
                        <?php the_category(); ?>                               
                    </div>
                    <div class="relato-ciranda-item-img">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'blog-relacionada'); ?></a>                                
                    </div>
                    <a class="relato-ciranda-item-link" href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>                        
                    <div class="data"><?php the_time('j \d\e F \d\e Y') ?></div>
                </div>
                <?php endwhile; endif; ?>
                <?php    
                /* Restore original Post Data */
                wp_reset_postdata();
                ?>           
                </div>        
                <a href="<?php echo bloginfo('url'); ?>/blog" class="btn-padrao">Ler todos os artigos no blog</a>
            </div>

            <?php }; ?>


        <?php }; ?>
    </div>
</section>
<!-- OWL CAROUSEL -->
<script>
$(document).ready(function() {   
    var owl2 = $('#owl-loja');
    owl2.owlCarousel({
        autoplay: true,
        autoplayHoverPause: true,
        rewind: true,      
        margin: 0,       
        dots: true,
        loop: true,
        thumbs: false,
        responsive:{
            0:{
                items: 1,
                slideBy: 1,
                nav: false
            },
            769:{
                items: 3,
                slideBy: 3,
                nav: true
            }
        }        
    })
})
</script>
<!-- FOOTER -->
<?php get_footer(); ?>