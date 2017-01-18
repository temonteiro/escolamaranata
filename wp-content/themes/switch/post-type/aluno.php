<?php

	//Adicionando o tipo Aluno como Post Type
	function type_post_aluno(){
		$labels = array(
				'name' => _x('Alunos', 'Post Type General Name', 'switch' ),
				'singular_name' => _x('Alunos','Post Type General Name', 'switch' ),
				'add_new' => __('Adicionar Novo', 'switch' ),
				'add_new_item' => __('Novo Aluno', 'switch' ),
				'edit_item' => __('Editar Aluno', 'switch' ),
				'new_item' => __('Novo Aluno', 'switch' ),
				'view_item' => __('Ver Aluno', 'switch' ),
				'search_items' => __('Procurar Alunos', 'switch' ),
				'not_found' => __('Nenhum registro encontrado', 'switch' ),
				'not_found_in_trash' => __('Nenhum registro encontrado na lixeira', 'switch' ),
				'parent_item_colon' => '',
				'menu_name' => __('Alunos', 'switch' )
			);

		$args = array(

				'labels' => $labels,
				'public' => true,
				'public_queryable' => true,
				'show_ui' => true,
				'show_in_menu'        => true,
				'query_var' => true,
				'rewrite' => true,
				'capability_type' => 'page',
				'has_archive' => true,
				'hierarchical' => false,
				'menu_position' => 5,
				'menu_icon' => get_template_directory_uri() . '/images/menu-icon/team.png',
				'register_meta_box_cb' => 'aluno_meta_box',
				'supports' => array('title'),

			);
		register_post_type('aluno', $args);
	}
	add_action('init', 'type_post_aluno', 0);

	

	//Campos personalizados para o cadastro do Aluno
	function aluno_meta_box(){
		add_meta_box('meta_box_test', __('Cadastro do Aluno'), 'meta_box_meta_test', 'aluno', 'normal', 'high');
		add_action('admin_print_scripts', 'my_admin_scripts');
	}

	function my_admin_scripts() {  

		wp_register_script('jquery-mask', get_template_directory_uri().'/js/jquery.mask.js',array('jquery'), '1.0.0');
	    wp_enqueue_script('jquery-mask');
	    wp_register_script('mask-aluno',  get_template_directory_uri().'/js/mask-aluno.js',array('jquery'), '1.0.0');
	    wp_enqueue_script('mask-aluno');
	    
	}

	function meta_box_meta_test(){
	  global $post;
	  global $wpdb;
 		
	  $alunoInfo = $wpdb->get_results("SELECT * 
									   FROM mar_aluno 
									   WHERE nome_aluno ='".$post->post_title."'");
	  

	  foreach ($alunoInfo as $aluno) {
	  	  $alunoNome = $aluno->nome_aluno;
	      $mae 		 = $aluno->nome_mae;
	      $pai 		 = $aluno->nome_pai;
	      $telefone  = $aluno->telefone_aluno;
	      $celular 	 = $aluno->celular_aluno;
	      $is_ativo  = $aluno->is_ativo;
	  }

      /**/

    ?>
    	<label for="inputMediaMeta">Nome do Aluno: </label>
    	<input id="nome_aluno" type="text" size="100" name="nome_aluno" value="<?php echo $alunoNome; ?>" />

    	<div class="clear"></div>
		<br/>

		<label for="inputMediaMeta">Nome da Mãe: </label>
    	<input id="nome_mae" type="text" size="100" name="nome_mae" value="<?php echo $mae; ?>" />

    	<div class="clear"></div>
		<br/>

		<label for="inputMediaMeta">Nome do Pai: </label>
    	<input id="nome_pai" type="text" size="100" name="nome_pai" value="<?php echo $pai; ?>" />

    	<div class="clear"></div>
		<br/>

		<label for="inputMediaMeta">Telefone: </label>
    	<input id="telefone_aluno" type="text" size="100" name="telefone_aluno" value="<?php echo $telefone; ?>" />

    	<div class="clear"></div>
		<br/>

		<label for="inputMediaMeta">Celular: </label>
    	<input id="celular_aluno" type="text" size="100" name="celular_aluno" value="<?php echo $celular; ?>" />

    	<div class="clear"></div>
		<br/>

		<label for="inputMediaMeta">Aluno está matriculado? </label>
    	<select name="is_ativo">
    		<option <?php if($is_ativo === "0"){ echo 'selected="select"';}?> value="0">Sim</option>
    		<option <?php if($is_ativo === "1"){ echo 'selected="select"';}?> value="1">Não</option>
    	</select>

    	<div class="clear"></div>
		<br/>
    	
    <?php
	}

	
	function save_aluno_post(){
		global $wpdb;

		$existeAluno = $wpdb->get_results("SELECT id_aluno 
										   FROM mar_aluno 
										   WHERE nome_aluno ='".$_POST['nome_aluno']."'");

		if(count($existeAluno) > 0){
			$wpdb->update('mar_aluno', array(
					'nome_aluno' => $_POST['nome_aluno'],
					'nome_mae' => $_POST['nome_mae'],
					'nome_pai' => $_POST['nome_pai'],
					'telefone_aluno' => $_POST['telefone_aluno'],
					'celular_aluno' => $_POST['celular_aluno'],
					'is_ativo' => $_POST['is_ativo']
				),
				array('nome_aluno' => $_POST['nome_aluno']));
		}else{
			$wpdb->insert('mar_aluno', array(
					'nome_aluno' => $_POST['nome_aluno'],
					'nome_mae' => $_POST['nome_mae'],
					'nome_pai' => $_POST['nome_pai'],
					'telefone_aluno' => $_POST['telefone_aluno'],
					'celular_aluno' => $_POST['celular_aluno'],
					'is_ativo' => $_POST['is_ativo']
				));
		}
		
	}
	add_action('save_post', 'save_aluno_post');
?>