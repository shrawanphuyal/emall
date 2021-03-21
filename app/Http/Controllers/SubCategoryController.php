<?php

namespace App\Http\Controllers;

use App\Category;
use App\Custom\FileUpload;
use App\Http\Requests\SubCategoryRequest;
use App\SubCategory;
use App\SubSubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends AsdhController {
	private $prefix = 'sub-category';

	public function __construct() {
		parent::__construct();
		$this->website['routeType'] = 'sub-category';
	}

	public function index() {
		$this->website['sub_categories'] = SubCategory::latest()->with('category')->paginate($this->default_pagination_limit);

		return view('admin.sub_category.index', $this->website);
	}

	public function create() {
		$this->website['edit']       = false;
		$this->website['categories'] = Category::orderBy('name')->get();

		return view('admin.sub_category.create', $this->website);
	}

	public function store(SubCategoryRequest $request, FileUpload $fileUpload) {
		$presentSubCategories = [];
		foreach ($request->name as $key => $name) {
			$subCategory            = SubCategory::whereName($name)->whereCategoryId($request->category_id)->first();
			$presentSubCategories[] = $subCategory;

			if ( ! $subCategory) {
				SubCategory::create([
					'category_id' => $request->category_id,
					'name'        => $name,
					'slug'        => asdh_str_slug($name),
					'image'       => $fileUpload->setFile($request->image[$key] ?? null)->setPrefix($this->prefix)->handle(),
				]);
			}
		}

		$filteredSubCategories = array_pluck(array_filter($presentSubCategories), 'name');
		if (count($filteredSubCategories) > 0) {
			return back()->with('success_message', '<b>' . implode(', ', $filteredSubCategories) . '</b> sub-categories were present before. So, they werent added.');
		}

		return redirect()->route('category.show', $request->category_id)->with('success_message', 'SubCategory successfully added.');
	}

	public function show(SubCategory $sub_category) {
		$this->website['routeType']    = 'sub-sub-category';
		$this->website['sub_category'] = $sub_category;
		$this->website['children']     = $sub_category->children;

		return view('admin.sub_category.show', $this->website);
	}

	public function edit(SubCategory $sub_category) {
		$this->website['edit']         = true;
		$this->website['sub_category'] = $sub_category;
		$this->website['categories']   = Category::orderBy('name')->get();

		return view('admin.sub_category.create', $this->website);
	}

	public function update(Request $request, SubCategory $sub_category, FileUpload $fileUpload) {
		$request->validate(['category_id' => 'required|integer|min:1', 'name' => 'required|string|max:255']);

		$image = $request->image;
		if ($image) {
			$sub_category->delete_image();
			$imageName = $fileUpload->setFile($image)->setPrefix($this->prefix)->handle();
		}

		return $sub_category->update([
			'category_id' => $request->category_id,
			'name'        => $request->name,
			'image'       => $imageName?? $sub_category->getOriginal('image'),
		])
			? redirect()->route('category.show', $request->category_id)->with('success_message', 'SubCategory successfully updated.')
			: back()->with('failure_message', 'SubCategory could not be updated. Please try again later.');
	}

	public function destroy(SubCategory $sub_category) {
		if ($sub_category->delete()) {
			return back()->with('success_message', 'SubCategory successfully deleted.');
		}

		return back()->with('failure_message', 'SubCategory could not be deleted. Please try again later.');
	}

}
