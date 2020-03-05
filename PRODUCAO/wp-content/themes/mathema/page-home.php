<?php /* Template Name: Home */ ?>
<?php get_header(); ?>
<!-- HOME -->
<section class="home-cards" <?php thumbnail_bg('home'); ?>>
    <div class="inner">
        <!-- BOX PRIMÁRIO -->
        <div class="box-primario">
            <div class="box-primario-inner">
                <h1><?php the_field('titulo_primario'); ?></h1>
                <p><?php the_field('texto_primario'); ?></p>
            </div>
            <a class="btn-rola-tela animate" href="#porque"><img src="<?php echo bloginfo('template_directory');?>/img/seta-baixo.png" alt=""></a>
        </div>
        <!-- BOX CARDS  -->
        <div class="box-cards">
            <!-- loop que repete os card -->
            <?php if( have_rows('cards') ): while ( have_rows('cards') ) : the_row(); ?>
            <div class="card-home">
                <img class="card-home-img" src="<?php the_sub_field('icone_card'); ?>" alt="<?php the_sub_field('titulo_card'); ?>">
                <div class="card-home-inner">
                    <h3><?php the_sub_field('titulo_card'); ?></h3>
                    <a href="<?php the_sub_field('destino_link_card_1'); ?>"><?php the_sub_field('link_card_1'); ?></a>
                    <!-- esconde se vazio -->
                    <?php if( get_sub_field('link_card_2') ): ?>
                        <a href="<?php the_sub_field('destino_link_card_2'); ?>"><?php the_sub_field('link_card_2'); ?></a>
                    <?php endif; ?>
                </div>
            </div> 
            <?php endwhile;endif; ?>            
        </div>
    </div>        
</section>
<!-- SLIDER HOME -->
<?php if( have_rows('banner_home') ) : ?>
<section class="slider-home owl-emenda">
    <div class="inner">
        <div id="owl-home" class="owl-carousel owl-theme">
            <!-- primeiro loop slides -->
            <?php while ( have_rows('banner_home') ) : the_row(); ?> 
            <a class="item-slide-home" href="<?php the_sub_field('link_banner_home'); ?>" style="background-image: url(<?php the_sub_field('imagem_banner_home_desktop'); ?>),url(<?php the_sub_field('imagem_banner_home_mobile'); ?>);">            
            </a>
            <?php endwhile;?>                      
        </div>
    </div>
</section>
<?php endif; ?>  
<!-- PORQUE -->
<section id="porque" class="porque">
    <div class="inner">
        <?php if(get_field('titulo_por_que_o_mathema')): ?>
        <h2><?php the_field('titulo_por_que_o_mathema'); ?></h2>
        <?php endif; ?>
        <div class="list-porque">            
            <?php
            if( have_rows('icones_por_que_o_mathema') ): 
            $i=0;
            while ( have_rows('icones_por_que_o_mathema') ) :
            $i++;
            the_row(); ?>
            <div id="porque-item-<?php echo $i; ?>" class="porque-item"><img src="<?php the_sub_field('icone_por_que_o_mathema'); ?>" alt="<?php the_sub_field('texto_por_que_o_mathema'); ?>"><span><?php the_sub_field('texto_por_que_o_mathema'); ?></span></div>
            <?php endwhile;endif; ?>  
        </div>        
    </div>
</section>
<!-- NOSSOS NÚMEROS -->
<section id="nossos-numeros" class="nossos-numeros">
    <div class="inner">
        <h2><?php the_field('titulo_nossos_numeros'); ?></h2>
        <div class="nossos-numeros-list">
            <div id="nossos-numeros-item-0" class="nossos-numeros-item"><img src="<?php echo bloginfo('template_directory');?>/img/educadores-formados.png" alt=""><span>+ de <?php the_field('educadores_formados'); ?></span><small>educadores formados</small></div>
            <div id="nossos-numeros-item-1" class="nossos-numeros-item"><img src="<?php echo bloginfo('template_directory');?>/img/alunos-impactados.png" alt=""><span>+ de <?php the_field('alunos_impactados'); ?></span><small>de alunos impactados diretamente</small></div>
            <div id="nossos-numeros-item-2" class="nossos-numeros-item"><img src="<?php echo bloginfo('template_directory');?>/img/livros-didaticos.png" alt=""><span>+ de <?php the_field('livros_didaticos_distribuidos'); ?></span><small>de livros didáticos distribuídos</small></div>
        </div>
    </div>
    <img id="folha" class="img-nossos-numeros" src="<?php echo bloginfo('template_directory');?>/img/folha-porque.png" alt="">
</section>
<!-- BLOG HOME -->
<section class="blog-home">
    <div class="inner">
        <?php $pageQuery = new WP_Query(array('pagename' => 'blog')); $pageQuery->the_post(); ?>
        <h2>Blog</h2>
        <div class="categorias">
            <h6><?php the_field('titulo_lista_de_categorias'); ?></h6>            
            <ul class="list-categorias">
                <?php wp_list_categories( array('orderby' => 'id','hide_empty' => 1,'title_li' => '') ); ?>               
            </ul>            
        </div>
        <?php wp_reset_postdata(); ?>
        <div class="clearfix"></div>
        <div class="blog-home-list">
            <!-- ÚLTIMO POST -->
            <?php
            // WP_Query arguments
            $blogArgs = array(
                'post_type'      => array( 'post' ),                   
                'posts_per_page' => '2',                
                'ignore_sticky_posts' => 1,           
            );
            // The Query       
            $blogQuery = new WP_Query( $blogArgs );
            // The Loop
            if ( $blogQuery->have_posts() ) :
            $b=0;
            while ( $blogQuery->have_posts() ) :
            $b++;
            $blogQuery->the_post();
            ?>            
            <div class="post-home post-home-<?php echo $b; ?>">
                <a class="post-home-img-link" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-home-' . $b . '', array('class' => 'post-home-img')); ?></a>                
                <div class="post-home-content">
                    <div class="post-home-content-inner">
                        <!-- exibe a categoria primária marcada (plugin Yoast SEO)-->
                        <?php get_primary_category(); ?>                   
                        <div class="data"><?php the_time('j \d\e F \d\e Y') ?></div>
                        <a href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a> 
                    </div>
                </div>
            </div>           
            <?php endwhile; endif;
            // Restore original Post Data
            wp_reset_postdata();
            ?>        
            <!-- IR PARA O BLOG -->           
            <?php $pageQuery = new WP_Query(array('pagename' => 'blog')); $pageQuery->the_post(); ?>
            <a href="<?php the_permalink(); ?>" class="btn-irblog">
                <div class="rotulo">Ir para a página do Blog!</div>
                <?php echo wp_get_attachment_image( get_field('imagem_botao_blog_na_home' ), 'blog-home-botao' ); ?> 
            </a>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<!-- MINUTO MATHEMA -->
<section class="minuto-home">
    <div class="inner">
        <div class="minuto-home-conteudo">
            <h2><?php the_field('titulo_galeria_video'); ?></h2>
            <div class="minuto-home-txt">
                <p><?php the_field('texto_galeria_video'); ?></p>                
            </div>
            <!-- ACF Link -->
            <?php 
            $linkGaleria = get_field('destino_do_botao_galeria_video');
            $linkGaleria_url = $linkGaleria['url'];	
            $linkGaleria_target = $linkGaleria['target'] ? $linkGaleria['target'] : '_self';
            ?>
            <a class="btn-padrao" href="<?php echo esc_url($linkGaleria_url); ?>" target="<?php echo esc_attr($linkGaleria_target); ?>"><?php the_field('rotulo_botao_galeria_video'); ?></a>
        </div>
        <div class="minuto-home-slide">
            <?php if( have_rows('videos_galeria_video') ) : ?>
            <div class="container-video owl-emenda">
                <div id="owl-minuto" class="owl-carousel owl-theme" data-slider-id="1">
                    <!-- primeiro loop slides -->
                    <?php while ( have_rows('videos_galeria_video') ) : the_row(); ?> 
                    <div class="minuto-home-video">
                        <div class="video"><?php the_sub_field('codigo_do_video_galeria_video'); ?></div>
                    </div>
                    <?php endwhile;?>                      
                </div>
            </div>
            <div class="owl-thumbs" data-slider-id="1">
                <!-- segundo loop thumbs -->
                <?php while ( have_rows('videos_galeria_video') ) : the_row(); ?> 
                <button class="owl-thumb-item">
                    <img src="<?php the_sub_field('capa_do_video_galeria_video'); ?>" alt="">
                </button>
                <?php endwhile;?>                
            </div>            
            <?php endif; ?>
        </div>        
        <div class="clearfix"></div>
    </div>
</section>
<!-- LOJA HOME -->
<section class="loja-home">
    <div class="inner">
        <h2><?php the_field('titulo_carrossel_produtos'); ?></h2>        
        <?php
        // pega todas as categorias de produtos
        $terms = get_terms( 'product_cat' ); 
        // lista somente os IDs das categorias
        $term_ids = wp_list_pluck( $terms, 'term_id' );
        ?>
        <!-- se escolher categoria exibe os produtos da categoria, se não exibe todos os produtos -->
        <?php 
        if( get_field('categoria_de_produtos') ):
            $catProdutos = get_field('categoria_de_produtos');
        else:
            $catProdutos = $term_ids;
        endif;  
        ?>
        <div class="loja-home-slide owl-emenda">
            <div id="owl-loja" class="owl-carousel owl-theme products">
                <!--  LOOP DOS PRODUTOS -->      
                <?php
                $args = array(
                    'post_type' => 'product',
                    // The taxonomy query
                    'tax_query'=>array(
                        array(
                          'taxonomy'  => 'product_cat',
                          'field'     => 'term_id',
                          'terms'     => $catProdutos
                        )
                    ),
                    'posts_per_page' => 12,
                    'orderby'        => 'rand'
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
    </div>
</section>
<!-- NOVIDADES -->
<section class="novidades">
    <div class="inner">
        <h2>Fique por dentro das novidades do Mathema!</h2>
        <span class="novidades-subtitulo">Assine gratuitamente nossa newsletter e receba, em primeira mão, atualizações sobre cursos e conteúdos em nosso site.</span>
        <?php echo do_shortcode('[mc4wp_form id="1989"]'); ?>
        <img class="img-novidades" src="<?php echo bloginfo('template_directory');?>/img/seta-novidades.png" alt="">
    </div>
</section>
<!-- ANIMAÇÕES DA HOME -->
<script>
$(window).scroll(function() {
    if( $(window).width() > 768 ){        
        //ANIMAÇÕES NOSSOS NÚMEROS
        var startNumeros = $('#nossos-numeros').offset().top - ( window.innerHeight / 2 );
        if ($(window).scrollTop() > startNumeros) {
            $('#nossos-numeros-item-0').delay(200).animate({top: "0px"},600,"swing");
            $('#nossos-numeros-item-1').delay(500).animate({top: "0px"},600,"swing");
            $('#nossos-numeros-item-2').delay(800).animate({top: "0px"},600,"swing");      
        }
    }
})
</script>  
<!-- OWL CAROUSEL -->
<script>
$(document).ready(function() {
    var owl0 = $('#owl-home');
    owl0.owlCarousel({
        autoplay: true,
        autoplayHoverPause: true,
        items: 1,      
        dots: true,
        loop: true,
        thumbs: false
    })
    var owl1 = $('#owl-minuto');
    owl1.owlCarousel({
        items: 1,
        margin: 10,
        nav: false,
        dots: false,
        loop: false,
        thumbs: true,
        thumbsPrerendered: true    
    })
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