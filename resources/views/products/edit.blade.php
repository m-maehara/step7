@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css').'?'.time() }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card-header"><h2>商品情報を変更する</h2></div>

                    <dl class="row mt-3" >
                        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3 row">
                                <label for="product_id" class="col-sm-2 col-form-label">商品ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="product_id" name="product_id" value="{{ $product->id }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="product_name" class="col-sm-2 col-form-label">商品名*</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="company_id" class="col-sm-2 col-form-label">メーカー*</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="company_id" name="company_id">
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="price" class="col-sm-2 col-form-label">価格*</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="stock" class="col-sm-2 col-form-label">在庫数*</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
                                </div>         
                            </div>

                            <div class="mb-3 row">
                                <label for="comment" class="col-sm-2 col-form-label">コメント</label>
                                <div class="col-sm-10">             
                                    <textarea id="comment" name="comment" class="form-control" rows="3">{{ $product->comment }}</textarea>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="img_path" class="col-sm-2 col-form-label">商品画像:</label>
                                <div class="col-sm-10">
                                    <input id="img_path" type="file" name="img_path" class="form-control">
                                    <img src="{{ asset($product->img_path) }}" alt="商品画像" class="product-image">
                                </div>
                            </div>

                            <div class="d-flex mt-3">
                                <button onclick="goBack()" class="btn btn-info mt-3">戻る</button>
                                <button type="submit" class="btn btn-primary">更新</button>
                            </div>
                        </form>
                    </dl>
                
                
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.location.href = document.referrer;
        }
    </script>
@endsection
