<?php
/**
 * Customer on-hold order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-on-hold-order.php.
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
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<?php _e( 'Olá! Nós recebemos o seu pedido! Obrigado por confiar no Mathema!', 'woocommerce' ); // phpcs:ignore WordPress.XSS.EscapeOutput ?>
<p><?php _e( 'Após a confirmação de pagamento pela administradora, você receberá um e-mail com todos os dados para acesso ao curso! Enquanto isso, veja os detalhes do seu pedido: ', 'woocommerce' ); ?></p><?php // phpcs:ignore WordPress.XSS.EscapeOutput ?>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

?>
<p>
<?php _e( 'Até mais!', 'woocommerce' ); // phpcs:ignore WordPress.XSS.EscapeOutput ?>
</p>

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

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
