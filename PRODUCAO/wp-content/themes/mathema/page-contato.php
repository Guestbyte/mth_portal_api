<?php /* Template Name: Contato */ ?>
<?php get_header(); ?>
<!-- HOME -->
<section class="topo-meia-altura" <?php thumbnail_bg('topo'); ?>>
    <div class="inner">
        <!-- BOX SECUNDÃRIO -->
        <div class="box-secundario">
            <div class="box-secundario-inner no-breadcrumb">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>
    </div>
</section>
<section class="namidia">
    <div class="inner">
        <div class="contato-txt">
            <?php the_content(); ?>
        </div>
        <div class="box-contato">
            <div class="contato-form">
                <?php echo do_shortcode(get_field('formulario_contato')); ?>
            </div>
        </div>
        <!-- LISTA OUTROS CANAIS -->
        <div class="outros-canais-list">
            <h1><?php the_field('titulo_outros_canais'); ?></h1>
            <!-- loop que carrega os downloads -->
            <?php if (have_rows('outros_canais')) : while (have_rows('outros_canais')) : the_row(); ?>
            <!-- ITEM -->
            <div class="canal-item">
                <?php echo wp_get_attachment_image(get_sub_field('icone_canal'), 'icone-contato', '', array('class' => 'canal-item-img')); ?>
                <div class="canal-item-info">
                    <span class="canal-item-rotulo"><?php the_sub_field('rotulo_canal'); ?></span>
                    <span><?php the_sub_field('informacao_canal'); ?></span>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php endwhile;
            endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>
</section>
<!-- FORMULÃRIO ORÃ‡AMENTO -->
<script>
$(document).ready(function() {
    //controla se o menu deve abrir ou fechar   
    var clicado = 0;
    //controla a abertura e fechamento dos filtros clicando no botao
    $("#btn-orcamento").click(function() {
        if (clicado == 0) {
            $("#btn-orcamento").css("transform", "rotate(180deg)");
            $("#orcamento-form").show();
            clicado = 1;
        } else {
            $("#btn-orcamento").css("transform", "rotate(0deg)");
            $("#orcamento-form").hide();
            clicado = 0;
        }
    });
})

//JS: Integração com Mailchimp 
//[23/10/2019 - Fernando]

$(".wpcf7-submit").click(function() {
    const basepathAPI = "https://mathema.com.br/api/v1/index.php";

    console.log("Nome: " + $("#name").val());
    console.log("Email: " + $("#email").val());
    console.log("Assunto: " + $("input[name=assunto]").val());
    console.log("Mensagem: " + $("textarea[name=mensagem]").val());
    console.log("Destinatario: " + $("select[name=destinatario]").val());

    var form = new FormData();
    form.append("list_id", "2da8383add");
    form.append("status", "subscribed");
    form.append("email_address", $("#email").val());
    form.append("merge_fields[FNAME]", $("#name").val());

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