<?php /* Template Name: Minha conta */ ?>
<!--HEADER-->
<?php get_header(); ?>
<!--LOOP WOOCOMMERCE-->
<section class="conteudo-woo">
    <div class="topo-woo"></div>
    <div class="inner">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
        <?php endwhile;
        endif; ?>
        <!-- FECHA A PÁGINA DO WOOCOMMERCE -->
        <div class="clearfix"></div>
    </div>
</section>
<!-- SCRIPTS -->
<script>
$(document).ready(function() {
    //FORMULÁRIO CADASTRO
    //controla se o menu deve abrir ou fechar   
    var LoginClicado = 1;
    var CadastroClicado = 0;
    //abre e fecha formulário de login
    $("#btn-login-cliente").click(function() {
        if (LoginClicado == 1) {
            $("#seta-login-cliente").css("transform", "rotate(0deg)");
            $("#formulario-login").hide();
            $("#seta-novo-cadastro").css("transform", "rotate(180deg)");
            $("#btn-novo-cadastro").css("border-bottom-width", 0);
            $("#customer_details").show();
            $("#btn-ir-pagamento").show();
            LoginClicado = 0;
            CadastroClicado = 1;
        } else {
            $("#seta-login-cliente").css("transform", "rotate(180deg)");
            $("#formulario-login").show();
            $("#seta-novo-cadastro").css("transform", "rotate(0deg)");
            $("#btn-novo-cadastro").css("border-bottom-width", 2);
            $("#customer_details").hide();
            $("#btn-ir-pagamento").hide();
            LoginClicado = 1;
            CadastroClicado = 0;
        }
    });
    //abre e fecha formulário de cadastro
    $("#btn-novo-cadastro").click(function() {
        if (CadastroClicado == 0) {
            $("#seta-login-cliente").css("transform", "rotate(0deg)");
            $("#formulario-login").hide();
            $("#seta-novo-cadastro").css("transform", "rotate(180deg)");
            $(this).css("border-bottom-width", 0);
            $("#customer_details").show();
            $("#btn-ir-pagamento").show();

            LoginClicado = 0;
            CadastroClicado = 1;
        } else {
            $("#seta-login-cliente").css("transform", "rotate(180deg)");
            $("#formulario-login").show();
            $("#seta-novo-cadastro").css("transform", "rotate(0deg)");
            $(this).css("border-bottom-width", 2);
            $("#customer_details").hide();
            $("#btn-ir-pagamento").hide();
            LoginClicado = 1;
            CadastroClicado = 0;
        }
    });

    //ADICIONA PLACEHOLDER AOS CAMPOS DE LOGIN  
    $("#username").attr("placeholder", "E-mail ou Login");
    $("#password").attr("placeholder", "Senha do Mathema Online");
    $("#reg_email").attr("placeholder", "E-mail");
    $("#reg_password").attr("placeholder", "Senha");

    /* altera a altura do menu da página minha conta */
    if ($(window).width() > 768) {
        $('.woocommerce-MyAccount-menu').css('min-height', $('.woocommerce-MyAccount-content-inner')
            .outerHeight());
    }

    /* abertura e fechamento do menu do perfil */
    if ($(window).width() < 769) {
        var perfilClicado = 0;
        $("#btn-perfil").click(function() {
            if (perfilClicado == 0) {
                $("#menu-perfil").css("right", "-50px");
                perfilClicado = 1;
            } else {
                $("#menu-perfil").css("right", "-220px");
                perfilClicado = 0;
            }
        });
    }
})
</script>

<!--FOOTER-->
<?php get_footer(); ?>