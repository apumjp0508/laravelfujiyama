<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>パスワードの再設定</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; padding: 30px; border-radius: 6px;">
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <h2 style="margin: 0; color: #333;">パスワードの再設定</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #555; font-size: 15px; line-height: 1.6;">
                            <p>{{ $user->name ?? 'ユーザー' }} 様</p>
                            <p>パスワードの再設定リクエストを受け付けました。</p>
                            <p>以下のボタンから新しいパスワードを設定してください。</p>

                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $url }}"
                                   style="display: inline-block; padding: 12px 24px; background-color: #28a745; color: #fff;
                                   text-decoration: none; border-radius: 5px; font-weight: bold;">
                                    パスワードを再設定する
                                </a>
                            </p>

                            <p>このリンクは {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} 分間有効です。</p>
                            <p>心当たりがない場合は、このメールは破棄してください。</p>

                            <p style="margin-top: 30px;">ご利用ありがとうございます。</p>
                            <p>―― サイト名</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
