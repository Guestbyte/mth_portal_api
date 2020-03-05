<?php /* Template Name: Sobre */ ?>
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
            <a class="btn-rola-tela animate" href="#video-sala-aula"><img src="<?php echo bloginfo('template_directory');?>/img/seta-baixo.png" alt=""></a>
        </div>      
    </div>        
</section>
<!-- VÍDEO QUERO CIRANDA -->
<section id="video-sala-aula" class="video-quero-ciranda">
    <div class="inner">
        <div class="video"><?php the_field('video_unico'); ?></iframe></div>        
    </div>    
</section>
<!-- HISTÓRIA -->
<section class="historia">
    <div class="inner">
        <div class="historia-titulo" style="background-image: url(<?php the_field('imagem_background_historia'); ?>);">
            <h2><?php the_field('titulo_historia'); ?></h2>
        </div>
        <div class="historia-txt">
            <?php the_field('texto_historia'); ?>
        </div>
    </div>
</section>
<!-- MISSÃO -->
<section class="missao">
    <div class="inner">
        <!-- ABAS -->
        <div id="abas-vertical" class="abas-missao">  
            <!-- PAINEIS -->            
            <!-- loop que carrega os paineis -->
            <?php
            if( have_rows('abas_missao') ): 
            $a=0;
            $b=0;
            while ( have_rows('abas_missao') ) :
            $a++;
            the_row(); ?>
            <div id="tab-<?php echo $a; ?>">
                <div class="aba-missao">
                    <!-- TÍTULO SÓ APARECE SE PREENCHER -->
                    <?php if( get_sub_field('titulo_aba_missao') ): ?>
                        <h2><?php the_sub_field('titulo_aba_missao'); ?></h2>
                    <?php endif; ?>
                    <div class="aba-missao-txt">
                        <?php the_sub_field('texto_aba_missao'); ?>                       
                    </div>
                    <!-- DEPOIMENTO SÓ APARECE SE PREENCHER O NOME DO AUTOR -->
                    <?php if( get_sub_field('nome_autor_aba_missao') ): ?>
                        <h4><?php the_sub_field('titulo_depoimento_missao'); ?></h4>
                        <div class="depoimento-missao">                        
                            <div class="depoimento-missao-autor">
                                <?php echo wp_get_attachment_image( get_sub_field('foto_autor_aba_missao' ), 'autor-imagem' ); ?>                        
                                <span class="subtitulo"><?php the_sub_field('cargo_autor_aba_missao'); ?></span>    
                                <h6><?php the_sub_field('nome_autor_aba_missao'); ?></h6>                                                    
                            </div>
                            <div class="depoimento-missao-info">
                                <p><?php the_sub_field('depoimento_aba_missao'); ?></p>
                            </div>
                            <div class="clearfix"></div>                        
                        </div>
                    <?php endif; ?>
                </div>                                                 
            </div>
            <?php endwhile; ?>                 
            <!-- MENU ABAS -->
            <ul>
                <!-- loop que carrega as abas -->
                <?php while ( have_rows('abas_missao') ) :
                $b++;
                the_row(); ?>           
                <li>
                    <a href="#tab-<?php echo $b; ?>">
                        <?php echo wp_get_attachment_image( get_sub_field('icone_aba_missao' ), 'icone-missao' ); ?>        
                        <span><?php the_sub_field('rotulo_aba_missao'); ?></span> 
                    </a>
                </li>
                <?php endwhile; ?>                
            </ul>
            <?php endif; ?>    
        </div>
    </div>
</section>
<!-- LINHA DO TEMPO -->
<section class="linha-tempo">
    <div class="inner">
        <h2><?php the_field('titulo_linha_do_tempo'); ?></h2>
        <p>Navegue usando as setas!</p>
        <div class="linha-tempo-anos owl-emenda">
            <div id="owl-linha-tempo" class="owl-carousel owl-theme">
                <!-- loop que carrega os paineis -->
                <?php
                if( have_rows('linha_do_tempo') ): while ( have_rows('linha_do_tempo') ) : the_row(); ?>
                    <div class="linha-tempo-item">
                        <div class="linha-tempo-item-ano">
                            <span><?php the_sub_field('ano_linha_tempo'); ?></span> 
                        </div>                   
                        <div class="linha-tempo-item-txt">   
                            <?php echo wp_get_attachment_image( get_sub_field('imagem_linha_tempo' ), 'linha-tempo' ); ?>   
                            <p><?php the_sub_field('texto_linha_tempo'); ?></p>  
                        </div>
                    </div>   
                <?php endwhile;endif; ?>     
            </div>
        </div>
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
<!-- RESPONSIVE TABS -->
<script type="text/javascript">
    $(document).ready(function () {
        var $tabs = $('#abas-vertical');
        $tabs.responsiveTabs({            
            startCollapsed: 'accordion',
            collapsible: 'accordion',            
        });
    });
</script>  
<!-- OWL CAROUSEL -->
<script>
$(document).ready(function() {
    var owl1 = $('#owl-linha-tempo');
    owl1.owlCarousel({
        items: 1,
        autoWidth: true,
        slideBy: 1,
        margin: 0,
        nav: true,
        dots: false,
        loop: false,
        thumbs: false,      
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