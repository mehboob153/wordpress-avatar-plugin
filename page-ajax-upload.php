<?php

$upload_dir	= wp_upload_dir();
$user_data	= get_user_by( 'id', get_current_user_id() );

$output_dir	= $upload_dir['basedir'] . "/unknown_images/";
$output_url	= $upload_dir['baseurl'] . "/unknown_images/";
$return		= array();
$files 		= $_FILES["files"];


if (  $_POST ) {
	// Additional values specified 

	if ( isset($_POST['type']) ) {

		$upload_type = $_POST['type'];
		
		// Upload type
		switch ( $_POST['type'] ) {
			// Profile Avatars
			case 'profile_avatar':
				$output_dir	= $upload_dir['basedir'] . "/" . VL_USER_AVATAR_FOLDER . "/";
			break;
			
		}
	}

	/**
		DELETION
	**/
	// Image Deletion 
	if ( isset($_POST['op']) || isset($_POST['name']) ) {
		switch ( $_POST['op'] ) {
			case 'delete_avatar':
				$user_id	= get_current_user_id();
				$extension	= get_user_meta( $user_id, 'avatar_file_extension', true);
				$user_data	= get_user_by( 'id', get_current_user_id() );
				$output_dir	= $upload_dir['basedir'] . "/" . VL_USER_AVATAR_FOLDER . "/";
				$output_url	= $upload_dir['baseurl'] . "/" . VL_USER_AVATAR_FOLDER . "/";
				$sizes		= array('50' => 50, '100' => 100, '150' => 150, '250' => 250);

				foreach ($sizes as $w => $h) { 
					$image_delete	= unlink($output_dir . $user_data->user_login . '_' . $w . 'x' . $h . '.' . $extension);
				}
				$return[] = $output_url . "no_avatar_100x100.png";
				echo json_encode($return);
			break;

		}
	}
}

$output_dir = trailingslashit( $output_dir );
if ( !is_dir($output_dir) ) {
	mkdir($output_dir);
}

// Handle the actual upload here
if ( isset($files) ) {

	$error = $files["error"];
	switch ($upload_type) {
		/**
			PROFILE AVATAR
		**/
		case 'profile_avatar':
			$user_data		= get_user_by( 'id', get_current_user_id() );		
			$old_extension	= get_user_meta(get_current_user_id(), 'avatar_file_extension', true);
			
			$ext		= strtolower(pathinfo($files["name"], PATHINFO_EXTENSION));
			$sizes		= array('50' => 50, '100' => 100, '150' => 150, '250' => 250);

			list($_w, $_h) = getimagesize($files["tmp_name"]);
			if ( ($_w >= 250) && ($_h >= 250) ) {
				foreach ($sizes as $w => $h) {
					if(is_file("{$output_dir}{$user_data->user_login}_{$w}x{$h}.{$old_extension}")){
						$image_delete	= unlink("{$output_dir}{$user_data->user_login}_{$w}x{$h}.{$old_extension}");
					}
					$path = "{$output_dir}{$user_data->user_login}_{$w}x{$h}.{$ext}";
					$image = wp_get_image_editor( $files["tmp_name"] );
					if ( ! is_wp_error( $image ) ) {
						$image->resize( $w, $h, true );
						$image->save(  $path );
					}
				}
				
				update_user_meta(get_current_user_id(), 'avatar_file_extension', $ext);
				$return[] = "{$upload_dir['baseurl']}/" . VL_USER_AVATAR_FOLDER . "/{$user_data->user_login}_100x100.{$ext}?{$files["size"]}";
				//location.reload();
			} else {
				$return[] = "ERROR: Image should be atleast 250 x 250 pixels";
			}
			echo json_encode($return);
		break;

	}

}
