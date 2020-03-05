<?php /* Template Name: Ciranda Calendário */ ?>
<!--HEADER-->
<?php get_header(); ?>
<!--LOOP WOOCOMMERCE-->
<section class="conteudo-woo">
    <div class="topo-woo"></div> 
    <div class="inner">    

    <?php do_action( 'woocommerce_account_navigation' ); ?>

    <div class="woocommerce-MyAccount-content">

        <?php include 'header-ciranda.php' ?>

        <?php include 'menu-minha-conta.php' ?>       

        <div class="woocommerce-MyAccount-content-inner">

            <div class="ciranda-interna-content">
                <?php if (have_posts()) : while (have_posts()): the_post(); ?>
                <h3><?php the_field('titulo_exibicao'); ?></h3>       
                <?php the_content(); ?>               
                <?php endwhile; endif;?>
                <?php echo do_shortcode( '[add_eventon_fc]' ) ?>
                <a class="agenda-anual" href="<?php the_field('link_arquivo_calendario_anual'); ?>" target="_blank"><?php the_field('rotulo_botao_calendario_anual'); ?></a>
            </div>

        </div>

        <div class="clearfix"></div>

    </div>

    
    </div>
</section>
<!-- SCRIPTS -->
<script>
$(document).ready(function() {
    /* altera a altura do meu da página minha conta */
    if( $(window).width() > 768 ){     
        $('.woocommerce-MyAccount-menu').css('min-height', $('.woocommerce-MyAccount-content-inner').outerHeight());  
    }

    /* abertura e fechamento do menu do perfil */
    if( $(window).width() < 769 ){        
        var perfilClicado = 0;   
        $("#btn-perfil").click(function(){            
            if(perfilClicado==0){            
                $("#menu-perfil").css("right", "-50px");
                perfilClicado = 1;
            }else{
                $("#menu-perfil").css("right", "-220px");
                perfilClicado = 0; 
            }        	
        });
    }
})
</script>

<!--FOOTER-->
<?php get_footer();?>
