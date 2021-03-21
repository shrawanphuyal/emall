<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ProductRequest;
use App\Product;
use App\SubCategory;
use Illuminate\Http\Request;

class ProductController extends AsdhController {
	private $prefix = 'product';

	public function __construct() {
		parent::__construct();
		$this->website['routeType'] = 'product';
	}

	public function index() {
		if (auth()->user()->hasRole('admin')) {
			$this->website['products'] = Product::with('category')->latest()->paginate($this->default_pagination_limit);
		}

		if (auth()->user()->hasRole('vendor')) {
			$this->website['products'] = auth()->user()->products()->with('category')->latest()->paginate($this->default_pagination_limit);
		}

		return view('admin.product.index', $this->website);
	}

	public function create() {
		$this->website['edit']           = false;
		$this->website['categories']     = $categories = Category::orderBy('name')->get();
		$this->website['sub_categories'] = $categories->first()->sub_categories;

		return view('admin.product.create', $this->website);
	}

	public function store(ProductRequest $request) {
		$image_name = null;
		if ( ! is_null($request->image)) {
			$image_name = upload_image_modified($request->image, $this->prefix);
		}

		/** @var Product $product */
		$product = Product::create([
			'category_id'         => $request->category_id,
			'sub_category_id'     => $request->sub_category_id,
			'sub_sub_category_id' => $request->sub_sub_category_id,
			'vendor_id'           => auth()->id(),
			'title'               => $request->title,
			'slug'                => asdh_str_slug($request->title),
			'image'               => $image_name,
			'quantity'            => $request->quantity,
			'discount'            => $request->discount,
			'discount_type'       => $request->discount_type, // 0 => percentage, 1 => amount
			'price'               => $request->price,
			'description'         => $request->description,
			'specification'       => $request->specification,
			'review'              => $request->review,
			'gender'              => $request->gender,
			'sale'                => $request->sale ? 1 : 0,
		]);

		if ($request->images & is_array($request->images)) {
			$uploadImages = [];
			foreach ($request->images as $image) {
				$imageName      = upload_image_modified($image, $this->prefix);
				$uploadImages[] = ['image' => $imageName];
			}

			$product->images()->createMany($uploadImages);
		}

		return back()->with('success_message', 'Product successfully added.');
	}

	public function edit(Product $product) {
		if ( ! $product->is_of_logged_in_vendor()) {
			return back()->with('failure_message', 'Access Denied');
		}

		$this->website['edit']           = true;
		$this->website['product']        = $product;
		$this->website['categories']     = Category::orderBy('name')->get();
		$this->website['sub_categories'] = SubCategory::orderBy('name')->get();

		return view('admin.product.create', $this->website);
	}

	public function update(ProductRequest $request, Product $product) {
		if ( ! $product->is_of_logged_in_vendor()) {
			return back()->with('failure_message', 'Access Denied');
		}

		$image_name = $product->getOriginal('image');
		if ( ! is_null($request->image)) {
			$product->delete_image();
			$image_name = upload_image_modified($request->image, $this->prefix);
		}

		$product->update([
			'category_id'         => $request->category_id,
			'sub_category_id'     => $request->sub_category_id,
			'sub_sub_category_id' => $request->sub_sub_category_id,
			'title'               => $request->title,
			'image'               => $image_name,
			'quantity'            => $request->quantity,
			'discount'            => $request->discount,
			'discount_type'       => $request->discount_type,
			'price'               => $request->price,
			'description'         => $request->description,
			'specification'       => $request->specification,
			'review'              => $request->review,
			'gender'              => $request->gender,
			'sale'                => $request->sale ? 1 : 0,
		]);

		if ($request->images & is_array($request->images)) {
			$uploadImages = [];
			foreach ($request->images as $image) {
				$imageName      = upload_image_modified($image, $this->prefix);
				$uploadImages[] = ['image' => $imageName];
			}

			$product->images()->createMany($uploadImages);
		}

		return back()->with('success_message', 'Product successfully updated.');
	}

	public function destroy(Product $product) {
		if ( ! $product->is_of_logged_in_vendor()) {
			return back()->with('failure_message', 'Access Denied');
		}

		try {
			$product->delete_image();

			foreach ($product->images as $image) {
				$image->delete_image();
			}
			$product->images()->delete();

			$product->delete();

			return back()->with('success_message', 'Product successfully deleted.');
		} catch (\Exception $exception) {
			return back()->with('failure_message', 'Product could not be deleted. Please try again later.');
		}

		/*if ($product->delete()) {
			$product->delete_image();

			return back()->with('success_message', 'Product successfully deleted.');
		}

		return back()->with('failure_message', 'Product could not be deleted. Please try again later.');*/
	}

	public function assign_discount_percentage(Request $request) {
		$request->validate([
			'product_ids'         => 'required|array',
			'product_ids.*'       => 'required|integer|min:1',
			'discount_percentage' => 'required|integer|min:0|max:100',
		], [
			'product_ids.required' => 'You must select at-least one product.',
		]);

		Product::whereIn('id', $request->product_ids)
		       ->update(['discount' => $request->discount_percentage, 'discount_type' => 0]);

		return back()->with(['success_message' => 'Discount percentage successfully added.']);
	}

	public function make_featured(Product $product) {
		$product->featured = $product->featured ? 0 : 1;
		$product->save();

		return response()->json(['message' => '<b>' . $product->title . '</b>' . ($product->featured ? ' marked as featured' : ' removed from featured')]);
	}

	public function make_hot(Product $product) {
		$product->hot = $product->hot ? 0 : 1;
		$product->save();

		return response()->json(['message' => '<b>' . $product->title . '</b>' . ($product->hot ? ' marked as hot' : ' removed from hot')]);
	}
}
