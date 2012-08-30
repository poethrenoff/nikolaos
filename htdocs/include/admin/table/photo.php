<?php
class admin_table_photo extends admin_table
{
	protected function action_add_save( $redirect = true )
	{
		$primary_field = parent::action_add_save( false );
		
		if ( ( isset( $_FILES['photo_image_file']['name'] ) && $_FILES['photo_image_file']['name'] ) &&
				!( isset( $_FILES['photo_preview_file']['name'] ) && $_FILES['photo_preview_file']['name'] ) )
			$this -> make_preview_images( $primary_field );
		
		if ( $redirect )
			$this -> redirect();
		
		return $primary_field;
	}
	
	protected function action_edit_save( $redirect = true )
	{
		parent::action_edit_save( false );
		
		if ( ( isset( $_FILES['photo_image_file']['name'] ) && $_FILES['photo_image_file']['name'] ) &&
				!( isset( $_FILES['photo_preview_file']['name'] ) && $_FILES['photo_preview_file']['name'] ) )
			$this -> make_preview_images();
		
		if ( $redirect )
			$this -> redirect();
	}
	
	//////////////////////////////////////////////////////////////////////////
	
	protected function make_preview_images( $primary_field = '' )
	{
		if ( $primary_field === '' ) $primary_field = id();
		
		$record = $this -> get_record( $primary_field );
		
		$photo_image_path = image::process(
			'resize', $record['photo_image'], $this -> fields['photo_image']['upload_dir'], 475, 340 );
		$photo_preview_path = image::process(
			'crop', $record['photo_image'], $this -> fields['photo_image']['upload_dir'], 210, 150 );
		
		db::update( 'photo', array( 'photo_image' => $photo_image_path,
			'photo_preview' => $photo_preview_path ), array( $this -> primary_field => $primary_field ));
	}
}