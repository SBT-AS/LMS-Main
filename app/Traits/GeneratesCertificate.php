<?php

namespace App\Traits;

use App\Models\Certificate;
use Illuminate\Support\Str;

trait GeneratesCertificate
{
    /**
     * Create a certificate for a user for a specific course if it doesn't exist.
     *
     * @param int $userId
     * @param int $courseId
     * @return Certificate|null
     */
    protected function generateCertificate($userId, $courseId)
    {
        $existing = Certificate::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$existing) {
            return Certificate::create([
                'user_id' => $userId,
                'course_id' => $courseId,
                'certificate_number' => 'CERT-' . strtoupper(Str::random(8)),
                'issued_at' => now()
            ]);
        }

        return $existing;
    }
}
