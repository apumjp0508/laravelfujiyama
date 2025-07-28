<?php

namespace App\Services;

use App\Models\Badge;
use App\Traits\ErrorHandlingTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BadgeService
{
    use ErrorHandlingTrait;

    public function getAllBadges()
    {
        return $this->executeWithErrorHandling(
            fn() => Badge::all(),
            'badge_retrieval'
        );
    }

    public function createBadge(array $data)
    {
        return $this->executeWithErrorHandling(
            fn() => Badge::create($data),
            'badge_creation',
            ['data' => $data]
        );
    }

    public function updateBadge(Badge $badge, array $data)
    {
        return $this->executeWithErrorHandling(
            function() use ($badge, $data) {
                $badge->update($data);
                return $badge;
            },
            'badge_update',
            [
                'badge_id' => $badge->id,
                'data' => $data
            ]
        );
    }

    public function deleteBadge(Badge $badge)
    {
        return $this->executeWithErrorHandling(
            fn() => $badge->delete(),
            'badge_deletion',
            ['badge_id' => $badge->id]
        );
    }

    public function handleImageUpload(Request $request, ?Badge $badge = null)
    {
        return $this->executeWithErrorHandling(
            function() use ($request, $badge) {
                if ($request->hasFile('img')) {
                    // 古い画像があれば削除
                    if ($badge && $badge->img) {
                        $parsedPath = parse_url($badge->img, PHP_URL_PATH);
                        $path = ltrim($parsedPath, '/');

                        if (app()->environment('production')) {
                            Storage::disk('s3')->delete($path);
                        } else {
                            $localPath = str_replace('storage/', 'public/', $path);
                            Storage::disk('public')->delete($localPath);
                        }
                    }

                    // 新しい画像を保存
                    if (app()->environment('production')) {
                        $path = $request->file('img')->store('images', 's3');
                        return Storage::disk('s3')->url($path);
                    } else {
                        $path = $request->file('img')->store('public/images');
                        return str_replace('public/', 'storage/', $path);
                    }
                }
                return $badge ? $badge->img : null;
            },
            'badge_image_upload',
            [
                'badge_id' => $badge ? $badge->id : null,
                'has_file' => $request->hasFile('img'),
                'environment' => app()->environment()
            ]
        );
    }
} 