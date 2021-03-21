@extends('admin.layouts.app')

@section('title', 'Company Information')

@section('content')

  <form role="form"
        action="{{route('company.update', $company->id)}}"
        method="post"
        enctype="multipart/form-data"
        id="form_validation">
    {{csrf_field()}}
    {{method_field('PUT')}}

    <div class="card">
      <div class="card-header card-header-text" data-background-color="green">
        <h4 class="card-title">Edit Company Information</h4>
      </div>

      <div class="card-content">

        <div class="row">
          <div class="col-md-2">
            {{--logo--}}
            @include('extras.input_image', ['input_image'=>$company->image(250,211,'logo'),'image_name_field'=>'logo'])
          </div>
          <div class="col-md-10">
            {{--name--}}
            <div class="form-group label-floating">
              <label for="name">
                Company Name
                <small>*</small>
              </label>
              <input type="text"
                     class="form-control"
                     id="name"
                     name="name"
                     required="true"
                     value="{{ $company->getOriginal('name') }}"/>
            </div>

            <div class="row">
              {{--email--}}
              <div class="col-md-6">
                <div class="form-group label-floating">
                  <label for="email">
                    Email
                    <small>*</small>
                  </label>
                  <input type="email"
                         class="form-control"
                         id="email"
                         name="email"
                         required="true"
                         email="true"
                         value="{{$company->email}}"/>
                </div>
              </div>
              {{--phone--}}
              <div class="col-md-6">
                <div class="form-group label-floating">
                  <label for="phone">
                    Phone
                  </label>
                  <input type="text"
                         class="form-control"
                         id="phone"
                         name="phone"
                         number="true"
                         value="{{$company->phone}}"/>
                </div>
              </div>
            </div>

            <div class="row">
              {{--established date--}}
              <div class="col-md-6">
                <div class="form-group">
                  <label for="established_date">
                    Established Date
                    <small>*</small>
                  </label>
                  <input type="text"
                         class="form-control datepicker"
                         id="established_date"
                         name="established_date"
                         value="{{$company->established_date->format('m/d/Y')}}"/>
                </div>
              </div>
              {{--address--}}
              <div class="col-md-6">
                <div class="form-group">
                  <label for="address">
                    Address
                  </label>
                  <input type="text"
                         class="form-control"
                         id="address"
                         name="address"
                         value="{{$company->getOriginal('address')}}"/>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                {{--facebook_url--}}
                <div class="form-group" {{ $errors->has('facebook_url')?'has-error is-focused':'' }}>
                  <label for="facebook_url">{{ ucwords('facebook url') }}</label>
                  <input type="url"
                         class="form-control"
                         id="facebook_url"
                         name="facebook_url"
                         value="{{$company->facebook_url}}"/>
                </div>
                {{--./facebook_url--}}
              </div>
              <div class="col-md-6">
                {{--twitter_url--}}
                <div class="form-group" {{ $errors->has('twitter_url')?'has-error is-focused':'' }}>
                  <label for="twitter_url">{{ ucwords('twitter url') }}</label>
                  <input type="url"
                         class="form-control"
                         id="twitter_url"
                         name="twitter_url"
                         value="{{$company->twitter_url}}"/>
                </div>
                {{--./twitter_url--}}
              </div>
            </div>

            {{--about--}}
            <div class="form-group">
              <label for="about">About</label>
              <textarea class="form-control asdh-tinymce"
                        id="about"
                        name="about"
                        rows="10">{{$company->about}}</textarea>
            </div>
          </div>
        </div>

        {{--submit--}}
        <div class="form-footer text-right">
          <button type="submit" class="btn btn-success btn-fill">Update</button>
        </div>

      </div>
    </div>
  </form>
@endsection

@push('script')
  @include('extras.tinymce')
  <script type="text/javascript">
    $(document).ready(function () {
      $('.datepicker').datetimepicker({
        format: 'MM/DD/YYYY',
        icons: {
          time: "fa fa-clock-o",
          date: "fa fa-calendar",
          up: "fa fa-chevron-up",
          down: "fa fa-chevron-down",
          previous: 'fa fa-chevron-left',
          next: 'fa fa-chevron-right',
          today: 'fa fa-screenshot',
          clear: 'fa fa-trash',
          close: 'fa fa-remove'
        }
      });
    });
  </script>
@endpush