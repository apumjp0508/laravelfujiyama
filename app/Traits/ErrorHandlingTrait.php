<?php

namespace App\Traits;

trait ErrorHandlingTrait
{
    /**
     * Service層でのエラーハンドリング
     */
    protected function handleServiceError(\Exception $e, string $operation, array $context = [])
    {
        $logData = array_merge([
            'error' => $e->getMessage(),
            'operation' => $operation,
            'method' => __METHOD__,
            'line' => __LINE__
        ], $context);

        \Log::error('[500] ' . $operation . ' failed - Service Error', $logData);
        
        throw new \Exception($this->getUserFriendlyMessage($operation), 500);
    }

    /**
     * Controller層でのエラーハンドリング
     */
    protected function handleControllerError(\Exception $e, string $operation, array $context = [])
    {
        $logData = array_merge([
            'error' => $e->getMessage(),
            'operation' => $operation,
            'method' => __METHOD__,
            'line' => __LINE__
        ], $context);

        \Log::error('[500] ' . $operation . ' failed - Controller Error', $logData);
        
        return $this->createErrorResponse($operation);
    }

    /**
     * Stripe API エラーの特別処理
     */
    protected function handleStripeError(\Stripe\Exception\ApiErrorException $e, string $operation, array $context = [])
    {
        $logData = array_merge([
            'error' => $e->getMessage(),
            'stripe_error_code' => $e->getStripeCode(),
            'operation' => $operation,
            'method' => __METHOD__,
            'line' => __LINE__
        ], $context);

        \Log::error('[500] ' . $operation . ' failed - Stripe API Error', $logData);
        
        throw new \Exception('決済セッションの作成に失敗しました。決済システムエラーが発生しました。', 500);
    }

    /**
     * データベースエラーの特別処理
     */
    protected function handleDatabaseError(\Illuminate\Database\QueryException $e, string $operation, array $context = [])
    {
        $logData = array_merge([
            'error' => $e->getMessage(),
            'sql' => $e->getSql(),
            'bindings' => $e->getBindings(),
            'operation' => $operation,
            'method' => __METHOD__,
            'line' => __LINE__
        ], $context);

        \Log::error('[500] ' . $operation . ' failed - Database Error', $logData);
        
        throw new \Exception($this->getUserFriendlyMessage($operation) . 'データベースエラーが発生しました。', 500);
    }

    /**
     * 400エラー（ビジネスロジックエラー）の処理
     */
    protected function handleBusinessLogicError(\Exception $e, string $operation, array $context = [])
    {
        $logData = array_merge([
            'error' => $e->getMessage(),
            'operation' => $operation,
            'method' => __METHOD__,
            'line' => __LINE__
        ], $context);

        \Log::error('[400] ' . $operation . ' failed - Business Logic Error', $logData);
        
        throw $e; // 400エラーはそのまま再スロー
    }

    /**
     * ユーザーフレンドリーなエラーメッセージを取得
     */
    protected function getUserFriendlyMessage(string $operation): string
    {
        $messages = [
            // 商品管理
            'product_retrieval' => '商品一覧の取得に失敗しました。',
            'product_creation' => '商品の作成に失敗しました。',
            'product_update' => '商品の更新に失敗しました。',
            'product_deletion' => '商品の削除に失敗しました。',
            'product_review_retrieval' => '商品レビューの取得に失敗しました。',
            'product_review_deletion' => 'レビューの削除に失敗しました。',
            'image_upload' => '画像のアップロードに失敗しました。',

            // バッジ管理
            'badge_retrieval' => 'バッジ一覧の取得に失敗しました。',
            'badge_creation' => 'バッジの作成に失敗しました。',
            'badge_update' => 'バッジの更新に失敗しました。',
            'badge_deletion' => 'バッジの削除に失敗しました。',
            'badge_image_upload' => 'バッジ画像のアップロードに失敗しました。',

            // カート管理
            'cart_view_data_retrieval' => 'カート表示データの取得に失敗しました。',
            'cart_summary_retrieval' => 'カート情報の取得に失敗しました。',
            'add_to_cart' => 'カートへの商品追加に失敗しました。',
            'cart_item_update' => 'カート商品の更新に失敗しました。',
            'cart_item_removal' => 'カート商品の削除に失敗しました。',

            // 決済処理
            'stripe_session_creation' => '決済セッションの作成に失敗しました。',
            'payment_processing' => '決済処理に失敗しました。',

            // 注文管理
            'order_finalization' => '注文の確定に失敗しました。',
            'paid_order_retrieval' => '支払い済み注文の取得に失敗しました。',
            'order_item_shipping' => '注文商品の発送処理に失敗しました。',
            'shipped_order_retrieval' => '発送済み注文の取得に失敗しました。',
            'selected_badges_retrieval' => '選択されたバッジの取得に失敗しました。',
            'selected_badges_creation' => '選択されたバッジの作成に失敗しました。',

            // 商品選択
            'product_selection' => '商品選択に失敗しました。',
            'badges_and_user_retrieval' => 'バッジとユーザー情報の取得に失敗しました。',

            // ページ表示
            'page_display' => 'ページの表示に失敗しました。',
            'cart_page_display' => 'カートページの表示に失敗しました。',
            'product_creation_page_display' => '商品作成ページの表示に失敗しました。',
            'product_edit_page_display' => '商品編集ページの表示に失敗しました。',
            'product_selection_page_display' => '商品選択ページの表示に失敗しました。',
            'order_confirmation_page_display' => '注文確認ページの表示に失敗しました。',
            'shipped_orders_page_display' => '発送済み注文ページの表示に失敗しました。',
            'selected_badges_page_display' => '選択バッジ確認ページの表示に失敗しました。',
            'checkout_success_page_display' => '決済完了ページの表示に失敗しました。',

            // 認証関連
            'admin_login' => '管理者ログインに失敗しました。',
            'admin_registration' => '管理者登録に失敗しました。',
            'admin_logout' => '管理者ログアウトに失敗しました。',
            'user_login' => 'ユーザーログインに失敗しました。',
            'user_registration' => 'ユーザー登録に失敗しました。',
            'user_logout' => 'ユーザーログアウトに失敗しました。',
            'session_regeneration' => 'セッションの再生成に失敗しました。',
            'session_invalidation' => 'セッションの無効化に失敗しました。',

            // プロフィール管理
            'profile_update' => 'プロフィールの更新に失敗しました。',
            'account_deletion' => 'アカウントの削除に失敗しました。',
            'profile_retrieval' => 'プロフィール情報の取得に失敗しました。',

            // ユーザー管理
            'user_profile_retrieval' => 'ユーザープロフィールの取得に失敗しました。',
            'user_orders_retrieval' => 'ユーザー注文履歴の取得に失敗しました。',
            'user_update' => 'ユーザー情報の更新に失敗しました。',
            'current_user_retrieval' => '現在のユーザー情報の取得に失敗しました。',

            // ページ表示
            'admin_login_page_display' => '管理者ログインページの表示に失敗しました。',
            'admin_registration_page_display' => '管理者登録ページの表示に失敗しました。',
            'user_login_page_display' => 'ユーザーログインページの表示に失敗しました。',
            'user_registration_page_display' => 'ユーザー登録ページの表示に失敗しました。',
            'profile_edit_page_display' => 'プロフィール編集ページの表示に失敗しました。',
            'user_mypage_display' => 'ユーザーマイページの表示に失敗しました。',
            'user_orders_display' => 'ユーザー注文履歴ページの表示に失敗しました。',
            'user_edit_page_display' => 'ユーザー編集ページの表示に失敗しました。',

            // 検索・表示機能
            'product_search' => '商品検索に失敗しました。',
            'products_retrieval' => '商品一覧の取得に失敗しました。',
            'product_details_retrieval' => '商品詳細の取得に失敗しました。',
            'products_by_category_retrieval' => 'カテゴリ別商品の取得に失敗しました。',
            'products_display' => '商品一覧ページの表示に失敗しました。',
            'product_details_display' => '商品詳細ページの表示に失敗しました。',
            'category_products_display' => 'カテゴリ商品ページの表示に失敗しました。',

            // お気に入り機能
            'user_favorites_retrieval' => 'お気に入り商品の取得に失敗しました。',
            'add_to_favorites' => 'お気に入りへの追加に失敗しました。',
            'remove_from_favorites' => 'お気に入りからの削除に失敗しました。',
            'favorites_display' => 'お気に入りページの表示に失敗しました。',

            // レビュー機能
            'review_creation' => 'レビューの投稿に失敗しました。',
            'product_reviews_retrieval' => '商品レビューの取得に失敗しました。',
            'review_deletion' => 'レビューの削除に失敗しました。',
            'admin_review_display' => '管理者レビューページの表示に失敗しました。',

            // バッジ管理
            'badge_list_display' => 'バッジ一覧ページの表示に失敗しました。',
            'badge_creation_page_display' => 'バッジ作成ページの表示に失敗しました。',
            'badge_edit_page_display' => 'バッジ編集ページの表示に失敗しました。',

            // 商品確認機能
            'confirm_items_data_retrieval' => '商品確認データの取得に失敗しました。',
            'confirm_items_display' => '商品確認ページの表示に失敗しました。',

            // 管理者レビュー機能
            'admin_review_deletion' => '管理者によるレビュー削除に失敗しました。',
        ];

        return $messages[$operation] ?? '操作に失敗しました。';
    }

    /**
     * エラーレスポンスを作成
     */
    protected function createErrorResponse(string $operation)
    {
        $message = $this->getUserFriendlyMessage($operation);
        
        // リクエストがAJAXの場合はJSONレスポンス
        if (request()->ajax()) {
            return response()->json([
                'success' => false, 
                'message' => $message
            ], 500);
        }
        
        // 通常のリクエストの場合はリダイレクト
        return redirect()->back()->with('error', $message);
    }

    /**
     * フォームデータを保持したエラーレスポンスを作成
     */
    protected function createErrorResponseWithInput(string $operation)
    {
        $message = $this->getUserFriendlyMessage($operation);
        
        // リクエストがAJAXの場合はJSONレスポンス
        if (request()->ajax()) {
            return response()->json([
                'success' => false, 
                'message' => $message
            ], 500);
        }
        
        // 通常のリクエストの場合はリダイレクト（フォームデータ保持）
        return redirect()->back()->with('error', $message)->withInput();
    }

    /**
     * 実行可能な操作をエラーハンドリング付きで実行（Service層用）
     */
    protected function executeWithErrorHandling(callable $operation, string $operationName, array $context = [])
    {
        try {
            return $operation();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->handleDatabaseError($e, $operationName, $context);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->handleStripeError($e, $operationName, $context);
        } catch (\Exception $e) {
            if ($e->getCode() == 400) {
                $this->handleBusinessLogicError($e, $operationName, $context);
            } else {
                $this->handleServiceError($e, $operationName, $context);
            }
        }
    }

    /**
     * 実行可能な操作をエラーハンドリング付きで実行（Controller層用）
     */
    protected function executeControllerWithErrorHandling(callable $operation, string $operationName, array $context = [])
    {
        try {
            return $operation();
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->handleStripeError($e, $operationName, $context);
        } catch (\Exception $e) {
            if ($e->getCode() == 400) {
                return redirect()->back()->with('error', $e->getMessage());
            } else {
                return $this->handleControllerError($e, $operationName, $context);
            }
        }
    }

    /**
     * フォームデータを保持したエラーハンドリング付きで実行（Controller層用）
     */
    protected function executeControllerWithErrorHandlingAndInput(callable $operation, string $operationName, array $context = [])
    {
        try {
            return $operation();
        } catch (\Exception $e) {
            $logData = array_merge([
                'error' => $e->getMessage(),
                'operation' => $operationName,
                'method' => __METHOD__,
                'line' => __LINE__
            ], $context);

            \Log::error('[500] ' . $operationName . ' failed - Controller Error', $logData);
            
            return $this->createErrorResponseWithInput($operationName);
        }
    }
} 