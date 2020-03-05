<?php /* Template Name: Woocommerce */ ?>
<!--HEADER-->
<?php get_header(); ?>
<!--LOOP WOOCOMMERCE-->
<section class="conteudo-woo">
    <div class="topo-woo"></div>
    <!-- ETAPAS DA COMPRA -->
    <div class="etapas-compra">
        <ul>
            <li class="carrinho-ativo"><img src="<?php echo bloginfo('template_directory');?>/img/icone-carrinho.png" alt="Carrinho"><span>Carrinho</span></li>
            <li class="li-identificacao"><img src="<?php echo bloginfo('template_directory');?>/img/icone-identificacao.png" alt="Identificação"><span>Identificação e Pagamento</span></li>
            <li class="confirmacao-ativo"><img src="<?php echo bloginfo('template_directory');?>/img/icone-confirmacao.png" alt="Confirmação"><span>Confirmação</span></li>
        </ul>            
    </div>   
    <div class="inner">    
    <?php if (have_posts()) : while (have_posts()): the_post(); ?>       
        <?php the_content(); ?>               
    <?php endwhile; endif;?>
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
     $("#btn-login-cliente").click(function(){
        if(LoginClicado==1){            
            $("#seta-login-cliente").css("transform", "rotate(0deg)");
            $("#formulario-login").hide();
            $("#seta-novo-cadastro").css("transform", "rotate(180deg)");
            $("#btn-novo-cadastro").css("border-bottom-width",0);               
            $("#customer_details").show();
            $("#btn-ir-pagamento").show();                         
            LoginClicado = 0;
            CadastroClicado = 1;
        }else{
            $("#seta-login-cliente").css("transform", "rotate(180deg)");
            $("#formulario-login").show();
            $("#seta-novo-cadastro").css("transform", "rotate(0deg)");
            $("#btn-novo-cadastro").css("border-bottom-width",2);
            $("#customer_details").hide();
            $("#btn-ir-pagamento").hide();                              
            LoginClicado = 1;
            CadastroClicado = 0;
        }        			
    });
    //abre e fecha formulário de cadastro
    $("#btn-novo-cadastro").click(function(){
        if(CadastroClicado==0){            
            $("#seta-login-cliente").css("transform", "rotate(0deg)");            
            $("#formulario-login").hide();
            $("#seta-novo-cadastro").css("transform", "rotate(180deg)");
            $(this).css("border-bottom-width",0);            
            $("#customer_details").show();
            $("#btn-ir-pagamento").show();

            LoginClicado = 0;                   
            CadastroClicado = 1;
        }else{
            $("#seta-login-cliente").css("transform", "rotate(180deg)");
            $("#formulario-login").show();
            $("#seta-novo-cadastro").css("transform", "rotate(0deg)");
            $(this).css("border-bottom-width",2);
            $("#customer_details").hide();
            $("#btn-ir-pagamento").hide(); 
            LoginClicado = 1;                        
            CadastroClicado = 0;
        }        			
    });
   
    //ADICIONA A CLASSE PARA ATIVAR O BOTÃO IDENTIFICAÇÃO NO MENU 
    $( ".li-identificacao" ).addClass('identificacao-ativo');

    //ADICIONA PLACEHOLDER AOS CAMPOS DE LOGIN  
    $("#username").attr("placeholder", "E-mail");
    $("#password").attr("placeholder", "Senha do Mathema Online");  
    $("#reg_email").attr("placeholder", "E-mail");
    $("#reg_password").attr("placeholder", "Senha");

})
</script>

<!--FOOTER-->
<?php get_footer();?>
