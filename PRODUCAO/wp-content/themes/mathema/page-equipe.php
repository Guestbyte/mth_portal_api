<?php /* Template Name: Equipe */ ?>
<?php get_header(); ?>
<!-- HOME -->
<section class="topo-meia-altura" <?php thumbnail_bg('topo'); ?>>
    <div class="inner">
        <!-- BOX PRIMÁRIO -->
        <div class="box-secundario">
            <div class="box-secundario-inner">
                <!-- breadcrumb -->
                <div class="breadcrumbs">
                    <span><a href="<?php echo bloginfo('url'); ?>"><span>Home</span></a></span>
                    <span class="separador-breadcrumb"> › </span>
                    <span><a href="<?php echo bloginfo('url'); ?>/o-grupo-mathema/o-grupo/"><span>O Grupo Mathema</span></a></span>
                    <span class="separador-breadcrumb"> › </span>
                    <span class="current-item"><?php the_title(); ?></span>                
                </div>           
                <h1><?php the_field('titulo_exibicao'); ?></h1>                
            </div>           
        </div>      
    </div>        
</section>
<section class="equipe">
    <div class="inner">
    <!-- LISTA TODOS OS MEMBROS DA EQUIPE -->
    <?php    
    //PEGA TODOS OS TERMOS DA TAXONOMIA
    $terms = get_terms( 'area', array(
        'orderby'    => 'ID',
        'hide_empty' => 0
    ) );
    // LOOP EM TODOS OS TERMOS DA TAXONOMIA
    foreach( $terms as $term ) { 
        // A QUERY
        $args = array(
            'post_type'      => 'membro',
            'area'           => $term->slug,
            'posts_per_page' => '-1',
            'orderby'        => 'menu_order'                       
        );
        $query = new WP_Query( $args );        
        ?>
        <!-- LISTA TODOS OS MEMBROS, SEPARADOS PELO TERMO -->
        <div class="lista-membros">
        <!--  EXIBE O TÍTULO DO TERMO-->
        <div class="lista-membros-titulo">
            <h3><?php echo $term->name; ?></h3>
        </div>      
        <?php
            // Start the Loop
            while ( $query->have_posts() ) : $query->the_post(); ?>    
            <div class="membro">            
                <a class="membro-img" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'autor-imagem' ); ?></a>
                <h6><?php the_title(); ?></h6>
                <span class="cargo"><?php the_field('cargo_membro_equipe'); ?></span> 
            </div>            
            <?php endwhile;        
        ?>
        </div>
        <?php        
        // use reset postdata to restore orginal query
        wp_reset_postdata();
    }
    ?>    
    </div>
</section>
<!-- FOOTER -->
<?php get_footer(); ?>