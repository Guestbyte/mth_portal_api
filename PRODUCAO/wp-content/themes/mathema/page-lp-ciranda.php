<?php /* Template Name: LP Ciranda */ ?>
<?php get_header(); ?>
<!-- MENU CIRANDA -->
<!-- <nav id="menu-lp-ciranda" class="menu-ciranda">
    <ul>
    <a class="animate" href="#topo"><img class="logo-ciranda-menu" src="<?php echo bloginfo('template_directory');?>/img/logo-lp-ciranda-s.png" alt=""></a>
        <li><a class="animate" href="#quero-ciranda">Formulário</a></li>
        <li><a class="animate" href="#video">Vídeo</a></li>
        <li><a class="animate" href="#material">Material Didático</a></li>        
        <li><a class="animate" href="#depoimentos">Depoimentos</a></li>
        <li><a class="animate" href="#parceiros">Parceiros</a></li>
    </ul>
</nav> -->


<section id="topo-lp" class="topo-lp-ciranda">
    <div class="inner">     
        <a class="animate" href="#topo"><img class="logo-lpc-topo" src="<?php echo bloginfo('template_directory');?>/img/logo-lp-ciranda-s.png" alt="Ciranda"></a>
        <!-- BOTÃO -->
        <button class="btn-menu btn-menu-lp">
            <span></span>
            <span></span>
            <span></span>
        </button>              
        <ul id="menu-lp" class="menu-topo-lp-ciranda">
            <button id="btn-fecha-menu-lp" class="btn-fecha">
                <span class="barrinha1"></span>
                <span class="barrinha2"></span>
            </button>
            <li><a class="animate" href="#quero-ciranda">Formulário</a></li>
            <li><a class="animate" href="#video">Vídeo</a></li>
            <li><a class="animate" href="#material">Material Didático</a></li>        
            <li><a class="animate" href="#depoimentos">Depoimentos</a></li>
            <li><a class="animate" href="#parceiros">Parceiros</a></li>   
        </ul>        
        <div class="clearfix"></div> 
    </div>
</section>


<!-- CIRANDA -->
<section id="topo" class="banner-lp-ciranda">
    <div class="inner">
        <!-- BOTÃO -->
        <button class="btn-menu btn-menu-lp-ciranda">
            <span></span>
            <span></span>
            <span></span>
        </button>              
        <!-- BOX PRIMÁRIO -->
        <div class="box-primario">
            <div class="box-primario-inner">
                <h1><img src="<?php the_field('imagem_primario'); ?>" alt="<?php the_field('titulo_primario'); ?>"></h1>
                <h2><?php the_field('titulo_primario'); ?></h2>
                <p><?php the_field('texto_primario'); ?></p>
                <?php if(get_field('rotulo_botao_primario')): ?>
                <a class="btn-padrao animate" href="#material"><?php the_field('rotulo_botao_primario'); ?></a>
                <?php endif; ?>
            </div>                
          
        </div>
        <!-- ELEMENTOS CIRANDA -->
        <img class="img-lp-ciranda" src="<?php echo bloginfo('template_directory');?>/img/topo-lp-ciranda.png" alt="">
             
    </div>        
</section>
<?php //$pageQuery = new WP_Query( 'page_id=701' ); $pageQuery->the_post(); ?>
<?php $pageQuery = new WP_Query( 'page_id=40387' ); $pageQuery->the_post(); ?>
<!-- SOBRE -->
<section id="sobre" class="sobre-ciranda">
    <div class="inner">
        <h3>Ciranda: muito mais que um material didático!</h3>
        <p>Ele foi pensado a partir de vivências que inserem as crianças em práticas sociais e culturais criativas e interativas, promovendo aprendizados significativos.</p>
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
                <a class="btn-sobre-ciranda" data-fancybox data-src="#popup-sobre-<?php echo $i; ?>" href="javascript:;"><span></span><span></span></a>                 
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
<?php if(get_field('video_unico')): ?>
<section id="video" class="video-quero-ciranda-2">
    <div class="inner">
        <div class="video">
        
        <?php the_field('video_unico'); ?>

        <!--<iframe title="Ciranda - Educação Infantil" src="https://www.youtube.com/embed/w-8rIHf1ryA?feature=oembed" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" width="640" height="360" frameborder="0"></iframe> -->
        
        </div>        
    </div>
    <img class="elemento-video-ciranda-1" src="<?php echo bloginfo('template_directory');?>/img/elemento-video-ciranda-1.png" alt="">
    <img class="elemento-video-ciranda-2" src="<?php echo bloginfo('template_directory');?>/img/elemento-video-ciranda-2.png" alt="">
    <img class="elemento-video-ciranda-3" src="<?php echo bloginfo('template_directory');?>/img/elemento-video-ciranda-3.png" alt="">
    <img class="elemento-video-ciranda-4" src="<?php echo bloginfo('template_directory');?>/img/elemento-video-ciranda-4.png" alt="">
    <img class="elemento-video-ciranda-5" src="<?php echo bloginfo('template_directory');?>/img/elemento-video-ciranda-5.png" alt="">   
</section>
<?php endif; ?>
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
                    <button class="btn-filtro-idade tablink idade-ativo" onclick="openIdade(event,'3anos')">3 anos</button>
                    <button class="btn-filtro-idade tablink" onclick="openIdade(event,'4anos')">4 anos</button>
                    <button class="btn-filtro-idade tablink" onclick="openIdade(event,'5anos')">5 anos</button>
                </div>

                <!-- 3 ANOS -->                
                <div id="3anos" class="filtro-idade-item idade">
                    <h5 class="titulo-filtro-tipo">Escolha um tipo</h5>
                    <div class="filtro-tipo">
                        <button class="btn-filtro-tipo tablinktipo3 tipo-ativo" onclick="openTipo3(event,'3anosprof')">Material do professor</button>
                        <button class="btn-filtro-tipo tablinktipo3" onclick="openTipo3(event,'3anosalun')">Material da criança</button>
                    </div>
                    <!-- 3 ANOS PROFESSOR --> 
                    <div id="3anosprof" class="filtro-tipo-item tipo3 btns-prof">
                        <div class="material-ciranda-conteudo">
                            <div class="material-ciranda-item">
                                <div class="material-ciranda-item-inner">
                                    <?php if( have_rows('lista_material_3_prof') ): $i = 0; while ( have_rows('lista_material_3_prof') ) : the_row(); $i++; ?>
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox data-src="#popup-material-3ap-<?php echo $i; ?>" href="javascript:;"><span></span><span></span></a>                                        
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
                                                <img src="<?php echo $image['sizes']['galeria-material']; ?>" alt="<?php echo $image['alt']; ?>" />                                                           
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
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox data-src="#popup-material-3aa-<?php echo $i; ?>" href="javascript:;"><span></span><span></span></a>                                        
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
                                                <img src="<?php echo $image['sizes']['galeria-material']; ?>" alt="<?php echo $image['alt']; ?>" />                                                           
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
                        <button class="btn-filtro-tipo tablinktipo4 tipo-ativo" onclick="openTipo4(event,'4anosprof')">Material do professor</button>
                        <button class="btn-filtro-tipo tablinktipo4" onclick="openTipo4(event,'4anosalun')">Material da criança</button>
                    </div>
                    <!-- 4 ANOS PROFESSOR -->  
                    <div id="4anosprof" class="filtro-tipo-item tipo4 btns-prof">
                        <div class="material-ciranda-conteudo">
                            <div class="material-ciranda-item">
                                <div class="material-ciranda-item-inner">
                                    <?php if( have_rows('lista_material_4_prof') ): $i = 0; while ( have_rows('lista_material_4_prof') ) : the_row(); $i++; ?>
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox data-src="#popup-material-4ap-<?php echo $i; ?>" href="javascript:;"><span></span><span></span></a>                                        
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
                                                <img src="<?php echo $image['sizes']['galeria-material']; ?>" alt="<?php echo $image['alt']; ?>" />                                                           
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
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox data-src="#popup-material-4aa-<?php echo $i; ?>" href="javascript:;"><span></span><span></span></a>                                        
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
                                                <img src="<?php echo $image['sizes']['galeria-material']; ?>" alt="<?php echo $image['alt']; ?>" />                                                           
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
                        <button class="btn-filtro-tipo tablinktipo5 tipo-ativo" onclick="openTipo5(event,'5anosprof')">Material do professor</button>
                        <button class="btn-filtro-tipo tablinktipo5" onclick="openTipo5(event,'5anosalun')">Material da criança</button>
                    </div>
                    <!-- 5 ANOS PROFESSOR -->   
                    <div id="5anosprof" class="filtro-tipo-item tipo5 btns-prof">
                        <div class="material-ciranda-conteudo">
                            <div class="material-ciranda-item">
                                <div class="material-ciranda-item-inner">
                                    <?php if( have_rows('lista_material_5_prof') ): $i = 0; while ( have_rows('lista_material_5_prof') ) : the_row(); $i++; ?>
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox data-src="#popup-material-5ap-<?php echo $i; ?>" href="javascript:;"><span></span><span></span></a>                                        
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
                                                <img src="<?php echo $image['sizes']['galeria-material']; ?>" alt="<?php echo $image['alt']; ?>" />                                                           
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
                                    <a class="btn-material-ciranda btn-material-ciranda-<?php echo $i; ?>" data-fancybox data-src="#popup-material-5aa-<?php echo $i; ?>" href="javascript:;"><span></span><span></span></a>                                        
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
                                                <img src="<?php echo $image['sizes']['galeria-material']; ?>" alt="<?php echo $image['alt']; ?>" />                                                           
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
            <a class="btn-padrao" data-fancybox data-src="#popup-formulario" href="javascript:;">Visualizar material demonstrativo</a>
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
        <img class="elemento-material-ciranda" src="<?php echo bloginfo('template_directory');?>/img/elemento-material-ciranda.png" alt="">
    </div>
</section>
<!-- QUERO CIRANDA -->
<section id="quero-ciranda" class="quero-ciranda-2">
    <div class="inner">
        <img class="formulario-ciranda-elemento-1" src="<?php echo bloginfo('template_directory');?>/img/elemento-formulario-ciranda-1.png" alt="">
        <div class="quero-ciranda-form">
            <h3><?php the_field('titulo_formulario_solicitacao'); ?></h3>           
            <?php echo do_shortcode( get_field('shortcode_do_formulario') ); ?>
        </div>        
        <img class="formulario-ciranda-elemento-2" src="<?php echo bloginfo('template_directory');?>/img/elemento-formulario-ciranda-2.png" alt="">
        <img class="formulario-ciranda-elemento-3" src="<?php echo bloginfo('template_directory');?>/img/elemento-formulario-ciranda-3.png" alt="">
    </div>
</section>
<!-- HISTORIAS CIRANDA -->
<section id="depoimentos" class="historias-ciranda-2">
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
                        <h6><?php the_sub_field('autor_depoimento'); ?>, <?php the_sub_field('cargo_depoimento'); ?></h6>                        
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>            
        </div>
        <div class="clearfix"></div>     
        <img class="elemento-depoimentos-ciranda-1" src="<?php echo bloginfo('template_directory');?>/img/elemento-depoimentos-ciranda-1.png" alt="">
        <img class="elemento-depoimentos-ciranda-2" src="<?php echo bloginfo('template_directory');?>/img/elemento-depoimentos-ciranda-2.png" alt="">       
    </div>
</section>
<!-- PARCEIROS CIRANDA -->
<section id="parceiros" class="parceiros-ciranda-2">
    <div class="inner">
        <h3><?php the_field('titulo_parceiros'); ?></h3>
        <div class="parceiros-ciranda-slide-2 owl-emenda">
            <div id="owl-parceiros-ciranda" class="owl-carousel owl-theme">
                <!-- loop que carrega os logos dos parceiros -->
                <?php if( have_rows('parceiros_itens') ): while ( have_rows('parceiros_itens') ) : the_row(); ?>            
                <!-- ITEM -->
                <div class="parceiro-ciranda-item"><a href="<?php the_sub_field('link_para_o_site_do_parceiro'); ?>" target="_blank"><?php echo wp_get_attachment_image( get_sub_field('logo_parceiro' ), 'logo-parceiro' ); ?></a></div>
                <?php endwhile; endif; ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<!-- RODAPÉ -->
<section class="rodape">
    <div class="inner">
        <img class="logo-mathema-lp-ciranda" src="<?php echo bloginfo('template_directory');?>/img/logo-mathema-lp-ciranda.png" alt="Mathema">
        <div class="endereco-lp-ciranda">
            <p>Rua Professor Aprígeo Gonzaga, 78, 13º andar<br>São Judas, São Paulo, SP | 04303-000 </p>
            <p>Tel: +55 11 5548 6912 | 5567 6912</p>
            <p>E-mail: contato@mathema.com.br</p>
        </div>
        <div class="direitos-lp-ciranda">© <?php echo date("Y"); ?>. Ciranda. .Site desenvolvido por NascerWeb e Amí Comunicação & Design</div>
    </div>
</section>

<?php wp_reset_postdata(); ?>
<!-- REMOVE ITENS -->
<script>
$(document).ready(function() {
    $('#busca').remove();
    $('header').remove();
    $('footer').remove();
    $('body').addClass('lpc');
    if( $(window).width() < 769){
       
    }
})
</script>
<!-- FIXA MENU NA ROLAGEM -->
<!--SHRINK MENU-->
<script type="text/javascript">
$( document ).on("scroll", function() {  
    if($( document ).scrollTop() > 160) {  
        $("#topo-lp").removeClass("topo-lp-e-c").addClass("topo-lp-v-c");                    
    } else {
        $("#topo-lp").removeClass("topo-lp-v-c").addClass("topo-lp-e-c");          
    } 
});
</script>
<!--CONTROLA MENU, BUSCA -->
<script type="text/javascript">
$(document).ready(function(){
    //abertura e fechamento do menu   
    $(".btn-menu-lp").click(function(){            
        $("#menu-lp").css("left", 0);
        $('body').css('overflow', 'hidden');           
    });
    $(".btn-menu-lp-ciranda").click(function(){            
        $("#menu-lp").css("left", 0);
        $('body').css('overflow', 'hidden');           
    });
    $("#btn-fecha-menu-lp").click(function(){       
        $("#menu-lp").css("left", "100vw");
        $('body').css("overflow", "auto");   					
    });
    $("#menu-lp li a").click(function(){       
        $("#menu-lp").css("left", "100vw");
        $('body').css("overflow", "auto");   					
    });      
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
        responsive:{
            0:{           
                items: 1,        
                margin: 0,     
                nav: false
            },
            769:{ 
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
        nav: true,        
        loop: true,
        thumbs: false,
        responsive:{
            0:{               
                dots: false
            },
            769:{               
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
        responsive:{
            0:{               
                items: 2,
                slideBy: 2
            },
            769:{               
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
        responsive:{
            0:{               
                nav: false,
            },
            769:{               
                nav: true,
            }
        }                               
    })        
})
</script>
<!-- FOOTER -->
<?php get_footer(); ?>