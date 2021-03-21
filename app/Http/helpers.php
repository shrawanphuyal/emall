<?php

function upload_image_modified(\Illuminate\Http\UploadedFile $image, $prefix = "") {
	// modify the image name and upload it and return modified image name.
	$image_name_with_extension          = $image->getClientOriginalName();
	$modified_image_name_with_extension = "{$prefix}-emall-" . date('YmdHis') . "-" . str_random(5) . "-" . str_replace(" ", "-", $image_name_with_extension);

	if ($image->storeAs('images', $modified_image_name_with_extension)) {
		return $modified_image_name_with_extension;
	} else {
		return redirect()->back()->with('failure_message', 'Sorry, something went wrong while uploading the image. Please try again later!');
	}
}

function admin_url_material($url) {
	return my_asset("admin_material/" . $url);
}

function material_dashboard_url($url) {
	return my_asset("material_dashboard/" . $url);
}

function delete_if_exists($file_path) {
	\Illuminate\Support\Facades\File::delete($file_path);
}

function asdh_str_slug($str) {
	$str_final = str_replace('&', 'and', $str);

	return str_slug($str_final) . '-' . date('YmdHis');
	//return str_slug($str_final);
}

function string_to_array($string) {
	// removes all white spaces and return array
	return preg_split('/\s+/', $string);
}

function my_asset($url) {
	return asset($url);
}

function frontend_url($url) {
	return my_asset("frontend/{$url}");
}

function successResponse($message, $code = 200) {
	return response()->json([
		'status'  => true,
		'message' => $message,
		'code'    => $code,
	], $code);
}

function failureResponse($message, $code = 422) {
	return response()->json([
		'status'  => false,
		'message' => $message,
		'code'    => $code,
	], $code);
}