@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Добавить машину</div>
                    <div class="card-body">
                        <form id="addCar" method="POST" action="{{route('car.store')}}">
                            @csrf

                            <div class="form-group row">
                                <label for="number" class="col-md-4 col-form-label text-md-right">Номер машины</label>
                                <div class="col-md-6">
                                    <input id="number" placeholder="гос. номер машины" type="text"
                                           class="form-control @error('number') is-invalid @enderror"
                                           name="number" value="{{ old('number') }}" required
                                           autocomplete="number" autofocus>

                                    @error('number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="trailerNumber" class="col-md-4 col-form-label text-md-right">Номер прицепа</label>
                                <div class="col-md-6">
                                    <input id="trailerNumber" placeholder="гос. номер прицепа" type="text"
                                           class="form-control @error('trailerNumber') is-invalid @enderror"
                                           name="trailerNumber" value="{{ old('trailerNumber') }}" required
                                           autocomplete="trailerNumber" autofocus>

                                    @error('trailerNumber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="length" class="col-md-4 col-form-label text-md-right">Длина</label>
                                <div class="col-md-6">
                                    <input id="length" placeholder="в метрах" type="text"
                                           class="form-control @error('length') is-invalid @enderror"
                                           name="length" value="{{ old('length') }}" required
                                           autocomplete="length" autofocus>

                                    @error('length')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="width" class="col-md-4 col-form-label text-md-right">Ширина</label>
                                <div class="col-md-6">
                                    <input id="width" placeholder="в метрах" type="text"
                                           class="form-control @error('width') is-invalid @enderror"
                                           name="width" value="{{ old('width') }}" required
                                           autocomplete="width" autofocus>

                                    @error('width')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="height" class="col-md-4 col-form-label text-md-right">Высота</label>
                                <div class="col-md-6">
                                    <input id="height" placeholder="в метрах" type="text"
                                           class="form-control @error('height') is-invalid @enderror"
                                           name="height" value="{{ old('height') }}" required
                                           autocomplete="height" autofocus>

                                    @error('height')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="serial" class="col-md-4 col-form-label text-md-right">Серийный номер</label>
                                <div class="col-md-6">
                                    <input id="serial" placeholder="VIN - идентификационный номер" type="text"
                                           class="form-control @error('serial') is-invalid @enderror"
                                           name="serial" value="{{ old('serial') }}" required
                                           autocomplete="serial" autofocus>

                                    @error('serial')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="maxWeight" class="col-md-4 col-form-label text-md-right">Грузоподъемность</label>
                                <div class="col-md-6">
                                    <input id="maxWeight" placeholder="в килограммах" type="text"
                                           class="form-control @error('maxWeight') is-invalid @enderror"
                                           name="maxWeight" value="{{ old('maxWeight') }}" required
                                           autocomplete="maxWeight" autofocus>
                                    <small id="maxWeightHelp" class="form-text text-muted">При наличии прицепа, необходимо ввести сумарную грузоподъемность</small>

                                    @error('maxWeight')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="maxCubage" class="col-md-4 col-form-label text-md-right">Объем кузова</label>
                                <div class="col-md-6">
                                    <input id="maxCubage" placeholder="в кубах" type="text"
                                           class="form-control @error('maxCubage') is-invalid @enderror"
                                           name="maxCubage" value="{{ old('maxCubage') }}" required
                                           autocomplete="maxCubage" autofocus>
                                    <small id="maxCubageHelp" class="form-text text-muted">При наличии прицепа, необходимо ввести сумарный объем</small>

                                    @error('maxCubage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Сохранить') }}
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