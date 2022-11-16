<?php
namespace Dob\DynamicTag;

use App\Http\Controllers\Controller;

class DTagController extends Controller
{
function getWysiwygGif() {
		$path = base_path('packages/dob/dtag/src/DWysiwyg.gif');
		if(!\File::exists($path)) {
			return response()->json(['message' => 'Image not found.'], 404);
		}
	
		$file = \File::get($path);
		$response = \Response::make($file, 200);
		$response->header("Content-Type", 'gif');
	
		return $response;
	}
	
	function getLoadGif() {
		$path = base_path('packages/dob/dtag/src/loading.gif');
		if(!\File::exists($path)) {
			return response()->json(['message' => 'Image not found.'], 404);
		}
	
		$file = \File::get($path);
		$response = \Response::make($file, 200);
		$response->header("Content-Type", 'gif');
	
		return $response;
	}
	
	function getJs() {
		$path = base_path('packages/dob/dtag/src/dtag.js');
		if(!\File::exists($path)) {
		    return response()->json(['message' => 'DTag js não encontrado.'], 404);
		}
	
		$file = \File::get($path);
		$response = \Response::make($file, 200);
		$response->header("Content-Type", 'application/javascript');
	
		return $response;
	}
}

