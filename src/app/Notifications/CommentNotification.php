<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    use Queueable;

    protected $commenter;
    protected $board;

    /**
     * Create a new notification instance.
     */
    public function __construct($commenter, $board)
    {
        $this->commenter = $commenter;
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
     * データベースに保存する通知データ
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'comment',
            'message' => "{$this->commenter->name} さんがあなたの投稿にコメントしました。",
            'board_id' => $this->board->id,
            'url' => route('boards.show', $this->board->id),
        ];
    }
}
