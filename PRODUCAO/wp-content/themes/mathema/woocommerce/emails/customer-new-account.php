<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

?>

<?php do_action('woocommerce_email_header', $email_heading, $email); ?>


<?php /* translators: %s Customer username */ ?>
<p><?php printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($user_login)); ?></p>
<?php /* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */ ?>
<p><?php
	// printf(__('Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: %3$s', 'woocommerce'), esc_html($blogname), '<strong>' . esc_html($user_login) . '</strong>', make_clickable(esc_url(wc_get_page_permalink('myaccount'))));
	?></p>
<?php

echo "Seja bem-vindo(a) ao Portal do Mathema!<br>
	Você realizou o seu cadastro com sucesso usando o seguinte login:<br><br>";
printf( esc_html__( 'Login: %s', 'woocommerce' ), esc_html( $email->user_email ) );
//printf(esc_html__('Login: SEU E-MAIL', 'woocommerce'), '<strong></strong>');
echo "<br>";
printf(esc_html__('Senha: %s', 'woocommerce'), '<strong>' . esc_html($user_pass) . '</strong>');

echo "<br><br>Nosso site está de cara nova e agora você encontra todos os produtos e serviços oferecidos pelo Mathema em um só lugar.<br>";
?>

<?php if ('yes' === get_option('woocommerce_registration_generate_password') && $password_generated) : ?>
<?php /* translators: %s Auto generated password */ ?>
<p><?php printf(esc_html__('Your password has been automatically generated: %s', 'woocommerce'), '<strong>' . esc_html($user_pass) . '</strong>'); ?>
</p>
<?php endif; ?>

<p><?php esc_html_e('Esperamos que a sua experiência seja enriquecedora!', 'woocommerce'); ?></p>
<table style="border: 0; background: #F7F7F7">
	<tr>
		<td><img src="https://mathema.com.br/wp-content/uploads/2019/05/icone-email.png" alt="Exemplo de imagem" style="text-align: center;"></td>
		<td>
<p> <strong>Em caso de dúvidas, entre em contato através dos canais:</strong></p>
<p> <strong>E-mail: </strong>Envie um e-mail para suporte.online@mathema.com.br </p>
<p><strong>Chat: </strong><a href="https://tawk.to/chat/59a7127c7ab2de3aea9d76f4/default">Clicando aqui</a> ou no botão de chat no canto direito do site.</p> 
<p> <strong>Telefone:</strong> +55 11 5548 6912 ou +55 11 5567 6912 </p>
<p><strong>Horário de atendimento: de Segunda a Sexta-Feira (exceto feriados), das 8h às 17h. </strong> </p></td>
</tr>
</table>
<?php
do_action('woocommerce_email_footer', $email);