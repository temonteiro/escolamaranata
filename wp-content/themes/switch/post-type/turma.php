<?php

	//Adicionando o tipo Aluno como Post Type
	function type_post_turma(){
		$labels = array(
				'name' => _x('Turma', 'Post Type General Name', 'switch' ),
				'singular_name' => _x('Turma','Post Type General Name', 'switch' ),
				'add_new' => __('Adicionar Novo', 'switch' ),
				'add_new_item' => __('Novo Turma', 'switch' ),
				'edit_item' => __('Editar Turma', 'switch' ),
				'new_item' => __('Novo Turma', 'switch' ),
				'view_item' => __('Ver Turma', 'switch' ),
				'search_items' => __('Procurar Turmas', 'switch' ),
				'not_found' => __('Nenhum registro encontrado', 'switch' ),
				'not_found_in_trash' => __('Nenhum registro encontrado na lixeira', 'switch' ),
				'parent_item_colon' => '',
				'menu_name' => __('Turma', 'switch' )
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
				'menu_icon' => get_template_directory_uri() . '/images/menu-icon/classes.png',
				'register_meta_box_cb' => 'turma_meta_box',
				'supports' => array('title'),

			);
		register_post_type('turma', $args);
	}
	add_action('init', 'type_post_turma', 0);

	

	//Campos personalizados para o cadastro do Aluno
	function turma_meta_box(){
		add_meta_box('meta_box_test', __('Cadastro da Turma'), 'meta_box_meta_turma', 'turma', 'normal', 'high');
		
	}
	
	add_action('admin_print_scripts', 'bootstrap_scripts');
	function bootstrap_scripts() {
		
	    wp_enqueue_script( 'bootstrap-js', get_template_directory_uri().'/style/js/bootstrap.min.js', array( 'jquery' ), '1.0.0' );
	}

	add_action('admin_print_styles', 'bootstrap_styles');
	function bootstrap_styles(){
	    wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/style/css/bootstrap.min.css', array('switch-stylesheet'));
	    wp_enqueue_style('bootstrap-theme-css', get_template_directory_uri() . '/style/css/bootstrap-theme.min.css', array('switch-stylesheet'));
	}

	function meta_box_meta_turma(){
	  global $wpdb;

	  $professores = $wpdb->get_results("SELECT id_professor, nome_professor FROM mar_professor ORDER BY nome_professor");
	  
	  $turmasExiste = $wpdb->get_results("SELECT id_turma_aluno FROM mar_turma_aluno LIMIT 1");

	  if(count($turmasExiste) > 0){
	  	$alunos = $wpdb->get_results("SELECT id_aluno, nome_aluno 
	  								  FROM mar_aluno alu
	  								  WHERE id_aluno NOT IN('SELECT id_aluno FROM mar_turma_aluno') ORDER BY nome_aluno");
	  }else{
	  	 $alunos = $wpdb->get_results("SELECT id_aluno, nome_aluno FROM mar_aluno ORDER BY nome_aluno");
	  }
	 

?>
    	<label for="inputMediaMeta"><strong>Professor: </strong></label>
    	<select name="is_ativo">
    		<?php foreach ($professores as $professor) { ?>
    			<option value="<?php echo $professor->nome_professor; ?>"><?php echo $professor->nome_professor; ?></option>
    		<?php } ?>
    		
    	</select>

    	<div class="clear"></div>
		<br/>
    	
   
		<label for="inputMediaMeta"><strong>Lista de Alunos: </strong></label>
    	<table class="table">
    		<tr>
    			<th>#</th>
    			<td><strong>Aluno</strong></td>
    		</tr>
    		<?php foreach ($alunos as $aluno) { ?>
    			<tr>
    				<td> <input type="checkbox" name="aluno" value="<?php echo $aluno->id_aluno; ?>"> </td>
    				<td> <?php echo $aluno->nome_aluno; ?></td>
    			</tr>
    		<?php } ?>
    	</table>

    	<div class="clear"></div>
		<br/>
    	
<?php
	}

	
	function save_turma_post(){
		global $wpdb;

		
		
	}
	add_action('save_post', 'save_turma_post');
?>