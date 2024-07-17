<table id="product-table" class="table table-striped tablesorter">
    <thead>
        <tr>
            <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">id</a></th>
            <th>商品画像</th>
            <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'product_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">商品名</a></th>
            <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">価格</a></th>
            <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'stock', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">在庫数</a></th>
            <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'company_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">メーカー</a></th>
            <th><a href="{{ route('products.create') }}" class="btn btn-primary mb-3">商品新規登録</a></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($products as $product)
        <tr id="product-{{ $product->id }}">
            <td>{{ $product->id }}</td>
            <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->company->company_name }}</td>
            <td>
                <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細</a>
                <form method="POST" action="{{ route('products.destroy', $product) }}" class="delete-form d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm mx-1">削除</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>