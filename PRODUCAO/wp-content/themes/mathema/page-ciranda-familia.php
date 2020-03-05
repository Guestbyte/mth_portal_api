<?php /* Template Name: Ciranda Pais */ ?>
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
                $images_per_page  = 8; // How many images to display on each page
                $images           = get_field( 'atividades_ciranda' );
                $total            = count( $images );
                $pages            = ceil( $total / $images_per_page );
                $min              = ( ( $page * $images_per_page ) - $images_per_page ) + 1;
                $max              = ( $min + $images_per_page ) - 1;

                // ACF Loop
                if( have_rows( 'atividades_ciranda' ) ) : ?>

                <?php while( have_rows( 'atividades_ciranda' ) ): the_row();

                    $row++;

                    // Ignore this image if $row is lower than $min
                    if($row < $min) { continue; }

                    // Stop loop completely if $row is higher than $max
                    if($row > $max) { break; } ?>
                    
                        <div class="item-atividade">
                            <?php if(get_sub_field('link_para_arquivos_da_atividade')): ?>
                            <a href="<?php the_sub_field('link_para_arquivos_da_atividade'); ?>" target="_blank">
                            <?php else: ?>
                            <a href="<?php the_sub_field('arquivo_da_atividade'); ?>" target="_blank">
                            <?php endif; ?>
                                <?php if(get_sub_field('imagem_da_atividade')): ?>
                                <?php echo wp_get_attachment_image( get_sub_field('imagem_da_atividade'), 'atividade-ciranda', "", array( "class" => "img-atividade" ) ); ?>
                                <?php else: ?>
                                <div class="item-atividade-icone"></div>
                                <?php endif; ?>
                                <h6><?php the_sub_field('titulo_da_atividade'); ?></h6>
                            </a>
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
