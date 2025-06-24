<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class MentionNotification extends Notification
{
    public function __construct(
        protected $fromUser,
        protected $board,
        protected $comment
    ) {}

    public function via($notifiable)
    {
        return ['database']; // または ['mail', 'database'] にもできる
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'mention',
            'from_user_id' => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'board_id' => $this->board->id,
            'comment_id' => $this->comment->id,
            'message' => "{$this->fromUser->name} さんがあなたをコメントでメンションしました。",
        ];
    }
}