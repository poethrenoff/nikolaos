<?php
class module_menu extends module
{
	protected function action_index()
	{
		$menu_id = $this -> get_param( 'id' );
		$menu_template = $this -> get_param( 'template' );
		
		$menu_list = db::select_all( 'select * from menu where menu_active = 1 order by menu_order' );
		$menu_tree = tree::get_tree( $menu_list, 'menu_id', 'menu_parent', $menu_id );
		
		$site = site(); $current_page = page();
		$page_list = array_reindex( $site['page'], 'page_id' );
		
		foreach ( $menu_tree as $menu_index => $menu_item )
		{
			if ( $menu_item['menu_url'] )
				continue;
			
			if ( isset( $page_list[$menu_item['menu_page']] ) )
			{
				$menu_tree[$menu_index]['menu_url'] = $page_list[$menu_item['menu_page']]['page_path'];
				if ( $menu_tree[$menu_index]['menu_url'] != '/' &&
						preg_match( '|^' . $menu_tree[$menu_index]['menu_url'] . '|i', $current_page['page_path'] ) )
					$menu_tree[$menu_index]['_selected'] = true;
			}
			else
				unset( $menu_tree[$menu_index] );
		}
		
		$this -> view -> assign( 'menu_tree', $menu_tree );
		
		$this -> content = $this -> view -> fetch( 'module/menu/' . $menu_template . '.tpl' );
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	// Дополнительные параметры хэша модуля
	protected function ext_cache_key()
	{
		$current_page = page();
		
		return parent::ext_cache_key() +
			array( '_page' => $current_page['page_id'] );
	}
}