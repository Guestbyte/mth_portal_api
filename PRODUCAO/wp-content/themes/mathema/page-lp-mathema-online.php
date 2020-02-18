<?php /* Template Name: LP Mathema Online */ ?>
<?php get_header(); ?>
<!-- TOPO -->
<section id="topo-lp" class="topo-lp">
    <div class="inner">     
        <a class="animate" href="#topo"><img class="logo-lp-topo" src="<?php echo bloginfo('template_directory');?>/img/mathema-online-rodape.svg" alt="Mathema Online"></a>
        <!-- BOTÃO -->
        <button class="btn-menu btn-menu-lp">
            <span></span>
            <span></span>
            <span></span>
        </button>              
        <ul id="menu-lp" class="menu-topo-lp">
            <button id="btn-fecha-menu-lp" class="btn-fecha">
                <span class="barrinha1"></span>
                <span class="barrinha2"></span>
            </button>
            <li><a class="animate" href="#topo">Início</a></li>
            <li><a class="animate" href="#beneficios">Benefícios</a></li>
            <li><a class="animate" href="#como-funciona">Como funciona</a></li>                        
            <li><a class="animate" href="#depoimentos">Depoimentos</a></li>
            <li><a class="animate" href="#experimente">Experimente</a></li>                
        </ul>        
        <div class="clearfix"></div> 
    </div>
</section>
<!-- BANNER -->
<section id="topo" class="banner-lp">
    <img class="logo-lp" src="<?php echo bloginfo('template_directory');?>/img/mathema-online.svg" alt="Mathema Online">
    <!-- BOTÃO -->
    <button class="btn-menu btn-menu-lp">
        <span></span>
        <span></span>
        <span></span>
    </button>       
    <div class="banner-lp-img"></div>
    <div class="banner-lp-info">
        <div class="banner-lp-info-inner">
            <h1><?php the_field('titulo_banner'); ?></h1>
            <p><?php the_field('texto_banner'); ?></p>
            <a class="btn-padrao animate" href="#experimente"><?php the_field('rotulo_botao_banner'); ?></a>
        </div>
    </div>
    <div class="clearfix"></div>    
</section>
<!-- PORQUE -->
<section id="beneficios" class="porque beneficios-lp">
    <div class="inner">       
        <h2><?php the_field('titulo_por_que_o_mathema'); ?></h2>        
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
<!-- COMO FUNCIONA -->
<section id="como-funciona" class="como-funciona-lp">
    <div class="inner">
        <div class="como-funciona-lp-txt">
            <h2><?php the_field('titulo_como_funciona'); ?></h2>
            <p><?php the_field('texto_como_funciona'); ?></p>
        </div>
        <img class="img-como-funciona-lp" src="<?php echo bloginfo('template_directory');?>/img/como-funciona.png" alt="Como Funciona">
        <div class="clearfix"></div>       
    </div>
</section>
<!-- FORMATO -->
<section id="formato" class="formato-lp">
    <div class="inner">        
        <div class="formato-lp-container">
            <div class="formato-lp-txt">
                <h2><?php the_field('titulo_formato_do_curso'); ?></h2>
                <p><?php the_field('texto_formato_do_curso'); ?></p>
            </div>
            <div class="formato-lp-icones">
                <!-- loop que carrega os ícones -->
                <?php if( have_rows('icones_formato_do_curso') ): while ( have_rows('icones_formato_do_curso') ) : the_row(); ?>
                <div class="formato-lp-item">
                    <?php echo wp_get_attachment_image( get_sub_field('icone_formato_do_curso' ), 'full', '', array( 'class' => 'formato-lp-item-img' ) ); ?>                    
                    <h4><?php the_sub_field('titulo_icone_formato_do_curso'); ?></h4>
                </div>
                <?php endwhile; endif; ?>
            </div>
            <img class="bg-formato-lp" src="<?php echo bloginfo('template_directory');?>/img/bg-como-funciona.svg" alt="Como Funciona">
        </div>  
    </div>
</section>
<!-- LOJA HOME -->
<section id="experimente" class="loja-lp">
    <div class="inner">
        <h2 class="titulo-loja-lp"><?php the_field('titulo_lista_cursos'); ?></h2>      
        <p class="lp-txt"><?php the_field('texto_lista_cursos'); ?></p>
        <div class="clearfix"></div>  
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
        
        <ul class="products columns-3">  
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
                    'posts_per_page' => -1,
                    'orderby'        => 'rand'
                    );
                $loop = new WP_Query( $args );
                if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
                ?>
                
                   <!--  CARREGA O PRODUTO WOOCOMMERCE -->
                    <?php wc_get_template_part( 'content', 'product' ); ?>
                
                <?php 
                endwhile;endif;            
                wp_reset_postdata();
                ?>
        </ul>
       
    </div>
</section>
<!-- DEPOIMENTOS -->
<section id="depoimentos" class="historias-ciranda">
    <div class="inner">
        <h2><?php the_field('titulo_depoimentos'); ?></h2>
        <!-- SLIDE -->
        <div class="historias-ciranda-slide owl-emenda">
            <div id="owl-historias-ciranda" class="owl-carousel owl-theme">
                <!-- loop que carrega os resultados -->
                <?php if( have_rows('itens_depoimentos') ): while ( have_rows('itens_depoimentos') ) : the_row(); ?>
                <!-- ITEM -->
                <div class="historias-ciranda-item">
                    <?php echo wp_get_attachment_image( get_sub_field('imagem_depoimento' ), 'autor-imagem', '', array( 'class' => 'historias-ciranda-item-img' ) ); ?>                        
                    <div class="historias-ciranda-item-info">
                        <p><?php the_sub_field('depoimento_depoimento'); ?></p>
                        <h6>- <?php the_sub_field('autor_depoimento'); ?>, <?php the_sub_field('cargo_depoimento'); ?></h6>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>            
        </div>
        <div class="clearfix"></div>        
    </div>
</section>
<!-- EXPERIMENTE -->
<section class="experimente-lp">
    <div class="inner">
        <a class="btn-padrao animate" href="#experimente">Experimente grátis!</a>
    </div>
</section>
<!-- RODAPE -->
<section class="rodape">
    <div class="inner">
        <div class="col-rodape-lp col-rodape-lp-1">
            <img class="logo-lp-rodape" src="<?php echo bloginfo('template_directory');?>/img/mathema-online-rodape.svg" alt="Mathema Online">
        </div>
        <div class="col-rodape-lp col-rodape-lp-2">
            <ul class="menu-rodape-lp">
                <li><a class="animate" href="#topo">Início</a></li>
                <li><a class="animate" href="#beneficios">Benefícios</a></li>
                <li><a class="animate" href="#como-funciona">Como funciona</a></li>                             
                <li><a class="animate" href="#depoimentos">Depoimentos</a></li>
                <li><a class="animate" href="#experimente">Cursos gratuitos</a></li>                   
            </ul>
        </div>
        <div class="col-rodape-lp col-rodape-lp-3">
            <div class="social-rodape-lp">
                <a class="icone-social-lp" href="https://www.linkedin.com/company/grupomathema" target="_blank"><img src="<?php echo bloginfo('template_directory');?>/img/linkedin-lp.svg" alt="Linkedin"></a>                
                <a class="icone-social-lp" href="https://www.facebook.com/grupomathema/" target="_blank"><img src="<?php echo bloginfo('template_directory');?>/img/facebook-lp.svg" alt="Facebook"></a>
                <a class="icone-social-lp" href="https://www.instagram.com/grupomathema/" target="_blank"><img src="<?php echo bloginfo('template_directory');?>/img/instagram-lp.svg" alt="Instagram"></a>                
            </div>
            <p class="contato-rodape-lp">Entre em contato pelo endereço<br><a href="mailto:contato@mathema.com.br" target="_blank"><strong>contato@mathema.com.br</strong></a>
            </p>
        </div>
        <div class="clearfix"></div> 
    </div>
</section>
<!-- OWL CAROUSEL -->
<script>
$(document).ready(function() {
    $('#busca').remove();
    $('header').remove();
    $('footer').remove();
    $('body').addClass('lpmo');
    if( $(window).width() < 769){
        $('.banner-lp-img').css('height', $(window).height() - $('.banner-lp-info').outerHeight());
        $('.bg-formato-lp').remove();        
    }
})
</script>
<!--SHRINK MENU-->
<script type="text/javascript">
$( document ).on("scroll", function() {  
    if($( document ).scrollTop() > 160) {  
        $("#topo-lp").removeClass("topo-lp-e").addClass("topo-lp-v");                    
    } else {
        $("#topo-lp").removeClass("topo-lp-v").addClass("topo-lp-e");          
    } 
});
</script>
<!--CONTROLA MENU, BUSCA -->
<script type="text/javascript">
$(document).ready(function(){
    //abertura e fechamento do menu   
    $(".btn-menu-lp").click(function(){            
        $("#menu-lp").css("left", 0);
        $('body').css('overflow', 'hidden');           
    });
    $("#btn-fecha-menu-lp").click(function(){       
        $("#menu-lp").css("left", "100vw");
        $('body').css("overflow", "auto");   					
    });
    $("#menu-lp li a").click(function(){       
        $("#menu-lp").css("left", "100vw");
        $('body').css("overflow", "auto");   					
    });      
});
</script>
<!-- OWL CAROUSEL -->
<script>
$(document).ready(function() {   
    var owl4 = $('#owl-historias-ciranda');
    owl4.owlCarousel({
        autoplay: false,
        autoplayHoverPause: true,        
        items: 1,        
        margin: 0,         
        loop: true,
        thumbs: false,
        responsive:{
            0:{           
                nav: false,          
                dots: true
            },
            769:{        
                nav: true,             
                dots: false
            }
        }                
    })   
})
</script>
<!-- FOOTER -->
<?php get_footer(); ?>