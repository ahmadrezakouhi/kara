@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card border mt-5">
                <div class="card-header text-center" style="font-size: 20px">{{ __('ورود') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- <div class="row mb-3"> --}}
                            <label for="mobile" class="col-form-label text-md-end">{{ __('موبایل') }}</label>

                            {{-- <div class="col-md-6"> --}}
                                <input id="mobile" type="mobile" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile" autofocus
                                dir="ltr">

                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <small>{{ $message }}</small>
                                    </span>
                                @enderror
                            {{-- </div> --}}
                        {{-- </div> --}}

                        {{-- <div class="row mb-3"> --}}
                            <label for="password" class=" col-form-label text-md-end">{{ __('رمز ورود') }}</label>

                            {{-- <div class="col-md-6"> --}}
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"
                                dir="ltr">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            {{-- </div> --}}
                        {{-- </div> --}}

                        <div class="row mb-3">
                            <div class="col-md-6 mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember" style="">
                                        {{ __('مرا بخاطر بسپار!') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row mb-0"> --}}
                            {{-- <div class="col-md-8 offset-md-4"> --}}
                                <div class="d-grid gap-2">

                                <button type="submit" class="btn btn-primary" style="font-size: 18px">
                                    {{ __('ورود') }}
                                </button>
                                </div>
                                {{-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('آیا رمز خود را فراموش کردید؟') }}
                                    </a>
                                @endif --}}
                            {{-- </div> --}}
                        {{-- </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
