<?php /* Template Name: Na Mídia */ ?>
<?php get_header(); ?>
<!-- HOME -->
<section class="topo-meia-altura" <?php thumbnail_bg('topo'); ?>>
    <div class="inner">
        <!-- BOX PRIMÁRIO -->
        <div class="box-secundario">
            <div class="box-secundario-inner no-breadcrumb">                         
                <h1><?php the_title(); ?></h1>                
            </div>           
        </div>      
    </div>        
</section>
<section class="namidia">
    <div class="inner">
        <div class="namidia-txt">
            <?php the_content(); ?>
        </div>
        <!-- LISTA OS POSTS DESTA CATEGORIA -->        
        <h2>Notícias</h2>                               
        <div class="relatos-ciranda-list">
        <?php
        // WP_Query arguments
        $args = array(
            'post_type'        => array( 'na-midia' ),                   
            'posts_per_page'   => '4',
            'paged'            => $paged,           
        );
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        // The Query
        $the_query = new WP_Query( $args ); 
        // The Loop
        if ( $the_query->have_posts() ) {   
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                ?>         
               <div class="relato-ciranda-item">
                    <div class="relato-ciranda-item-categorias">
                        <?php the_category(); ?>                               
                    </div>
                    <div class="relato-ciranda-item-img">
                        <a href="<?php the_field('link_da_materia'); ?>" target="_blank"><?php the_post_thumbnail( 'blog-relacionada'); ?></a>                                
                    </div>
                    <a class="relato-ciranda-item-link" href="<?php the_field('link_da_materia'); ?>" target="_blank"><h4><?php the_title(); ?></h4></a>
                    <span class="fonte-da-materia"><?php the_field('fonte_materia'); ?></span>                   
                    <div class="data">
                        <?php
                            //traduz o mês para portguês                       
                            $mes_extenso = array(
                                'Jan' => 'Janeiro',
                                'Feb' => 'Fevereiro',
                                'Mar' => 'Março',
                                'Apr' => 'Abril',
                                'May' => 'Maio',
                                'Jun' => 'Junho',
                                'Jul' => 'Julho',
                                'Aug' => 'Agosto',			 
                                'Sep' => 'Setembro',
                                'Oct' => 'Outubro',
                                'Nov' => 'Novembro',
                                'Dec' => 'Dezembro'
                            );			 	
                            //pega o valor no campo
                            $dataMateria = get_field('data_da_materia');
                            //formata a data para x. 23 de maio de 2019
                            $date = DateTime::createFromFormat('Ymd', $dataMateria);
                            $dia = $date->format('d');
                            $mes = $date->format('m');			 
                            $ano = $date->format('Y');                        
                            $mesextenso = $date->format('M');
                            //exibe a data formatada
                            echo $dia . " de " . $mes_extenso["$mesextenso"] . " de " . $ano;                           
                        ?>                
                    </div>
                </div>
                <?php 
                }   
            } 
            /* Restore original Post Data */
            wp_reset_postdata();
            ?>
            <!-- PAGINAÇÃO -->
            <?php wordpress_pagination(); ?>       
        </div>
        
        
        
        
         <!-- LISTA OS DOWNLOADS -->        
         <h2>Downloads</h2>                                       
        <div class="download-midia-list">
            <!-- loop que carrega os downloads -->
            <?php if( have_rows('materiais_para_download') ): while ( have_rows('materiais_para_download') ) : the_row(); ?>
                <!-- ITEM -->
                <div class="download-midia-item">
                    <?php echo wp_get_attachment_image( get_sub_field('imagem_download_midia' ), 'download', '', array( 'class' => 'download-midia-img' ) ); ?>                        
                    <div class="download-midia-titulo">
                        <a href="<?php the_sub_field('arquivo_download_midia'); ?>" target="_blank"><span><?php the_sub_field('titulo_download_midia'); ?></span></a>
                    </div>                   
                </div>
            <?php endwhile; endif; ?>           
        </div>
        
        
    </div>
</section>
<!-- FOOTER -->
<?php get_footer(); ?>