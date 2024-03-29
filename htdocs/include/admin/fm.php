<?php
class admin_fm extends admin
{
	protected $upload_path = '/upload/';

	protected $real_upload_path = '/upload/';

	protected $records_per_page = 20;
	
	protected $sort_field = 'name';
	
	protected $sort_order = 'asc';
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function __construct( $object )
	{
		parent::__construct( $object );
		
		$this -> real_upload_path = realpath( $_SERVER['DOCUMENT_ROOT'] . $this -> upload_path ) . DIRECTORY_SEPARATOR;
	}
	
	protected function action_index()
	{
		if( !file_exists( $this -> real_upload_path ) )
			if ( !( @mkdir( $this -> real_upload_path , 0777, true ) ) )
				throw new Exception( 'Ошибка. Невозможно создать каталог "' . $this -> real_upload_path . '".', true );
		
		if( !is_readable( $this -> real_upload_path ) )
			throw new Exception( 'Ошибка. Невозможно прочитать каталог "' . $this -> real_upload_path . '".', true );
		
		$sort_field = init_string( 'sort_field' );
		$sort_order = init_string( 'sort_order' );
		if ( $sort_field && in_array( $sort_field, array( 'id', 'name', 'size', 'date' ) ) )
			$this -> sort_field = $sort_field;
		if ( $sort_order && in_array( $sort_order, array( 'asc', 'desc' ) ) )
			$this -> sort_order = $sort_order;
		
		$records_header['id'] = array( 'title' => 'ID' );
		$records_header['name'] = array( 'title' => 'Название', 'type' => 'string', 'main' => 1 );
		$records_header['size'] = array( 'title' => 'Размер', 'type' => 'int' );
		$records_header['date'] = array( 'title' => 'Дата', 'type' => 'datetime' );
		$records_header['_action'] = array( 'title' => 'Действия' );
		
		foreach( array( 'id', 'name', 'size', 'date' ) as $show_field )
		{
			$field_sort_order = $show_field == $this -> sort_field && $this -> sort_order == 'asc' ? 'desc' : 'asc';
			$records_header[$show_field]['sort_url'] =
				request_url( array( 'sort_field' => $show_field, 'sort_order' => $field_sort_order ), array( 'page' ) );
			if ( $show_field == $this -> sort_field )
				$records_header[$show_field]['sort_sign'] = $field_sort_order == 'asc' ? 'desc' : 'asc';
		}
		
		$upload_dir = opendir( $this -> real_upload_path );
		
		$file_list = array();
		while ( ( $file = readdir( $upload_dir ) ) !== false )
		{
			$real_file_path = $this -> real_upload_path . $file;
			if ( is_file( $real_file_path ) )
				$file_list[] = array( 'name' => $file, 'size' => filesize( $real_file_path ), 'date' => filemtime( $real_file_path ) );
		}
		closedir( $upload_dir );
		
		foreach ( $file_list as $file_index => $file_item )
			$file_list[$file_index]['id'] = $file_index + 1;
		
		usort( $file_list, array( $this, 'sort_file_list' ) );
		
		$records_count = count( $file_list );
		
		$pages = paginator::construct( $records_count, array( 'by_page' => $this -> records_per_page ) );
		
		foreach ( $file_list as $file_index => $file_item )
		{
			if ( $file_index >= $pages['current_page'] * $this -> records_per_page &&
				$file_index < ( $pages['current_page'] + 1 ) * $this -> records_per_page )
			{
				$file_list[$file_index]['name'] = '<a href="' . $this -> upload_path . $file_item['name'] . '">' . $file_item['name'] . '</a>';
				$file_list[$file_index]['date'] = str_replace( ' ', '&nbsp;', date( 'd.m.Y H:i', $file_item['date'] ) );
				$file_list[$file_index]['_action'] = array( 'delete' => array( 'title' => 'Удалить', 'url' =>
					url_for( array( 'object' => 'fm', 'action' => 'delete', 'file' => urlencode( $file_item['name'] ) ) ),
						'event' => array( 'method' => 'onclick', 'value' => 'return confirm( \'Вы действительно хотите удалить этот файл?\' )' ) ) );
			}
			else
				unset( $file_list[$file_index] );
		}
		
		$actions = array( 'add' => array( 'title' => 'Закачать файл', 'url' =>
			url_for( array( 'object' => $this -> object, 'action' => 'upload' ) ) ) );
		
		$this -> view -> assign( 'title', $this -> object_desc['title'] );
		$this -> view -> assign( 'actions', $actions );
		$this -> view -> assign( 'records', $file_list );
		$this -> view -> assign( 'header', $records_header );
		$this -> view -> assign( 'counter', $records_count );
		
		$this -> view -> assign( 'pages', paginator::fetch( $pages, 'admin/pages.tpl' ) );
		
		$this -> content = $this -> view -> fetch( 'admin/table.tpl' );
		
		$this -> store_state();
	}
	
	protected function action_delete()
	{
		$file = init_string( 'file' );
		
		$real_file_path = $this -> real_upload_path . $file;
		
		if( $real_file_path != realpath( $real_file_path ) )
			throw new Exception( 'Ошибка. Недопустимое имя файла "' . $real_file_path . '".', true );
		
		if( !file_exists( $real_file_path ) || !is_file( $real_file_path ) )
			throw new Exception( 'Ошибка. Файл "' . $real_file_path . '" не существует.', true );
		
		@unlink( $real_file_path );
	
		if ( file_exists( $real_file_path ) )
			throw new Exception( 'Ошибка. Невозможно удалить файл "' . $real_file_path . '".', true );
	
		$this -> redirect();
	}
	
	protected function action_upload()
	{
		$action_title = 'Закачка файла';
		$form_url = url_for( array( 'object' => 'fm', 'action' => 'upload_save' ) );
		
		$this -> view -> assign( 'record_title', $this -> object_desc['title'] );
		$this -> view -> assign( 'action_title', $action_title );
		$this -> view -> assign( 'form_url', $form_url );
		
		$this -> view -> assign( 'back_url', url_for( $this -> restore_state() ) );
		
		$this -> content = $this -> view -> fetch( 'admin/fm/upload.tpl' );
		$this -> output['meta_title'] .= ' :: ' . $action_title;
	}
	
	protected function action_upload_save()
	{
		$field_name = 'file';
		
		if ( isset( $_FILES[$field_name . '_file']['name'] ) && $_FILES[$field_name . '_file']['name'] )
			upload::upload_file( $_FILES[$field_name . '_file'], $this -> upload_path );
		else
			throw new Exception( 'Ошибка. Отсутствует файл для закачки.', true );
		
		$this -> redirect();
	}
	
	protected function action_upload_file()
	{
		$CKEditorFuncNum = intval( init_string( 'CKEditorFuncNum' ) );
		
		if ( isset( $_FILES['upload']['name'] ) && $_FILES['upload']['name'] )
		{
			try
			{
				$upload_file = upload::upload_file( $_FILES['upload'], $this -> upload_path );
				
				die( '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(' . $CKEditorFuncNum . ', "' . $upload_file . '", "");</script>' );
			}
			catch ( Exception $e )
			{
				die( '<script type="text/javascript">alert( "' . addslashes( $e -> getMessage() ) . '" ); window.parent.CKEDITOR.tools.callFunction(' . $CKEditorFuncNum . ', "", "");</script>' );
			}
		}
		
		die( '<script type="text/javascript">alert( "Ошибка! Отсутствует файл для закачки." ); window.parent.CKEDITOR.tools.callFunction(' . $CKEditorFuncNum . ', "", "");</script>' );
	}
	
	private function sort_file_list( $a, $b )
	{
		if ( $this -> sort_field == 'id' or $this -> sort_field == 'size' )
			$result = strnatcmp( $a[ $this -> sort_field ], $b[ $this -> sort_field ] );
		else
			$result = strcmp( $a[ $this -> sort_field ], $b[ $this -> sort_field ] );
		return ( ( $this -> sort_order == 'asc' ) ? 1 : -1 ) * $result;
	}
}
