<?php /* Template Name: Trabalhe Conosco */ ?>
<?php get_header(); ?>
<!-- TRABALHE -->
<section class="home-cards" <?php thumbnail_bg('home'); ?>>
    <div class="inner">
        <!-- BOX PRIMÁRIO -->
        <div class="box-primario">
            <div class="box-primario-inner">
                <h1><?php the_field('titulo_primario'); ?></h1>
                <p><?php the_field('texto_primario'); ?></p>
                <?php if(get_field('rotulo_botao_primario')): ?>
                    <a class="btn-padrao animate" href="#quero-ciranda"><?php the_field('rotulo_botao_primario'); ?></a>
                <?php endif; ?>
            </div>                
            <a class="btn-rola-tela animate" href="#porque"><img src="<?php echo bloginfo('template_directory');?>/img/seta-baixo.png" alt=""></a>
        </div>                 
    </div>        
</section>
<!-- PORQUE -->
<section id="porque" class="porque porque-ciranda">
    <div class="inner">
        <div class="conteudo-trabalhe">
            <?php if(get_field('titulo_por_que_o_mathema')): ?>
            <h2><?php the_field('titulo_por_que_o_mathema'); ?></h2>
            <?php endif; ?>
            <?php 
            $image = get_field('imagem_unica');
            ?>
            <img class="trabalhe-img" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
            <div class="trabalhe-txt"><?php the_content(); ?></div>
            <div class="clearfix"></div>
        </div>
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
<!-- VAGAS -->
<section class="vagas">
    <div class="inner">
        <!-- link do botão -->
        <?php 
        $linkTrabalhe = get_field('link_botao_trabalhe_conosco');
        $linkTrabalhe_url = $linkTrabalhe['url'];	
        $linkTrabalhe_target = $linkTrabalhe['target'] ? $linkTrabalhe['target'] : '_self';
        ?>
        <a class="btn-vagas" href="<?php echo esc_url($linkTrabalhe_url); ?>" target="<?php echo esc_attr($linkTrabalhe_target); ?>"><span><?php the_field('rotulo_botao_trabalhe_conosco'); ?></span></a>
    </div>
</section>
<!-- DEPOIMENTOS -->
<section class="historias-ciranda">
    <div class="inner">
        <h2><?php the_field('titulo_depoimentos'); ?></h2>
        <!-- SLIDE -->
        <div class="historias-ciranda-slide owl-emenda">
            <div id="owl-historias-ciranda" class="owl-carousel owl-theme">
                <!-- loop que carrega os resultados -->
                <?php if( have_rows('itens_depoimentos') ): while ( have_rows('itens_depoimentos') ) : the_row(); ?>
                <!-- ITEM -->
                <div class="historias-ciranda-item">
                    <div class="historias-ciranda-item-autor">
                        <?php echo wp_get_attachment_image( get_sub_field('imagem_depoimento' ), 'autor-imagem', '', array( 'class' => 'historias-ciranda-item-img' ) ); ?>                        
                        <h6><?php the_sub_field('autor_depoimento'); ?></h6>
                        <span class="subtitulo"><?php the_sub_field('cargo_depoimento'); ?></span>                        
                    </div>
                    <div class="historias-ciranda-item-info">
                        <p><?php the_sub_field('depoimento_depoimento'); ?></p>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>            
        </div>
        <div class="clearfix"></div>
        <img class="historias-ciranda-casa" src="<?php echo bloginfo('template_directory');?>/img/folha-depoimentos.png" alt="">
    </div>
</section>
<!-- QUERO CIRANDA -->
<section id="quero-ciranda" class="quero-ciranda">
    <div class="inner">
        <div class="quero-ciranda-box">
            <h2><?php the_field('titulo_formulario_solicitacao'); ?></h2>
            <?php if( get_field('texto_formulario_solicitacao') ) : ?>   
                <p><?php the_field('texto_formulario_solicitacao'); ?></p>
            <?php endif; ?>
            <?php echo do_shortcode( get_field('shortcode_do_formulario') ); ?>
        </div>       
    </div>
</section>
<!-- OWL CAROUSEL -->
<script>
$(document).ready(function() {
    var owl4 = $('#owl-historias-ciranda');
    owl4.owlCarousel({
        autoplay: true,
        autoplayHoverPause: true,        
        items: 1,        
        margin: 0,
        nav: true,        
        loop: true,
        thumbs: false,
        responsive:{
            0:{               
                dots: false
            },
            769:{               
                dots: true
            }
        }                
    })
})
</script>
<!-- FOOTER -->
<?php get_footer(); ?>