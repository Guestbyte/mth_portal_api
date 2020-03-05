<div class="woocommerce-MyAccount-header">
    <div class="nome-usuario"><?php echo 'Olá, ' . $current_user->display_name; ?></div>
    <a class="btn-sair" href="<?php echo wp_logout_url( 'https://mathema.com.br/minha-conta/' ); ?>"></a>		
</div>