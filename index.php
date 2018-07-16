<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Gerador de Formulario</title>

</head>

<body>

<div class="container">
<div class="row">
<?php

require_once( 'GerarForm.php' );

/*

Crie uma nova instância
Passar em um URL para definir a ação
*/
$form = new GerarForm();

/*
Atributos de formulário são modificados com a função set_att.
Primeiro argumento é a configuração
Segundo argumento é o valor
*/

$form->set_att('method', 'post'); //POST ou GET
$form->set_att('enctype', 'multipart/form-data');
$form->set_att('markup', 'html');
//$form->set_att('class', 'class_1');
//$form->set_att('id', 'contato');
//$form->set_att('novalidate', false);
$form->set_att('add_honeypot', true);
//$form->set_att('add_nonce', 'contato');
$form->set_att('form_element', true);
$form->set_att('add_submit', true);
$form->set_att('btn', 'btn-lg'); // Bootstrap 3 ou 4
$form->set_att('class_btn', 'success'); // Bootstrap 3 ou 4
$form->set_att('value_btn', 'Enviar Form');

/*
Use add_input para criar campos de formulário
Primeiro argumento é o nome
Segundo argumento é uma matriz de argumentos para o campo
O terceiro argumento é um campo de nome alternativo, se necessário
*/
$form->add_input( 'Nome', array(
	'request_populate' => false,
	'required' => 'required'
), 'contato_nome' );

$form->add_input( 'Email', array(
	'type' => 'email',
	'class' => array( 'form-control')
), 'contato_email' );

$form->add_input( 'Arquivo', array(
	'type' => 'file'
), 'contato_arquivo' );

$form->add_input( 'Devemos ligar para você?', array(
	'type'  => 'checkbox',
	'value' => 1
) );

$form->add_input( 'Verdadeiro ou falso?', array(
	'type'    => 'radio',
	'checked' => false,
	'value'   => 1
) );

$form->add_input( 'Razão para entrar em contato', array(
	'type'    => 'checkbox',
	'options' => array(
		'opcao1'     => 'Opção 1',
		'opcao2'   => 'Opção 2',
		'opcao3' => 'Opção 3',
	)
) );

$form->add_input( 'Opção do Radio', array(
	'type'    => 'radio',
	'options' => array(
		'opcao1'     => 'Opção 1',
		'opcao2'   => 'Opção 2',
		'opcao3' => 'Opção 3',
	)
) );

$form->add_input( 'Razão para contato', array(
	'type'    => 'select',
	'class' => array( 'form-control'),
	'options' => array(
		''           => 'Selecione...',
		'opcao1'     => 'Opção 1',
		'opcao2'   => 'Opção 2',
		'opcao3' => 'Opção 3',
	)
) );

$form->add_input( 'Question or comment', array(
	'required' => true,
	'type'     => 'textarea',
	'value'    => 'Type away!'
) );

$form->add_inputs( array(
	array( 'Field 1' ),
	array( 'Field 2' ),
	array( 'Field 3' )
) );




/*
Criar o Formulário
*/
$form->build_form();

/*
 * Debugging
 */
echo '<pre>';
print_r( $_REQUEST );




echo '</pre>';
echo '<pre>';
print_r( $_FILES );
echo '</pre>';
?>
</div></div>
</body>
</html>