<?php
class module_question extends module
{
	// Вывод списка вопросов
	protected function action_index()
	{
		$error = ( init_string( 'action' ) == 'question' ) ? $this -> add_question() : '';
		
		$question_per_page = max( intval( get_preference('question_per_page', 10 ) ), 1 );
		$question_count = db::select_cell( 'select count(*) from question where question_active = 1' );
		
		$pages = paginator::construct( $question_count, array( 'by_page' => $question_per_page ) );
		
		$question_list = $this -> get_question_list( $pages['by_page'], $pages['offset'] );
		
		$this -> view -> assign( 'error', $error );
		$this -> view -> assign( 'form_url', self_url() );
		$this -> view -> assign( 'question_list', $question_list );
		$this -> view -> assign( 'pages', paginator::fetch( $pages ) );
		
		$this -> content = $this -> view -> fetch( 'module/question/question_list.tpl' );
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	// Получение количества вопросов
	protected function get_question_count()
	{
		return db::select_cell( 'select count(*) from question where question_active = 1' );
	}
	
	// Получение списка вопросов
	protected function get_question_list( $limit = null, $offset = null )
	{
		$limit_cond = !is_null( $limit ) ? ( 'limit ' . $limit . ( !is_null( $offset ) ? ( ' offset ' . $offset ) : '' ) ) : '';
		
		$question_list = db::select_all( 'select * from question where question_active = 1 order by question_date desc ' . $limit_cond );
		
		foreach ( $question_list as $question_index => $question_item )
			$question_list[$question_index]['question_date'] = date::get( $question_item['question_date'], 'short' );
		
		return $question_list;
	}
	
	// Добавление вопроса
	protected function add_question()
	{
		$field_list = array(
			'question_author', 'question_email', 'question_content', 'captcha_value' );
		foreach ( $field_list as $field_name )
			if ( is_empty( $$field_name = trim( init_string( $field_name ) ) ) )
				$error[$field_name] = 'Поле обязательно для заполнения';
		
		if ( !isset( $error['question_email'] ) && !valid::email( $question_email ) )
			$error['question_email'] = 'Некорректный email';
		
		if ( !isset( $error['captcha_value'] ) && !captcha::check( $captcha_value ) )
			$error['captcha_value'] = 'Введеное число не соответствует коду на изображении';
		
		if ( !isset( $error['question_author'] ) && db::select_cell('
				select true from question_blacklist where blacklist_ip = :blacklist_ip',
					array( 'blacklist_ip' => $_SERVER['REMOTE_ADDR'] ) ) )
			$error['question_author'] = 'Ваш IP-адрес заблокирован';
		
		if ( count( $error ) )
			return $error;
		
		$question_record = array(
			'question_content' => $question_content,
			'question_author' => $question_author,
			'question_email' => $question_email,
			'question_date' => date::now(),
			'question_ip' => $_SERVER['REMOTE_ADDR'],
			'question_active' => 1 );
		
		db::insert( 'question', $question_record );
		
		redirect_back();
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	// Отключаем кеширование
	protected function get_cache_key()
	{
		return false;
	}
}
