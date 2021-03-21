<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\CompanyRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CompanyController extends AsdhController {
	protected $image_prefix = 'company';

	public function edit($id) {
		$this->website['company'] = Company::findOrFail($id);

		return view('admin.company.edit', $this->website);
	}

	public function update(CompanyRequest $request, $id) {
		/** @var Company $company */
		$company                   = Company::findOrFail($id);
		$established_date          = new Carbon($request->established_date);
		$old_name                  = $company->name;
		$company->name             = $request->name;
		$company->email            = $request->email;
		$company->phone            = $request->phone;
		$company->established_date = $established_date->toDateTimeString();
		$company->address          = $request->address;
		$company->about            = $request->about;
		$company->facebook_url     = $request->facebook_url;
		$company->twitter_url      = $request->twitter_url;

		if ( ! is_null($request->logo)) {
			$company->delete_image('logo');
			$company->logo = upload_image_modified($request->logo, $this->image_prefix);
		}

		if ($company->save()) {
			return $request->ajax()
				? response()->json(['result' => 'Successfully updated: ' . $old_name . ' to ' . $company->name], 200)
				: redirect()->back()->with('success_message', 'Successfully updated');
		} else {
			return $request->ajax()
				? response()->json(['result' => 'Sorry, could not update. Please try again later'], 200)
				: redirect()->back()->with('failure_message', 'Sorry, company information could not be updated. Please try again later!');
		}
	}

}
