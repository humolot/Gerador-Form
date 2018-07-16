# Gerador de Formulario em PHP


Esta é uma pequena classe PHP que facilita a criação e saída de formulários como HTML ou XHTML. Os formulários são tediosos e podem ser difíceis de construir. Além disso, há tantas opções diferentes possíveis que é fácil esquecer o que você pode fazer com elas.

Tentei equilibrar a facilidade de uso com flexibilidade e descobri algo que acho muito útil. Eu estou considerando este um "beta" por enquanto já que ele está sendo usado apenas em algumas aplicações e todas as diferentes opções não foram exaustivamente verificadas.


## Trabalhando com o Construtor de Formulário

O processo é muito simples

1. Instanciar a classe
2. Altere os atributos de formulário, se desejado
3. Adicione os campos, para que você possa vê-los
4. Exiba o formulário


Vamos percorrer estes um por um

### 1) Instanciar a Classe

Simples:

```php
$new_form = new GerarForm();
```

Abaixo são atributos utilizados todas as configurações padrão do formulário, que são as seguintes:

* `action: empty, submit to current URL`
* `method: post`
* `enctype: application/x-www-form-urlencoded`
* `class: none`
* `id: none`
* `markup: html`
* `novalidate: false`
* `add_nonce: false`
* `add_honeypot: true`
* `form_element: true`
* `add_submit: true`

As explicações para cada uma das configurações estão abaixo

Você também pode instanciar passando um URL, que se torna a ação do formulário

```php
$new_form = new GerarForm('http://seuurlparaenviodoform.com');
```

### 2) Altere os atributos de formulário, se desejado

Depois que o formulário for criado, use a função <code>set_att</code> para alterar os atributos padrão:

```php
// Adicionar ação ao formulário
$new_form->set_att('action', 'http://seuurlparaenviodoform.com');

// Alterar o metodo do formulário
$new_form->set_att('method', 'get');

// Alterar o enctype
$new_form->set_att('enctype', 'multipart/form-data');

// Pode ser definido para 'html' or 'xhtml'
$new_form->set_att('markup', 'xhtml');

// Classes são adicionadas como um array
$new_form->set_att('class', array());

// Adicione um id ao formulário
$new_form->set_att('id', 'xhtml');

// Adiciona o atributo "novalidate" do HTML5
$new_form->set_att('novalidate', true);

// Adiciona um campo nonce do WordPress usando a string sendo passada
$new_form->set_att('add_nonce', 'build_a_nonce_using_this');

// Adiciona um campo de texto oculto em branco para controle de spam
$new_form->set_att('add_honeypot', true);

// Envolve as entradas com um elemento de formulário
$new_form->set_att('form_element', true);

// Se você usa o Bootstrap 3 ou 4 defina a classe do botão de envio
$new_form->set_att('class_btn', 'success');

// Se você usa o Bootstrap 3 ou 4 defina o tamanho do botão
$new_form->set_att('btn', 'btn-lg');

// Defina o valor padrão do botão
$new_form->set_att('value_btn', 'Enviar Formulário');

// Se nenhum tipo de envio for adicionado, adicione um automaticamente
$new_form->set_att('form_element', true);
```


Atualmente, existem algumas restrições para o que pode ser adicionado, mas nenhuma verificação quanto a se as classes ou ids são válidos, portanto, esteja ciente disso.

### 3) Adicione os campos, para que você possa vê-los


Os campos podem ser adicionados um de cada vez ou como um grupo. De qualquer forma, a ordem em que eles são adicionados é a ordem em que eles serão exibidos.


Adicione campos usando seu rótulo (label) (em formato legível), uma matriz de configurações e um slug de nome / id, se necessário.

```php
$new_form->add_input('Eu sou um pequeno campo', array(), 'nome_campo')
```

* Argumento 1: Um rótulo legível que é analisado e transformado em nome e id, se essas opções não forem definidas explicitamente. Se você usar um rótulo simples como "email" aqui, certifique-se de definir um nome mais específico no argumento 3.
* Argumento 2: Uma matriz de configurações que são mescladas com as configurações padrão para controlar a exibição e o tipo de campo. Veja abaixo as configurações padrão e possíveis aqui.
* Argumento 3: Uma string, válida para um atributo HTML, usada como nome e id. Isso permite que você defina nomes de envio específicos que diferem do rótulo do campo.

Configurações padrão e possíveis para entradas de campo (argumento 2):

<code>type</code>

* Padrão "text"
* Pode ser definido para qualquer coisa e, a menos que seja mencionado abaixo, é usado como o "tipo" para um campo de entrada
* Configurar isto para "title" irá mostrar um elemento h3 usando o texto da etiqueta
* Definir isso para "textarea" irá construir um campo de área de texto
* Usando "select" em combinação com o argumento "options" irá criar um dropdown.

## Opções

<code>name</code>

* Default is argument 3, if set, or the label text formatted
* This becomes the "name" attribute on the field

<code>id</code>

* Default is argument 3, if set, or the label text formatted
* This becomes the "id" attribute on the field and the "for" attribute on the label

<code>label</code>

* Default is argument 1, can be set explicitly using this argument

<code>value</code>

* Default is empty
* If a $_REQUEST index is found with the same name, the value is replaced with that value found

<code>placeholder</code>

* Default is empty
* HTML5 attribute to show text that disappears on field focus

<code>class</code>

* Default is an empty array
* Add multiple classes using an array of valid class names

<code>options</code>

* Default is an empty array
* The options array is used for fields of type "select," "checkbox," and "radio." For other inputs, this argument is ignored
* The array should be an associative array with the value as the key and the label name as the value like <code>array('value' => 'Name to show')</code>
* The label name for the field is used as a header for the multiple options (set "add_label" to "false" to suppress)

<code>min</code>

* Default is empty
* Used for types "range" and "number"

<code>max</code>

* Default is empty
* Used for types "range" and "number"

<code>step</code>

* Default is empty
* Used for types "range" and "number"

<code>autofocus</code>

* Default is "false"
* A "true" value simply adds the HTML5 "autofocus" attribute

<code>checked</code>

* Default is "false"
* A "true" value simply adds the "checked" attribute

<code>required</code>

* Default is "false"
* A "true" value simply adds the HTML5 "required" attribute

<code>add_label</code>

* Default is "true"
* A "false" value will suppress the label for this field

<code>wrap_tag</code>

* Default is "div"
* A valid HTML tag name for the field wrapper. 
* Set this to an empty string to not use a wrapper for the field

<code>wrap_class</code>

* Default is an array with "form_field_wrap" as the only value
* Classes should be added as an array of valid HTML class names

<code>wrap_id</code>

* Default is empty
* Add an id to this field by passing a string

<code>wrap_style</code>

* Default is empty
* This string of text will be added within a style attribute

### 4) Gerar o FOrmulário

Uma instrução rápida mostra o formulário como HTML:

```php
$new_form->build_form();
```

## Bons estudos e trabalhos a todos
