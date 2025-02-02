<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface NotificationServiceInterface
{
    public function sendSuccessNotification(
        string $title,
        string $body,
        Model $model,
        string $route,
        string $tableSearch,
        $recipient
    );

    public function sendUpdateNotification(
        string $title,
        string $body,
        Model $model,
        string $route,
        string $tableSearch,
        $recipient
    );

    public function sendDeleteNotification(
        string $title,
        string $body,
        string $route,
        $recipient
    );
}
