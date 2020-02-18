<?php /* Template Name: Ciranda 2 */ ?>
<?php get_header(); ?>
<!-- MENU CIRANDA -->
<div class="btn-menu-ciranda-container">
    <label>MENU CIRANDA</label>
    <button id="btn-menu-ciranda" class="btn-menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
</div>

<nav id="menu-ciranda" class="menu-ciranda">
    <button id="btn-fecha-ciranda" class="btn-fecha">
        <span class="barrinha1"></span>
        <span class="barrinha2"></span>
    </button>
    <ul>
        <li><a class="mcitem animate" href="#sobre">Sobre o Ciranda</a></li>
        <li><a class="mcitem animate" href="#video">Vídeo</a></li>
        <li><a class="mcitem animate" href="#material">Material Didático</a></li>
        <li><a class="mcitem animate" href="#quero-ciranda">Quero o Ciranda</a></li>
        <li><a class="mcitem animate" href="#resultados">Resultados</a></li>
    </ul>
</nav>
<!-- CIRANDA -->
<section class="ciranda-2">
    <div class="inner">
        <!-- BOX PRIMÁRIO -->
        <div class="box-primario">
            <div class="box-primario-inner">
                <h1><img src="<?php the_field('imagem_primario'); ?>" alt="<?php the_field('titulo_primario'); ?>"></h1>
                <p><?php the_field('texto_primario'); ?></p>
                <?php if(get_field('rotulo_botao_primario')): ?>
                <a class="btn-padrao animate" href="#quero-ciranda"><?php the_field('rotulo_botao_primario'); ?></a>
                <?php endif; ?>
            </div>
            <?php /*                
            <a class="btn-rola-tela animate" href="#porque"><img src="<?php echo bloginfo('template_directory');?>/img/seta-baixo.png"
            alt=""></a>
            */ ?>
        </div>
        <!-- ELEMENTOS CIRANDA -->
        <img class="ciranda-flor" src="<?php echo bloginfo('template_directory');?>/img/ciranda-flor.png" alt="">
        <img class="elemento-ciranda-1" src="<?php echo bloginfo('template_directory');?>/img/ciranda-elemento-1.png"
            alt="">
        <img class="elemento-ciranda-2" src="<?php echo bloginfo('template_directory');?>/img/ciranda-elemento-2.png"
            alt="">
        <img class="elemento-ciranda-3" src="<?php echo bloginfo('template_directory');?>/img/ciranda-elemento-3.png"
            alt="">
        <img class="elemento-ciranda-4" src="<?php echo bloginfo('template_directory');?>/img/ciranda-elemento-4.png"
            alt="">
    </div>
</section>
<!-- SOBRE -->
<section id="sobre" class="sobre-ciranda">
    <div class="inner">
        <h3>Ciranda: muito mais que um material didático!</h3>
        <p>Ele foi pensado a partir de vivências que inserem as crianças em práticas sociais e culturais criativas e
            interativas, promovendo aprendizados significativos.</p>
        <div class="sobre-ciranda-lista">
            <!-- loop que carrega os itens -->
            <?php
            if( have_rows('itens_sobre_o_ciranda') ) :
            $i = 0;
            while ( have_rows('itens_sobre_o_ciranda') ) :
            $i++;
            the_row();
            ?>
            <div class="sobre-ciranda-item">
                <a class="btn-sobre-ciranda" data-fancybox data-src="#popup-sobre-<?php echo $i; ?>"
                    href="javascript:;"><span></span><span></span></a>
                <?php echo wp_get_attachment_image( get_sub_field('icone_item_sobre_o_ciranda' ), 'full', '' ); ?>
                <h5><?php the_sub_field('titulo_item_sobre_o_ciranda'); ?></h5>
                <!-- JANELA MODAL -->
                <div class="janela-modal-sobre" id="popup-sobre-<?php echo $i; ?>">
                    <button data-fancybox-close class="btn-fecha">
                        <span class="barrinha1"></span>
                        <span class="barrinha2"></span>
                    </button>
                    <div class="janela-modal-sobre-inner">
                        <div class="popup-sobre-info">
                            <h5><?php the_sub_field('titulo_item_sobre_o_ciranda'); ?></h5>
                            <?php the_sub_field('texto_item_sobre_o_ciranda'); ?>
                        </div>
                        <?php echo wp_get_attachment_image( get_sub_field('imagem_item_sobre_o_ciranda' ), 'full', '', array( 'class' => 'popup-sobre-img' ) ); ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>
</section>
<!-- VÍDEO QUERO CIRANDA -->

<section id="video" class="video-quero-ciranda-2">
    <div class="inner">
        <div class="video">
            <?php the_field('video_unico'); ?>

            <?php /* the_field('video_unico'); 

        <iframe src="https://www.youtube.com/embed/w-8rIHf1ryA?feature=oembed" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" width="640" height="360" frameborder="0"></iframe>
                */ ?>
        </div>
    </div>
    <img class="elemento-video-ciranda-1"
        src="<?php echo bloginfo('template_directory');?>/img/elemento-video-ciranda-1.png" alt="">
    <img class="elemento-video-ciranda-2"
        src="<?php echo bloginfo('template_directory');?>/img/elemento-video-ciranda-2.png" alt="">
    <img class="elemento-video-ciranda-3"
        src="<?php echo bloginfo('template_directory');?>/img/elemento-video-ciranda-3.png" alt="">
    <img class="elemento-video-ciranda-4"
        src="<?php echo bloginfo('template_directory');?>/img/elemento-video-ciranda-4.png" alt="">
    <img class="elemento-video-ciranda-5"
        src="<?php echo bloginfo('template_directory');?>/img/elemento-video-ciranda-5.png" alt="">
</section>

<!-- MATERIAL DIDÁTICO -->
<section id="material" class="material-ciranda">
    <div class="inner">
        <h3>O material didático:<br>lúdico e funcional</h3>
        <p>Clique nos livros do Mosaico para conhecer mais sobre o material do Ciranda</p>
        <div class="clearfix"></div>
        <div class="material-ciranda-container">
            <div class="material-ciranda-filtros">
                <h5 class="titulo-filtro-idade">Escolha uma idade</h5>
                <div class="filtro-idade">
                    <button class="btn-filtro-idade tablink idade-ativo" onclick="openIdade(event,'3anos')">3
                        anos</button>
                    <button class="btn-filtro-idade tablink" onclick="openIdade(event,'4anos')">4 anos</button>
                    <button class="btn-filtro-idade tablink" onclick="openIdade(event,'5anos')">5 anos</button>
                </div>

                <!-- 3 ANOS -->
                <div id="3anos" class="filtro-idade-item idade">
                    <h5 class="titulo-filtro-tipo">Escolha um tipo</h5>
                    <div class="filtro-tipo">
                        <button class="btn-filtro-tipo tablinktipo3 tipo-ativo"
                            onclick="openTipo3(event,'3anosprof')">Material do professor</button>
                        <button class="btn-filtro-tipo tablinktipo3" onclick="openTipo3(event,'3anosalun')">Material da
                            criança</button>
                    </div>
                    <!-- 3 ANOS PROFESSOR -->
                    <div id="3anosprof" class="filtro-tipo-item tipo3 btns-prof">
                        <div class="material-ciranda-conteudo">
                            <div class="material-ciranda-item">
                                <div class="material-ciranda-item-inner">
                                    <?php if( have_rows('lista_material_3_prof') ): $i = 0; while ( have_rows('lista_material_3_prof') ) : the_row(); $i++; ?>
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox
                                        data-src="#popup-material-3ap-<?php echo $i; ?>"
                                        href="javascript:;"><span></span><span></span></a>
                                    <!-- JANELA MODAL -->
                                    <div class="janela-modal-material" id="popup-material-3ap-<?php echo $i; ?>">
                                        <button data-fancybox-close class="btn-fecha">
                                            <span class="barrinha1"></span>
                                            <span class="barrinha2"></span>
                                        </button>
                                        <div class="janela-modal-sobre-inner">
                                            <div class="popup-sobre-info">
                                                <h5><?php the_sub_field('titulo_material_3_prof'); ?></h5>
                                                <?php the_sub_field('texto_material_3_prof'); ?>
                                            </div>
                                            <div class="popup-sobre-galeria owl-emenda">
                                                <div class="owl-material owl-carousel owl-theme">
                                                    <?php 
                                                $images = get_sub_field('galeria_material_3_prof');
                                                if( $images ): ?>
                                                    <?php foreach( $images as $image ): ?>
                                                    <img src="<?php echo $image['sizes']['galeria-material']; ?>"
                                                        alt="<?php echo $image['alt']; ?>" />
                                                    <?php endforeach; endif; ?>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <?php endwhile; endif; ?>
                                    <?php echo wp_get_attachment_image( get_field('capa_material_3_prof' ), 'material' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 3 ANOS ALUNO -->
                    <div id="3anosalun" class="filtro-tipo-item tipo3 btns-aluno3" style="display:none">
                        <div class="material-ciranda-conteudo">
                            <div class="material-ciranda-item">
                                <div class="material-ciranda-item-inner">
                                    <?php if( have_rows('lista_material_3_aluno') ): $i = 0; while ( have_rows('lista_material_3_aluno') ) : the_row(); $i++; ?>
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox
                                        data-src="#popup-material-3aa-<?php echo $i; ?>"
                                        href="javascript:;"><span></span><span></span></a>
                                    <!-- JANELA MODAL -->
                                    <div class="janela-modal-material" id="popup-material-3aa-<?php echo $i; ?>">
                                        <button data-fancybox-close class="btn-fecha">
                                            <span class="barrinha1"></span>
                                            <span class="barrinha2"></span>
                                        </button>
                                        <div class="janela-modal-sobre-inner">
                                            <div class="popup-sobre-info">
                                                <h5><?php the_sub_field('titulo_material_3_aluno'); ?></h5>
                                                <?php the_sub_field('texto_material_3_aluno'); ?>
                                            </div>
                                            <div class="popup-sobre-galeria owl-emenda">
                                                <div class="owl-material owl-carousel owl-theme">
                                                    <?php 
                                                $images = get_sub_field('galeria_material_3_aluno');
                                                if( $images ): ?>
                                                    <?php foreach( $images as $image ): ?>
                                                    <img src="<?php echo $image['sizes']['galeria-material']; ?>"
                                                        alt="<?php echo $image['alt']; ?>" />
                                                    <?php endforeach; endif; ?>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <?php endwhile; endif; ?>
                                    <?php echo wp_get_attachment_image( get_field('capa_material_3_aluno' ), 'material' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 ANOS -->
                <div id="4anos" class="filtro-idade-item idade" style="display:none">
                    <h5 class="titulo-filtro-tipo">Escolha um tipo</h5>
                    <div class="filtro-tipo">
                        <button class="btn-filtro-tipo tablinktipo4 tipo-ativo"
                            onclick="openTipo4(event,'4anosprof')">Material do professor</button>
                        <button class="btn-filtro-tipo tablinktipo4" onclick="openTipo4(event,'4anosalun')">Material da
                            criança</button>
                    </div>
                    <!-- 4 ANOS PROFESSOR -->
                    <div id="4anosprof" class="filtro-tipo-item tipo4 btns-prof">
                        <div class="material-ciranda-conteudo">
                            <div class="material-ciranda-item">
                                <div class="material-ciranda-item-inner">
                                    <?php if( have_rows('lista_material_4_prof') ): $i = 0; while ( have_rows('lista_material_4_prof') ) : the_row(); $i++; ?>
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox
                                        data-src="#popup-material-4ap-<?php echo $i; ?>"
                                        href="javascript:;"><span></span><span></span></a>
                                    <!-- JANELA MODAL -->
                                    <div class="janela-modal-material" id="popup-material-4ap-<?php echo $i; ?>">
                                        <button data-fancybox-close class="btn-fecha">
                                            <span class="barrinha1"></span>
                                            <span class="barrinha2"></span>
                                        </button>
                                        <div class="janela-modal-sobre-inner">
                                            <div class="popup-sobre-info">
                                                <h5><?php the_sub_field('titulo_material_4_prof'); ?></h5>
                                                <?php the_sub_field('texto_material_4_prof'); ?>
                                            </div>
                                            <div class="popup-sobre-galeria owl-emenda">
                                                <div class="owl-material owl-carousel owl-theme">
                                                    <?php 
                                                $images = get_sub_field('galeria_material_4_prof');
                                                if( $images ): ?>
                                                    <?php foreach( $images as $image ): ?>
                                                    <img src="<?php echo $image['sizes']['galeria-material']; ?>"
                                                        alt="<?php echo $image['alt']; ?>" />
                                                    <?php endforeach; endif; ?>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <?php endwhile; endif; ?>

                                    <?php echo wp_get_attachment_image( get_field('capa_material_4_prof' ), 'material' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 4 ANOS ALUNO -->
                    <div id="4anosalun" class="filtro-tipo-item tipo4 btns-aluno45" style="display:none">
                        <div class="material-ciranda-conteudo">
                            <div class="material-ciranda-item">
                                <div class="material-ciranda-item-inner">
                                    <?php if( have_rows('lista_material_4_aluno') ): $i = 0; while ( have_rows('lista_material_4_aluno') ) : the_row(); $i++; ?>
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox
                                        data-src="#popup-material-4aa-<?php echo $i; ?>"
                                        href="javascript:;"><span></span><span></span></a>
                                    <!-- JANELA MODAL -->
                                    <div class="janela-modal-material" id="popup-material-4aa-<?php echo $i; ?>">
                                        <button data-fancybox-close class="btn-fecha">
                                            <span class="barrinha1"></span>
                                            <span class="barrinha2"></span>
                                        </button>
                                        <div class="janela-modal-sobre-inner">
                                            <div class="popup-sobre-info">
                                                <h5><?php the_sub_field('titulo_material_4_aluno'); ?></h5>
                                                <?php the_sub_field('texto_material_4_aluno'); ?>
                                            </div>
                                            <div class="popup-sobre-galeria owl-emenda">
                                                <div class="owl-material owl-carousel owl-theme">
                                                    <?php 
                                                $images = get_sub_field('galeria_material_4_aluno');
                                                if( $images ): ?>
                                                    <?php foreach( $images as $image ): ?>
                                                    <img src="<?php echo $image['sizes']['galeria-material']; ?>"
                                                        alt="<?php echo $image['alt']; ?>" />
                                                    <?php endforeach; endif; ?>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <?php endwhile; endif; ?>
                                    <?php echo wp_get_attachment_image( get_field('capa_material_4_aluno' ), 'material' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5 ANOS -->
                <div id="5anos" class="filtro-idade-item idade" style="display:none">
                    <h5 class="titulo-filtro-tipo">Escolha um tipo</h5>
                    <div class="filtro-tipo">
                        <button class="btn-filtro-tipo tablinktipo5 tipo-ativo"
                            onclick="openTipo5(event,'5anosprof')">Material do professor</button>
                        <button class="btn-filtro-tipo tablinktipo5" onclick="openTipo5(event,'5anosalun')">Material da
                            criança</button>
                    </div>
                    <!-- 5 ANOS PROFESSOR -->
                    <div id="5anosprof" class="filtro-tipo-item tipo5 btns-prof">
                        <div class="material-ciranda-conteudo">
                            <div class="material-ciranda-item">
                                <div class="material-ciranda-item-inner">
                                    <?php if( have_rows('lista_material_5_prof') ): $i = 0; while ( have_rows('lista_material_5_prof') ) : the_row(); $i++; ?>
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox
                                        data-src="#popup-material-5ap-<?php echo $i; ?>"
                                        href="javascript:;"><span></span><span></span></a>
                                    <!-- JANELA MODAL -->
                                    <div class="janela-modal-material" id="popup-material-5ap-<?php echo $i; ?>">
                                        <button data-fancybox-close class="btn-fecha">
                                            <span class="barrinha1"></span>
                                            <span class="barrinha2"></span>
                                        </button>
                                        <div class="janela-modal-sobre-inner">
                                            <div class="popup-sobre-info">
                                                <h5><?php the_sub_field('titulo_material_5_prof'); ?></h5>
                                                <?php the_sub_field('texto_material_5_prof'); ?>
                                            </div>
                                            <div class="popup-sobre-galeria owl-emenda">
                                                <div class="owl-material owl-carousel owl-theme">
                                                    <?php 
                                                $images = get_sub_field('galeria_material_5_prof');
                                                if( $images ): ?>
                                                    <?php foreach( $images as $image ): ?>
                                                    <img src="<?php echo $image['sizes']['galeria-material']; ?>"
                                                        alt="<?php echo $image['alt']; ?>" />
                                                    <?php endforeach; endif; ?>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <?php endwhile; endif; ?>
                                    <?php echo wp_get_attachment_image( get_field('capa_material_5_prof' ), 'material' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 5 ANOS ALUNO -->
                    <div id="5anosalun" class="filtro-tipo-item tipo5 btns-aluno45" style="display:none">
                        <div class="material-ciranda-item">
                            <div class="material-ciranda-item-inner">
                                <?php if( have_rows('lista_material_5_aluno') ): $i = 0; while ( have_rows('lista_material_5_aluno') ) : the_row(); $i++; ?>
                                <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox
                                    data-src="#popup-material-5aa-<?php echo $i; ?>"
                                    href="javascript:;"><span></span><span></span></a>
                                <!-- JANELA MODAL -->
                                <div class="janela-modal-material" id="popup-material-5aa-<?php echo $i; ?>">
                                    <button data-fancybox-close class="btn-fecha">
                                        <span class="barrinha1"></span>
                                        <span class="barrinha2"></span>
                                    </button>
                                    <div class="janela-modal-sobre-inner">
                                        <div class="popup-sobre-info">
                                            <h5><?php the_sub_field('titulo_material_5_aluno'); ?></h5>
                                            <?php the_sub_field('texto_material_5_aluno'); ?>
                                        </div>
                                        <div class="popup-sobre-galeria owl-emenda">
                                            <div class="owl-material owl-carousel owl-theme">
                                                <?php 
                                                $images = get_sub_field('galeria_material_5_aluno');
                                                if( $images ): ?>
                                                <?php foreach( $images as $image ): ?>
                                                <img src="<?php echo $image['sizes']['galeria-material']; ?>"
                                                    alt="<?php echo $image['alt']; ?>" />
                                                <?php endforeach; endif; ?>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <?php endwhile; endif; ?>
                                <?php echo wp_get_attachment_image( get_field('capa_material_5_aluno' ), 'material' ); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <a class="btn-padrao" data-fancybox data-src="#popup-formulario" href="javascript:;">Visualizar material
                demonstrativo</a>
            <!-- JANELA MODAL -->
            <div class="janela-modal-sobre" id="popup-formulario">
                <button data-fancybox-close class="btn-fecha">
                    <span class="barrinha1"></span>
                    <span class="barrinha2"></span>
                </button>
                <div class="janela-modal-sobre-inner">
                    <h5 class="tit-form-material-ciranda"><?php the_field('titulo_formulario_material_ciranda'); ?></h5>
                    <p class="txt-form-material-ciranda"><?php the_field('texto_formulario_material_ciranda'); ?></p>
                    <div class="quero-ciranda-form">
                        <?php echo do_shortcode( get_field('shortcode_formulario_material_ciranda') ); ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <img class="elemento-material-ciranda"
            src="<?php echo bloginfo('template_directory');?>/img/elemento-material-ciranda.png" alt="">
    </div>
</section>
<!-- QUERO CIRANDA -->
<section id="quero-ciranda" class="quero-ciranda-2">
    <div class="inner">
        <img class="formulario-ciranda-elemento-1"
            src="<?php echo bloginfo('template_directory');?>/img/elemento-formulario-ciranda-1.png" alt="">
        <div class="quero-ciranda-form">
            <h3><?php the_field('titulo_formulario_solicitacao'); ?></h3>
            <?php echo do_shortcode( get_field('shortcode_do_formulario') ); ?>
        </div>
        <img class="formulario-ciranda-elemento-2"
            src="<?php echo bloginfo('template_directory');?>/img/elemento-formulario-ciranda-2.png" alt="">
        <img class="formulario-ciranda-elemento-3"
            src="<?php echo bloginfo('template_directory');?>/img/elemento-formulario-ciranda-3.png" alt="">
    </div>
</section>
<!-- HISTORIAS CIRANDA -->
<section class="historias-ciranda-2">
    <div class="inner">
        <h3><?php the_field('titulo_depoimentos'); ?></h3>
        <!-- SLIDE -->
        <div class="historias-ciranda-slide-2 owl-emenda">
            <div id="owl-historias-ciranda" class="owl-carousel owl-theme">
                <!-- loop que carrega os resultados -->
                <?php if( have_rows('itens_depoimentos') ): while ( have_rows('itens_depoimentos') ) : the_row(); ?>
                <!-- ITEM -->
                <div class="historias-ciranda-item">
                    <div class="historias-ciranda-item-autor">
                        <?php echo wp_get_attachment_image( get_sub_field('imagem_depoimento' ), 'autor-imagem', '', array( 'class' => 'historias-ciranda-item-img' ) ); ?>
                    </div>
                    <div class="historias-ciranda-item-info">
                        <p><?php the_sub_field('depoimento_depoimento'); ?></p>
                        <h6><?php the_sub_field('autor_depoimento'); ?>, <?php the_sub_field('cargo_depoimento'); ?>
                        </h6>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <img class="elemento-depoimentos-ciranda-1"
            src="<?php echo bloginfo('template_directory');?>/img/elemento-depoimentos-ciranda-1.png" alt="">
        <img class="elemento-depoimentos-ciranda-2"
            src="<?php echo bloginfo('template_directory');?>/img/elemento-depoimentos-ciranda-2.png" alt="">
    </div>
</section>
<!-- PARCEIROS CIRANDA -->
<section class="parceiros-ciranda-2">
    <div class="inner">
        <h3><?php the_field('titulo_parceiros'); ?></h3>
        <div class="parceiros-ciranda-slide-2 owl-emenda">
            <div id="owl-parceiros-ciranda" class="owl-carousel owl-theme">
                <!-- loop que carrega os logos dos parceiros -->
                <?php if( have_rows('parceiros_itens') ): while ( have_rows('parceiros_itens') ) : the_row(); ?>
                <!-- ITEM -->
                <div class="parceiro-ciranda-item"><a href="<?php the_sub_field('link_para_o_site_do_parceiro'); ?>"
                        target="_blank"><?php echo wp_get_attachment_image( get_sub_field('logo_parceiro' ), 'logo-parceiro' ); ?></a>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<!-- RESULTADOS -->
<section id="resultados" class="resultados-ciranda-2">
    <div class="inner">
        <div class="resultados-ciranda-txt">
            <h3><?php the_field('titulo_resultados'); ?></h3>
            <p><?php the_field('texto_resultados'); ?></p>
        </div>
        <!-- SLIDE -->
        <div class="resultados-ciranda-slide-2 owl-emenda">
            <div id="owl-resultados-ciranda" class="owl-carousel owl-theme">
                <!-- loop que carrega os resultados -->
                <?php if( have_rows('itens_resultados') ): $i=0; while ( have_rows('itens_resultados') ) : $i++; the_row(); ?>
                <!-- ITEM -->
                <div class="resultados-ciranda-item">
                    <div class="resultados-ciranda-item-inner">
                        <div class="resultados-ciranda-item-topo">
                            <div class="resultados-ciranda-item-titulo">
                                <h6><?php the_sub_field('titulo_item_resultado'); ?></h6>
                                <span class="subtitulo"><?php the_sub_field('subtitulo_item_resultado'); ?></span>
                            </div>
                        </div>
                        <?php echo wp_get_attachment_image( get_sub_field('imagem_grafico_item_resultado' ), 'resultados', '', array( 'class' => 'resultados-ciranda-item-img' ) ); ?>
                    </div>
                    <a class="btn-resultado-ciranda" data-fancybox data-src="#popup-resultado-<?php echo $i; ?>"
                        href="javascript:;">Saiba mais</a>
                    <!-- JANELA MODAL -->
                    <div class="janela-modal-resultado" id="popup-resultado-<?php echo $i; ?>">
                        <button data-fancybox-close class="btn-fecha">
                            <span class="barrinha1"></span>
                            <span class="barrinha2"></span>
                        </button>
                        <div class="janela-modal-sobre-inner">
                            <div class="popup-resultado-titulo">
                                <h5><?php the_sub_field('titulo_item_resultado'); ?><span><?php the_sub_field('subtitulo_item_resultado'); ?></span>
                                </h5>
                            </div>
                            <div class="popup-resultado-info">
                                <p><?php the_sub_field('resumo_item_resultado'); ?></p>
                            </div>
                            <?php echo wp_get_attachment_image( get_sub_field('imagem_grafico_item_resultado'), 'full', '', array( 'class' => 'popup-resultado-img' ) ); ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <img class="elemento-resultados-ciranda-1"
            src="<?php echo bloginfo('template_directory');?>/img/elemento-resultados-ciranda-1.png" alt="">
        <img class="elemento-resultados-ciranda-2"
            src="<?php echo bloginfo('template_directory');?>/img/elemento-resultados-ciranda-2.png" alt="">
    </div>
</section>
<!-- RELATOS CIRANDA -->
<section class="relatos-ciranda-2">
    <div class="inner">
        <h3><?php the_field('titulo_3_posts'); ?></h3>
        <!-- PEGA A CATEGORIA -->
        <?php $cat3Posts = get_field('categoria_3_posts'); ?>
        <div class="relatos-ciranda-list">
            <?php
        // WP_Query arguments
        $argsPost = array(
            'post_type'        => array( 'post' ),
            'cat'              => $cat3Posts,           
            'posts_per_page'   => '3'            
        );        
        // The Query
        $the_query = new WP_Query( $argsPost ); 
        // The Loop
        if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

            <!-- ITEM -->
            <div class="relato-ciranda-item">
                <div class="relato-ciranda-item-categorias">
                    <?php the_category(); ?>
                    <!-- <a style="background-color: #FA8469" href="">Nome da editoria</a>
                    <a style="background-color: #DF4661" href="">Site</a>
                    <a style="background-color: #67A6C9" href="">Educação</a> -->
                </div>
                <div class="relato-ciranda-item-img">
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'blog-relacionada'); ?></a>
                </div>
                <a class="relato-ciranda-item-link" href="<?php the_permalink(); ?>">
                    <h4><?php the_title(); ?></h4>
                </a>
                <div class="data"><?php the_time('j \d\e F \d\e Y') ?></div>
            </div>

            <?php endwhile; endif; ?>
            <?php    
        /* Restore original Post Data */
        wp_reset_postdata();
        ?>
        </div>
        <a href="<?php echo esc_url( get_category_link( $cat3Posts ) ); ?>"
            class="btn-padrao"><?php the_field('rotulo_do_botao_3_posts'); ?></a>
    </div>
</section>
<!-- FIXA MENU NA ROLAGEM -->
<script>
$(window).load(function() {
    var $w = $(window);
    if ($(window).width() > 768) {
        $w.on("scroll", function() {
            if ($w.scrollTop() > 80) {
                $('#menu-ciranda').removeClass("mcmovel").addClass("mcfixo");
            } else {
                $('#menu-ciranda').removeClass("mcfixo").addClass("mcmovel");
            }
        });
    } else {

    }
});
</script>
<!--CONTROLA MENU, BUSCA -->
<script type="text/javascript">
$(document).ready(function() {
    //abertura e fechamento do menu   
    $("#btn-menu-ciranda").click(function() {
        $("#menu-ciranda").css("left", 0);
        $('body').css('overflow', 'hidden')
    });
    $("#btn-fecha-ciranda").click(function() {
        $("#menu-ciranda").css("left", "100vw");
        $('body').css("overflow", "auto")
    });
    if ($(window).width() < 769) {
        $(".mcitem").click(function() {
            $("#menu-ciranda").css("left", "100vw");
            $('body').css("overflow", "auto")
        });
    }
});
</script>
<!-- FILTROS TABS -->
<script>
//TAB IDADE
function openIdade(evt, faixaIdade) {
    var i, x, tablinks;
    x = document.getElementsByClassName("idade");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < x.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" idade-ativo", "");
    }
    document.getElementById(faixaIdade).style.display = "block";
    evt.currentTarget.className += " idade-ativo";
}
//TAB TIPO
function openTipo3(evt, tipoMaterial) {
    var i, x, tablinkstipo;
    x = document.getElementsByClassName("tipo3");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    tablinkstipo = document.getElementsByClassName("tablinktipo3");
    for (i = 0; i < x.length; i++) {
        tablinkstipo[i].className = tablinkstipo[i].className.replace(" tipo-ativo", "");
    }
    document.getElementById(tipoMaterial).style.display = "block";
    evt.currentTarget.className += " tipo-ativo";
}

function openTipo4(evt, tipoMaterial) {
    var i, x, tablinkstipo;
    x = document.getElementsByClassName("tipo4");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    tablinkstipo = document.getElementsByClassName("tablinktipo4");
    for (i = 0; i < x.length; i++) {
        tablinkstipo[i].className = tablinkstipo[i].className.replace(" tipo-ativo", "");
    }
    document.getElementById(tipoMaterial).style.display = "block";
    evt.currentTarget.className += " tipo-ativo";
}

function openTipo5(evt, tipoMaterial) {
    var i, x, tablinkstipo;
    x = document.getElementsByClassName("tipo5");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    tablinkstipo = document.getElementsByClassName("tablinktipo5");
    for (i = 0; i < x.length; i++) {
        tablinkstipo[i].className = tablinkstipo[i].className.replace(" tipo-ativo", "");
    }
    document.getElementById(tipoMaterial).style.display = "block";
    evt.currentTarget.className += " tipo-ativo";
}
</script>
<!--  
JS: Integracao com Mailchimp 
[25/10/2019 - Fernando] - [04/02/2020 - Christiano]
-->
<script>
$(".wpcf7-submit").click(function(event) {
    // event.preventDefault();

    const basepathAPI = "https://mathema.com.br/api/v2";

    // console.log("Nome: " + $("input[name=mc4wp-NAME]")[1].value);
    // console.log("Email: " + $("input[name=mc4wp-EMAIL]")[1].value);
    // console.log("Atuacao: " + $("select[name=atuacao]").val());
    // console.log("Instituicao: " + $("input[name=instituicao]")[1].value);
    // console.log("Telefone: " + $("input[name=telefone]")[1].value);
    // console.log("numero-alunos: " + $("select[name=numero-alunos]")[1].value);
    // console.log("Cidade: " + $("input[name=cidade]")[1].value);
    // console.log("Estado: " + $("select[name=estado]")[1].value);


    var form = new FormData();
    form.append("list_id", "f3397d3993");
    form.append("status", "subscribed");
    form.append("email_address", $("input[name=mc4wp-EMAIL]")[1].value);
    form.append("merge_fields[NOME]", $("input[name=mc4wp-NAME]")[1].value);
    form.append("merge_fields[TELEFONE]", $("input[name=telefone]")[1].value);
    form.append("merge_fields[INSTITUICA]", $("input[name=instituicao]")[1].value);
    form.append("merge_fields[CIDADE]", $("input[name=cidade]")[1].value);
    form.append("merge_fields[ESTADO]", $("select[name=estado]")[1].value);
    form.append("merge_fields[N_ALUNOS]", $("select[name=numero-alunos]")[1].value);
    form.append("merge_fields[N_ALUNOS]", $("select[name=numero-alunos]")[1].value);
    form.append("tags[]", ["Ciranda visualização"]);

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
<!-- OWL CAROUSEL -->
<script>
$(document).ready(function() {
    var owl3 = $('#owl-resultados-ciranda');
    owl3.owlCarousel({
        autoplay: false,
        rewind: true,
        dots: true,
        loop: false,
        thumbs: false,
        responsive: {
            0: {
                items: 1,
                margin: 0,
                nav: false
            },
            769: {
                items: 2,
                margin: 0,
                nav: true
            }
        }
    })
    var owl4 = $('#owl-historias-ciranda');
    owl4.owlCarousel({
        autoplay: false,
        autoplayHoverPause: true,
        items: 1,
        margin: 0,
        dots: true,
        loop: true,
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
    var owl6 = $('.owl-material');
    owl6.owlCarousel({
        autoplay: false,
        margin: 0,
        items: 1,
        dots: true,
        loop: true,
        thumbs: false,
        responsive: {
            0: {
                nav: false,
            },
            769: {
                nav: true,
            }
        }
    })
})
</script>
<!-- FOOTER -->
<?php get_footer(); ?>