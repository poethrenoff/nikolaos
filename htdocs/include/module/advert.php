<?php
class module_advert extends module
{
	// Вывод полного списка объявлений
	protected function action_index()
	{
		$item_list = $this -> get_list();
		
		foreach( $item_list as $item_index => $item )
		{
			$item_list[$item_index]['advert_date'] = date::get( $item['advert_date'], 'd.m.Y' );
		}
		
		$this -> view -> assign( 'item_list', $item_list );
		
		$this -> content = $this -> view -> fetch( 'module/advert/list.tpl' );
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	// Получение списка объявлений
	protected function get_list()
	{
		return db::select_all( 'select * from advert order by advert_date desc' );
	}
}