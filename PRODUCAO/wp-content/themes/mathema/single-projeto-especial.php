<?php get_header(); ?>
<!-- TOPO - background carregado da página PROJETOS ESPECIAIS -->
<?php $pageQuery = new WP_Query(array('pagename' => 'solucoes-educacionais/projetos-especiais')); $pageQuery->the_post(); ?>
<section class="topo-meia-altura" <?php thumbnail_bg('topo'); ?>>
<?php wp_reset_postdata(); ?>
    <div class="inner">
         <!-- BOX SECUNDÁRIO -->
         <div class="box-secundario">
            <div class="box-secundario-inner">
                 <!-- breadcrumb -->
                 <div class="breadcrumbs">
                    <span><a href="<?php echo bloginfo('url'); ?>"><span>Home</span></a></span>
                    <span class="separador-breadcrumb"> › </span>
                    <span><a href="<?php echo bloginfo('url'); ?>/solucoes-educacionais/projetos-especiais/"><span>Projetos especiais</span></a></span>                            
                </div>                                                
                <h1><?php the_title(); ?></h1>                
            </div>           
        </div>               
    </div>        
</section>
<section class="namidia">
    <div class="inner">
        <div class="conteudo-projeto-especial">
            <?php the_content(); ?>
        </div>        
    </div>
</section>
<!-- ABAS VERTICAIS -->
<!-- ABA SÓ APARECE SE TIVER CONTEÚDO -->
<?php if( have_rows('abas_vertical') ): ?>
<section class="download-ciranda">
    <div class="inner">
        <!-- ABAS -->
        <div id="abas-vertical">  
            <!-- PAINEIS -->            
            <!-- loop que carrega os paineis -->
            <?php           
            $a=0;
            $b=0;
            while ( have_rows('abas_vertical') ) :
            $a++;
            the_row(); ?>
            <div id="tab-<?php echo $a; ?>">
                <div class="download-ciranda-txt">
                    <h2><?php the_sub_field('titulo_aba_vertical'); ?></h2>
                    <?php the_sub_field('texto_aba_vertical'); ?>                   
                </div>
                <?php echo wp_get_attachment_image( get_sub_field('imagem_aba_vertical' ), 'img-tab-ciranda' ); ?>                                     
            </div>
            <?php endwhile; ?>                 
            <!-- MENU ABAS -->
            <ul>
                <!-- loop que carrega as abas -->
                <?php while ( have_rows('abas_vertical') ) :
                $b++;
                the_row(); ?>           
                <li><a href="#tab-<?php echo $b; ?>"><?php the_sub_field('titulo_aba_vertical'); ?></a></li>
                <?php endwhile; ?>                
            </ul>            
        </div>        
    </div>
</section>
<?php endif; ?>
<!-- CARREGADOS DA PÁGINA PROJETOS ESPECIAIS-->
<?php $pageQuery = new WP_Query(array('pagename' => 'solucoes-educacionais/projetos-especiais')); $pageQuery->the_post(); ?>    
<!-- PARCEIROS -->
<section class="parceiros-ciranda">
    <div class="inner">
        <h2><?php the_field('titulo_parceiros'); ?></h2>
        <div class="parceiros-ciranda-slide owl-emenda">
            <div id="owl-parceiros-ciranda" class="owl-carousel owl-theme">
                <!-- loop que carrega os logos dos parceiros -->
                <?php if( have_rows('parceiros_itens') ): while ( have_rows('parceiros_itens') ) : the_row(); ?>            
                <!-- ITEM -->
                <div class="parceiro-ciranda-item"><a href="<?php the_sub_field('link_para_o_site_do_parceiro'); ?>" target="_blank"><?php echo wp_get_attachment_image( get_sub_field('logo_parceiro' ), 'logo-parceiro' ); ?></a></div>
                <?php endwhile; endif; ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<!-- CONTRATAR MODALIDADE -->
<section class="contratar-modalidade quero-ciranda">
    <div class="inner">
        <button id="btn-form-modalidade" class="btn-vagas"><span><?php the_field('rotulo_do_botao_formulario_modalidade'); ?></span></button>
        <div id="form-modalidade" class="quero-ciranda-box">
            <h2><?php the_field('titulo_formulario_modalidade'); ?></h2>
            <?php if( get_field('texto_formulario_modalidade') ) : ?>   
                <p class="quero-ciranda-txt"><?php the_field('texto_formulario_modalidade'); ?></p>
            <?php endif; ?>
            <?php echo do_shortcode( get_field('shortcode_do_formulario_modalidade') ); ?>
        </div>
    </div>
</section>
<?php wp_reset_postdata(); ?>
<!-- FORMULÁRIO MODALIDADE -->
<script>
$(document).ready(function() {
//controla se o menu deve abrir ou fechar   
var clicado = 0;
    //controla a abertura e fechamento dos filtros clicando no botao
    $("#btn-form-modalidade").click(function(){
        if(clicado==0){            
            $("#form-modalidade").show();           
            clicado = 1;
        }else{            
            $("#form-modalidade").hide();           
            clicado = 0;
        }        			
    }); 
})
</script>
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
    var owl5 = $('#owl-parceiros-ciranda');
    owl5.owlCarousel({
        autoplay: true,       
        margin: 0,
        nav: true,
        dots: true,
        loop: true,
        thumbs: false,
        responsive:{
            0:{               
                items: 2,
                slideBy: 2
            },
            769:{               
                items: 4,
                slideBy: 4
            }
        }                        
    })   
})
</script>    
<!-- FOOTER -->
<?php get_footer(); ?>