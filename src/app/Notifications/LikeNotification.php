<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LikeNotification extends Notification
{
    use Queueable;

    protected $liker;
    protected $board;

    /**
     * Create a new notification instance.
     */
    public function __construct($liker, $board)
    {
        $this->liker = $liker;
        $this->board = $board;
    }

    /**
     * 通知の配信チャネル（データベースのみ）
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * データベース通知の内容
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'like',
            'message' => "{$this->liker->name} さんがあなたの投稿にいいねしました。",
            'board_id' => $this->board->id,
            'url' => route('boards.show', $this->board->id),
        ];
    }
}
