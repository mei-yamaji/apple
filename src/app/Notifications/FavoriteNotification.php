<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FavoriteNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $board;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $board)
    {
        $this->user = $user;
        $this->board = $board;
    }

    /**
     * 通知の配信チャネル（データベース通知のみ）
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * データベースに保存される通知内容
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'favorite',
            'message' => "{$this->user->name} さんがお気に入りに追加しました。",
            'board_id' => $this->board->id,
        ];
    }
}
