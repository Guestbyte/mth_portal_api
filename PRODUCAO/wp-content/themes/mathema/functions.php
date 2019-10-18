<?php
/*	
* 18/10 - Incluído action 'add_cpf_no_moodle'
*/


//Miniaturas de Post  (já estava disponível no v2.9)

add_theme_support('post-thumbnails');

add_image_size('home', 1920, 1080, true);
add_image_size('logo-box-primario', 286, 999, false);
add_image_size('icone-card', 128, 100, false);
add_image_size('icone-por-que', 145, 145, false);
add_image_size('nossos-numeros', 1920, 635, true);
add_image_size('autor-imagem', 188, 188, true);
add_image_size('aba-vertical', 490, 430, true);
add_image_size('resultado', 668, 325, false);
add_image_size('logo-parceiro', 150, 80, false);
add_image_size('icone-missao', 93, 93, false);
add_image_size('linha-tempo', 268, 133, true);
add_image_size('topo', 1920, 590, true);
add_image_size('membro', 232, 232, true);
add_image_size('download', 350, 234, true);
add_image_size('icone-contato', 73, 73, false);
add_image_size('bg-titulo-historia', 555, 475, false);
add_image_size('aba-accordion', 826, 235, true);
add_image_size('projeto-especial', 320, 280, true);
add_image_size('infografico', 1100, 420, false);
add_image_size('bg-conheca-planos', 1920, 582, true);
add_image_size('imagem-galeria', 760, 440, true);
add_image_size('icone-plano', 115, 132, false);
add_image_size('trabalhe', 555, 999, false);
add_image_size('ad-busca', 320, 420, true);
add_image_size('atividade-ciranda', 195, 215, true);
// blog
add_image_size('blog-home-1', 365, 215, true);
add_image_size('blog-home-2', 400, 435, true);
add_image_size('blog-home-botao', 425, 220, true);
add_image_size('topo-blog', 945, 450, true);
add_image_size('thumb-blog', 360, 362, true);
add_image_size('topo-single', 760, 450, true);
add_image_size('blog-relacionada', 344, 200, true);
//video
add_image_size('capa-video', 173, 97, true);
//ícone menu
add_image_size('icone-menu', 89, 75, true);



//Uso: the_post_thumbnail();

//Usar post_thumbnail como background

function thumbnail_bg($tamanho)
{
	global $post;
	$get_post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $tamanho, false, '');
	echo 'style="background-image: url(' . $get_post_thumbnail[0] . ' )"';
}


//Menu Dinamico

add_theme_support('menus');

//Exibe a descrição do item do menu

function prefix_nav_description($item_output, $item, $depth, $args)
{
	if (!empty($item->description)) {
		$item_output = str_replace($args->link_after . '</a>', '<span class="menu-item-description">' . $item->description . '</span>' . $args->link_after . '</a>', $item_output);
	}

	return $item_output;
}
add_filter('walker_nav_menu_start_el', 'prefix_nav_description', 10, 4);

//Sidebar Dinamica 
function wpdocs_theme_slug_widgets_init()
{

	if (function_exists('register_sidebar')) {

		$sidebar1 = array(
			'name'          => __('Filtros cursos online', 'textdomain'),
			'id'            => 'filtros-formacao-online',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		);
		$sidebar2 = array(
			'name'          => __('Filtros cursos presenciais', 'textdomain'),
			'id'            => 'filtros-formacao-presencial',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		);


		register_sidebar($sidebar1);
		register_sidebar($sidebar2);
	}
}
add_action('widgets_init', 'wpdocs_theme_slug_widgets_init');


/* PEGA A CATEGORIA PRIMÁRIA DEFINIDA NO PLUGIN YOAST SEO
 */

function get_primary_category()
{

	$cat_name = ''; // seta a varíavel que exibe a categoria

	if (!isset($cat_name) || $cat_name == '') {

		if (class_exists('WPSEO_Primary_Term')) {

			// Mostrar a categoria 'Principal' do post, se este recurso do Yoast estiver disponível, e um estiver definido. categoria pode ser substituída por termos personalizados

			$wpseo_primary_term = new WPSEO_Primary_Term('category', get_the_id());

			$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
			$term               = get_term($wpseo_primary_term);

			if (is_wp_error($term)) {
				$categories = get_the_category(get_the_ID(), 'category');
				echo '<a class="categoria" href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
			} else {
				$cat_name = $term->name;

				echo '<a class="categoria" href="' . esc_url(get_category_link($term->term_id)) . '">' . esc_html($cat_name) . '</a>';
			}
		} else {
			$categories = get_the_category(get_the_ID(), 'category');

			echo '<a class="categoria" href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
		}
	}
}

/*-------------------------------------------------------
WOOCOMMERCE
-------------------------------------------------------*/
//adiciona suporte ao Woocommerce
add_theme_support('woocommerce');
//desativa o css do woocommerce
add_filter('woocommerce_enqueue_styles', 'jk_dequeue_styles');
function jk_dequeue_styles($enqueue_styles)
{
	unset($enqueue_styles['woocommerce-general']);	// Remove the gloss
	unset($enqueue_styles['woocommerce-layout']);		// Remove the layout
	unset($enqueue_styles['woocommerce-smallscreen']);	// Remove the smallscreen optimisation
	return $enqueue_styles;
}
add_filter('woocommerce_enqueue_styles', '__return_false');

//remove hooks padrão
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

//altera a quantidade de produtos por página
add_filter('loop_shop_per_page', 'new_loop_shop_per_page', 20);

function new_loop_shop_per_page($cols)
{
	$cols = 9;
	return $cols;
}

//altera a quantidade de produtos por linha
add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
	function loop_columns()
	{
		return 3; // 3 products per row
	}
}

//remove contador de produtos
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

//remove item de ordenação
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

//personaliza produto do loop
// abre a div info do produto e carrega os campos personalizados
add_action('woocommerce_before_shop_loop_item_title', 'abre_div_homeiteminfo', 11);
function abre_div_homeiteminfo()
{
	echo '<div class="loja-home-item-info">';
	//só exibe o campo duração se tiver conteúdo
	if (get_field('carga_horaria_produto')) :
		echo '<div class="duracao">' . get_field('carga_horaria_produto') . ' horas</div>';
	endif;
	//exibe o campo fase escolar que é check box	
	$publicos = get_field('publico_produto');
	if ($publicos) :
		echo '<div class="publico">';
		foreach ($publicos as $publico) :
			echo '<div class="publico-item">' . $publico . '</div>';
		endforeach;
		echo '</div>';
	endif;
	//só exibe o autor(es) do produto  se tiver conteúdo
	$rows = get_field('autores_produto');
	if ($rows) {
		echo '<div class="autor">';
		foreach ($rows as $row) {
			echo '<span>' . $row['nome_do_autor_produto'] . '</span>';
		}
		echo '</div>';
	}
}

// fecha a div info do produto
add_action('woocommerce_after_shop_loop_item', 'fecha_div_homeiteminfo', 11);
function fecha_div_homeiteminfo()
{
	echo '</div>';
}

// abre a div preço do produto
add_action('woocommerce_after_shop_loop_item_title', 'abre_div_homeiteminfoprice', 9);
function abre_div_homeiteminfoprice()
{
	echo '<div class="preco">';
}

// fecha a div preço do produto e carrega o campo personalizado
add_action('woocommerce_after_shop_loop_item_title', 'fecha_div_homeiteminfoprice', 11);
function fecha_div_homeiteminfoprice()
{
	//pega o valor do ACF para comparar
	$num = get_field('numero_de_parcelas_produto');
	$int = (int) $num;

	if ($int > 3) :
		echo '<div class="parcelamento">em até ' . get_field('numero_de_parcelas_produto') . 'x com juros</div></div>';
	else :
		echo '<div class="parcelamento">em até ' . get_field('numero_de_parcelas_produto') . 'x sem juros</div></div>';
	endif;
}

//exibe a categoria do produto

function exibe_cat_product_home()
{
	$terms_product = get_the_terms($post->ID, 'product_cat');

	echo '<div class="categoria-loja">';
	foreach ($terms_product as $term_product) {
		//excluí a categoria Destaque home da lista
		if ($term_product->term_id != 69) {

			$term_link = get_term_link($term_product);

			echo '<a href="' . esc_url($term_link) . '">' . $term_product->name . '</a>';
		}
	}
	echo '</div>';
}
add_action('woocommerce_before_shop_loop_item', 'exibe_cat_product_home', 9);

//remove o botão comprar do produto do loop
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

/* PÁGINAS DAS CATEGORIAS DE PRODUTOS */

/* remove os produtos da categoria "planos" */
/* add_action( 'woocommerce_product_query', 'bbloomer_hide_products_category_shop' );   
function bbloomer_hide_products_category_shop( $q ) {  
    $tax_query = (array) $q->get( 'tax_query' );  
    $tax_query[] = array(
           'taxonomy' => 'product_cat',
           'field' => 'slug',
           'terms' => array( 'planos' ), // Category slug here
           'operator' => 'NOT IN'
    );  
    $q->set( 'tax_query', $tax_query );  
}
 */
// personaliza as categorias, se for "planos" usa um layout diferente
add_action('woocommerce_archive_description', 'woocommerce_category_image', 2);
function woocommerce_category_image()
{
	/**SE FOR A CATEGORIA PLANOS O TEMPLATE FICA DIFERENTE */
	if (is_product_category('planos')) {
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		$catID = $cat->term_id;


		/**carrega a imagem da categoria */
		$thumbnail_id = get_woocommerce_term_meta($catID, 'thumbnail_id', true);
		$image = wp_get_attachment_url($thumbnail_id);
		/** carrega os campos personalizados da categoria do produto */
		$rows = get_field('slide_categoria_de_produto', 'product_cat_' . $catID);
		/**coloca a imagem de background e monta o slider */
		if ($image) {
			echo '<div id="topo-cat-produto" class="topo-cat-produto" style="background-image:url(' . $image . ')">
							<div class="inner">
								<div class="box-cat-produto-planos">';
			//breadcrumb								
			?>
			<div class="breadcrumbs">
				<span><a href="<?php echo bloginfo('url'); ?>"><span>Home</span></a></span>
				<span class="separador-breadcrumb"> › </span>
				<span><a href="<?php echo bloginfo('url'); ?>/categoria-produto/formacao-online/"><span>Loja</span></a></span>
				<span class="separador-breadcrumb"> › </span>
				<span><a href="<?php echo bloginfo('url'); ?>/categoria-produto/formacao-online/"><span>Formação online</span></a></span>
			</div>
		<?php
					echo '<h1>' . $cat->name . '</h1>
									<p>' . $cat->description . '</p>
								</div>

						
							</div>
						</div>';
				} ?>
		<!-- LISTA OS 2 BOXES COM OS PLANOS ANUAIS -->
		<div class="list-planos">
			<!-- carrega os campos personalizados da categoria do produto -->
			<?php if (have_rows('planos_cursos', 'product_cat_' . $catID)) : while (have_rows('planos_cursos', 'product_cat_' . $catID)) : the_row(); ?>
					<div class="plano-item">
						<img class="plano-item-img" src="<?php the_sub_field('icone_plano'); ?>" alt="<?php the_sub_field('titulo_plano'); ?>">
						<h1><?php the_sub_field('titulo_plano'); ?></h1>
						<div class="txt-plano">
							<p><?php the_sub_field('texto_plano'); ?></p>
						</div>
						<div class="carac-plano"><?php the_sub_field('caracteristicas_plano'); ?></div>
						<div class="valor-plano">
							<div class="periodo-plano"><?php the_sub_field('periodo_plano'); ?></div>
							<div class="parcelas-plano"><?php the_sub_field('valor_parcelado_plano'); ?></div>
							<div class="avista-plano"><?php the_sub_field('valor_a_vista_plano'); ?></div>
						</div>
						<!-- INÍCIO LISTA OS PRODUTOS DO PLANO -->
						<div class="list-produtos-plano">
							<?php
											if (have_rows('produtos_plano')) : while (have_rows('produtos_plano')) : the_row(); ?>
									<!-- pega os elementos do produto (botão) usando o ID -->
									<?php $product = wc_get_product(get_sub_field('produto_plano_item')); ?>
									<div class="produto-plano-item">
										<!-- retira da string (título do produto as palavras "Plano anual ") -->
										<h2><?php the_sub_field('nome_de_exibicao_produto_plano_item'); ?></h2>
										<form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
											<button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
										</form>
									</div>
							<?php endwhile;
											endif;    ?>
							<!-- FIM LISTA OS PRODUTOS DO PLANO -->
						</div>
					</div>
			<?php endwhile;
					endif; ?>
		</div>

	<?php
			/**SE FOR OUTRA CATEGORIA O TEMPLATE É IGUAL PRA TODAS */
		} else {
			global $wp_query;
			$cat = $wp_query->get_queried_object();
			$parent = $cat->parent;
			/**se for subcategoria usa o ID da categoria pai */
			if (0 < $cat->parent) {
				$isChild = $parent;
			} else {
				$isChild = $cat->term_id;
			};
			/**carrega a imagem da categoria */
			$thumbnail_id = get_woocommerce_term_meta($isChild, 'thumbnail_id', true);
			$image = wp_get_attachment_url($thumbnail_id);
			/** carrega os campos personalizados da categoria do produto */
			$rows = get_field('slide_categoria_de_produto', 'product_cat_' . $isChild);
			/**coloca a imagem de background e monta o slider */
			if ($image) {
				echo '<div id="topo-cat-produto" class="topo-cat-produto" style="background-image:url(' . $image . ')">
				<div class="inner">
					<div class="box-cat-produto owl-emenda">
						<div id="owl-box-cat" class="owl-carousel owl-theme">';
				if ($rows) {
					foreach ($rows as $row) {
						echo '<div class="box-cat-produto-item">
											<h1>' . $row['titulo_slide_categoria_de_produto'] . '</h1>
											<p>' . $row['texto_slide_categoria_de_produto'] . '</p>	
										</div>';
					}
				}
				echo '</div>
					</div>
				</div>
				</div>';
			}
		}
	}

	//exibe os ícones porque mathema
	add_action('woocommerce_before_shop_loop', 'woocommerce_icones_mathema', 10);
	function woocommerce_icones_mathema()
	{
		/**SE FOR A CATEGORIA PLANOS NÃO EXIBE OS ÍCONES */
		if (is_product_category('planos')) { } else {
			global $wp_query;
			$cat = $wp_query->get_queried_object();
			$parent = $cat->parent;
			/**se for subcategoria usa o ID da categoria pai */
			if (0 < $cat->parent) {
				$isChild = $parent;
			} else {
				$isChild = $cat->term_id;
			};
			/** carrega os campos personalizados da categoria do produto */
			$rows = get_field('icones_por_que_o_mathema', 'product_cat_' . $isChild);
			if ($rows) {
				echo '<div class="icones-porque">       
						<div class="list-porque inner">';
				if ($rows) {
					foreach ($rows as $row) {
						echo '<div class="porque-item"><img src="' . $row['icone_por_que_o_mathema'] . '" alt="' . $row['texto_por_que_o_mathema'] . '"><span>' . $row['texto_por_que_o_mathema'] . '</span></div>';
					}
				}
				echo '</div>        
						</div>';
			}
		}
	}


	//select que lista as categorias filhas da atual
	add_action('woocommerce_before_shop_loop', 'woocommerce_categories_child', 11);
	function woocommerce_categories_child()
	{
		//SE FOR A CATEGORIA PLANOS NÃO EXIBE O SELECT/
		if (is_product_category('planos')) { } elseif (is_product_category('formacao-online')) {
			?>
		<div class="filtros-container">
			<?php dynamic_sidebar('filtros-formacao-online'); ?>
		</div>
	<?php
		} elseif (is_product_category('formacao-presencial')) {
			?>
		<div class="filtros-container">
			<?php dynamic_sidebar('filtros-formacao-presencial'); ?>
		</div>
	<?php
		} else {
			global $wp_query;
			$cat = $wp_query->get_queried_object();
			$parent = $cat->parent;
			//exibe nome da categoria parent
			$termCat = get_term($parent, 'product_cat');
			$parentName = $termCat->name;
			//$parentName = $parent->name; 
			//se for subcategoria usa o ID da categoria pai
			if (0 < $cat->parent) {
				$isChild = $parent;
				$isChildCatName = $parentName;
			} else {
				$isChild = $cat->term_id;
				$isChildCatName =	$cat->name;
			};

			//select que lista as categorias filhas da atual
			$categories = get_term_children($isChild, 'product_cat');
			if ($categories && !is_wp_error($category)) {
				echo '<div class="inner select-categorias-container">
						<select class="select-categorias" id="select-categorias">';
				echo '<option value="">Escolha uma categoria</option>';
				echo '<option value="' . get_term_link($isChild) . '">' . $isChildCatName . ' (Todos os itens)</option>';
				foreach ($categories as $category) {
					$term = get_term($category, 'product_cat');
					echo '<option value="' . get_term_link($term) . '">' . $term->name . '</option>';
				}
				echo '</select>
					</div>';
			}
		}
	}


	//elementos da página formação online
	add_action('woocommerce_after_shop_loop', 'woocommerce_elementos_formacao_online', 15);
	function woocommerce_elementos_formacao_online()
	{
		/**SE FOR A CATEGORIA PLANOS NÃO EXIBE OS ELEMENTOS */
		if (is_product_category('planos')) { } else {
			global $wp_query;
			$cat = $wp_query->get_queried_object();
			$parent = $cat->parent;
			/**se for subcategoria usa o ID da categoria pai */
			if (0 < $cat->parent) {
				$isChild = $parent;
			} else {
				$isChild = $cat->term_id;
			};
			?>

		<!-- INFOGRÁFICO  -->
		<?php if (get_field('infografico', 'product_cat_' . $isChild)) : ?>
			<section class="infografico-fo">
				<div class="inner">
					<div class="box-infografico">
						<?php echo wp_get_attachment_image(get_field('infografico', 'product_cat_' . $isChild), 'infografico', '', array('class' => 'infografico-img')); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- EXPERIMENTE -->
		<?php if (get_field('titulo_experimente', 'product_cat_' . $isChild)) : ?>
			<section class="experimente">
				<div class="inner">
					<div class="experimente-info">
						<h1><?php echo get_field('titulo_experimente', 'product_cat_' . $isChild); ?></h1>
						<p class="experimente-txt"><?php echo get_field('texto_experimente', 'product_cat_' . $isChild); ?></p>
					</div>
					<?php
								$linkExperimente = get_field('link_do_botao_experimente', 'product_cat_' . $isChild);
								$linkExperimente_url = $linkExperimente['url'];
								$linkExperimente_target = $linkExperimente['target'] ? $linkExperimente['target'] : '_self';
								?>
					<a class="btn-vagas" href="<?php echo esc_url($linkExperimente_url); ?>" target="<?php echo esc_attr($linkExperimente_target); ?>"><span><?php echo get_field('rotulo_do_botao_experimente', 'product_cat_' . $isChild); ?></span></a>
				</div>
			</section>
		<?php endif; ?>

		<!-- CONHEÇA PLANOS -->
		<?php if (get_field('titulo_conheca_planos', 'product_cat_' . $isChild)) : ?>
			<section class="conheca-planos" style="background-image:url(<?php echo get_field('imagem_background_conheca_planos', 'product_cat_' . $isChild); ?>)">
				<div class="inner">
					<div class="conheca-planos-info">
						<h1><?php echo get_field('titulo_conheca_planos', 'product_cat_' . $isChild); ?></h1>
						<a class="btn-vagas" href="<?php echo get_field('link_do_botao_conheca_planos', 'product_cat_' . $isChild); ?>"><span><?php echo get_field('rotulo_do_botao_conheca_planos', 'product_cat_' . $isChild); ?></span></a>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- DEPOIMENTOS -->
		<?php if (have_rows('itens_depoimentos', 'product_cat_' . $isChild)) : ?>
			<section class="historias-ciranda">
				<div class="inner">
					<h1><?php the_field('titulo_depoimentos', 'product_cat_' . $isChild); ?></h1>
					<!-- SLIDE -->
					<div class="historias-ciranda-slide owl-emenda">
						<div id="owl-historias-ciranda" class="owl-carousel owl-theme">
							<!-- loop que carrega os resultados -->
							<?php if (have_rows('itens_depoimentos', 'product_cat_' . $isChild)) : while (have_rows('itens_depoimentos', 'product_cat_' . $isChild)) : the_row(); ?>
									<!-- ITEM -->
									<div class="historias-ciranda-item">
										<div class="historias-ciranda-item-autor">
											<?php echo wp_get_attachment_image(get_sub_field('imagem_depoimento', 'product_cat_' . $isChild), 'autor-imagem', '', array('class' => 'historias-ciranda-item-img')); ?>
											<h6><?php the_sub_field('autor_depoimento', 'product_cat_' . $isChild); ?></h6>
											<span class="subtitulo"><?php the_sub_field('cargo_depoimento', 'product_cat_' . $isChild); ?></span>
										</div>
										<div class="historias-ciranda-item-info">
											<p><?php the_sub_field('depoimento_depoimento', 'product_cat_' . $isChild); ?></p>
										</div>
									</div>
							<?php endwhile;
										endif; ?>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</section>
		<?php endif; ?>

		<!-- CONTRATAR MODALIDADE -->
		<?php if (get_field('rotulo_do_botao_link_pagina_especifica', 'product_cat_' . $isChild)) : ?>
			<section class="contratar-modalidade quero-ciranda">
				<div class="inner">
					<a class="btn-vagas" href="<?php echo get_field('link_do_botao_link_pagina_especifica', 'product_cat_' . $isChild); ?>"><span><?php echo get_field('rotulo_do_botao_link_pagina_especifica', 'product_cat_' . $isChild); ?></span></a>
				</div>
			</section>
		<?php endif; ?>

		<!-- GALERIA DE IMAGENS -->
		<?php if (get_field('titulo_galeria_imagens', 'product_cat_' . $isChild)) : ?>
			<section class="galeria-imagens">
				<div class="inner">
					<h1><?php echo get_field('titulo_galeria_imagens', 'product_cat_' . $isChild); ?></h1>
					<!-- SLIDE -->
					<div class="galeria-imagens-slide owl-emenda">
						<div id="owl-galeria-imagens" class="owl-carousel owl-theme">
							<!-- loop que carrega os resultados -->
							<?php if (have_rows('itens_galeria_imagens', 'product_cat_' . $isChild)) : while (have_rows('itens_galeria_imagens', 'product_cat_' . $isChild)) : the_row(); ?>
									<!-- ITEM -->
									<div class="galeria-imagem-item">
										<?php echo wp_get_attachment_image(get_sub_field('imagem_galeria_imagens', 'product_cat_' . $isChild), 'imagem-galeria', '', array('class' => 'galeria-imagem-item-img')); ?>
										<div class="galeria-imagem-item-legenda">
											<p><?php the_sub_field('resumo_galeria_imagens', 'product_cat_' . $isChild); ?></p>
										</div>
									</div>
							<?php endwhile;
										endif; ?>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</section>
		<?php endif; ?>

		<!-- FORMULÁRIO SUGESTÃO -->
		<?php if (get_field('texto_formulario_sugestao', 'product_cat_' . $isChild)) : ?>
			<section class="formulario-fp">
				<div class="inner">
					<div class="box-contato">
						<div class="contato-form">
							<p class="desc-form"><?php echo get_field('texto_formulario_sugestao', 'product_cat_' . $isChild); ?></p>
							<?php echo do_shortcode(get_field('formulario_formulario_sugestao', 'product_cat_' . $isChild)); ?>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>

	<?php


		}
	}

	/*--------SINGLE-----------*/

	//adiciona ao body uma classe com mesmo nome da classe da categoria pai
	add_filter('body_class', 'bbloomer_wc_product_cats_css_body_class');
	function bbloomer_wc_product_cats_css_body_class($classes)
	{
		if (is_tax('product_cat')) {
			global $wp_query;
			$cat = $wp_query->get_queried_object();
			$parent = $cat->parent;
			/**pega o slug da categoria parent */
			$termCat = get_term($parent, 'product_cat');
			$parentName = $termCat->slug;
			if (0 < $cat->parent) $classes[] = 'term-' . $parentName;
		}
		return $classes;
	}

	//ativa recursos da galeria do produto
	/* add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' ); 
add_theme_support( 'wc-product-gallery-slider' );*/
	//altera a quantidade de miniaturas na galeria de produto
	add_filter('woocommerce_single_product_image_gallery_classes', 'bbloomer_columns_product_gallery');
	function bbloomer_columns_product_gallery($wrapper_classes)
	{
		$columns = 4; // change this to 2, 3, 5, etc. Default is 4.
		$wrapper_classes[2] = 'woocommerce-product-gallery--columns-' . absint($columns);
		return $wrapper_classes;
	}

	//adiciona avaliação abaixo da imagem
	add_action('woocommerce_product_thumbnails', 'woocommerce_template_single_rating', 40);

	//adiciona breadcrumb junto a imagem, para usar na versão responsiva
	add_action('woocommerce_product_thumbnails', 'add_breadcrumb_single_mobile', 0);
	function add_breadcrumb_single_mobile()
	{
		//pega as informações da categoria do produto
		global $post;
		$terms = get_the_terms($post->ID, 'product_cat');
		foreach ($terms as $term) {
			$product_cat_name = $term->name;
			$term_link = get_term_link($term);
		}
		?>
	<div class="breadcrumbs breadcrumbs-mobile">
		<span><a href="<?php echo bloginfo('url'); ?>"><span>Home</span></a></span>
		<span class="separador-breadcrumb"> › </span>
		<span><a href="<?php echo esc_url($term_link); ?>"><span>Loja</span></a></span>
		<span class="separador-breadcrumb"> › </span>
		<span><a href="<?php echo esc_url($term_link); ?>"><span><?php echo $product_cat_name; ?></span></a></span>
	</div>
<?php
}

//adiciona lista de 3 produtos aleatórios
add_action('woocommerce_product_thumbnails', 'list_3_produtos_aletorios', 50);
function list_3_produtos_aletorios()
{
	echo '<div id="produtos-aletorios" class="produtos-aletorios">';
	//se preencher o título exibe, senão exibe o padrão
	if (get_field('titulo_carrossel_produtos')) {
		echo '<h3>' . get_field('titulo_carrossel_produtos') . '</h3>';
	} else {
		echo '<h3>Conheça outros produtos:</h3>';
	}
	// pega todas as categorias de produtos
	$termsCat = get_terms('product_cat');
	// lista somente os IDs das categorias
	$termCat_ids = wp_list_pluck($termsCat, 'term_id');
	//se escolher categoria exibe os produtos da categoria, se não exibe todos os produtos      
	if (get_field('categoria_de_produtos')) :
		$catProdutos = get_field('categoria_de_produtos');
	else :
		$catProdutos = $termCat_ids;
	endif;
	//LOOP DOS PRODUTOS
	$args = array(
		'post_type' => 'product',
		// The taxonomy query
		'tax_query' => array(
			array(
				'taxonomy'         => 'product_cat',
				'field'            => 'term_id',
				'terms'            => $catProdutos,
				'include_children' => false,
			)
		),
		'posts_per_page' => 3,
		'orderby'        => 'rand'
	);
	$loop = new WP_Query($args);
	echo '<div class="woocommerce columns-1 ">
							<ul class="products columns-1">';
	if ($loop->have_posts()) : while ($loop->have_posts()) : $loop->the_post();
			wc_get_template_part('content', 'product');
		endwhile;
	endif;
	echo '</ul>
						</div>';
	wp_reset_postdata();
	echo '</div>';
}

//abre top product e adiciona breadcrumb
add_action('woocommerce_single_product_summary', 'abre_top_product', 4);
function abre_top_product()
{
	echo '<div class="product-single-top">';
	//breadcrumb
	//pega as informações da categoria do produto
	global $post;
	$terms = get_the_terms($post->ID, 'product_cat');
	foreach ($terms as $term) {
		$product_cat_name = $term->name;
		$term_link = get_term_link($term);
	}
	?>
	<div class="breadcrumbs">
		<span><a href="<?php echo bloginfo('url'); ?>"><span>Home</span></a></span>
		<span class="separador-breadcrumb"> › </span>
		<span><a href="<?php echo esc_url($term_link); ?>"><span>Loja</span></a></span>
		<span class="separador-breadcrumb"> › </span>
		<span><a href="<?php echo esc_url($term_link); ?>"><span><?php echo $product_cat_name; ?></span></a></span>
	</div>
<?php
}

//fecha top product e insere a descrição
add_action('woocommerce_single_product_summary', 'fecha_top_product', 6);
function fecha_top_product()
{
	if (get_field('link_para_degustacao_do_curso')) {
		echo '<a class="btn-padrao" href="' . get_field('link_para_degustacao_do_curso') . '" target="_blank">Experimente grátis</a>';
	}
	echo '</div>
				<div class="product-single-description">';
	the_content();
	echo '</div>';
}

//remove short description
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

//abre container add to cart e abre a div preço do produto
add_action('woocommerce_single_product_summary', 'abre_add_to_cart', 9);
function abre_add_to_cart()
{
	echo '<div id="boxAddToCart" class="product-add-to-cart">
				<div class="preco">';
}

// carrega o campo personalizado das parcelas e fecha a div preço do produto
add_action('woocommerce_single_product_summary', 'fecha_product_price', 11);
function fecha_product_price()
{
	echo '<div class="parcelamento">em até ' . get_field('numero_de_parcelas_produto') . 'x</div></div>';
}

//fecha container add to cart e abre a product info
add_action('woocommerce_after_add_to_cart_form', 'fecha_add_to_cart', 5);
function fecha_add_to_cart()
{
	echo '</div>';
	//DATA E LOCAL
	if (get_field('data_da_formacao_produto')) {
		echo '<div class="product-infos info-data-local">
						<div class="data-hora">' . get_field('data_da_formacao_produto') . '<br><span>' . get_field('horario_da_formacao_produto_copiar') . '</span></div>
						<div class="local-endereco"><span>Endereço:</span><br>' . get_field('endereco_da_formacao_produto') . '</div>							
					</div>';
	}
	//INFORMAÇÕES ADICIONAIS
	if (get_field('carga_horaria_produto')) {
		echo '<div id="productInfos" class="product-infos">
						<div class="product-info-item product-info-item-1"><h6>Carga Horária: ' . get_field('carga_horaria_produto') . ' horas</h6></div>
						<div class="product-info-item product-info-item-2"><h6>Curso indicado para:</h6>';
		$indicados = get_field('publico_produto');
		if ($indicados) {
			echo '<ul>';
			foreach ($indicados as $indicado) {
				echo '<li>' . $indicado . '</li>';
			};
			echo '</ul>';
		};
		echo '</div>';
		if (get_field('prazo_conclusao')) {
			echo '<div class="product-info-item product-info-item-3"><h6>Prazo para conclusão do curso:</h6>' . get_field('prazo_conclusao') . '</div>';
		}
		echo '<div class="product-info-item product-info-item-4"><h6>Modalidade do curso:</h6>' . get_field('modalidade_do_curso') . '</div>
					</div>';
	}
}

//altera o rotulo do botão add to cart
add_filter('woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text');
function woo_custom_single_add_to_cart_text()
{
	return __('Compre!', 'woocommerce');
}

//percurso formativo

add_action('woocommerce_after_add_to_cart_form', 'abre_percurso_formativo', 6);
function abre_percurso_formativo()
{
	/** exibe se tiver campos preenchidos */
	$rows = get_field('percurso_formativo');
	if ($rows) {

		/**abre a div percurso-formativo */
		echo '<div class="percurso-formativo">
					<h2>Percurso formativo:</h2>';

		/**abre as div abas-accordion */
		echo '<div class="abas-percurso-formativo"><div id="abas-accordion">';
		if ($rows) {
			$aula = 0;
			$txtAula = 0;
			/**MENU ABAS */
			echo '<ul>';
			foreach ($rows as $row) {
				$aula++;
				echo '<li><a href="#acc-' . $aula . '"><div class="titulo-aula-produto"><span class="numero-aula-produto">Aula ' . $aula . '</span>' . $row['titulo_aula_produto'] . '</div></a></li>';
			}
			echo '</ul>';
			/** PAINEIS */
			foreach ($rows as $row) {
				$txtAula++;
				echo '<div id="acc-' . $txtAula . '"><div class="texto-aula-produto"><p>' . $row['texto_aula_produto'] . '</p></div></div>';
			}
		}
		/**fecha as div abas-accordion */
		echo '</div></div>';
		/**fecha a div percurso-formativo */
		echo '</div>';
	}
}


//sobre autores

add_action('woocommerce_after_add_to_cart_form', 'abre_sobre_autores', 7);
function abre_sobre_autores()
{
	if (get_field('titulo_box_autores_produto')) {
		/**abre a div sobre-autoras */
		echo '<div class="sobre-autores">
		<h2>' . get_field('titulo_box_autores_produto') . '</h2>';
		echo '<div class="list-autores">';
		/** carrega os campos personalizados e lista os autores */
		$rows = get_field('autores_produto');
		if ($rows) {
			foreach ($rows as $row) {
				echo '<div class="autor-item">
								<img src="' . $row['imagem_autor_produto'] . '" alt="' . $row['nome_do_autor_produto'] . '">
								<div class="autor-item-info">
									<h3>' . $row['nome_do_autor_produto'] . '</h3>
									<span class="autor-item-cargo">' . $row['cargo_do_autor_produto'] . '</span>
								</div>
								<div class="autor-item-txt"><p>' . $row['mini_curriculo_do_autor_produto'] . '</p></div>
							</div>';
			}
		}
		echo '</div></div>';
	}
}

//adiciona autor a produto do woocommerce
add_post_type_support('product', 'author');


//remove avaliação abaixo do título do produto
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

//remove meta dados
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

//adiciona slider de depoimentos
add_action('woocommerce_after_single_product_summary', 'add_depoimentos_product', 5);
function add_depoimentos_product()
{
	if (get_field('titulo_depoimentos')) {
		/** carrega os campos personalizados e lista os autores */
		$rows = get_field('itens_depoimentos');
		/**limpa o float da siv summary */
		echo '<div class="clearfix"></div>
			<section class="historias-ciranda">
				<div class="inner">
					<h1>' . get_field('titulo_depoimentos') . '</h1>';
		/** SLIDE*/
		echo '<div class="historias-ciranda-slide owl-emenda">
						<div id="owl-historias-ciranda" class="owl-carousel owl-theme">';
		/**loop que carrega os depoimentos */
		if ($rows) {
			foreach ($rows as $row) {
				/**ITEM*/
				echo '<div class="historias-ciranda-item">
								<div class="historias-ciranda-item-autor">';
				$image_attributes = wp_get_attachment_image_src($attachment_id = $row['imagem_depoimento']);
				/**carrega a imagem usando o ID */
				if ($image_attributes) {
					echo '<img class="historias-ciranda-item-img" src="' . $image_attributes[0] . '" alt="' . $row['autor_depoimento'] . '">';
				}
				echo '<h6>' . $row['autor_depoimento'] . '</h6>
									<span class="subtitulo">' . $row['cargo_depoimento'] . '</span>                        
								</div>
								<div class="historias-ciranda-item-info">
									<p>' . $row['depoimento_depoimento'] . '</p>
								</div>
							</div>';
			}
		};
		echo '</div>            
					</div>
					<div class="clearfix"></div>						 
				</div>
			</section>';
	}
}

//adiciona clearfix após product summary
add_action('woocommerce_after_single_product_summary', 'add_clearfix_after_product_summary', 0);
function add_clearfix_after_product_summary()
{
	echo '<div class="clearfix"></div>';
}

//remove tabs, upsells, related products do single
// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

// Remove product data tabs
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);

function woo_remove_product_tabs($tabs)
{
	unset($tabs['description']);      	// Remove the description tab    
	unset($tabs['additional_information']);  	// Remove the additional information tab
	return $tabs;
}

//adiciona upsells, related products do single em posições diferente
add_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 20);
add_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 15);

//adiciona classe da categoria do produto no body do single
add_filter('body_class', 'wc_product_cats_css_body_class');
function wc_product_cats_css_body_class($classes)
{
	if (is_singular('product')) {
		$custom_terms = get_the_terms(0, 'product_cat');
		if ($custom_terms) {
			foreach ($custom_terms as $custom_term) {
				$classes[] = 'product_cat_' . $custom_term->slug;
			}
		}
	}
	return $classes;
}

//altera a quantidade de produtos relacionados
add_filter('woocommerce_output_related_products_args', 'jk_related_products_args', 20);
function jk_related_products_args($args)
{
	$args['posts_per_page'] = 3; // 4 related products
	$args['columns'] = 3; // arranged in 2 columns
	return $args;
}


/* CARRINHO */

//adiciona título antes da tabela de produtos do carrinho
add_action('woocommerce_before_cart_table', 'add_title_before_table_cart', 0);
function add_title_before_table_cart()
{
	echo '<h1 class="titulo-pagina">' . get_the_title() . '</h1>';
}

//adiciona a categoria acima do nome do produto na tabela do carrinho
add_filter('woocommerce_cart_item_name', 'bbloomer_cart_item_category', 99, 3);
function bbloomer_cart_item_category($name, $cart_item, $cart_item_key)
{
	$product_item = $cart_item['data'];
	// make sure to get parent product if variation
	if ($product_item->is_type('variation')) {
		$product_item = wc_get_product($product_item->get_parent_id());
	}
	$cat_ids = $product_item->get_category_ids();
	// if product has categories, concatenate cart item name with them
	if ($cat_ids) $coisa .= wc_get_product_category_list($product_item->get_id(), ', ', '<span class="posted_in">' . _n('', '', count($cat_ids), 'woocommerce') . ' ', '</span>') . '<br>' . $name;

	return $coisa;
}

//remove cross sells do carrinho
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');

//adiciona cross sells em baixo de tudo no carrinho
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');



//DESABILITA ESTADO E CIDADE NO CÁLCULO DO FRETE
/* add_filter( 'woocommerce_shipping_calculator_enable_country', '__return_false' ); */
add_filter('woocommerce_shipping_calculator_enable_state', '__return_false');
add_filter('woocommerce_shipping_calculator_enable_city', '__return_false');


//adiciona ao body uma classe cart_empty
add_filter('body_class', 'cart_empty_css_body_class');
function cart_empty_css_body_class($classes)
{
	global $woocommerce;
	if (is_cart() && WC()->cart->cart_contents_count == 0) {
		$classes[] = 'woocommerce-cart-empty';
	}
	return $classes;
}



/* CHECKOUT */

//remove cupom de desconto 
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
//e inseri em outro lugar
add_action('woocommerce_checkout_before_order_review', 'woocommerce_checkout_coupon_form', 10);


//fecha div após formulário de cadastro (página 1) e  abre e div antes informações do pedido (página 2)
add_action('woocommerce_checkout_after_customer_details', 'fecha_pagina1_abre_pagina2', 0);
function fecha_pagina1_abre_pagina2()
{
	echo '<div class="pagamento-info">';
}

//fecha div após checkout (página 2)
add_action('woocommerce_after_checkout_form', 'fecha_div_apos_checkout', 0);
function fecha_div_apos_checkout()
{
	echo '</div>';
}

//DESATIVA EXIGÊNCIA DE SENHA SEGURA
function wc_remove_password_strength()
{
	if (wp_script_is('wc-password-strength-meter', 'enqueued')) {
		wp_dequeue_script('wc-password-strength-meter');
	}
}
add_action('wp_print_scripts', 'wc_remove_password_strength', 100);

//ajusta os labels dos campos complemento do checkout
add_filter('woocommerce_after_checkout_form', 'ajusta_label_campo_complemento');
function ajusta_label_campo_complemento()
{
	?>
	<script>
		$(document).ready(function() {
			$("#billing_address_2_field label").replaceWith('<div class="rotulo">Complemento (opcional)</div>');
			$("#shipping_address_2_field label").replaceWith('<div class="rotulo">Complemento (opcional)</div>');
		})
	</script>
	<?php
	}


	/* MINHA CONTA */

	//adiciona botão de aula
	add_action('woocommerce_account_dashboard', 'add_sala_aula', 0);
	function add_sala_aula()
	{

		$groups_user = new Groups_User(get_current_user_id());
		$user_group_ids = $groups_user->group_ids;

		// if( $user_group_ids[1] == 3 && $user_group_ids[2] == '' ){
		$user_in_mathema_online_group = (in_array("4", $user_group_ids));
		if ($user_in_mathema_online_group) {
			?>
		<div id="btn-online" class="btn-sala-aula sala-de-aula">
			<div class="btn-sala-aula-titulo">
				<a href="https://mathema.com.br/online/my"><span>Acessar ambiente de estudos online</span></a>
			</div>
		</div>
	<?php
		}

		$user_in_ciranda_group = (in_array("3", $user_group_ids));
		if ($user_in_ciranda_group) {
			?>
		<div id="btn-ciranda" class="btn-sala-aula conteudo-ciranda">

			<div class="btn-sala-aula-titulo">
				<a href="https://mathema.com.br/calendario-de-formacao/"><span>Acessar ambiente do Ciranda</span></a>
			</div>
		</div>
	<?php
		}
		?>


	<?php
		//  //elseif( $user_group_ids[1] == 4 && $user_group_ids[2] == '' ) {
		?>
	<!-- <div id="btn-online" class="btn-sala-aula sala-de-aula">		                      
		<div class="btn-sala-aula-titulo">
			<a href="https://mathema.com.br/online/my"><span>Acessar ambiente de estudos online</span></a>
		</div>                   
	</div>
	<?php
		// } elseif( $user_group_ids[1] == 3 && $user_group_ids[2] == 4 ){
		?>
	<div id="btn-ciranda-1" class="btn-sala-aula conteudo-ciranda">		                      
		<div class="btn-sala-aula-titulo">
			<a href="https://mathema.com.br/calendario-de-formacao/"><span>Acessar ambiente do Ciranda</span></a>
		</div>                   
	</div>
	<div id="btn-online" class="btn-sala-aula sala-de-aula">		                      
		<div class="btn-sala-aula-titulo">
			<a href="https://mathema.com.br/online/my"><span>Acessar ambiente de estudos online</span></a>
		</div>                   
	</div>
	<?php
		//}	
		?> -->

	<div class="clearfix"></div>
<?php
}

//exibe o título do endpoint
add_action('woocommerce_account_content', 'add_titulo_endpoint', 0);
function add_titulo_endpoint()
{
	if (is_account_page() && !is_wc_endpoint_url()) {
		//se for o dashboar não exibe nada, pois ele não tem título 
	} else {
		//se for outro exibe o título	
		echo '<h1>' . wc_page_endpoint_title($title) . '</h1>';
	}
}

/*-------------------------------------------------------
FIM WOOCOMMERCE
-------------------------------------------------------*/


//adiciona a class "has_children", no item do wp_list_categorie que tem categoria filha
function add_category_parent_css($css_classes, $category, $depth, $args)
{
	if ($args['has_children']) {
		$css_classes[] = 'has_children';
	}
	return $css_classes;
}

add_filter('category_css_class', 'add_category_parent_css', 10, 4);

// LIMITE A QUANTIDA DE DE CARACTERES DO TÍTULO

function limita_titulo($limit)
{

	$permalink = get_permalink($post->ID);

	$title = get_the_title();

	$title = strip_tags($title);

	$title = substr($title, 0, $limit);

	$title = $title;

	return $title;
}

//LIMITA A QUANTIDADE DE PALAVRAS DO EXCERPT
function excerpt($limit)
{
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt) >= $limit) {
		array_pop($excerpt);
		$excerpt = implode(" ", $excerpt) . '[...]';
	} else {
		$excerpt = implode(" ", $excerpt);
	}
	$excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
	return $excerpt;
}

//PAGINAÇÃO SEM PLUGINS

function wordpress_pagination()
{

	?>
	<div class="paginacao">
		<?php

			global $wp_query;

			$big = 999999999;

			echo paginate_links(array(

				'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),

				'format' => '?paged=%#%',

				'current' => max(1, get_query_var('paged')),

				'total' => $wp_query->max_num_pages,

				'show_all'     => false,

				'type'         => 'plain',

				'end_size'     => 2,

				'mid_size'     => 1,

				'prev_next'    => true,

				'prev_text'    => '‹',

				'next_text'    => '›',

				'add_args'     => false,

				'add_fragment' => '',

			));

			?>
	</div>
<?php
}

//PEGA A DATA DA ÚLTIMA ATUALIZAÇÃO DO POST
function wpb_last_updated_date()
{
	$u_time = get_the_time('U');
	$u_modified_time = get_the_modified_time('U');
	if ($u_modified_time >= $u_time + 86400) {
		$updated_date = get_the_modified_time('d/m/Y');
		$custom_content .= ' | Atualizado em ' . $updated_date;
	}
	echo $custom_content;
}


// ADICIONA DIV ANTES DO IFRAME.
function div_wrapper($content)
{
	// match any iframes
	$pattern = '~<iframe.*</iframe>|<embed.*</embed>~';
	preg_match_all($pattern, $content, $matches);

	foreach ($matches[0] as $match) {
		// wrap matched iframe with div
		$wrappedframe = '<div class="video"><div class="video-wrap">' . $match . '</div></div>';

		//replace original iframe with new in content
		$content = str_replace($match, $wrappedframe, $content);
	}

	return $content;
}
add_filter('the_content', 'div_wrapper');

//MASCARA NO CONTACT FORM 7 COM 8 OU 9 DIGITOS
add_action('wp_enqueue_scripts', 'wpmidia_enqueue_masked_input');
function wpmidia_enqueue_masked_input()
{
	wp_enqueue_script('masked-input', get_template_directory_uri() . '/js/jquery.maskedinput.js', array('jquery'));
}
add_action('wp_footer', 'wpmidia_activate_masked_input');

function wpmidia_activate_masked_input()
{
	?>
	<script type="text/javascript">
		$('#tel').focusout(function() {
			var phone, element;
			element = $(this);
			element.unmask();
			phone = element.val().replace(/\D/g, '');
			if (phone.length > 10) {
				element.mask("(99) 99999-999?9");
			} else {
				element.mask("(99) 9999-9999?9");
			}
		}).trigger('focusout');
	</script>
<?php
}

//abre uma janela popup(CSS) após o envio com sucesso do formulário
add_action('wp_footer', 'contact_form_success');
function contact_form_success()
{
	?>
	<script type="text/javascript">
		document.addEventListener('wpcf7mailsent', function(event) {
			$(document).one("ajaxComplete", function(event, xhr, settings) {
				var data = xhr.responseText;
				var jsonResponse = JSON.parse(data);
				// console.log(jsonResponse);
				if (!jsonResponse.hasOwnProperty('into') || $('.wpcf7' + jsonResponse.into).length === 0) return;
				$('#mensagem-sucesso').html(jsonResponse.message);
				$('body').css("overflow", "hidden");
				$('#sucesso').fadeIn();
			});
		}, false);
	</script>
<?php
}


//ALTERA A LEGENDA PARA O NOME DO SITE

function wpmidia_custom_wp_login_title()
{

	return get_option('blogname');
}

add_filter('login_headertitle', 'wpmidia_custom_wp_login_title');



//EDITOR DE TEXTO APARECE EXPANDIDO COMO PADRÃO

function enable_more_buttons($buttons)
{

	$buttons[] = 'hr';

	$buttons[] = 'fontselect';

	$buttons[] = 'sup';

	// etc, etc...  

	return $buttons;
}

add_filter("mce_buttons", "enable_more_buttons");





/* 

//ADICIONA UM CSS PERSONALIZADO NA PÁGINA DE LOGIN
function wpmidia_custom_login() {
	echo '<link media="all" type="text/css" href="'.get_template_directory_uri().'/login-style.css" rel="stylesheet">';
}
add_action( 'login_head', 'wpmidia_custom_login' );



//ENVIA PARA O SITE AO CLICAR NA LOGO NA PÁGINA DE LOGIN

function wpmidia_custom_wp_login_url() {

	return home_url();

}

add_filter('login_headerurl', 'wpmidia_custom_wp_login_url');

//ESCONDE ITEM DO MENU DO ADMIN

function remove_menus() {

    global $menu;

    global $current_user;

    get_currentuserinfo();   

        $restricted = array(

							//__('Dashboard'),

							__('Posts'),

                            //__('Media'),

                            __('Links'),

                            //__('Pages'),

                            __('Comments'),

                            __('Appearance'),

                           __('Plugins'),

                            //__('Users'),

                            __('Tools'),							

                            __('Settings')	

        );

        end ($menu);

        while (prev($menu)){

            $value = explode(' ',$menu[key($menu)][0]);

            if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}

        }// end while   

}

add_action('admin_menu', 'remove_menus');


//ESCONDE O BOTÃO DE ADICIONAR NOVA PÁGINA

function hide_add_new_page() {

    echo '<style type="text/css">

           a.page-title-action { display: none !important; }

          </style>';

}

add_action('admin_head', 'hide_add_new_page');



//RETIRA MENU LOGO WORDPRESS DA BARRA DE FERRAMENTAS

function wps_admin_bar() {

    global $wp_admin_bar;

    $wp_admin_bar->remove_menu('wp-logo');

    $wp_admin_bar->remove_menu('about');

    $wp_admin_bar->remove_menu('wporg');

    $wp_admin_bar->remove_menu('documentation');

    $wp_admin_bar->remove_menu('support-forums');

    $wp_admin_bar->remove_menu('feedback');

    $wp_admin_bar->remove_menu('view-site');

	$wp_admin_bar->remove_menu('updates');	

	}

add_action( 'wp_before_admin_bar_render', 'wps_admin_bar' );



//ESCONDE OPÇÕES DE TELA E AJUDA DO PAINEL ADMIN

function hide_help() {

    echo '<style type="text/css">

            #contextual-help-link-wrap, #screen-options-link-wrap, #wp-admin-bar-comments, .add-new-h2 { display: none !important; }

          </style>';

}

add_action('admin_head', 'hide_help');



//ESCONDE A NOTIFICAÇÃO DE COMENTÁRIOS E O BOTÃO DE ADCIONAR POSTS, PAGINAS

function hide_button_bar_admin() {

    echo '<style type="text/css">

            #wp-admin-bar-comments, #wp-admin-bar-new-content, #wp-admin-bar-archive { display: none !important; }

		  </style>';

}

add_action('admin_head', 'hide_button_bar_admin');



//ESCONDE O RODAPÉ COM AGRADECIMENTO E VERSÃO DO WP

function hide_wpfooter_admin() {

    echo '<style type="text/css">

            #wpfooter { display: none !important; }

		  </style>';

}

add_action('admin_head', 'hide_wpfooter_admin');



//REMOVER BARRA DE FERRAMENTAS AO VISUALIZAR O SITE

function function_hide_admin_bar(){

    return false;

}

add_filter( 'show_admin_bar' , 'function_hide_admin_bar');



//ESCONDE ESQUEMA DE CORES NO PERFIL

function admin_color_scheme() {

   global $_wp_admin_css_colors;

   $_wp_admin_css_colors = 0;

}

add_action('admin_head', 'admin_color_scheme');



//ESCONDE OPÇÕES PESSOAIS DA PÁGINA DO PERFIL

function hide_personal_options(){

	echo "\n" . '<script type="text/javascript">jQuery(document).ready(function($) { $(\'form#your-profile > h3:first\').hide(); $(\'form#your-profile > table:first\').hide(); $(\'form#your-profile\').show(); });</script>' . "\n";	

	}	

	add_action('admin_head','hide_personal_options');



//ESCONDE NOTIFICAÇÕES DE UPDATE PRA USUARIOS, EXCETO ADMINISTRADORES

global $user_login;

get_currentuserinfo();

if (!current_user_can('update_plugins')) { // checks to see if current user can update plugins

add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );

add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );

}



//ADICIONA UM WIDGET PERSONALIZADO NA TELA INICIAL DO WP-ADMIN

add_action('wp_dashboard_setup', 'wpmidia_custom_dashboard_widgets');

function wpmidia_custom_dashboard_widgets() {

    global $wp_meta_boxes;

    wp_add_dashboard_widget('custom_help_widget', 'NASCER WEB+DESIGN | Suporte', 'wpmidia_custom_dashboard_help');

}

function wpmidia_custom_dashboard_help() {

    echo 'Se você tiver qualquer dúvida ou precisar de ajuda, por favor, entre em contato comigo através do e-mail contato@nascer.net, telefone 31 3657-1220 ou Whatsapp 31 98833-4187 de segunda a sexta-feira de 9h às 18h.';

}



//ESCONDE ALGUNS WIDGETS DO PAINEL

function wpmidia_remove_dashboard_widgets() {

	global $wp_meta_boxes;	

	// Remove o widget "Links de entrada" (Incomming links)

	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);        

	// remove o widget "Plugins"

	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);        

	// remove o widget "Rascunhos recentes" (Recent drafts)

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);

	// remove o widget "QuickPress"

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

	// remove o widget "Agora" (Right now)

	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);

	// remove o widget "Atividade" (Activity)

	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);	

	// remove o widget "Blog do WordPress" (Primary)

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);

	// remove o widget "Outras notícias do WordPress" (Secondary)

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);              

}

add_action('wp_dashboard_setup', 'wpmidia_remove_dashboard_widgets' );



*/

// CRIA CPT EQUIPE
function criar_equipe_post_type()
{
	$labels = array(
		'name'                  => _x('Membros', 'Post Type General Name', 'text_domain'),
		'singular_name'         => _x('Membro', 'Post Type Singular Name', 'text_domain'),
		'menu_name'             => __('Equipe', 'text_domain'),
		'name_admin_bar'        => __('Equipe', 'text_domain'),
		'archives'              => __('Item Archives', 'text_domain'),
		'attributes'            => __('Item Attributes', 'text_domain'),
		'parent_item_colon'     => __('Parent Item:', 'text_domain'),
		'all_items'             => __('Todos Membros', 'text_domain'),
		'add_new_item'          => __('Adicionar Novo Membro', 'text_domain'),
		'add_new'               => __('Adicionar Novo', 'text_domain'),
		'new_item'              => __('Novo Membro', 'text_domain'),
		'edit_item'             => __('Editar Membro', 'text_domain'),
		'update_item'           => __('Atualizar Membro', 'text_domain'),
		'view_item'             => __('Visualizar Membro', 'text_domain'),
		'view_items'            => __('Visualizar Membros', 'text_domain'),
		'search_items'          => __('Search Item', 'text_domain'),
		'not_found'             => __('Não Encontrado', 'text_domain'),
		'not_found_in_trash'    => __('Não Encontrado na Lixeira', 'text_domain'),
		'featured_image'        => __('Imagem Destacada', 'text_domain'),
		'set_featured_image'    => __('Configurar Imagem Destacada', 'text_domain'),
		'remove_featured_image' => __('Remover Imagem Destacada', 'text_domain'),
		'use_featured_image'    => __('Usar como Imagem Destacada', 'text_domain'),
		'insert_into_item'      => __('Insert into item', 'text_domain'),
		'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
		'items_list'            => __('Items list', 'text_domain'),
		'items_list_navigation' => __('Items list navigation', 'text_domain'),
		'filter_items_list'     => __('Filter items list', 'text_domain'),
	);
	$args = array(
		'label'                 => __('Membro', 'text_domain'),
		'description'           => __('Membros', 'text_domain'),
		'labels'                => $labels,
		'supports'              => array('title', 'editor', 'thumbnail'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 11,
		'menu_icon'             => 'dashicons-groups',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => false,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type('membro', $args);
}
add_action('init', 'criar_equipe_post_type', 0);

// CRIA A TAXOMIA ÁREA
function gera_categoria_membro()
{
	$labels = array(
		'name'                       => _x('Áreas', 'Taxonomy General Name', 'text_domain'),
		'singular_name'              => _x('Área', 'Taxonomy Singular Name', 'text_domain'),
		'menu_name'                  => __('Áreas', 'text_domain'),
		'all_items'                  => __('Todas áreas', 'text_domain'),
		'parent_item'                => __('Parent Item', 'text_domain'),
		'parent_item_colon'          => __('Parent Item:', 'text_domain'),
		'new_item_name'              => __('Nova área', 'text_domain'),
		'add_new_item'               => __('Adicionar nova área', 'text_domain'),
		'edit_item'                  => __('Editar área', 'text_domain'),
		'update_item'                => __('Atualizar área', 'text_domain'),
		'view_item'                  => __('Visualizar área', 'text_domain'),
		'separate_items_with_commas' => __('Separate items with commas', 'text_domain'),
		'add_or_remove_items'        => __('Add or remove items', 'text_domain'),
		'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
		'popular_items'              => __('Popular Items', 'text_domain'),
		'search_items'               => __('Search Items', 'text_domain'),
		'not_found'                  => __('Not Found', 'text_domain'),
		'no_terms'                   => __('No items', 'text_domain'),
		'items_list'                 => __('Items list', 'text_domain'),
		'items_list_navigation'      => __('Items list navigation', 'text_domain'),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy('area', array('membro'), $args);
}
add_action('init', 'gera_categoria_membro', 0);

// CRIA CPT PROJETOS ESPECIAIS
function criar_projetos_espaciais_post_type()
{
	$labels = array(
		'name'                  => _x('Projetos Especiais', 'Post Type General Name', 'text_domain'),
		'singular_name'         => _x('Projeto Especial', 'Post Type Singular Name', 'text_domain'),
		'menu_name'             => __('Projetos Especiais', 'text_domain'),
		'name_admin_bar'        => __('Projetos Especiais', 'text_domain'),
		'archives'              => __('Item Archives', 'text_domain'),
		'attributes'            => __('Item Attributes', 'text_domain'),
		'parent_item_colon'     => __('Parent Item:', 'text_domain'),
		'all_items'             => __('Todos Projetos', 'text_domain'),
		'add_new_item'          => __('Adicionar Novo Projeto', 'text_domain'),
		'add_new'               => __('Adicionar Novo', 'text_domain'),
		'new_item'              => __('Novo Projeto', 'text_domain'),
		'edit_item'             => __('Editar Projeto', 'text_domain'),
		'update_item'           => __('Atualizar Projeto', 'text_domain'),
		'view_item'             => __('Visualizar Projeto', 'text_domain'),
		'view_items'            => __('Visualizar Projetos', 'text_domain'),
		'search_items'          => __('Search Item', 'text_domain'),
		'not_found'             => __('Não Encontrado', 'text_domain'),
		'not_found_in_trash'    => __('Não Encontrado na Lixeira', 'text_domain'),
		'featured_image'        => __('Imagem Destacada', 'text_domain'),
		'set_featured_image'    => __('Configurar Imagem Destacada', 'text_domain'),
		'remove_featured_image' => __('Remover Imagem Destacada', 'text_domain'),
		'use_featured_image'    => __('Usar como Imagem Destacada', 'text_domain'),
		'insert_into_item'      => __('Insert into item', 'text_domain'),
		'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
		'items_list'            => __('Items list', 'text_domain'),
		'items_list_navigation' => __('Items list navigation', 'text_domain'),
		'filter_items_list'     => __('Filter items list', 'text_domain'),
	);
	$args = array(
		'label'                 => __('Projeto Especial', 'text_domain'),
		'description'           => __('Projetos Especiais', 'text_domain'),
		'labels'                => $labels,
		'supports'              => array('title', 'editor', 'thumbnail'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 13,
		'menu_icon'             => 'dashicons-lightbulb',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => false,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type('projeto-especial', $args);
}
add_action('init', 'criar_projetos_espaciais_post_type', 0);

// CRIA CPT Mathema na mídia
function criar_na_midia_post_type()
{
	$labels = array(
		'name'                  => _x('Mathema na mídia', 'Post Type General Name', 'text_domain'),
		'singular_name'         => _x('Mathema na mídia', 'Post Type Singular Name', 'text_domain'),
		'menu_name'             => __('Mathema na mídia', 'text_domain'),
		'name_admin_bar'        => __('Mathema na mídia', 'text_domain'),
		'archives'              => __('Item Archives', 'text_domain'),
		'attributes'            => __('Item Attributes', 'text_domain'),
		'parent_item_colon'     => __('Parent Item:', 'text_domain'),
		'all_items'             => __('Todas Matérias', 'text_domain'),
		'add_new_item'          => __('Adicionar Nova Matéria', 'text_domain'),
		'add_new'               => __('Adicionar Nova', 'text_domain'),
		'new_item'              => __('Novo Projeto', 'text_domain'),
		'edit_item'             => __('Editar Matéria', 'text_domain'),
		'update_item'           => __('Atualizar Matéria', 'text_domain'),
		'view_item'             => __('Visualizar Matéria', 'text_domain'),
		'view_items'            => __('Visualizar Matéria', 'text_domain'),
		'search_items'          => __('Search Item', 'text_domain'),
		'not_found'             => __('Não Encontrado', 'text_domain'),
		'not_found_in_trash'    => __('Não Encontrado na Lixeira', 'text_domain'),
		'featured_image'        => __('Imagem Destacada', 'text_domain'),
		'set_featured_image'    => __('Configurar Imagem Destacada', 'text_domain'),
		'remove_featured_image' => __('Remover Imagem Destacada', 'text_domain'),
		'use_featured_image'    => __('Usar como Imagem Destacada', 'text_domain'),
		'insert_into_item'      => __('Insert into item', 'text_domain'),
		'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
		'items_list'            => __('Items list', 'text_domain'),
		'items_list_navigation' => __('Items list navigation', 'text_domain'),
		'filter_items_list'     => __('Filter items list', 'text_domain'),
	);
	$args = array(
		'label'                 => __('Mathema na mídia', 'text_domain'),
		'description'           => __('Mathema na mídia', 'text_domain'),
		'labels'                => $labels,
		'supports'              => array('title', 'thumbnail'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 12,
		'menu_icon'             => 'dashicons-megaphone',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => false,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type('na-midia', $args);
}
add_action('init', 'criar_na_midia_post_type', 0);

function add_cpf_no_moodle($userdetails, $upgrade = false)
{
	$cart = WC()->cart;
	if ($upgrade == false && $cart !== null) {
		$userdetails['customfields'] = [
			[
				'type' => 'cpf',
				'value' => $cart->get_customer()->get_meta('billing_cpf')
			]
		];
	}
	return $userdetails;
}

add_action('eb_moodle_user_profile_details', 'add_cpf_no_moodle', 99);

?>