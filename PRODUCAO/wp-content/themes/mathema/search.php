<!--HEADER-->
<?php get_header(); ?>
<!-- TOPO BUSCA -->
<section class="resultado-busca-topo">

</section>
<!-- BUSCA -->
<section class="resultado-busca">
    <div class="inner">
        <div class="resultado-busca-col">
            <div class="resultado">
                <p>Você buscou por <?php /* Search Count */ $allsearch = new WP_Query("s=$s&showposts=-1"); $key = wp_specialchars($s, 1); $count = $allsearch->post_count; _e(''); _e('<span class="termo-buscado">"'); echo $key; _e('"</span>'); _e(' e foram<br>encontrados '); echo $count . ' '; _e('resultados'); wp_reset_query(); ?></p>
            </div>
            <!-- LISTA OS RESULTADOS DA BUSCA -->
            <div class="lista-resultados">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="item-busca">
                    <?php get_primary_category(); ?>                    
                    <h4><a href="<?php the_permalink(); ?>"><?php echo limita_titulo(65); ?></a></h4>                           
                    <p><?php echo excerpt('10'); ?></p>   
                    <a class="btn-resultado" href="<?php the_permalink(); ?>">Leia mais...</a>                       
                </div>                    
                <?php endwhile;?>
                <div class="clearfix"></div>
            </div>      
            <?php else : ?>
            <div class="nao_encontrado"><p>Não foi encontrado nenhum conteúdo com a palavra:<br><span class="termo-buscado"><?php printf( __( '"%s"'), get_search_query() ); ?></span></p></div>
            <?php endif; ?>
            <div class="paginacao"><?php wordpress_pagination(); ?></div>
        </div>
        <div class="publicidade-busca-col">
            <div class="ad-container">
                <!-- link da imagem -->
                <?php 
                $linkAd = get_field('link_publicidade_na_busca');
                $linkAd_url = $linkAd['url'];	
                $linkAd_target = $linkAd['target'] ? $linkAd['target'] : '_self';
                ?>
                <a href="<?php echo esc_url($linkAd_url); ?>" target="<?php echo esc_attr($linkAd_target); ?>">
                    <?php echo wp_get_attachment_image( get_sub_field('imagem_publicidade_na_busca' ), 'ad-busca' ); ?>
                </a>    
            </div>
        </div> 
        <div class="clearfix"></div>  
    </div>        
</section>
<!-- COLOCA TODAS AS DIVS DA MESMA ALTURA-->
<!-- <script type="text/javascript">
$(document).ready(function(){  
    var maxHeight = 0;
    $(".item-busca").each(function(){
        if ($(this).height() > maxHeight) {
             maxHeight = $(this).height();
        }
    });
    $(".item-busca").height(maxHeight);
})
</script>   -->
<!--FOOTER-->
<?php get_footer();?>