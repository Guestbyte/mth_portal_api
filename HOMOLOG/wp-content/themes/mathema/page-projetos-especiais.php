<?php /* Template Name: Projetos Especiais */ ?>
<?php get_header(); ?>
<!-- TOPO -->
<section class="topo-meia-altura" <?php thumbnail_bg('topo'); ?>>
    <div class="inner">
        <!-- BOX PRIMÝRIO -->
        <div class="box-primario">
            <div class="box-primario-inner">
                <h1><?php the_field('titulo_primario'); ?></h1>
                <p><?php the_field('texto_primario'); ?></p>
                <?php if (get_field('rotulo_botao_primario')) : ?>
                <a class="btn-padrao animate" href="#quero-ciranda"><?php the_field('rotulo_botao_primario'); ?></a>
                <?php endif; ?>
            </div>
            <img class="folha-projetos" src="<?php echo bloginfo('template_directory'); ?>/img/folha-grande.png" alt="">
        </div>
    </div>
</section>
<section class="namidia">
    <div class="inner">
        <div class="list-projetos-especiais">
            <?php
            // WP_Query arguments
            $args = array(
                'post_type'        => array('projeto-especial'),
                'posts_per_page'   => '-1'
            );
            // The Query
            $the_query = new WP_Query($args);
            // The Loop
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
            ?>
            <div class="projeto-especial-item">
                <a href="<?php the_permalink(); ?>">
                    <div class="projeto-especial-item-titulo">
                        <span><?php the_title(); ?></span>
                    </div>
                    <?php the_post_thumbnail('projeto-especial'); ?>
                </a>
            </div>
            <?php
                }
            }
            /* Restore original Post Data */
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
<?php if (have_rows('videos_galeria_video')) : ?>
<!-- HISTÓRIAS CIRANDA VÝDEO -->
<section class="minuto-home historias-ciranda-video">
    <div class="inner">
        <div class="minuto-home-conteudo">
            <h2><?php the_field('titulo_galeria_video'); ?></h2>
            <div class="minuto-home-txt">
                <p><?php the_field('texto_galeria_video'); ?></p>
            </div>
            <!-- ACF Link -->
            <?php if (get_field('rotulo_botao_galeria_video')) : ?>
            <?php
                    $linkGaleria = get_field('destino_do_botao_galeria_video');
                    $linkGaleria_url = $linkGaleria['url'];
                    $linkGaleria_target = $linkGaleria['target'] ? $linkGaleria['target'] : '_self';
                    ?>
            <a class="btn-padrao" href="<?php echo esc_url($linkGaleria_url); ?>"
                target="<?php echo esc_attr($linkGaleria_target); ?>"><?php the_field('rotulo_botao_galeria_video'); ?></a>
            <?php endif; ?>
        </div>
        <div class="minuto-home-slide">
            <?php if (have_rows('videos_galeria_video')) : ?>
            <div class="container-video owl-emenda">
                <div id="owl-minuto" class="owl-carousel owl-theme" data-slider-id="1">
                    <!-- primeiro loop slides -->
                    <?php while (have_rows('videos_galeria_video')) : the_row(); ?>
                    <div class="minuto-home-video">
                        <div class="video"><?php the_sub_field('codigo_do_video_galeria_video'); ?></div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="owl-thumbs" data-slider-id="1">
                <!-- segundo loop thumbs -->
                <?php while (have_rows('videos_galeria_video')) : the_row(); ?>
                <button class="owl-thumb-item">
                    <img src="<?php the_sub_field('capa_do_video_galeria_video'); ?>" alt="">
                </button>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<?php endif; ?>
<!-- CONTRATAR MODALIDADE -->
<section class="contratar-modalidade quero-ciranda">
    <div class="inner">
        <?php /** <button id="btn-form-modalidade" class="btn-vagas"><span><?php the_field('rotulo_do_botao_formulario_modalidade'); ?></span></button>
        */ ?>
        <div id="form-modalidade" class="quero-ciranda-box">
            <h2><?php the_field('titulo_formulario_modalidade'); ?></h2>
            <?php if (get_field('texto_formulario_modalidade')) : ?>
            <p class="quero-ciranda-txt"><?php the_field('texto_formulario_modalidade'); ?></p>
            <?php endif; ?>
            <?php echo do_shortcode(get_field('shortcode_do_formulario_modalidade')); ?>
        </div>
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
                <?php if (have_rows('itens_depoimentos')) : while (have_rows('itens_depoimentos')) : the_row(); ?>
                <!-- ITEM -->
                <div class="historias-ciranda-item">
                    <div class="historias-ciranda-item-autor">
                        <?php echo wp_get_attachment_image(get_sub_field('imagem_depoimento'), 'autor-imagem', '', array('class' => 'historias-ciranda-item-img')); ?>
                        <h6><?php the_sub_field('autor_depoimento'); ?></h6>
                        <span class="subtitulo"><?php the_sub_field('cargo_depoimento'); ?></span>
                    </div>
                    <div class="historias-ciranda-item-info">
                        <p><?php the_sub_field('depoimento_depoimento'); ?></p>
                    </div>
                </div>
                <?php endwhile;
                endif; ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <img class="historias-ciranda-casa"
            src="<?php echo bloginfo('template_directory'); ?>/img/folha-depoimentos.png" alt="">
    </div>
</section>
<!-- PARCEIROS -->
<section class="parceiros-ciranda">
    <div class="inner">
        <h2><?php the_field('titulo_parceiros'); ?></h2>
        <div class="parceiros-ciranda-slide owl-emenda">
            <div id="owl-parceiros-ciranda" class="owl-carousel owl-theme">
                <!-- loop que carrega os logos dos parceiros -->
                <?php if (have_rows('parceiros_itens')) : while (have_rows('parceiros_itens')) : the_row(); ?>
                <!-- ITEM -->
                <div class="parceiro-ciranda-item"><a href="<?php the_sub_field('link_para_o_site_do_parceiro'); ?>"
                        target="_blank"><?php echo wp_get_attachment_image(get_sub_field('logo_parceiro'), 'logo-parceiro'); ?></a>
                </div>
                <?php endwhile;
                endif; ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<!-- FORMULÝRIO MODALIDADE -->
<script>
$(document).ready(function() {
    //controla se o menu deve abrir ou fechar   
    var clicado = 0;
    //controla a abertura e fechamento dos filtros clicando no botao
    $("#btn-form-modalidade").click(function() {
        if (clicado == 0) {
            $("#form-modalidade").show();
            clicado = 1;
        } else {
            $("#form-modalidade").hide();
            clicado = 0;
        }
    });
});

//JS: Integracao com Mailchimp 
//[25/10/2019 - Fernando]
$(".wpcf7-submit").click(function(event) {
    event.preventDefault();
    const basepathAPI = "https://mathema.com.br/api/homolog";

    console.log("Nome: " + $("#name").val());
    console.log("Email: " + $("#email").val());
    console.log("Atuacao: " + $("select[name=atuacao]").val());
    console.log("Instituicao: " + $("input[name=instituicao]").val());
    console.log("Telefone: " + $("input[name=telefone]").val());
    console.log("numero-alunos: " + $("select[name=numero-alunos]").val());
    console.log("Cidade: " + $("input[name=cidade]").val());
    console.log("Estado: " + $("select[name=estado]").val());

    var form = new FormData();
    form.append("list_id", "f3397d3993");
    form.append("status", "subscribed");
    form.append("email_address", $("#email").val());
    form.append("merge_fields[NOME]", $("#name").val());
    form.append("merge_fields[ATUACAO]", $("select[name=atuacao]").val());
    form.append("merge_fields[INSTITUICA]", $("input[name=instituicao]").val());
    form.append("merge_fields[CELULAR]", $("input[name=telefone]").val());
    form.append("merge_fields[N_ALUNOS]", $("select[name=numero-alunos]").val());
    form.append("merge_fields[CIDADE]", $("input[name=cidade]").val());
    form.append("merge_fields[ESTADO]", $("select[name=estado]").val());
    form.append("tags[]", ["Projetos especiais"]);

    var settings = {
        async: true,
        crossDomain: true,
        url: basepathAPI + "/mailchimp/subscribe/",
        method: "POST",
        processData: false,
        contentType: false,
        mimeType: "multipart/form-data",
        data: form
    };

    $.ajax(settings)
        .done(function(response) {
            console.log("MTH-API: Mailchimp: subscribe done!");
        })
        .fail(function(error) {
            console.log("MTH-API: Mailchimp: error on subscribe:");
            console.log(error.responseText);
        });
});
</script>
<!-- RESPONSIVE TABS -->
<script type="text/javascript">
$(document).ready(function() {

    var $tabs = $('#abas-vertical');
    $tabs.responsiveTabs({
        startCollapsed: 'accordion',
        collapsible: 'accordion',
    });

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
    var owl1 = $('#owl-minuto');
    owl1.owlCarousel({
        items: 1,
        margin: 10,
        nav: false,
        dots: false,
        loop: false,
        thumbs: true,
        thumbsPrerendered: true
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
        responsive: {
            0: {
                dots: false
            },
            769: {
                dots: true
            }
        }
    })
    var owl5 = $('#owl-parceiros-ciranda');
    owl5.owlCarousel({
        autoplay: true,
        margin: 0,
        nav: true,
        dots: true,
        loop: true,
        thumbs: false,
        responsive: {
            0: {
                items: 2,
                slideBy: 2
            },
            769: {
                items: 4,
                slideBy: 4
            }
        }
    })
})
</script>
<!-- FOOTER -->
<?php get_footer(); ?>