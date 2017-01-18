<?php

	//Adicionando o tipo Aluno como Post Type
	function type_post_professor(){
		$labels = array(
				'name' => _x('Professor', 'Post Type General Name', 'switch' ),
				'singular_name' => _x('Professor','Post Type General Name', 'switch' ),
				'add_new' => __('Adicionar Novo', 'switch' ),
				'add_new_item' => __('Novo Professor', 'switch' ),
				'edit_item' => __('Editar Professor', 'switch' ),
				'new_item' => __('Novo Professor', 'switch' ),
				'view_item' => __('Ver Professor', 'switch' ),
				'search_items' => __('Procurar Professores', 'switch' ),
				'not_found' => __('Nenhum registro encontrado', 'switch' ),
				'not_found_in_trash' => __('Nenhum registro encontrado na lixeira', 'switch' ),
				'parent_item_colon' => '',
				'menu_name' => __('Professor', 'switch' )
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
				'menu_icon' => get_template_directory_uri() . '/images/menu-icon/prof.png',
				'register_meta_box_cb' => 'prof_meta_box',
				'supports' => array('title'),

			);
		register_post_type('professor', $args);
	}
	add_action('init', 'type_post_professor', 0);

	

	//Campos personalizados para o cadastro do Aluno
	function prof_meta_box(){
		add_meta_box('meta_box_test', __('Cadastro do Professor'), 'meta_box_meta_prof', 'professor', 'normal', 'high');
		
	}

	function meta_box_meta_prof(){
	  global $post;
	  global $wpdb;
 		
	  $professorInfo = $wpdb->get_results("SELECT * 
									   FROM mar_professor 
									   WHERE nome_professor ='".$post->post_title."'");

	  foreach ($professorInfo as $professor) {
	  	  $professorNome 	= $professor->nome_professor;
	      $data_nascimento 	= date('d/m/Y ', strtotime($professor->data_nascimento));
	      $is_ativo 		= $professor->is_ativo;
	  }

      /**/

    ?>
    	<label for="inputMediaMeta">Nome do Professor: </label>
    	<input id="nome_professor" type="text" size="100" name="nome_professor" value="<?php echo $professorNome; ?>" />

    	<div class="clear"></div>
		<br/>
   
		<label for="inputMediaMeta">Data de Aniversário: </label>
    	<input id="data_nascimento" type="text" size="100" name="data_nascimento" value="<?php echo $data_nascimento; ?>" />

    	<div class="clear"></div>
		<br/>

		<label for="inputMediaMeta">Professor está ativo? </label>
    	<select name="is_ativo">
    		<option <?php if($is_ativo === "0"){ echo 'selected="select"';}?> value="0">Sim</option>
    		<option <?php if($is_ativo === "1"){ echo 'selected="select"';}?> value="1">Não</option>
    	</select>

    	<div class="clear"></div>
		<br/>
    	
    <?php
	}

	
	function save_prof_post(){
		global $wpdb;

		$existeAluno = $wpdb->get_results("SELECT id_professor
										   FROM mar_professor 
										   WHERE nome_professor ='".$_POST['nome_professor']."'");

		$date1 = strtr($_POST['data_nascimento'], '/', '-');
		$timestamp = date('Y-m-d H:i:s', strtotime($date1));  

		if(count($existeAluno) > 0){
			$wpdb->update('mar_professor', array(
					'nome_professor' => $_POST['nome_professor'],
					'data_nascimento' => $timestamp,
					'is_ativo' => $_POST['is_ativo']
				),
				array('id_professor' => $existeAluno[0]->id_professor));
		}else{
			$wpdb->insert('mar_professor', array(
					'nome_professor' => $_POST['nome_professor'],
					'data_nascimento' => $timestamp,
					'is_ativo' => $_POST['is_ativo']
				));
		}
		
	}
	add_action('save_post', 'save_prof_post');
?>