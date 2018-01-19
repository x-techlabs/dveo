<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <title>Signup ― Dveo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />
</head>
<body>
<header id = "signup_header">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1">
                <div class="col-md-10 col-xs-9 col-sm-10">
                    <h1 class="page_title">
                        launch more ott & tv everywhere apps
                    </h1>
                </div>
                <div class="col-md-2 col-xs-3 col-sm-2" id = "logo_wrapper">
                    <div class="loginText" id="stud_logo">
                        1studi
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="18" viewBox="0 0 24 18">
                            <g fill="#ff0000" fill-rule="evenodd">
                                <ellipse cx="12" cy="8.705" rx="3" ry="3"></ellipse>
                                <path id="on-air-out" d="M3.51471863.219669914C-1.17157288 4.90596141-1.17157288 12.5039412 3.51471863 17.1902327 3.80761184 17.4831259 4.28248558 17.4831259 4.5753788 17.1902327 4.86827202 16.8973394 4.86827202 16.4224657 4.5753788 16.1295725.474873734 12.0290674.474873734 5.38083515 4.5753788 1.28033009 4.86827202.987436867 4.86827202.512563133 4.5753788.219669914 4.28248558-.0732233047 3.80761184-.0732233047 3.51471863.219669914zM20.4852814 17.1902327C25.1715729 12.5039412 25.1715729 4.90596141 20.4852814.219669914 20.1923882-.0732233047 19.7175144-.0732233047 19.4246212.219669914 19.131728.512563133 19.131728.987436867 19.4246212 1.28033009 23.5251263 5.38083515 23.5251263 12.0290674 19.4246212 16.1295725 19.131728 16.4224657 19.131728 16.8973394 19.4246212 17.1902327 19.7175144 17.4831259 20.1923882 17.4831259 20.4852814 17.1902327z"></path>
                                <path id="on-air-in" d="M17.3033009 14.0082521C18.7217837 12.5897693 19.4928584 10.6983839 19.4999509 8.73215792 19.507111 6.74721082 18.7352286 4.8335782 17.3033009 3.40165043 17.0104076 3.10875721 16.5355339 3.10875721 16.2426407 3.40165043 15.9497475 3.69454365 15.9497475 4.16941738 16.2426407 4.4623106 17.3890249 5.6086948 18.0056933 7.13752465 17.9999607 8.72674718 17.9942823 10.30094 17.3782748 11.8119579 16.2426407 12.947592 15.9497475 13.2404852 15.9497475 13.7153589 16.2426407 14.0082521 16.5355339 14.3011454 17.0104076 14.3011454 17.3033009 14.0082521zM6.69669914 3.40165043C3.76776695 6.33058262 3.76776695 11.07932 6.69669914 14.0082521 6.98959236 14.3011454 7.46446609 14.3011454 7.75735931 14.0082521 8.05025253 13.7153589 8.05025253 13.2404852 7.75735931 12.947592 5.41421356 10.6044462 5.41421356 6.80545635 7.75735931 4.4623106 8.05025253 4.16941738 8.05025253 3.69454365 7.75735931 3.40165043 7.46446609 3.10875721 6.98959236 3.10875721 6.69669914 3.40165043z"></path>
                            </g>
                        </svg>
                        <p class="go_text">go</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="bottom_text">
        <h2>custom development, design, & app management by Dveo</h2>
    </div>
</header>
<div id = "logos_wrapper">
    <img src="/images/signup/logos.png" alt="">
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-offset-1 col-xs-10" id = "signup_content">
            <form action = "/signup_user" method = "post">
                <input type="hidden" name = "token" value = "{{ csrf_token() }}">
                <div class="form-group">
                    <label for="email">
                        <span class="step_number">1 <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                        <span class="question">
                                     What is your company  Email address?
                                </span>
                    </label>
                    <input type="text" class="form-control" id="company_email" {{ (($errors->any() && !empty(Input::old('email'))) || (!empty($userdata['email']) && isset($userdata['email'])) ) ? 'readonly' : '' }} name = "email" value = "{{ (!empty($userdata['email']) && isset($userdata['email'])) ? $userdata['email'] : Input::old('email') }}">
                    @if($errors->first('email'))
                        <div class="text-danger">{{ $errors->first('email') }}</div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">
                        <span class="step_number">2 <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                        <span class="question">What kind of company are you?</span>
                    </label>
                    <div class="options">
                        <input type="radio" name = "company_type" class="" value = "1" {{ ($errors->any() && !empty(Input::old('company_type'))) ? 'disabled' : '' }} {{ (null !== Input::old('company_type') && Input::old('company_type') == '1') ? 'checked' : '' }}> Video Production? <br>
                        <input type="radio" name = "company_type" class="" value = "2" {{ ($errors->any() && !empty(Input::old('company_type'))) ? 'disabled' : '' }} {{ (null !== Input::old('company_type') && Input::old('company_type') == '2') ? 'checked' : '' }}> Creative Agency? <br>
                        <input type="radio" name = "company_type" class="" value = "5" {{ ($errors->any() && !empty(Input::old('company_type'))) ? 'disabled' : '' }} {{ (null !== Input::old('company_type') && Input::old('company_type') == '5') ? 'checked' : '' }}> Talent Agency? <br>
                        <input type="radio" name = "company_type" class="" value = "3" {{ ($errors->any() && !empty(Input::old('company_type'))) ? 'disabled' : '' }} {{ (null !== Input::old('company_type') && Input::old('company_type') == '3') ? 'checked' : '' }}> Custom media? <br>
                        <input type="radio" name = "company_type" class="" value = "6" {{ ($errors->any() && !empty(Input::old('company_type'))) ? 'disabled' : '' }} {{ (null !== Input::old('company_type') && Input::old('company_type') == '6') ? 'checked' : '' }}> Sports Network? <br>
                        <input type="radio" name = "company_type" class="" value = "7" {{ ($errors->any() && !empty(Input::old('company_type'))) ? 'disabled' : '' }} {{ (null !== Input::old('company_type') && Input::old('company_type') == '7') ? 'checked' : '' }}> Internet Broadcasting? <br>
                        <input type="radio" name = "company_type" class="" value = "8" {{ ($errors->any() && !empty(Input::old('company_type'))) ? 'disabled' : '' }} {{ (null !== Input::old('company_type') && Input::old('company_type') == '8') ? 'checked' : '' }}> Startup Channel? <br>
                        <input type="radio" name = "company_type" class="" value = "4" {{ ($errors->any() && !empty(Input::old('company_type'))) ? 'disabled' : '' }} {{ (null !== Input::old('company_type') && Input::old('company_type') == '4') ? 'checked' : '' }}> None of these apply
                    </div>
                    @if($errors->first('company_type'))
                        <div class="text-danger">{{ $errors->first('company_type') }}</div>
                    @endif
                </div>
                <div class="step">
                    <p>
                        <span class="step_number">3 <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                        <span class="question">Who is the Admin App Manager?</span>
                    </p>
                </div>
                <div class="step_content">
                    <div class="form-group">
                        <label for="first_name"><span class="question">First Name?</span></label>
                        <input type="text" class="form-control" id="first_name" name = "first_name" {{ (($errors->any() && !empty(Input::old('first_name'))) || (!empty($userdata['given_name']) && isset($userdata['given_name'])) ) ? 'readonly' : '' }} value = "{{ (!empty($userdata['given_name']) && isset($userdata['given_name'])) ? $userdata['given_name'] : Input::old('first_name') }}">
                        @if($errors->first('first_name'))
                            <div class="text-danger">{{ $errors->first('first_name') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="last_name"><span class="question">Last Name?</span></label>
                        <input type="text" class="form-control" id="last_name" name = "last_name" {{ (($errors->any() && !empty(Input::old('last_name'))) || (!empty($userdata['family_name']) && isset($userdata['family_name']))) ? 'readonly' : '' }} value = "{{ (!empty($userdata['family_name']) && isset($userdata['family_name'])) ? $userdata['family_name'] : Input::old('last_name') }}">
                        @if($errors->first('last_name'))
                            <div class="text-danger">{{ $errors->first('last_name') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="company"><span class="question">Company?</span></label>
                        <input type="text" class="form-control" id="company" name = "company" {{ ($errors->any() && !empty(Input::old('company'))) ? 'disabled' : '' }} value = "{{ Input::old('company') }}">
                        @if($errors->first('company'))
                            <div class="text-danger">{{ $errors->first('company') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="channel_name"><span class="question">Channel Name?</span></label>
                        <input type="text" class="form-control" id="channel_name" name = "channel_name" {{ ($errors->any() && !empty(Input::old('channel_name'))) ? 'disabled' : '' }} value = "{{ Input::old('channel_name') }}">
                        @if($errors->first('channel_name'))
                            <div class="text-danger">{{ $errors->first('channel_name') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="phone"><span class="question">Phone?</span></label>
                        <input type="text" class="form-control" id="phone" name = "phone" {{ ($errors->any() && !empty(Input::old('phone'))) ? 'disabled' : '' }} value = "{{ Input::old('phone') }}">
                        @if($errors->first('phone'))
                            <div class="text-danger">{{ $errors->first('phone') }}</div>
                        @endif
                    </div>
                </div>
                <div class="step">
                    <p>
                        <span class="step_number">4 <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                        <span class="question">Login details</span>
                    </p>
                </div>
                <div class="step_content">
                    <div class="form-group">
                        <label for="username"><span class="question">What is your username?</span></label>
                        <input type="text" class="form-control" id="username" name = "username" {{ ($errors->any() && !empty(Input::old('username'))) ? 'disabled' : '' }} value = "{{ Input::old('username') }}">
                        @if($errors->first('username'))
                            <div class="text-danger">{{ $errors->first('username') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password"><span class="question">What is your password?</span></label>
                        <input type="password" class="form-control" id="password" name = "password">
                        @if($errors->first('password'))
                            <div class="text-danger">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                </div>
                <div class="step">
                    <p>
                        <span class="step_number">5 <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                        <span class="question">What Plan do you want?</span>
                    </p>
                </div>
                <div class="step_content">
                    <div class="form-group">
                        <label for="">All plans come with a 14 day free trial</label>
                        <div class="options">
                            <div class="option_item">
                                <input type="radio" name = "plan" class="" value = "standard-le-x-2" {{ ($errors->any() && !empty(Input::old('plan'))) ? 'disabled' : '' }} {{ (null !== Input::old('plan') && Input::old('plan') == 'standard-le-x-2') ? 'checked' : '' }}> Small
                            </div>
                            <div class="option_item">
                                <input type="radio" name = "plan" class="" value = "pro-lx" {{ ($errors->any() && !empty(Input::old('plan'))) ? 'disabled' : '' }} {{ (null !== Input::old('plan') && Input::old('plan') == 'pro-lx') ? 'checked' : '' }}> Medium
                            </div>
                            <div class="option_item">
                                <input type="radio" name = "plan" class="" value = "network-xs" {{ ($errors->any() && !empty(Input::old('plan'))) ? 'disabled' : '' }} {{ (null !== Input::old('plan') && Input::old('plan') == 'network-xs') ? 'checked' : '' }}> Large
                            </div>
                            <div class="option_item">
                                <input type="radio" name = "plan" class="" value="standard-le" {{ ($errors->any() && !empty(Input::old('plan'))) ? 'disabled' : '' }} {{ (null !== Input::old('plan') && Input::old('plan') == 'standard-le') ? 'checked' : '' }}> Grow
                            </div>
                        </div>
                        @if($errors->first('plan'))
                            <div class="text-danger">{{ $errors->first('plan') }}</div>
                        @endif
                    </div>
                </div>
                <button type="submit" id = "signup" class="btn btn-default">Signup</button>
            </form>
        </div>
    </div>
</div>
<footer id = "signup_footer">
</footer>
<script src = "/js/jquery-2.1.1.min.js"></script>
<script src = "/js/bootstrap.min.js"></script>
<script src = "/js/signup.js"></script>
</body>
</html>