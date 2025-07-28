<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メールアドレスの確認</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; padding: 30px; border-radius: 6px;">
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <h2 style="margin: 0; color: #333;">メールアドレスの確認</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #555; font-size: 15px; line-height: 1.6;">
                            <p>{{ $user->name }} 様</p>
                            <p>この度はご登録ありがとうございます。</p>
                            <p>以下のボタンをクリックして、メールアドレスの確認を完了してください。</p>

                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $url }}"
                                   style="display: inline-block; padding: 12px 24px; background-color: #007bff; color: #fff;
                                   text-decoration: none; border-radius: 5px; font-weight: bold;">
                                    メールアドレスを確認する
                                </a>
                            </p>

                            <p>このリンクは60分間のみ有効です。</p>
                            <p>このメールに心当たりがない場合は、破棄してください。</p>

                            <p style="margin-top: 30px;">今後ともよろしくお願いいたします。</p>
                            <p>―― サイト名</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
