<?php

namespace App\Http\Controllers;

use App\Custom\FileUpload;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Requests\SubSubCategoryRequest;
use App\SubCategory;
use App\SubSubCategory;
use Illuminate\Http\Request;

class SubSubCategoryController extends AsdhController {
	private $prefix = 'sub-sub-category';

	public function __construct() {
		parent::__construct();
		$this->website['routeType'] = 'sub-sub-category';
	}

	public function index() {
		$this->website['sub_categories'] = SubSubCategory::latest()->with('category')->paginate($this->default_pagination_limit);

		return view('admin.sub_sub_category.index', $this->website);
	}

	public function create() {
		$this->website['edit']       = false;
		$this->website['categories'] = SubCategory::orderBy('name')->get();

		return view('admin.sub_sub_category.create', $this->website);
	}

	public function store(SubCategoryRequest $request, FileUpload $fileUpload) {
		$presentSubCategories = [];
		foreach ($request->name as $key => $name) {
			$subCategory            = SubSubCategory::whereName($name)->whereSubCategoryId($request->category_id)->first();
			$presentSubCategories[] = $subCategory;

			if ( ! $subCategory) {
				SubSubCategory::create([
					'sub_category_id' => $request->category_id,
					'name'            => $name,
					'slug'            => asdh_str_slug($name),
					'image'           => $fileUpload->setFile($request->image[$key] ?? null)->setPrefix($this->prefix)->handle(),
				]);
			}
		}

		$filteredSubCategories = array_pluck(array_filter($presentSubCategories), 'name');
		if (count($filteredSubCategories) > 0) {
			return redirect()->back()->with('success_message', '<b>' . implode(', ', $filteredSubCategories) . '</b> sub-categories were present before. So, they werent added.');
		}

		return redirect()->route('sub-category.show', $request->category_id)->with('success_message', 'SubSubCategory successfully added.');
	}

	public function edit(SubSubCategory $sub_sub_category) {
		$this->website['edit']         = true;
		$this->website['sub_category'] = $sub_sub_category;
		$this->website['categories']   = SubCategory::orderBy('name')->get();

		return view('admin.sub_sub_category.create', $this->website);
	}

	public function update(Request $request, SubSubCategory $sub_sub_category, FileUpload $fileUpload) {
		$request->validate(['category_id' => 'required|integer|min:1', 'name' => 'required|string|max:255']);

		$image = $request->image;
		if ($image) {
			$sub_sub_category->delete_image();
			$imageName = $fileUpload->setFile($image)->setPrefix($this->prefix)->handle();
		}

		return $sub_sub_category->update([
			'sub_category_id' => $request->category_id,
			'name'            => $request->name,
			'image'           => $imageName ?? $sub_sub_category->getOriginal('image'),
		])
			? redirect()->route('sub-category.show', $request->category_id)->with('success_message', 'SubSubCategory successfully updated.')
			: back()->with('failure_message', 'SubSubCategory could not be updated. Please try again later.');
	}

	public function destroy(SubSubCategory $sub_sub_category) {
		if ($sub_sub_category->delete()) {
			return back()->with('success_message', 'SubSubCategory successfully deleted.');
		}

		return back()->with('failure_message', 'SubSubCategory could not be deleted. Please try again later.');
	}
}
