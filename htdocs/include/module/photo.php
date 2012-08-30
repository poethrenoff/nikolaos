<?php
class module_photo extends module
{
	// Вывод списка фотографий
	protected function action_index()
	{
		$album_list = $this -> get_album_list();
		$photo_list = $this -> get_photo_list();
		
		$photo_list = array_group( $photo_list, 'photo_album' );
		foreach ( $album_list as $album_index => $album_item )
			$album_list[$album_index]['photo_list'] =
				isset( $photo_list[$album_item['album_id']] ) ? $photo_list[$album_item['album_id']] : array();
		
		$this -> view -> assign( 'album_list', $album_list );
		$this -> content = $this -> view -> fetch( 'module/photo/photo_list.tpl' );
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	// Получение списка альбомов
	protected function get_album_list()
	{
		return db::select_all( 'select * from photo_album order by album_order' );
	}
	
	// Получение списка фотографий
	protected function get_photo_list()
	{
		return db::select_all( 'select * from photo order by photo_order' );
	}
}
