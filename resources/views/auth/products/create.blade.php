@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css').'?'.time() }}">
<div class="container">
    <h1 class="mb-4">商品新規登録</h1>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        <dl class="row mt-3">
            <div class="mb-3 row">
                <label for="product_name" class="col-sm-2 col-form-label">商品名*</label>
                <div class="col-sm-10">
                    <input id="product_name" type="text" name="product_name" class="form-control" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="company_id" class="col-sm-2 col-form-label">メーカー*</label>
                <div class="col-sm-10">
                    <select class="form-select" id="company_id" name="company_id">
                        @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="price" class="col-sm-2 col-form-label">価格*</label>
                <div class="col-sm-10">
                    <input id="price" type="text" name="price" class="form-control" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="stock" class="col-sm-2 col-form-label">在庫数*</label>
                <div class="col-sm-10">
                    <input id="stock" type="text" name="stock" class="form-control" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="comment" class="col-sm-2 col-form-label">コメント</label>
                <div class="col-sm-10">
                    <textarea id="comment" name="comment" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="img_path" class="col-sm-2 col-form-label">商品画像</label>
                <div class="col-sm-10">
                    <input id="img_path" type="file" name="img_path" class="form-control">
                </div>
            </div>

            <div class="container mt-3">
                <button type="submit" class="btn btn-primary">登録</button>
                <a href="{{ route('products.index') }}" class="btn btn-info mb-3">商品一覧に戻る</a>
            </div>
        </dl>
    </form>
</div>
@endsection

