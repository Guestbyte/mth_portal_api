<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email );?>



<?php /* translators: %s: Customer first name */




?>
<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Olá!', 'woocommerce' )); ?>
<?php /* translators: %s: Store name */ ?>
 <p><?php printf( esc_html__( 'Alguém solicitou uma nova senha para a seguinte conta no %s:', 'woocommerce' ), esc_html( wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) ) ); ?></p>
<?php /* translators: %s Customer username */ ?>
<!--<p><//?php printf( esc_html__( 'Username: %s', 'woocommerce' ), esc_html( $user_login ) ); ?></p>-->
<p><?php printf( esc_html__( 'E-mail: %s', 'woocommerce' ), esc_html( $email->user_email ) ); ?></p>
<p><?php printf( esc_html__( 'Username: %s', 'woocommerce' ), esc_html( $user_login ) ); ?></p>
<p><?php esc_html_e( 'Se você não fez essa solicitação, ignore este e-mail. Caso deseje prosseguir:', 'woocommerce' ); ?></p>
<p>
	<a class="link" href="<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ); ?>"><?php // phpcs:ignore ?>
		<?php esc_html_e( 'Click here to reset your password', 'woocommerce' ); ?>
	</a>
</p>
<!--<p><//?php //esc_html_e( 'Thanks for reading.', 'woocommerce' ); ?></p>-->
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

<?php do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );?>
<?php do_action( 'woocommerce_email_footer', $email ); ?>

