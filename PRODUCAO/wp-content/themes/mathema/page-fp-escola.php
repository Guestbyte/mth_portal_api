<?php /* Template Name: FP B2B */ ?>
<?php get_header(); ?>
<!-- HOME -->
<section class="home-cards" <?php thumbnail_bg('home'); ?>>
    <div class="inner">
        <!-- BOX PRIMÁRIO -->
        <div class="box-primario">
            <div class="box-primario-inner">
                <h1><?php the_field('titulo_primario'); ?></h1>
                <p><?php the_field('texto_primario'); ?></p>
                <?php if (get_field('rotulo_botao_primario')) : ?>
                <a class="btn-padrao animate" href="#quero-ciranda"><?php the_field('rotulo_botao_primario'); ?></a>
                <?php endif; ?>
            </div>
            <a class="btn-rola-tela animate" href="#porque"><img
                    src="<?php echo bloginfo('template_directory'); ?>/img/seta-baixo.png" alt=""></a>
        </div>
    </div>
</section>
<!-- PORQUE -->
<section id="porque" class="porque">
    <div class="inner">
        <?php if (get_field('titulo_por_que_o_mathema')) : ?>
        <h2><?php the_field('titulo_por_que_o_mathema'); ?></h2>
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
<!-- ABAS VERTICAIS -->
<section class="download-ciranda">
    <div class="inner">
        <!-- ABAS -->
        <div id="abas-vertical">
            <!-- PAINEIS -->
            <!-- loop que carrega os paineis -->
            <?php
            if (have_rows('abas_vertical_formacao_presencial_b2b')) :
                $a = 0;
                $b = 0;
                while (have_rows('abas_vertical_formacao_presencial_b2b')) :
                    $a++;
                    the_row(); ?>
            <div id="tab-<?php echo $a; ?>">
                <div class="download-ciranda-txt">
                    <h2><?php the_sub_field('titulo_formacao_presencial_b2b'); ?></h2>
                    <?php the_sub_field('texto_formacao_presencial_b2b'); ?>
                </div>
                <?php echo wp_get_attachment_image(get_sub_field('imagem_formacao_presencial_b2b'), 'img-tab-ciranda'); ?>


                <!-- ABAS ACCORDEON -->
                <?php if (have_rows('abas_temas')) :  ?>
                <div class="abas-accordion">
                    <h2 class="temas-titulo">Conheça alguns temas disponíveis:</h2>
                    <!-- loop que carrega os accordeons -->
                    <?php
                                $c = 0;
                                while (have_rows('abas_temas')) :
                                    $c++;
                                    the_row(); ?>
                    <div class="aba-accordion-item">
                        <input type="radio" id="rd-<?php echo $a; ?>-<?php echo $c; ?>" name="rd">
                        <label class="aba-accordion-item-label"
                            for="rd-<?php echo $a; ?>-<?php echo $c; ?>"><?php the_sub_field('titulo_aba_tema'); ?><img
                                class="seta-tab"
                                src="<?php echo bloginfo('template_directory'); ?>/img/seta-baixo-tab.png"
                                alt=""></label>
                        <div class="aba-accordion-conteudo">
                            <div class="aba-accordion-conteudo-info">
                                <div class="aba-accordion-conteudo-info-item"><span>Duração:</span>
                                    <?php the_sub_field('duracao_aba_tema'); ?></div>
                                <div class="aba-accordion-conteudo-info-item"><span>Local:</span>
                                    <?php the_sub_field('local_aba_tema'); ?></div>
                            </div>
                            <div class="aba-accordion-conteudo-txt">
                                <h4><?php the_sub_field('subtitulo_aba_tema'); ?></h4>
                                <?php the_sub_field('texto_aba_tema'); ?>
                            </div>
                            <?php echo wp_get_attachment_image(get_sub_field('imagem_aba_tema'), 'aba-accordion'); ?>
                            <?php if (get_sub_field('legenda_da_imagem_aba_tema')) :  ?>
                            <div class="aba-accordion-img-legenda">
                                <p><?php the_sub_field('legenda_da_imagem_aba_tema'); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <div class="aba-accordion-item">
                        <input type="radio" id="rd-fecha-todas" name="rd">
                        <label class="aba-accordion-item-label" for="rd-fecha-todas">Fechar todas</label>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
            <!-- MENU ABAS -->
            <ul>
                <!-- loop que carrega as abas -->
                <?php while (have_rows('abas_vertical_formacao_presencial_b2b')) :
                        $b++;
                        the_row(); ?>
                <li><a href="#tab-<?php echo $b; ?>"><?php the_sub_field('titulo_formacao_presencial_b2b'); ?></a></li>
                <?php endwhile; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- CONTRATAR MODALIDADE -->
<section id="quero-ciranda" class="contratar-modalidade quero-ciranda">
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
        <img class="historias-ciranda-casa" src="<?php echo bloginfo('template_directory'); ?>/img/gota.png" alt="">
    </div>
</section>
<!-- HISTÓRIAS CIRANDA VÍDEO -->
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
<!-- NÃO ENCONTROU -->
<section class="quero-ciranda">
    <div class="inner">
        <div class="quero-ciranda-box">
            <h2><?php the_field('titulo_formulario_solicitacao'); ?></h2>
            <?php if (get_field('texto_formulario_solicitacao')) : ?>
            <p class="quero-ciranda-txt"><?php the_field('texto_formulario_solicitacao'); ?></p>
            <?php endif; ?>
            <?php echo do_shortcode(get_field('shortcode_do_formulario')); ?>
        </div>
        <img class="quero-ciranda-elemento-1" src="<?php echo bloginfo('template_directory'); ?>/img/gotas.png" alt="">
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
<!-- FORMULÁRIO MODALIDADE -->
<script>
$(document).ready(function() {
    $("input:radio[name='rd']").each(function(i) {
        this.checked = false;
    });
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
    const basepathAPI = "https://mathema.com.br/api/v2";

    // console.log("Nome: " + $("#name").val());
    // console.log("Email: " + $("#email").val());
    // console.log("Telefone: " + $("input[name=telefone]").val());
    // console.log("Atuacao: " + $("select[name=atuacao]").val());
    // console.log("Instituicao: " + $("input[name=instituicao]").val());
    // console.log("numero-alunos: " + $("select[name=numero-alunos]").val());
    // console.log("Cidade: " + $("input[name=cidade]").val());
    // console.log("Estado: " + $("select[name=estado]").val());

    var form = new FormData();
    form.append("list_id", "f3397d3993");
    form.append("status", "subscribed");
    form.append("email_address", $("#email").val());
    form.append("merge_fields[NOME]", $("#name").val());
    form.append("merge_fields[CELULAR]", $("input[name=telefone]").val());
    form.append("merge_fields[ATUACAO]", $("select[name=atuacao]").val());
    form.append("merge_fields[INSTITUICA]", $("input[name=instituicao]").val());
    form.append("merge_fields[N_ALUNOS]", $("select[name=numero-alunos]").val());
    form.append("merge_fields[CIDADE]", $("input[name=cidade]").val());
    form.append("merge_fields[ESTADO]", $("select[name=estado]").val());
    form.append("tags[]", ["Formação Presencial B2B"]);

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