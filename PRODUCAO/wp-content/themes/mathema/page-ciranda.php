<?php /* Template Name: Ciranda */ ?>
<?php get_header(); ?>
<!-- CIRANDA -->
<section class="ciranda">
    <div class="inner">
        <!-- BOX PRIMÁRIO -->
        <div class="box-primario">
            <div class="box-primario-inner">
                <h1><img src="<?php the_field('imagem_primario'); ?>" alt="<?php the_field('titulo_primario'); ?>"></h1>
                <p><?php the_field('texto_primario'); ?></p>
                <?php if (get_field('rotulo_botao_primario')) : ?>
                <a class="btn-padrao animate" href="#quero-ciranda"><?php the_field('rotulo_botao_primario'); ?></a>
                <?php endif; ?>
            </div>
            <a class="btn-rola-tela animate" href="#porque"><img
                    src="<?php echo bloginfo('template_directory'); ?>/img/seta-baixo.png" alt=""></a>
        </div>
        <!-- ELEMENTOS CIRANDA -->
        <img class="ciranda-flor" src="<?php echo bloginfo('template_directory'); ?>/img/ciranda-flor.png" alt="">
        <img class="elemento-ciranda-1" src="<?php echo bloginfo('template_directory'); ?>/img/ciranda-elemento-1.png"
            alt="">
        <img class="elemento-ciranda-2" src="<?php echo bloginfo('template_directory'); ?>/img/ciranda-elemento-2.png"
            alt="">
        <img class="elemento-ciranda-3" src="<?php echo bloginfo('template_directory'); ?>/img/ciranda-elemento-3.png"
            alt="">
        <img class="elemento-ciranda-4" src="<?php echo bloginfo('template_directory'); ?>/img/ciranda-elemento-4.png"
            alt="">
    </div>
</section>
<!-- PORQUE -->
<section id="porque" class="porque porque-ciranda">
    <div class="inner">
        <?php if (get_field('titulo_por_que_o_mathema')) : ?>
        <h1><?php the_field('titulo_por_que_o_mathema'); ?></h1>
        <?php endif; ?>
        <div class="list-porque">
            <?php
            if (have_rows('icones_por_que_o_mathema')) :
                $i = 0;
                while (have_rows('icones_por_que_o_mathema')) :
                    $i++;
                    the_row(); ?>
            <div id="porque-item-<?php echo $i; ?>" class="porque-item"><img
                    src="<?php the_sub_field('icone_por_que_o_mathema'); ?>"
                    alt="<?php the_sub_field('texto_por_que_o_mathema'); ?>"><span><?php the_sub_field('texto_por_que_o_mathema'); ?></span>
            </div>
            <?php endwhile;
            endif; ?>
        </div>
    </div>
</section>
<!-- VÍDEO QUERO CIRANDA -->
<?php if (get_field('video_unico')) : ?>
<section id="video-quero-ciranda" class="video-quero-ciranda">
    <div class="inner">
        <div class="video"><?php the_field('video_unico'); ?></iframe></div>
        <a class="btn-padrao animate" href="#quero-ciranda"><?php the_field('rotulo_botao_primario'); ?></a>
    </div>
    <img id="elemento-5" class="elemento-ciranda-5"
        src="<?php echo bloginfo('template_directory'); ?>/img/ciranda-elemento-5.png" alt="">
    <img id="flor" class="ciranda-flor-1" src="<?php echo bloginfo('template_directory'); ?>/img/ciranda-flor.png"
        alt="">
</section>
<?php endif; ?>
<!-- ABAS VERTICAIS -->
<section class="download-ciranda">
    <div class="inner">
        <!-- ABAS -->
        <div id="abas-vertical">
            <!-- PAINEIS -->
            <!-- link do botão -->
            <?php
            $linkBotaoAbaVertical = get_field('link_do_botao_aba_vertical');
            $linkBotaoAbaVertical_url = $linkBotaoAbaVertical['url'];
            $linkBotaoAbaVertical_target = $linkBotaoAbaVertical['target'] ? $linkBotaoAbaVertical['target'] : '_self';
            ?>
            <!-- loop que carrega os paineis -->
            <?php
            if (have_rows('abas_vertical')) :
                $a = 0;
                $b = 0;
                while (have_rows('abas_vertical')) :
                    $a++;
                    the_row(); ?>
            <div id="tab-<?php echo $a; ?>">
                <div class="download-ciranda-txt">
                    <div>
                        <h1><?php the_sub_field('titulo_aba_vertical'); ?></h1>
                        <?php the_sub_field('texto_aba_vertical'); ?>
                    </div>
                </div>
                <?php echo wp_get_attachment_image(get_sub_field('imagem_aba_vertical'), 'img-tab-ciranda'); ?>
            </div>
            <?php endwhile; ?>
            <!-- BOTÃO -->
            <a class="btn-padrao" href="<?php echo esc_url($linkBotaoAbaVertical_url); ?>"
                target="<?php echo esc_attr($linkBotaoAbaVertical_target); ?>"><?php the_field('rotulo_do_botao_aba_vertical'); ?></a>
            <!-- MENU ABAS -->
            <ul>
                <!-- loop que carrega os paineis -->
                <?php while (have_rows('abas_vertical')) :
                            $b++;
                            the_row(); ?>
                <li><a href="#tab-<?php echo $b; ?>"><?php the_sub_field('titulo_aba_vertical'); ?></a></li>
                <?php endwhile; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- RESULTADOS -->
<section class="resultados-ciranda">
    <div class="inner">
        <div class="resultados-ciranda-txt">
            <h1><?php the_field('titulo_resultados'); ?></h1>
            <p><?php the_field('texto_resultados'); ?></p>
        </div>
        <!-- SLIDE -->
        <div class="resultados-ciranda-slide owl-emenda">
            <div id="owl-resultados-ciranda" class="owl-carousel owl-theme">
                <!-- loop que carrega os resultados -->
                <?php if (have_rows('itens_resultados')) : while (have_rows('itens_resultados')) : the_row(); ?>
                <!-- ITEM -->
                <div class="resultados-ciranda-item">
                    <div class="resultados-ciranda-item-topo">
                        <div class="resultados-ciranda-item-titulo">
                            <h6><?php the_sub_field('titulo_item_resultado'); ?></h6>
                            <span class="subtitulo"><?php the_sub_field('subtitulo_item_resultado'); ?></span>
                        </div>
                        <div class="resultados-ciranda-item-info">
                            <p><?php the_sub_field('resumo_item_resultado'); ?></p>
                        </div>
                    </div>
                    <?php echo wp_get_attachment_image(get_sub_field('imagem_grafico_item_resultado'), 'resultados', '', array('class' => 'resultados-ciranda-item-img')); ?>
                </div>
                <?php endwhile;
                endif; ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<!-- HISTORIAS CIRANDA -->
<section class="historias-ciranda">
    <div class="inner">
        <h1><?php the_field('titulo_depoimentos'); ?></h1>
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
        <img class="historias-ciranda-casa" src="<?php echo bloginfo('template_directory'); ?>/img/casa-historias.png"
            alt="">
    </div>
</section>
<!-- HISTÓRIAS CIRANDA VÍDEO -->
<?php if (have_rows('videos_galeria_video')) : ?>
<section class="minuto-home historias-ciranda-video">
    <div class="inner">
        <div class="minuto-home-conteudo">
            <h1><?php the_field('titulo_galeria_video'); ?></h1>
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
        <img class="historias-video-elemento"
            src="<?php echo bloginfo('template_directory'); ?>/img/ciranda-elemento-6.png" alt="">
    </div>
</section>
<?php endif; ?>
<!-- PARCEIROS CIRANDA -->
<section class="parceiros-ciranda">
    <div class="inner">
        <h1><?php the_field('titulo_parceiros'); ?></h1>
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
<!-- QUERO CIRANDA -->
<section id="quero-ciranda" class="quero-ciranda">
    <div class="inner">
        <div class="quero-ciranda-box">
            <h1><?php the_field('titulo_formulario_solicitacao'); ?></h1>
            <?php if (get_field('texto_formulario_solicitacao')) : ?>
            <p><?php the_field('texto_formulario_solicitacao'); ?></p>
            <?php endif; ?>
            <?php echo do_shortcode(get_field('shortcode_do_formulario')); ?>
        </div>
        <img class="quero-ciranda-elemento-1"
            src="<?php echo bloginfo('template_directory'); ?>/img/quero-ciranda-elemento-1.png" alt="">
        <img class="quero-ciranda-elemento-2"
            src="<?php echo bloginfo('template_directory'); ?>/img/quero-ciranda-elemento-2.png" alt="">
        <img class="quero-ciranda-elemento-3"
            src="<?php echo bloginfo('template_directory'); ?>/img/quero-ciranda-elemento-3.png" alt="">
    </div>
</section>
<!-- RELATOS CIRANDA -->
<section class="relatos-ciranda">
    <div class="inner">
        <h1><?php the_field('titulo_3_posts'); ?></h1>
        <!-- PEGA A CATEGORIA -->
        <?php $cat3Posts = get_field('categoria_3_posts'); ?>
        <div class="relatos-ciranda-list">
            <?php
            // WP_Query arguments
            $argsPost = array(
                'post_type'        => array('post'),
                'cat'              => $cat3Posts,
                'posts_per_page'   => '3'
            );
            // The Query
            $the_query = new WP_Query($argsPost);
            // The Loop
            if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post(); ?>

            <!-- ITEM -->
            <div class="relato-ciranda-item">
                <div class="relato-ciranda-item-categorias">
                    <?php the_category(); ?>
                    <!-- <a style="background-color: #FA8469" href="">Nome da editoria</a>
                    <a style="background-color: #DF4661" href="">Site</a>
                    <a style="background-color: #67A6C9" href="">Educação</a> -->
                </div>
                <div class="relato-ciranda-item-img">
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-relacionada'); ?></a>
                </div>
                <a class="relato-ciranda-item-link" href="<?php the_permalink(); ?>">
                    <h3><?php the_title(); ?></h3>
                </a>
                <div class="data"><?php the_time('j \d\e F \d\e Y') ?></div>
            </div>

            <?php endwhile;
            endif; ?>
            <?php
            /* Restore original Post Data */
            wp_reset_postdata();
            ?>
        </div>
        <a href="<?php echo esc_url(get_category_link($cat3Posts)); ?>"
            class="btn-padrao"><?php the_field('rotulo_do_botao_3_posts'); ?></a>
    </div>
</section>
<!-- ANIMAÇÕES DA CIRANDA -->
<script>
$(window).scroll(function() {
    if ($(window).width() > 768) {
        var startVideo = $('#video-quero-ciranda').offset().top - (window.innerHeight / 2);
        if ($(window).scrollTop() > startVideo) {
            $('#flor').animate({
                left: "50%",
                bottom: "-75px"
            }, 1000, "swing");
        }
    }
})
</script>
<!-- RESPONSIVE TABS -->
<script type="text/javascript">
$(document).ready(function() {
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
    var owl3 = $('#owl-resultados-ciranda');
    owl3.owlCarousel({
        autoplay: false,
        rewind: true,
        items: 1,
        margin: 0,
        dots: true,
        loop: false,
        thumbs: false,
        responsive: {
            0: {
                nav: false
            },
            769: {
                nav: true
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

$(".wpcf7-submit").click(function() {
    const basepathAPI = "https://mathema.com.br/api/v1";

    // console.log("Nome: " + $("#name").val());
    // console.log("Email: " + $("#email").val());
    // console.log("Atuacao: " + $("select[name=atuacao]").val());
    // console.log("instituicao: " + $("input[name=instituicao]").val());
    // console.log("Telefone: " + $("input[name=telefone]").val());
    // console.log("n alunos: " + $("select[name=numero-alunos]").val());
    // console.log("Cidade: " + $("input[name=cidade]").val());
    // console.log("Estado: " + $("select[name=estado]").val());

    var form = new FormData();
    form.append("list_id", "f3397d3993");
    form.append("status", "subscribed");
    form.append("email_address", $("#email").val());
    form.append("merge_fields[NOME]", $("#name").val());
    form.append("merge_fields[ATUACAO]", $("select[name=atuacao]").val());
    form.append("merge_fields[INSTITUICA]", $("input[name=instituicao]").val());
    form.append("merge_fields[ATUACAO]", $("select[name=atuacao]").val());
    form.append("merge_fields[CELULAR]", $("input[name=telefone]").val());
    form.append("merge_fields[N_ALUNOS]", $("select[name=numero-alunos]").val());
    form.append("merge_fields[CIDADE]", $("input[name=cidade]").val());
    form.append("merge_fields[ESTADO]", $("select[name=estado]").val());
    form.append("tags[]", ["Ciranda"]);

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
<!-- FOOTER -->
<?php get_footer(); ?>