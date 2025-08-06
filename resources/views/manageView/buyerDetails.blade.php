@extends('layouts.AdminApp')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h3 class="mb-0">購入者詳細情報</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="bg-light" style="width: 30%;">ユーザーID</th>
                        <td>{{ $buyer->id }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">氏名</th>
                        <td>{{ $buyer->name ?? '未設定' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">メールアドレス</th>
                        <td>{{ $buyer->email }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">郵便番号</th>
                        <td>{{ $buyer->postal_code ?? '未設定' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">住所</th>
                        <td>{{ $buyer->address ?? '未設定' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">電話番号</th>
                        <td>{{ $buyer->phone ?? '未設定' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">登録日時</th>
                        <td>{{ $buyer->created_at->format('Y年m月d日 H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">メール認証状態</th>
                        <td>
                            @if($buyer->email_verified_at)
                                <span class="badge bg-success">認証済み</span>
                                <small class="text-muted">({{ $buyer->email_verified_at->format('Y年m月d日 H:i') }})</small>
                            @else
                                <span class="badge bg-warning">未認証</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="d-flex gap-2">
                <a href="{{ route('order.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> 注文一覧に戻る
                </a>
                <a href="{{ route('order.shipped') }}" class="btn btn-outline-secondary">
                    発送済み一覧
                </a>
            </div>
        </div>
    </div>
</div>
@endsection