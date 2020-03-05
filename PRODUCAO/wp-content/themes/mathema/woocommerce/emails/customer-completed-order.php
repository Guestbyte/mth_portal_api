<?php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
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

<?php /* translators: %s: Customer first name */ 




?>

<p style="text-align: center; font-size: 18px;"><a href="mathema.com.br/login" target=“_blank” >Clique aqui para acessar o seu curso</a></p>
<?php /* translators: %s: Site title */ ?>
<p>
	<p>
<strong><p><?php esc_html_e( 'O seu pagamento foi aprovado. Seja bem-vindo(a) à plataforma de cursos online do Mathema!', 'woocommerce' ); ?></p></strong>
<p><?php esc_html_e('Para acessar os cursos, clique no botão acima e preencha seu e-mail e senha (os mesmos cadastrados na hora da compra). Em seguida, clique em “Acessar ambiente de estudos online”.', 'woocommerce' ); ?></p>
<p></p>
<p style="text-align: center; color: D91D45;"><a href="https://mathema.com.br/on-line/manual_do_aluno_2019.pdf" target=“_blank” >Clique aqui para baixar o manual</a></p>

<p></p>
<h2>Importante!</h2>
<p><?php esc_html_e( 'Atente-se ao prazo para conclusão do(s) curso(s)!', 'woocommerce' ); ?></p>
<br><br>
<h2>Veja aqui os detalhes do seu pedido:</h2>
<hr/>
<p></p>
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
<p><?php esc_html_e( 'Bons estudos!', 'woocommerce' ); ?></p>
</p> 
<br>
<p>
<table style="border: 0; background: #F7F7F7">
	<tr>
		<td><img src="https://mathema.com.br/wp-content/uploads/2019/05/icone-email.png" alt="Exemplo de imagem" style="text-align: center;"></td>
		<td>
<p> <strong>Em caso de dúvidas, entre em contato através dos canais:</strong></p>
<p> <strong>E-mail: </strong>Envie um e-mail para suporte.online@mathema.com.br </p>
<p><strong>Chat: </strong><a href="https://tawk.to/chat/59a7127c7ab2de3aea9d76f4/default">Clicando aqui</a> ou no botão de chat no canto direito do site.</p> 
<p> <strong>Telefone:</strong> +55 11 5548 6912 ou +55 11 5567 6912 </p>
<p> <strong>Whatsapp:</strong> +55 11 99473-7173 ou <a href="wa.me/551994737173">clique aqui</a></p>
<p><strong>Horário de atendimento: de Segunda a Sexta-Feira (exceto feriados), das 8h às 17h. </strong> </p></td>
</tr>
</table>
</p>



<?php

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
