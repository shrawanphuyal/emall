<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
  private function get_array_from_url($url)
  {
    try {
      $ch = curl_init();
      // Disable SSL verification
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      // Will return the response, if false it print the response
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Set the url
      curl_setopt($ch, CURLOPT_URL, $url);
      // Execute
      $result = curl_exec($ch);
      // Closing
      curl_close($ch);
      $response = json_decode($result, true);

      return $response;
    } catch (\Exception $e) {
      return response()->json(['status' => false, 'code' => 400, 'message' => 'Login Session Expired.']);
    }
  }

  private function google_signup()
  {
    $url = 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . Input::get('login_token');
    try {
      $json_data = $this->get_array_from_url($url);
    } catch (\Exception $e) {
      return response()->json(['status' => false, 'code' => 400, 'message' => 'Login Session Expired.']);
    }

    $google_client_id_for_android = '357990397093-r762ttq71d4ce7j72aat5qjqgk5s7onj.apps.googleusercontent.com';
    $google_client_id_for_ios     = '567316986777-9qpe69koi9on77nbq7sep1b3cskc4km5.apps.googleusercontent.com';

    $is_verified = ($json_data['aud'] == $google_client_id_for_ios || $json_data['aud'] == $google_client_id_for_android) && ($json_data['email'] == Input::get('email'));

    return $is_verified;
  }

  private function facebook_signup()
  {
    $login_token = Input::get('login_token');

    // accessing user data
    $url_to_access_user_detail = 'https://graph.facebook.com/me?fields=email&access_token=' . $login_token;
    $json_data_user            = $this->get_array_from_url($url_to_access_user_detail);

    // accessing app data
    $url_to_access_app_id = 'https://graph.facebook.com/app?access_token=' . $login_token;
    $json_data_app        = $this->get_array_from_url($url_to_access_app_id);

    $app_id_for_android = '1255097601179316';
    $app_id_for_ios     = '271714683590439';

    $is_verified = ($json_data_app['id'] == $app_id_for_android || $json_data_app['id'] == $app_id_for_ios) && $json_data_user['email'] == Input::get('email');

    return $is_verified;
  }

  private function my_validation($validation)
  {
    $validator = Validator::make(request()->all(), $validation);
    if ($validator->fails()) {
      return ['status' => false, 'message' => $validator->errors()->all()];
    }

    return ['status' => true];
  }

  public function signup()
  {
    $validator = Validator::make(Input::all(), [
      'name'        => 'required|string|max:191',
      'email'       => 'required|email|max:191',
      'social_id'   => 'required|string',
      'from'        => 'required|string|min:6|max:8',
      'login_token' => 'required|string',
    ]);

    if ($validator->fails()):
      return response()->json(['status' => false, 'message' => $validator->errors()->all()]);
    else:
      $login_portal = Input::get('from');
      // if from google (1)
      if ($login_portal == 'google') {
        $is_verified = $this->google_signup();

        // if from facebook (0)
      } else if ($login_portal == 'facebook') {
        $is_verified = $this->facebook_signup();

      } else {
        return response()->json(['status' => false, 'message' => 'Login from other than Google or Facebook is not allowed.']);
      }

      if ($is_verified) {
        $old_user = User::where('email', Input::get('email'))->first();
        if (!is_null($old_user)) {
          $user = $old_user;
        } else {
          $user = new User();
        }
        $user->name       = Input::get('name');
        $user->email      = Input::get('email');
        $user->social_id  = Input::get('social_id');
        $user->password   = bcrypt(str_random(10));
        $user->auth_token = str_random(250);
        $user->verified   = 1;

        if ($user->save()):
          return response()->json(["status" => true, "profile" => $user]);
        else:
          return response()->json(["status" => false, "message" => 'Signup/Login failed. Please try again later.']);
        endif;

      } else {
        return response()->json(["status" => false, "message" => 'Client id or App id did not match.']);
      }
    endif;
  }

  public function profile(User $user)
  {
    return new UserResourceCollection(User::paginate(1));
  }

  public function test(Request $request)
  {
    return "hello world";
    return $this->ipAddress($request);
  }

  public function testPost(Request $request) {
    // return response()->json($request->all());
    $test = new \App\Test();
    $test->name = $request->name;
    $test->save();

    return response()->json($test);
  }

  private function ipAddress(Request $request)
  {
    return $request->header('AP-Remote-Addr');
  }

  private function countryShortCode(Request $request)
  {
    return $request->header('AP-IPCountry');
  }
}
