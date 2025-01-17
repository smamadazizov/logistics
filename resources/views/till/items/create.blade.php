@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Добавить статью расхода/прихода</div>

                    <div class="card-body">

                        <form method="POST" action="{{ route('payment-items.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="title" class="col-md-4 col-form-label text-md-right">Наименование</label>

                                <div class="col-md-6">
                                    <input id="title" type="text"
                                           class="form-control @error('title') is-invalid @enderror" name="title"
                                           value="{{ old('title') }}" required autocomplete="title" autofocus>

                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">Описание</label>

                                <div class="col-md-6">
                                    <input id="description" type="text"
                                           class="form-control @error('description') is-invalid @enderror"
                                           name="description" value="{{ old('description') }}"
                                           autocomplete="description">

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


{{--                            <div class="form-group row">--}}
{{--                                <label for="type" class="col-md-4 col-form-label text-md-right">Тип</label>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <select id="type"--}}
{{--                                            type="text"--}}
{{--                                            class="form-control custom-select @error('type') is-invalid @enderror"--}}
{{--                                            name="type"--}}
{{--                                            value="{{ old('type') }}"--}}
{{--                                            autocomplete="type" required>--}}
{{--                                        <option disabled>--Выберите тип--</option>--}}
{{--                                        <option value="in">Доход</option>--}}
{{--                                        <option value="out">Расход</option>--}}
{{--                                    </select>--}}

{{--                                    @error('type')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Добавить') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
