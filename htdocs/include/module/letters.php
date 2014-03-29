<?php
class module_letters extends module
{
	// Вывод полного списка писем
	protected function action_index()
	{
		$total = $this -> get_count();
		$count = max( 1, intval( $this -> get_param( 'count' ) ) );
		
		$pages = paginator::construct( $total, array( 'by_page' => $count ) );
		
		$item_list = $this -> get_list( $pages['by_page'], $pages['offset'] );
		
		foreach( $item_list as $item_index => $item )
		{
			$item_list[$item_index]['letters_date'] = date::get( $item['letters_date'], 'd.m.Y' );
			$item_list[$item_index]['letters_url'] = url_for( array( 'controller' => 'pilgrimages/letters', 'action' => 'item', 'id' => $item['letters_id'] ) );
		}
		
		$this -> view -> assign( 'item_list', $item_list );
		$this -> view -> assign( 'pages', paginator::fetch( $pages ) );
		
		$this -> content = $this -> view -> fetch( 'module/letters/list.tpl' );
	}
	
	// Вывод конкретного письма
	protected function action_item()
	{
		$item = $this -> get_item( id() );
		
		$item['letters_date'] = date::get( $item['letters_date'], 'd.m.Y' );
		
		$this -> view -> assign( $item );
		$this -> view -> assign( 'letters_url', url_for( array( 'controller' => 'pilgrimages/letters' ) ) );
		
		$this -> output['meta_title'] = $item['letters_title'];
		$this -> content = $this -> view -> fetch( 'module/letters/item.tpl' );
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	// Получение количества писем
	protected function get_count()
	{
		return db::select_cell( 'select count(*) from letters' );
	}
	
	// Получение списка писем
	protected function get_list( $limit = null, $offset = null )
	{
		$limit_cond = '';
		if ( isset( $limit ) )
		{
			$limit_cond .= 'limit ' . $limit;
			if ( isset( $offset ) )
				$limit_cond .= ' offset ' . $offset;
		}
		
		return db::select_all( 'select * from letters order by letters_date desc ' . $limit_cond );
	}
	
	// Получение конкретного письма
	protected function get_item( $id )
	{
		$item = db::select_row( 'select * from letters where letters_id = :letters_id',
			array( 'letters_id' => $id ) );
		
		if ( !$item )
			not_found();
		
		return $item;
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	// Дополнительные параметры хэша модуля
	protected function ext_cache_key()
	{
		return parent::ext_cache_key() +
			( $this -> action == 'item' ? array( '_id' => id() ) : array() );
	}
}