<?php /* Template Name: Ciranda Conteúdo Exclusivo */ ?>
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
            </div>
            <div class="ciranda-interna-downloads">

                <?php /* Paginate Advanced Custom Field repeater */

                if( get_query_var('page') ) {
                $page = get_query_var( 'page' );
                } else {
                $page = 1;
                }

                // Variables
                $row              = 0;
                $images_per_page  = 4; // How many images to display on each page
                $images           = get_field( 'conteudo_exclusivo_bimestral' );
                $total            = count( $images );
                $pages            = ceil( $total / $images_per_page );
                $min              = ( ( $page * $images_per_page ) - $images_per_page ) + 1;
                $max              = ( $min + $images_per_page ) - 1;

                // ACF Loop
                if( have_rows( 'conteudo_exclusivo_bimestral' ) ) : ?>

                <?php while( have_rows( 'conteudo_exclusivo_bimestral' ) ): the_row();

                    $row++;

                    // Ignore this image if $row is lower than $min
                    if($row < $min) { continue; }

                    // Stop loop completely if $row is higher than $max
                    if($row > $max) { break; } ?>                    
                    
                    <div class="item-conteudo-exclusivo">
                        <a href="<?php the_sub_field('arquivo_do_conteudo_exclusivo'); ?>" target="_blank">
                            <?php echo wp_get_attachment_image( get_sub_field('imagem_do_conteudo_exclusivo' ), 'imagem-galeria' ); ?>
                        </a>                        
                        <h6><?php the_sub_field('titulo_do_conteudo_exclusivo'); ?></h6>
                        <div class="item-conteudo-exclusivo-inner"><?php the_sub_field('resumo_do_conteudo_exclusivo'); ?></div>                        
                        <a class="btn-padrao" href="<?php the_sub_field('arquivo_do_conteudo_exclusivo'); ?>" target="_blank">Faça o download aqui</a>                        
                    </div>                                  

                <?php endwhile; ?>
                    
                    <div class="clearfix"></div>

                    <div class="paginacao">
                    <?php  // Pagination
                    echo paginate_links( array(
                        'base' => get_permalink() . '%#%' . '/',
                        'format' => '?page=%#%',
                        'current' => $page,
                        'total' => $pages,
                        'prev_next'    => true,
                        'prev_text'    => '‹',          
                        'next_text'    => '›',
                    ) );
                    ?>
                    </div>

                <?php else: ?>

                Não há itens para exibir

                <?php endif; ?>



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
