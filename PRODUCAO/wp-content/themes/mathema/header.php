<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mathema</title>
    <!-- ÍCONES -->
    <link rel="apple-touch-icon" sizes="57x57"
        href="<?php echo bloginfo('template_directory');?>/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60"
        href="<?php echo bloginfo('template_directory');?>/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72"
        href="<?php echo bloginfo('template_directory');?>/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76"
        href="<?php echo bloginfo('template_directory');?>/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114"
        href="<?php echo bloginfo('template_directory');?>/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120"
        href="<?php echo bloginfo('template_directory');?>/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144"
        href="<?php echo bloginfo('template_directory');?>/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152"
        href="<?php echo bloginfo('template_directory');?>/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180"
        href="<?php echo bloginfo('template_directory');?>/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"
        href="<?php echo bloginfo('template_directory');?>/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32"
        href="<?php echo bloginfo('template_directory');?>/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96"
        href="<?php echo bloginfo('template_directory');?>/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="<?php echo bloginfo('template_directory');?>/icons/favicon-16x16.png">
    <link rel="manifest" href="<?php echo bloginfo('template_directory');?>/icons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage"
        content="<?php echo bloginfo('template_directory');?>/icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- CARREGA SCRIPTS NO HEADER -->
    <?php wp_head(); ?>
    <!-- CSS -->
    <link href="<?php echo bloginfo('template_directory');?>/style.css" rel="stylesheet">
    <link href="<?php echo bloginfo('template_directory');?>/woocommerce.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900&display=swap" rel="stylesheet">
    <!--SHRINK MENU-->
    <script type="text/javascript">
    $(document).on("scroll", function() {
        if ($(document).scrollTop() > 80) {
            $("header").removeClass("large").addClass("small");
        } else {
            $("header").removeClass("small").addClass("large");
        }
    });
    </script>
    <!--CONTROLA MENU, BUSCA -->
    <script type="text/javascript">
    $(document).ready(function() {



        //abertura e fechamento do menu   
        $("#btn-menu").click(function() {
            $("#menu").css("left", 0);
            $('body').css('overflow', 'hidden')
        });
        $("#btn-fecha-menu").click(function() {
            $("#menu").css("left", "100vw");
            $('body').css("overflow", "auto")
        });
        //abertura e fechamento da busca   
        $("#btn-busca").click(function() {
            $("#busca").css("left", 0);
        });
        $("#btn-fecha-busca").click(function() {
            $("#busca").css("left", "100vw");
        });
    });
    </script>
    <!--  POSICIONA O MEGA MENU EM 0 LEFT DA JANELA -->
    <script type="text/javascript">
    $(document).ready(function() {
        if ($(window).width() > 768) {

            //cria a função que posiciona o mega menu
            function posicionaMegaMenu() {
                //localiza a classe dentro do menu        
                $('#menu-principal').find('li.menu-item-has-children').each(function() {
                    //pega as margens laterais do menu
                    var marginDifference = ($(window).width() - $('.header-inner').width()) / 2;
                    // pega a posição das li, que tem submenu
                    var positionLeft = $(this).position().left;
                    //posiciona o submenu no zero da janela
                    $(this).children().css('left', 0 - positionLeft - marginDifference);
                })
            }
            //executa a função que posiciona o mega menu
            posicionaMegaMenu();
            //ao redimensionar a janela, atualiza os valores
            $(window).resize(function() {
                posicionaMegaMenu();
            });
        } else {
            $('#menu-principal li.menu-item-has-children a').click(function(event) {
                var visibleSubmenu = $('#menu-principal ul.sub-menu:visible');
                var submenu = $(this).next('ul.sub-menu');
                if (submenu.length >= 1) {
                    event.preventDefault();
                    visibleSubmenu.hide();
                    submenu.toggle();
                }
            });
        }
    });
    </script>
    <!--FANCYBOX-->
    <link href="<?php echo bloginfo('template_directory');?>/fancybox/jquery.fancybox.min.css" rel="stylesheet"
        type="text/css">
    <script src="<?php echo bloginfo('template_directory');?>/fancybox/jquery.fancybox.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('[data-fancybox]').fancybox({
            slideShow: false,
            fullScreen: false,
            thumbs: false,
            closeBtn: false,
        });
    });
    </script>
    <!-- ANCORA -->
    <script src="<?php echo bloginfo('template_directory');?>/js/anchor-animate.js"></script>
    <!-- MAILCHIMP -->
    <script id="mcjs">
    ! function(c, h, i, m, p) {
        m = c.createElement(h), p = c.getElementsByTagName(h)[0], m.async = 1, m.src = i, p.parentNode.insertBefore(m,
            p)
    }(document, "script",
        "https://chimpstatic.com/mcjs-connected/js/users/22c743aeeb35ad2373802b33f/c75b45f2f382e856b5ba4ee37.js");
    </script>


    <!-- 02/03/2020 - Fernando - teste de uso de CDN para imagens -->
    <script src="//spdrjs-13d1.kxcdn.com/speeder.js"></script>
    <script>
    speeder('993489d8', '515');
    </script>
    <!-- ------------- -->

</head>

<body <?php body_class(); ?>>
    <!-- GOOGLE TAG MANAGER -->
    <?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); } ?>
    <!-- BUSCA -->
    <div id="busca" class="busca">
        <button id="btn-fecha-busca" class="btn-fecha">
            <span class="barrinha1"></span>
            <span class="barrinha2"></span>
        </button>
        <div class="form-busca">
            <form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label id="header-search">
                    <input type="text" name="s" id="s" placeholder="O que você deseja buscar?">
                    <input type="submit" name="submit" id="searchsubmit" value="OK">
                </label>
            </form>
        </div>
    </div>
    <!-- HEADER -->
    <header>
        <div class="header-inner">
            <a href="<?php echo bloginfo('url'); ?>">
                <img class="logo logo-desktop" src="<?php echo bloginfo('template_directory');?>/img/mathema.png"
                    alt="Mathema">
                <img class="logo-color logo-desktop"
                    src="<?php echo bloginfo('template_directory');?>/img/mathema-logo.png" alt="Mathema">
                <img class="logo logo-mobile" src="<?php echo bloginfo('template_directory');?>/img/mathema-mobile.svg"
                    alt="Mathema">
                <img class="logo-color logo-mobile"
                    src="<?php echo bloginfo('template_directory');?>/img/mathema-mobile-color.svg" alt="Mathema">
            </a>

            <!-- BOTÃO -->
            <button id="btn-menu" class="btn-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- MENU -->
            <nav id="menu" class="menu-header">
                <button id="btn-fecha-menu" class="btn-fecha">
                    <span class="barrinha1"></span>
                    <span class="barrinha2"></span>
                </button>
                <?php wp_nav_menu( array('menu' => 'Principal', 'container' => 'ul', 'menu_class' => 'menu-principal')); ?>
            </nav>

            <div class="icones-header">
                <!-- BOTÃO BUSCA -->
                <a id="btn-busca" class="btn-header"><i class="fas fa-search"></i>
                    <div class="btn-header-rotulo">Buscar no site</div>
                </a>
                <!-- BOTÃO MINHA CONTA -->
                <?php
            if ( is_user_logged_in() ) {
                //se logado exibe iniciais do usuário
                $current_user = wp_get_current_user();
                $user_name = $current_user->user_firstname;
                ?>
                <a class="btn-header" href="<?php echo bloginfo('url'); ?>/minha-conta"><?php echo $user_name[0]; ?>
                    <div class="btn-header-rotulo">Minha conta</div>
                </a>
                <?php
            } else {
                //não logado exibe botão minha conta
                ?>
                <a class="btn-header" href="<?php echo bloginfo('url'); ?>/minha-conta"><i class="fas fa-user"></i>
                    <div class="btn-header-rotulo">Minha conta</div>
                </a>
                <?php
            }
            ?>
                <!-- BOTÃO DO CARRINHO -->
                <a class="btn-header" href="<?php echo wc_get_cart_url(); ?>">
                    <div class="btn-carrinho-contador">
                        <?php echo sprintf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?>
                    </div>
                    <i class="fas fa-shopping-cart"></i>
                    <div class="btn-header-rotulo">Total: <?php echo WC()->cart->get_cart_total(); ?></div>
                </a>
            </div>

            <!-- REDES SOCIAIS -->
            <div class="social-header">
                <div class="rotulo-social-header">Conecte-se ao Mathema</div>
                <a class="icone-social-header" href="https://www.facebook.com/grupomathema/" target="_blank"><i
                        class="fab fa-facebook-f"></i></a>
                <a class="icone-social-header" href="https://www.linkedin.com/company/grupomathema" target="_blank"><i
                        class="fab fa-linkedin-in"></i></a>
                <a class="icone-social-header" href="https://www.instagram.com/grupomathema/" target="_blank"><i
                        class="fab fa-instagram"></i></a>
                <a class="icone-social-header" href="https://www.youtube.com/channel/UCfZJckA_5eSs3J-kHSbhiMw"
                    target="_blank"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </header>
    <!-- SITE -->
    <section class="site">