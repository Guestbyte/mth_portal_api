<?php /* Template Name: FO B2B */ ?>
<?php get_header(); ?>
<!-- HOME -->
<section class="home-cards" <?php thumbnail_bg('home'); ?>>
    <div class="inner">
        <!-- BOX PRIMÃRIO -->
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
<!-- ABAS VERTICAIS -->
<section class="download-ciranda">
    <div class="inner">
        <?php if (get_field('titulo_aba_vertical')) : ?>
        <h1><?php the_field('titulo_aba_vertical'); ?></h1>
        <?php endif; ?>
        <!-- ABAS -->
        <div id="abas-vertical">
            <!-- PAINEIS -->
            <!-- link do botÃ£o -->
            <?php if (get_field('rotulo_do_botao_aba_vertical')) : ?>
            <?php
                    $linkBotaoAbaVertical = get_field('link_do_botao_aba_vertical');
                    $linkBotaoAbaVertical_url = $linkBotaoAbaVertical['url'];
                    $linkBotaoAbaVertical_target = $linkBotaoAbaVertical['target'] ? $linkBotaoAbaVertical['target'] : '_self';
                    ?>
            <?php endif; ?>
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
                    <h1><?php the_sub_field('titulo_aba_vertical'); ?></h1>
                    <?php the_sub_field('texto_aba_vertical'); ?>
                    <?php if (get_field('rotulo_do_botao_aba_vertical')) : ?>
                    <a class="btn-padrao" href="<?php echo esc_url($linkBotaoAbaVertical_url); ?>"
                        target="<?php echo esc_attr($linkBotaoAbaVertical_target); ?>"><?php the_field('rotulo_do_botao_aba_vertical'); ?></a>
                    <?php endif; ?>
                </div>
                <?php echo wp_get_attachment_image(get_sub_field('imagem_aba_vertical'), 'img-tab-ciranda'); ?>
                <div class="clearfix"></div>
            </div>
            <?php endwhile; ?>
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
        <img class="download-ciranda-elemento-1" src="<?php echo bloginfo('template_directory'); ?>/img/passaros.png"
            alt="">
    </div>
</section>
<!-- HISTÃ“RIA -->
<section class="historia">
    <div class="inner">
        <div class="historia-titulo" style="background-image: url(<?php the_field('imagem_background_historia'); ?>);">
            <h1><?php the_field('titulo_historia'); ?></h1>
        </div>
        <div class="historia-txt">
            <?php the_field('texto_historia'); ?>
        </div>
    </div>
</section>
<!-- QUERO CIRANDA -->
<section id="quero-ciranda" class="quero-ciranda">
    <div class="inner">
        <div class="quero-ciranda-box">
            <h1><?php the_field('titulo_formulario_solicitacao'); ?></h1>
            <?php if (get_field('texto_formulario_solicitacao')) : ?>
            <p class="quero-ciranda-txt"><?php the_field('texto_formulario_solicitacao'); ?></p>
            <?php endif; ?>
            <?php echo do_shortcode(get_field('shortcode_do_formulario')); ?>
        </div>
    </div>
    <img class="quero-ciranda-elemento-1" src="<?php echo bloginfo('template_directory'); ?>/img/sol.png" alt="">
</section>
<!-- DEPOIMENTOS -->
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
        <img class="historias-ciranda-casa" src="<?php echo bloginfo('template_directory'); ?>/img/tetris.png" alt="">
    </div>
</section>
<!-- PARCEIROS -->
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
    // console.log("Telefone: " + $("input[name=telefone]").val());
    // console.log("Cargo: " + $("select[name=cargo]").val());
    // console.log("Instituicao: " + $("input[name=instituicao]").val());
    // console.log("Perfil: " + $("select[name=perfil]").val());

    var form = new FormData();
    form.append("list_id", "f3397d3993");
    form.append("status", "subscribed");
    form.append("email_address", $("#email").val());
    form.append("merge_fields[NOME]", $("#name").val());
    form.append("merge_fields[CELULAR]", $("input[name=telefone]").val());
    form.append("merge_fields[CARGO]", $("select[name=cargo]").val());
    form.append("merge_fields[INSTITUICA]", $("input[name=instituicao]").val());
    form.append("merge_fields[ATUACAO]", $("select[name=perfil]").val());
    form.append("tags[]", ["Formação Online B2B"]);

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