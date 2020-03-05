<?php
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<!-- FORMULÁRIO -->
	<div class="comentarios" id="respond">
		<div class="comentarios-col-1">
			<h2>Conta pra gente: qual sua opinião sobre esse texto?</h2>
			<p>Todos os campos devem ser preenchidos.<br>Seu e-mail não será publicado. </p>
		</div>
		<div class="comentarios-col-2">
			<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
				<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
			<?php else : ?>
			<!-- start of comment form-->
			<div class="formulario">
				<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="formArea">
				<?php comment_id_fields(); ?>
					<textarea name="comment" id="comment" placeholder="Comentários:"></textarea>
					<?php if ( $user_ID ) : ?>
						<p>Logado como <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>
					<?php else : ?>
						<input type="text" name="author" placeholder="Nome:" /><input type="email" name="email" placeholder="E-mail:" />		
					<?php endif; ?>				
					<input type="submit" value="Enviar" /><div class="clearfix"></div>
				<?php do_action('comment_form', $post->ID); ?>
				</form>
			</div>
			<!-- end of comment form-->
			<?php endif; // If registration required and not logged in ?>
		</div>
		<div class="clearfix"></div>
	</div>
	<!-- LISTA DE COMENTÁRIOS -->
	<div class="comentarios-col-2">
	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
	?>
		<h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */				
				printf( _x( '1 Comentário para &ldquo;%s&rdquo;', 'comments title', 'rosa' ), get_the_title() );
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s Comentário para &ldquo;%2$s&rdquo;',
						'%1$s Comentários para &ldquo;%2$s&rdquo;',
						$comments_number,
						'comments title',
						'rosa'
					),
					number_format_i18n( $comments_number ),
					get_the_title()
				);
			}
			?>
		</h2>
		<ol class="comment-list">
			<?php
				wp_list_comments(
					array(
						'avatar_size' => 96,
						'style'       => 'ol',
						'short_ping'  => true,						
					)
				);
			?>
		</ol>
		<?php
	endif; // Check for have_comments().
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyseventeen' ); ?></p>
	<?php
	endif;
	?>
	</div>
	<div class="clearfix"></div>
</div><!-- #comments -->
<!-- ADICIONA CLASSE DE ANIMAÇÃO PRA ROLAR ATÉ O FORMULÁRIO -->



