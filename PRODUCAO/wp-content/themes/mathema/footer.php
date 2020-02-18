<!-- FECHA SITE -->
</section>
<!-- FOOTER -->
<footer>
    <div class="inner">
        <!-- REDES SOCIAIS -->
        <div class="social-footer">
            <div class="rotulo-social-footer">Conecte-se ao Mathema</div>
            <a class="icone-social-footer" href="https://www.facebook.com/grupomathema/" target="_blank"><i
                    class="fab fa-facebook-f"></i></a>
            <a class="icone-social-footer" href="https://www.linkedin.com/company/grupomathema" target="_blank"><i
                    class="fab fa-linkedin-in"></i></a>
            <a class="icone-social-footer" href="https://www.instagram.com/grupomathema/" target="_blank"><i
                    class="fab fa-instagram"></i></a>
            <a class="icone-social-footer" href="https://www.youtube.com/channel/UCfZJckA_5eSs3J-kHSbhiMw"
                target="_blank"><i class="fab fa-youtube"></i></a>
        </div>
        <div class="col-endereco">
            <h3>Entre em contato</h3>
            <div class="col-endereco-item">
                <address>Rua Professor Aprígio Gonzaga, 78, 13º andar<br>São Judas, São Paulo, SP | 04303-000</address>
            </div>
            <div class="col-endereco-item">Tel: +55 11 5548 6912 | 5567 6912</div>
            <div class="col-endereco-item">E-mail: contato@mathema.com.br</div>
        </div>
        <div class="col-instagram">
            <h3>Nosso Instagram</h3>
            <div class="instafeed">
                <?php echo do_shortcode('[instagram-feed]'); ?>
            </div>
        </div>
        <div class="col-pagamento">
            <img src="<?php echo bloginfo('template_directory'); ?>/img/pagamento.png" alt="">
        </div>
        <div class="direitos">© 2019. Mathema - Formação e Pesquisa. .Site desenvolvido por Adapta, NascerWeb e Amí
            Comunicação & Design</div>
    </div>
</footer>
<!-- SUCESSO NO ENVIO DE MENSAGEM -->
<div id="sucesso" class="janela-sucesso">
    <div class="janela-sucesso-inner"><button id="btn-fecha-sucesso" class="btn-fecha"><span
                class="barrinha1"></span><span class="barrinha2"></span></button><span id="mensagem-sucesso"
            class="mensagem-sucesso"></span></div>
</div>
<!-- ANIMAÇÕES PORQUE MATHEMA -->
<script>
$(window).scroll(function() {
    if ($(window).width() > 768) {
        var startPorque = $('#porque').offset().top - (window.innerHeight / 2);
        if ($(window).scrollTop() > startPorque) {
            $('#porque-item-1').delay(100).animate({
                top: "0px"
            }, 400, "swing");
            $('#porque-item-2').delay(400).animate({
                top: "0px"
            }, 400, "swing");
            $('#porque-item-3').delay(700).animate({
                top: "0px"
            }, 400, "swing");
            $('#folha').animate({
                left: "50%",
                top: "-115px"
            }, 1000, "swing");
        }
    }
})
</script>
<!-- JAVASCRIPT (JQUERY) -->
<script>
// MTH API - global settings
const basepathAPI = "https://mathema.com.br/api/v2";
// const basepathAPI = "https://mathema.com.br/api/homolog";

var MailChimp = {
    subscribe: (form) => {

        //VALIDATION
        $is_empty = (form.get('email_address').length == 0)
        if ($is_empty) {
            console.log("MailChimp.subscribe: 'email_address' missed or form data not passed!");
            return false
        };

        //PREPARATION
        form.append("list_id", "f3397d3993");
        form.append("status", "subscribed");

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

        //ACTION
        $.ajax(settings)
            .done(function(response) {
                console.log("Mailchimp.subscribe: Done!");
                console.log(response);
                return response;
            })
            .fail(function(error) {
                console.log("MTH-API: Mailchimp: error on subscribe:");
                console.log(error.responseText);
                return error.responseText;
            });
    }
}
//****/

$(document).ready(function() {
    //DESABILITA O PRIMEIRO ITEM DO SELECT COM A CLASSE "select-rotulo" DO CONTACT FORM 7
    $('form.wpcf7-form').find('select.select-rotulo').each(function() {
        $(this).children().first().attr("disabled", "true");
    })
    //MOVE A LABEL PRA DENTRO DA DIV QUE CONTEM O INPUT FILE
    $(".wpcf7-form-control-wrap.curriculo").append($("#label-inputfile"));
    $("#curriculo").change(function() {
        $("#label-inputfile").find("span").text(this.files[0].name);
    });
    //ESCONDE A JANELA SUCESSO
    $("#btn-fecha-sucesso").click(function() {
        $("#sucesso").fadeOut();
        $('body').css("overflow", "auto");
    });
});
</script>
<!-- SCROLL BAR -->
<link rel="stylesheet" href="<?php echo bloginfo('template_directory'); ?>/scrollbar/scrollBar.css" />
<script src="<?php echo bloginfo('template_directory'); ?>/scrollbar/scrollBar.js"></script>
<script>
if ($(window).width() > 768) {
    $(".download-ciranda-txt").scrollBox();
}
</script>
<!-- RESPONSIVE TABS -->
<link rel="stylesheet" href="<?php echo bloginfo('template_directory'); ?>/tabs/responsive-tabs.css" />
<script src="<?php echo bloginfo('template_directory'); ?>/tabs/jquery.responsiveTabs.min.js"></script>
<!-- OWL CAROUSEL -->
<link rel="stylesheet" href="<?php echo bloginfo('template_directory'); ?>/owl/owl.carousel.min.css">
<link rel="stylesheet" href="<?php echo bloginfo('template_directory'); ?>/owl/owl.theme.default.min.css">
<script src="<?php echo bloginfo('template_directory'); ?>/owl/owl.carousel.min.js"></script>
<script src="<?php echo bloginfo('template_directory'); ?>/owl/owl.carousel2.thumbs.min.js"></script>
<!-- CARREGA SCRIPTS NO FOOTER -->
<?php wp_footer(); ?>
</body>

</html>