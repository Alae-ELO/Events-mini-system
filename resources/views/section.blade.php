@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- Cookie Section --}}
    <div class="card mb-5 shadow-sm">
        <div class="card-header bg-pink-200 text-center">
            <h3 class="mb-0">Cookie Greeting</h3>
        </div>
        <div class="card-body d-flex flex-column align-items-center gap-3">

            <h5>
                Hello 
                @if(Cookie::has("OrganizerName"))
                    <span class="text-primary">{{ Cookie::get("OrganizerName") }}</span>
                @endif
            </h5>

            <form method="POST" action="{{ url('saveCookie') }}" class="w-100" style="max-width: 500px;">
                @csrf
                <div class="mb-3">
                    <label for="txtCookie" class="form-label">{{ __('Type your name') }}</label>
                    <input type="text" id="txtCookie" name="txtCookie" class="form-control" required>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn bg-pink text-white fw-bold px-4">
                        {{ __('Save Cookie') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Session Section --}}
    <div class="card shadow-sm">
        <div class="card-header bg-pink-200 text-center">
            <h3 class="mb-0">Session Greeting</h3>
        </div>
        <div class="card-body d-flex flex-column align-items-center gap-3">

            <h5>
                Hello 
                @if(Session::has("SessionName"))
                    <span class="text-success">{{ Session("SessionName") }}</span>
                @endif
            </h5>

            <form method="POST" action="{{ url('saveSession') }}" class="w-100" style="max-width: 500px;">
                @csrf
                <div class="mb-3">
                    <label for="txtSession" class="form-label">{{ __('Type your name') }}</label>
                    <input type="text" id="txtSession" name="txtSession" class="form-control" required>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn bg-pink text-white fw-bold px-4">
                        {{ __('Save Session') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
