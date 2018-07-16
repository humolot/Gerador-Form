<?php

// V 1.0
// Gianck Luiz @humolot
// Gerador de Formularios

class GerarForm {

	// Armazena todas as entradas de formulário
	private $inputs = array();

	// Armazena todos os atributos do formulário
	private $form = array();

	// Este formulário tem um botão de envio?
	private $has_submit = false;

	/**
	 * Função de construtor para definir ações e atributos do formulário
	 *
	 * @param string $action
	 * @param bool   $args
	 */
	function __construct( $action = '', $args = false ) {

		// Atributos de formulário padrão
		$defaults = array(
			'action'       => $action,
			'method'       => 'post',
			'enctype'      => 'application/x-www-form-urlencoded',
			'class'        => array(),
			'id'           => '',
			'markup'       => 'html',
			'novalidate'   => false,
			'add_nonce'    => false,
			'add_honeypot' => true,
			'form_element' => true,
			'add_submit'   => true
		);

		// Mesclar com argumentos, se presente
		if ( $args ) {
			$settings = array_merge( $defaults, $args );
		} // Caso contrário, use os padrões.
		else {
			$settings = $defaults;
		}

		// Iterar e salvar cada opção
		foreach ( $settings as $key => $val ) {
		// Tenta definir com a configuração passada pelo usuário
		// Se não, tenta o padrão com o mesmo nome de chave
			if ( ! $this->set_att( $key, $val ) ) {
				$this->set_att( $key, $defaults[ $key ] );
			}
		}
	}

	/**
	 * Validar e definir formulários
	 *
	 * @param string        $key A valid key; switch statement ensures validity
	 * @param string | bool $val A valid value; validated for each key
	 *
	 * @return bool
	 */
	function set_att( $key, $val ) {

		switch ( $key ) :

			case 'action':
				break;

			case 'method':
				if ( ! in_array( $val, array( 'post', 'get' ) ) ) {
					return false;
				}
				break;

			case 'enctype':
				if ( ! in_array( $val, array( 'application/x-www-form-urlencoded', 'multipart/form-data' ) ) ) {
					return false;
				}
				break;

			case 'markup':
				if ( ! in_array( $val, array( 'html', 'xhtml' ) ) ) {
					return false;
				}
				break;

			case 'class':
			case 'id':
				if ( ! $this->_check_valid_attr( $val ) ) {
					return false;
				}
				break;
			case 'value_btn':
				if ( ! $this->_check_valid_attr( $val ) ) {
					return false;
				}
				break;
			

			case 'class_btn':
				if ( ! in_array( $val, array('light','dark','success', 'primary', 'info', 'danger', 'warning' ) ) ) {
					return false;
				}
				break;
			case 'btn':
				if ( ! in_array( $val, array( 'btn-sm', 'btn-lg', 'btn') ) ) {
					return false;
				}
				break;
				
			case 'novalidate':
			case 'add_honeypot':
			case 'form_element':
			case 'add_submit':
				if ( ! is_bool( $val ) ) {
					return false;
				}
				break;

			case 'add_nonce':
				if ( ! is_string( $val ) && ! is_bool( $val ) ) {
					return false;
				}
				break;

			default:
				return false;

		endswitch;

		$this->form[ $key ] = $val;

		return true;

	}

	/**
	 * Adicione um campo de entrada ao formulário para saída posterior
	 *
	 * @param string $label
	 * @param string $args
	 * @param string $slug
	 */
	function add_input( $label, $args = '', $slug = '' ) {

		if ( empty( $args ) ) {
			$args = array();
		}

		// Crie um id válido ou atributo de classe
		if ( empty( $slug ) ) {
			$slug = $this->_make_slug( $label );
		}

		$defaults = array(
			'type'             => 'text',
			'name'             => $slug,
			'id'               => $slug,
			'label'            => $label,
			'value'            => '',
			'placeholder'      => '',
			'class'            => array(),
			'class_btn'        => array(),
			'min'              => '',
			'max'              => '',
			'step'             => '',
			'autofocus'        => false,
			'checked'          => false,
			'selected'         => false,
			'required'         => false,
			'add_label'        => true,
			'options'          => array(),
			'wrap_tag'         => 'div',
			'wrap_class'       => array( 'form-group' ),
			'wrap_id'          => '',
			'wrap_style'       => '',
			'before_html'      => '',
			'after_html'       => '',
			'request_populate' => true
		);
		
		$args                  = array_merge( $defaults, $args );
		$this->inputs[ $slug ] = $args;

	}

	/**
	 * Adicione várias entradas à fila de entrada
	 *
	 * @param $arr
	 *
	 * @return bool
	 */
	function add_inputs( $arr ) {

		if ( ! is_array( $arr ) ) {
			return false;
		}

		foreach ( $arr as $field ) {
			$this->add_input(
				$field[0], isset( $field[1] ) ? $field[1] : '',
				isset( $field[2] ) ? $field[2] : ''
			);
		}

		return true;
	}

	/**
	 * Construa o HTML para o formulário com base na fila de entrada
	 *
	 * @param bool $echo Should the HTML be echoed or returned?
	 *
	 * @return string
	 */
	function build_form( $echo = true ) {

		$output = '';

		if ( $this->form['form_element'] ) {
			$output .= '<form method="' . $this->form['method'] . '"';

			if ( ! empty( $this->form['enctype'] ) ) {
				$output .= ' enctype="' . $this->form['enctype'] . '"';
			}

			if ( ! empty( $this->form['action'] ) ) {
				$output .= ' action="' . $this->form['action'] . '"';
			}

			if ( ! empty( $this->form['id'] ) ) {
				$output .= ' id="' . $this->form['id'] . '"';
			}

			if ( count( $this->form['class'] ) > 0 ) {
				$output .= $this->_output_classes( $this->form['class'] );
			}

			if ( $this->form['novalidate'] ) {
				$output .= ' novalidate';
			}

			$output .= '>';
		}

		// Adicionar campo anti-spam ao honeypot
		if ( $this->form['add_honeypot'] ) {
			$this->add_input( 'Leave blank to submit', array(
				'name'             => 'honeypot',
				'slug'             => 'honeypot',
				'id'               => 'form_honeypot',
				'wrap_tag'         => 'div',
				'wrap_class'       => array( 'form-group', 'hidden' ),
				'wrap_id'          => '',
				'wrap_style'       => 'display: none',
				'request_populate' => false
			) );
		}

		// Adicione um campo nonce WordPress
		if ( $this->form['add_nonce'] && function_exists( 'wp_create_nonce' ) ) {
			$this->add_input( 'WordPress nonce', array(
				'value'            => wp_create_nonce( $this->form['add_nonce'] ),
				'add_label'        => false,
				'type'             => 'hidden',
				'request_populate' => false
			) );
		}

		// Iterar pela fila de entrada e adicionar entrada HTML
		foreach ( $this->inputs as $val ) :

			$min_max_range = $element = $end = $attr = $field = $label_html = '';

			// População automática de valores usando dados $ _REQUEST
			if ( $val['request_populate'] && isset( $_REQUEST[ $val['name'] ] ) ) {

				// Esse campo pode ser preenchido diretamente?
				if ( ! in_array( $val['type'], array( 'html', 'title', 'radio', 'checkbox', 'select', 'submit' ) ) ) {
					$val['value'] = $_REQUEST[ $val['name'] ];
				}
			}

			// checkboxes e radios
			if (
				$val['request_populate'] &&
				( $val['type'] == 'radio' || $val['type'] == 'checkbox' ) &&
				empty( $val['options'] )
			) {
				$val['checked'] = isset( $_REQUEST[ $val['name'] ] ) ? true : $val['checked'];
			}

			switch ( $val['type'] ) {

				case 'html':
					$element = '';
					$end     = $val['label'];
					break;

				case 'title':
					$element = '';
					$end     = '
					<h3>' . $val['label'] . '</h3>';
					break;

				case 'textarea':
					$element = 'textarea';
					$end     = '>' . $val['value'] . '</textarea>';
					break;

				case 'select':
					$element = 'select';
					$end     .= '>';
					foreach ( $val['options'] as $key => $opt ) {
						$opt_insert = '';
						if (
							
							$val['request_populate'] &&
							isset( $_REQUEST[ $val['name'] ] ) &&
							$_REQUEST[ $val['name'] ] === $key
						) {
							$opt_insert = ' selected';

						
						// O campo tem um valor selecionado padrão?
						} else if ( $val['selected'] === $key ) {
							$opt_insert = ' selected';
						}
						$end .= '<option value="' . $key . '"' . $opt_insert . '>' . $opt . '</option>';
					}
					$end .= '</select>';
					break;

				case 'radio':
				case 'checkbox':

					// Caso especial para várias caixas de seleção
					if ( count( $val['options'] ) > 0 ) :
						$element = '';
						foreach ( $val['options'] as $key => $opt ) {
							$slug = $this->_make_slug( $opt );
							$end .= sprintf(
								'<input type="%s" name="%s[]" value="%s" id="%s"',
								$val['type'],
								$val['name'],
								$key,
								$slug
							);
							if (
								$val['request_populate'] &&
								isset( $_REQUEST[ $val['name'] ] ) &&
								in_array( $key, $_REQUEST[ $val['name'] ] )
							) {
								$end .= ' checked';
							}
							$end .= $this->field_close();
							$end .= ' <label for="' . $slug . '">' . $opt . '</label>';
						}
						$label_html = '<div class="checkbox_header">' . $val['label'] . '</div>';
						break;
					endif;

				// Usado para todos os campos de texto (text, email, url, etc), single radios, single checkboxes, and submit
				default :
					$element = 'input';
					$end .= ' type="' . $val['type'] . '" value="' . $val['value'] . '"';
					$end .= $val['checked'] ? ' checked' : '';
					$end .= $this->field_close();
					break;

			}

			// Adicionado um botão de envio, não é necessário adicionar automaticamente um
			if ( $val['type'] === 'submit' ) {
				$this->has_submit = true;
			}
			
			// Valores numéricos especiais para tipos de intervalo e número
			if ( $val['type'] === 'range' || $val['type'] === 'number' ) {
				$min_max_range .= ! empty( $val['min'] ) ? ' min="' . $val['min'] . '"' : '';
				$min_max_range .= ! empty( $val['max'] ) ? ' max="' . $val['max'] . '"' : '';
				$min_max_range .= ! empty( $val['step'] ) ? ' step="' . $val['step'] . '"' : '';
			}

			// Adicione um campo de ID, se houver algum presente
			$id = ! empty( $val['id'] ) ? ' id="' . $val['id'] . '"' : '';

			// Saída classes
			$class = $this->_output_classes( $val['class'] );

			// Campos especiais HTML5, se definidos

			$attr .= $val['autofocus'] ? ' autofocus' : '';
			$attr .= $val['checked'] ? ' checked' : '';
			$attr .= $val['required'] ? ' required' : '';

			// Labels
			if ( ! empty( $label_html ) ) {
				$field .= $label_html;
			} elseif ( $val['add_label'] && ! in_array( $val['type'], array( 'hidden', 'submit', 'title', 'html' ) ) ) {
				if ( $val['required'] ) {
					$val['label'] .= ' <strong>*</strong>';
				}
				$field .= '<label for="' . $val['id'] . '">' . $val['label'] . '</label>';
			}

			if ( ! empty( $element ) ) {
				if ( $val['type'] === 'checkbox' ) {
					$field = '
					<' . $element . $id . ' name="' . $val['name'] . '"' . $min_max_range . $class . $attr . $end .
					         $field;
				} else {
					$field .= '
					<' . $element . $id . ' name="' . $val['name'] . '"' . $min_max_range . $class . $attr . $end;
				}
			
			} else {
				$field .= $end;
			}

			// Analisar e criar wrap, se necessário
			if ( $val['type'] != 'hidden' && $val['type'] != 'html' ) :

				$wrap_before = $val['before_html'];
				if ( ! empty( $val['wrap_tag'] ) ) {
					$wrap_before .= '<' . $val['wrap_tag'];
					$wrap_before .= count( $val['wrap_class'] ) > 0 ? $this->_output_classes( $val['wrap_class'] ) : '';
					$wrap_before .= ! empty( $val['wrap_style'] ) ? ' style="' . $val['wrap_style'] . '"' : '';
					$wrap_before .= ! empty( $val['wrap_id'] ) ? ' id="' . $val['wrap_id'] . '"' : '';
					$wrap_before .= '>';
				}

				$wrap_after = $val['after_html'];
				if ( ! empty( $val['wrap_tag'] ) ) {
					$wrap_after = '</' . $val['wrap_tag'] . '>' . $wrap_after;
				}

				$output .= $wrap_before . $field . $wrap_after;
			else :
				$output .= $field;
			endif;

		endforeach;

		// Add Botão
		if ( ! $this->has_submit && $this->form['add_submit'] ) {
			$output .= '<div class="form-group"><input type="submit"';
			if ( ! empty( $this->form['value_btn'] ) ) {
				$output .= ' value="' . $this->form['value_btn'] . '"';
			}
			if ( ! empty( $this->form['class_btn'] ) ) {
				$output .= ' class="btn btn-' . $this->form['class_btn'] . ' ' . $this->form['btn'] . '"';
			}
			
			$output .= ' name="submit"></div>';
		}

		// Finaliza o Formulário
		if ( $this->form['form_element'] ) {
			$output .= '</form>';
		}

		// Retorno?
		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	function field_close() {
		return $this->form['markup'] === 'xhtml' ? ' />' : '>';
	}

	private function _check_valid_attr( $string ) {

		$result = true;
		// Check $name for correct characters
		// "^[a-zA-Z0-9_-]*$"
		return $result;

	}

	private function _make_slug( $string ) {

		$result = '';

		$result = str_replace( '"', '', $string );
		$result = str_replace( "'", '', $result );
		$result = str_replace( '_', '-', $result );
		$result = str_replace('á','a',$result);
	 	$result = str_replace('Á','A',$result);
		$result = str_replace('à','a',$result);
		$result = str_replace('À','A',$result);
		$result = str_replace('â','a',$result);
		$result = str_replace('Â','A',$result);
		$result = str_replace('ã','a',$result);
		$result = str_replace('Ã','A',$result);
		$result = str_replace('ç','c',$result);
		$result = str_replace('Ç','C',$result);
		$result = str_replace('é','e',$result);
		$result = str_replace('É','E',$result);
		$result = str_replace('ê','e',$result);
		$result = str_replace('Ê','E',$result);
		$result = str_replace('è','e',$result);
		$result = str_replace('È','E',$result);
		$result = str_replace('í','i',$result);
		$result = str_replace('Í','I',$result);
		$result = str_replace('ó','o',$result);
		$result = str_replace('Ó','O',$result);
		$result = str_replace('ô','o',$result);
		$result = str_replace('Ô','O',$result);
		$result = str_replace('õ','o',$result);
		$result = str_replace('Õ','O',$result);
		$result = str_replace('ú','u',$result);
		$result = str_replace('Ú','U',$result);
		$result = str_replace('~','',$result);
		$result = str_replace('&','e',$result);
		$result = str_replace('.','',$result);
		$result = str_replace('-','',$result);
		$result = str_replace(',','',$result);
		$result = str_replace(';','',$result);
		$result = str_replace(':','',$result);
		$result = str_replace('(','',$result);
		$result = str_replace(')','',$result);
		$result = str_replace('/','',$result);
		$result = str_replace('@','',$result);
		$result = str_replace('#','',$result);
		$result = str_replace('$','',$result);
		$result = str_replace('%','',$result);
		$result = preg_replace( '~[\W\s]~', '-', $result );

		$result = strtolower( $result );

		return $result;

	}

	private function _output_classes( $classes ) {

		$output = '';

		
		if ( is_array( $classes ) && count( $classes ) > 0 ) {
			$output .= ' class="';
			foreach ( $classes as $class ) {
				$output .= $class . ' ';
			}
			$output .= '"';
		} else if ( is_string( $classes ) ) {
			$output .= ' class="' . $classes . '"';
		}

		return $output;
	}
}