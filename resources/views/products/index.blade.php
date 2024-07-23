@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css').'?'.time() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.default.min.css">

<div class="container">
    <h1 class="mb-4">商品情報一覧</h1>

    <div class="search mt-5">
        <form id="search-form" class="row g-3">
            <div class="col-sm-12 col-md-3">
                <label for="search" class="form-label"></label>
                <input type="text" id="search" name="search" class="form-control" placeholder="商品名" value="{{ request('search') }}">
            </div>
            <div class="col-sm-12 col-md-3">
                <label for="manufacturer" class="form-label"></label>
                <select id="manufacturer" name="manufacturer" class="form-control">
                    <option value="">メーカーを選択してください</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('manufacturer') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-md-2">
                <label for="price_min" class="form-label"></label>
                <input type="number" id="price_min" name="price_min" class="form-control" placeholder="価格下限" value="{{ request('price_min') }}">
            </div>
            <div class="col-sm-12 col-md-2">
                <label for="price_max" class="form-label"></label>
                <input type="number" id="price_max" name="price_max" class="form-control" placeholder="価格上限" value="{{ request('price_max') }}">
            </div>
            <div class="col-sm-12 col-md-2">
                <label for="stock_min" class="form-label"></label>
                <input type="number" id="stock_min" name="stock_min" class="form-control" placeholder="在庫数下限" value="{{ request('stock_min') }}">
            </div>
            <div class="col-sm-12 col-md-2">
                <label for="stock_max" class="form-label"></label>
                <input type="number" id="stock_max" name="stock_max" class="form-control" placeholder="在庫数上限" value="{{ request('stock_max') }}">
            </div>
            <div class="col-sm-12 col-md-1 align-self-end">
                <button id="search-btn" class="btn btn-outline-secondary" type="submit">検索</button>
            </div>
        </form>
    </div>

    <div class="products mt-5">
        <div id="product-list">
            @include('products.partials.product_list', ['products' => $products])
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script>
$(document).ready(function() {
    $('#product-table').tablesorter({
        sortList: [[0, 1]] // 初期表示時はID降順
    });

    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#search-btn').attr('disabled', true);
        var query = $(this).serialize();

        $.ajax({
            url: '{{ route('products.search') }}',
            type: 'GET',
            data: query,
            success: function(data) {
                $('#product-list').html(data);
                $('#product-table').tablesorter({
                    sortList: [[0, 1]]
                });
                $btn.attr('disabled', false);
            },
            error: function() {
                alert('検索に失敗しました。もう一度お試しください。');
                $btn.attr('disabled', false);
            }
        });
    });

    // 非同期削除処理
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();

        var form = $(this);
        var productId = form.closest('tr').attr('id').split('-')[1];

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#product-' + productId).fadeOut(function() {
                        $(this).remove();
                    });
                } else {
                    alert('削除に失敗しました。');
                }
            },
            error: function() {
                alert('削除に失敗しました。');
            }
        });
    });
});
</script>
@endsection