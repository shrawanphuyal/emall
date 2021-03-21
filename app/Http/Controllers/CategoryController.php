<?php

namespace App\Http\Controllers;

use App\Category;
use App\Custom\FileUpload;
use App\Product;
use Illuminate\Http\Request;

class CategoryController extends AsdhController {
	private $prefix = 'category';

	public function __construct() {
		parent::__construct();
		$this->website['routeType'] = 'category';
	}

	public function index() {
		$this->website['categories'] = Category::orderBy('priority')->latest()->paginate($this->default_pagination_limit);

		return view('admin.category.index', $this->website);
	}

	public function show(Category $category) {
		$this->website['routeType']      = 'sub-category';
		$this->website['category']       = $category;
		$this->website['sub_categories'] = $category->sub_categories;

		return view('admin.category.show', $this->website);
	}

	public function create() {
		$this->website['edit'] = false;

		return view('admin.category.create', $this->website);
	}

	public function store(Request $request, FileUpload $fileUpload) {
		$validData = $request->validate(['name.*' => 'required|string|max:255']);

		$presentCategories = [];
		foreach ($validData['name'] as $key => $validateDatum) {
			$category            = Category::whereName($validateDatum)->first();
			$presentCategories[] = $category;

			if ( ! $category) {
				Category::create([
					'name'  => $validateDatum,
					'slug'  => asdh_str_slug($validateDatum),
					'image' => $fileUpload->setFile($request->image[$key] ?? null)->setPrefix($this->prefix)->handle(),
				]);
			}
		}

		$filteredCategories = array_pluck(array_filter($presentCategories), 'name');
		if (count($filteredCategories) > 0) {
			return back()->with('success_message', '<b>' . implode(', ', $filteredCategories) . '</b> categories were present before. So, they werent added.');
		}

		return redirect()->route($this->website['routeType'] . '.index')->with('success_message', 'Category successfully added.');
	}

	public function edit(Category $category) {
		$this->website['edit']     = true;
		$this->website['category'] = $category;

		return view('admin.category.create', $this->website);
	}

	public function update(Request $request, Category $category, FileUpload $fileUpload) {
		$validData = $request->validate(['name' => 'required|string|max:255']);

		$image = $request->image;
		if ($image) {
			$category->delete_image();
			$imageName = $fileUpload->setFile($image)->setPrefix($this->prefix)->handle();
		}

		return $category->update([
			'name'  => $validData['name'],
			'slug'  => asdh_str_slug($validData['name']),
			'image' => $imageName ?? $category->getOriginal('image'),
		])
			? back()->with('success_message', 'Category successfully updated.')
			: back()->with('failure_message', 'Category could not be updated. Please try again later.');
	}

	public function destroy(Category $category) {
		if ($category->delete()) {
			return back()->with('success_message', 'Category successfully deleted.');
		}

		return back()->with('failure_message', 'Category could not be deleted. Please try again later.');
	}

	public function show_or_hide_sub_category() {
		// when book is in edit mode, $book_id will have a value else it will have null
		$product_id = request()->product_id;
		$category   = Category::find(request()->category_id);
		$data       = '';

		if ($category->has_sub_categories()) {

			$data .= '<div class="form-group">';
			$data .= '<label class="control-label" for="sub_category_id">Sub-Category</label>';
			$data .= '<select name="sub_category_id" id="sub_category_id" class="selectpicker" data-style="select-with-transition" data-size="5" data-live-search="true">';
			$data .= '<option value="">Choose Sub-Category</option>';
			if ( ! is_null($product_id)) {
				$product = Product::find($product_id);
				foreach ($category->sub_categories as $sub_category) {
					$hasChildren = $sub_category->has_children() ? ' +' : '';
					$data        .= '<option value="' . $sub_category->id . '"';
					if ($sub_category->id == $product->sub_category_id) {
						$data .= ' selected';
						$data .= '>' . $sub_category->name . $hasChildren . '</option>';
					} else {
						$data .= '>' . $sub_category->name . $hasChildren . '</option>';
					}
				}
			} else {
				foreach ($category->sub_categories as $sub_category) {
					$hasChildren = $sub_category->has_children() ? ' +' : '';
					$data        .= '<option value="' . $sub_category->id . '">' . $sub_category->name . $hasChildren . '</option>';
				}
			}
			$data .= '</select>';
			$data .= '<div class="material-icons select-drop-down-arrow">keyboard_arrow_down</div>';
			$data .= '</div>';

			return response()->json(['status' => true, 'data' => $data]);
		}

		return response()->json(['status' => false]);
	}

	public function show_on_menu(Category $category) {
		$category->show_on_menu = $category->show_on_menu ? 0 : 1;
		$category->save();

		return response()->json(['message' => $category->name . ($category->show_on_menu ? ' shown in menu' : ' removed from menu')]);
	}

	public function make_exclusive(Category $category) {
		$category->exclusive = $category->exclusive ? 0 : 1;
		$category->save();

		return response()->json(['message' => $category->name . ($category->exclusive ? ' made exclusive' : ' removed from exclusive')]);
	}

	public function set_priority(Category $category) {
		$priority = \request()->priority;

		$category->priority = $priority;
		$category->save();

		return response()->json(['message' => $category->name . ' priority changed to ' . $priority]);
	}
}
