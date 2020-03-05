<!--HEADER-->
<?php get_header(); ?>
<!-- CONTEÚDO WOOCOMMERCE -->
<section class="conteudo-woo">
<div class="topo-woo"></div>
<!-- MENU LOJA -->
<nav class="menu-loja">
    <?php wp_nav_menu( array('menu' => 'Loja', 'container' => 'ul')); ?>            
</nav>
<!-- CONTEÚDO DO WOOCOMMERCE -->
<?php woocommerce_content(); ?>
<!-- FINAL CONTEÚDO DO WOOCOMMERCE -->
</section>
<!-- SCRIPTS -->
<!-- DIVS DA MESMA ALTURA -->
<script>
$.fn.equalHeights = function(){
	var max_height = 0;
	$(this).each(function(){
		max_height = Math.max($(this).height(), max_height);
	});
	$(this).each(function(){
		$(this).height(max_height);
	});
};

$(document).ready(function(){
    if( $(window).width() > 768 ){
        $('.plano-item-3').equalHeights();
    }
});
</script>
<script>
 $(document).ready(function(){
    //SELECT DE CATEGORIAS
    $('#select-categorias').on('change', function () {
         var url = $(this).val(); 
         if (url) { 
             window.open(url, '_self');
          }
          return false;
        });
     });
     //FORMULÁRIO MODALIDADE
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
    var $accordion = $('#abas-accordion');
    $accordion.responsiveTabs({            
        startCollapsed: 'accordion',
        collapsible: 'accordion',            
    });    
});
</script>   
<!-- OWL CAROUSEL -->
<script>
$(document).ready(function() {
    var owl1 = $('#owl-box-cat');
    owl1.owlCarousel({
        autoplay: false, 
        autoplayHoverPause: true,     
        items: 1,
        margin: 0,
        nav: true,        
        loop: true,
        thumbs: false,
        responsive:{
            0:{
                dots: false,
            },
            769:{
                dots: true,
            }
        }               
    })
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
    var owl6 = $('#owl-galeria-imagens');
    owl6.owlCarousel({
        autoplay: false, 
        autoplayHoverPause: true,     
        items: 1,
        margin: 0,
        nav: true,
        dots: false,
        loop: true,
        thumbs: false,         
    })      
})
</script>
<script type="text/javascript">
//faz os cálculos após o carregamento da página
$(window).load(function(){
    //FIXA BOX PREÇO NA ROLAGEM
    var $w = $(window);
    var $alturaDescricao = $('.product-single-description').outerHeight();
    var alturaImagem = $('.woocommerce-product-gallery').outerHeight();  
    var alturaTopoProduto = $('.product-single-top').outerHeight();
    var $fixaRolagem;
    if( $(window).width() > 768 ){
        $fixaRolagem = $alturaDescricao + 422;
        $w.on("scroll", function(){
            if( $w.scrollTop() > $fixaRolagem ) {           
                $('#boxAddToCart').css({'position': 'fixed', 'top': 80});
                $('#productInfos').css({'margin-top': 105});
            }else{
                $('#boxAddToCart').css({'position': 'relative','top': 0});
                $('#productInfos').css({'margin-top': 0});
            }
        });
    }else{
        $fixaRolagem = alturaImagem + alturaTopoProduto + $alturaDescricao + 125;
        console.log('imagem ' + alturaImagem );
        $w.on("scroll", function(){
            if( $w.scrollTop() > $fixaRolagem ) {           
                $('#boxAddToCart').css({'position': 'fixed', 'top': 58, 'left': 0});
                $('#productInfos').css({'margin-top': 88});
            }else{
                $('#boxAddToCart').css({'position': 'relative','top': 0});
                $('#productInfos').css({'margin-top': 0});
            }
        });
    }
});
</script>   
<!--FOOTER-->
<?php get_footer();?>
