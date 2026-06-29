<?php

namespace Database\Seeders;

use App\Enums\FriendshipStatus;
use App\Enums\MessageType;
use App\Models\Conversation;
use App\Models\Friendship;
use App\Models\Message;
use App\Models\TypingIndicator;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UranusDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $users = $this->seedUsers();
            $this->seedFriendships($users);
            $this->seedConversationsAndMessages($users);
        });
    }

    private function seedUsers(): array
    {
        $now = now();

        $rows = [
            [
                'email' => 'nova@uranus.test',
                'username' => 'nova',
                'full_name' => 'Nova Hart',
                'gender' => 'female',
                'bio' => 'Loves late night chats, music, and tiny cosmic details.',
                'is_online' => true,
                'last_seen_at' => $now,
            ],
            [
                'email' => 'orion@uranus.test',
                'username' => 'orion',
                'full_name' => 'Orion Vale',
                'gender' => 'male',
                'bio' => 'Always online, usually planning the next conversation.',
                'is_online' => true,
                'last_seen_at' => $now->copy()->subMinutes(2),
            ],
            [
                'email' => 'lyra@uranus.test',
                'username' => 'lyra',
                'full_name' => 'Lyra Stone',
                'gender' => 'female',
                'bio' => 'Coffee, design notes, and calm chats.',
                'is_online' => false,
                'last_seen_at' => $now->copy()->subMinutes(18),
            ],
            [
                'email' => 'atlas@uranus.test',
                'username' => 'atlas',
                'full_name' => 'Atlas Noor',
                'gender' => 'male',
                'bio' => 'File sender, image sharer, and group energy keeper.',
                'is_online' => false,
                'last_seen_at' => $now->copy()->subHours(2),
            ],
            [
                'email' => 'mira@uranus.test',
                'username' => 'mira',
                'full_name' => 'Mira Lane',
                'gender' => 'other',
                'bio' => 'Quiet profile for pending request testing.',
                'is_online' => true,
                'last_seen_at' => $now->copy()->subMinute(),
            ],
            [
                'email' => 'sol@uranus.test',
                'username' => 'sol',
                'full_name' => 'Sol Reed',
                'gender' => 'male',
                'bio' => 'Blocked-user scenario account.',
                'is_online' => false,
                'last_seen_at' => $now->copy()->subDay(),
            ],
        ];

        $users = [];

        foreach ($rows as $row) {
            $users[$row['username']] = User::updateOrCreate(
                ['email' => $row['email']],
                $row + [
                    'password' => Hash::make('password'),
                    'email_verified_at' => $now,
                    'completed_profile' => true,
                    'email_login_otp_hash' => null,
                    'email_login_otp_expires_at' => null,
                ]
            );
        }

        return $users;
    }

    private function seedFriendships(array $users): void
    {
        $this->friendship($users['nova'], $users['orion'], FriendshipStatus::Accepted);
        $this->friendship($users['nova'], $users['lyra'], FriendshipStatus::Accepted);
        $this->friendship($users['nova'], $users['atlas'], FriendshipStatus::Accepted);
        $this->friendship($users['orion'], $users['lyra'], FriendshipStatus::Accepted);
        $this->friendship($users['mira'], $users['nova'], FriendshipStatus::Pending);
        $this->friendship($users['atlas'], $users['mira'], FriendshipStatus::Pending);
        $this->friendship($users['sol'], $users['nova'], FriendshipStatus::Blocked, $users['nova']);
        $this->friendship($users['lyra'], $users['sol'], FriendshipStatus::Rejected);
    }

    private function seedConversationsAndMessages(array $users): void
    {
        $this->conversation($users['nova'], $users['orion'], [
            [$users['nova'], MessageType::Text, 'Hey Orion, did you see the new Uranus chat design?', null, -42, true],
            [$users['orion'], MessageType::Text, 'Yes. The space vibe feels really clean.', null, -39, true],
            [$users['nova'], MessageType::Image, 'This is the profile direction I like.', [
                'attachment_path' => 'demo/messages/uranus-profile-preview.jpg',
                'attachment_name' => 'uranus-profile-preview.jpg',
                'attachment_mime' => 'image/jpeg',
                'attachment_size' => 184320,
            ], -30, true],
            [$users['orion'], MessageType::Text, 'Perfect. I will test unread and seen states now.', null, -8, false],
        ]);

        $this->conversation($users['nova'], $users['lyra'], [
            [$users['lyra'], MessageType::Text, 'Can you send the static Flutter brief again?', null, -120, true],
            [$users['nova'], MessageType::File, 'Sure, here is the document.', [
                'attachment_path' => 'demo/messages/flutter-static-design-brief.pdf',
                'attachment_name' => 'flutter-static-design-brief.pdf',
                'attachment_mime' => 'application/pdf',
                'attachment_size' => 245760,
            ], -116, true],
            [$users['lyra'], MessageType::Text, 'Thanks. I will match the Uranus theme.', null, -12, false],
        ]);

        $this->conversation($users['nova'], $users['atlas'], [
            [$users['atlas'], MessageType::Text, 'Testing delivered state on this one.', null, -65, true],
            [$users['nova'], MessageType::Text, 'Delivered and seen should look different in Flutter.', null, -60, true],
            [$users['atlas'], MessageType::Text, 'Got it. I am typing a longer response soon.', null, -2, false],
        ], true);

        $this->conversation($users['orion'], $users['lyra'], [
            [$users['orion'], MessageType::Text, 'Mutual friends should show between us and Nova.', null, -180, true],
            [$users['lyra'], MessageType::Text, 'Nice, that helps profile testing.', null, -175, true],
        ]);
    }

    private function friendship(User $requester, User $addressee, FriendshipStatus $status, ?User $blockedBy = null): void
    {
        Friendship::updateOrCreate(
            [
                'requester_id' => $requester->id,
                'addressee_id' => $addressee->id,
            ],
            [
                'status' => $status,
                'blocked_by_id' => $blockedBy?->id,
            ]
        );
    }

    private function conversation(User $firstUser, User $secondUser, array $messages, bool $typing = false): void
    {
        $conversation = Conversation::firstOrCreate(Conversation::userPair($firstUser->id, $secondUser->id));

        Message::where('conversation_id', $conversation->id)->forceDelete();

        $createdMessages = [];

        foreach ($messages as [$sender, $type, $body, $attachment, $minutesAgo, $seen]) {
            $createdAt = now()->addMinutes($minutesAgo);

            $createdMessages[] = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $sender->id,
                'reply_to_message_id' => count($createdMessages) === 2 ? $createdMessages[0]->id : null,
                'type' => $type,
                'body' => $body,
                'attachment_path' => $attachment['attachment_path'] ?? null,
                'attachment_name' => $attachment['attachment_name'] ?? null,
                'attachment_mime' => $attachment['attachment_mime'] ?? null,
                'attachment_size' => $attachment['attachment_size'] ?? null,
                'delivered_at' => $createdAt->copy()->addMinute(),
                'seen_at' => $seen ? $createdAt->copy()->addMinutes(2) : null,
                'edited_at' => null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $conversation->update([
            'latest_message_at' => collect($createdMessages)->last()?->created_at,
        ]);

        TypingIndicator::updateOrCreate(
            [
                'conversation_id' => $conversation->id,
                'user_id' => $secondUser->id,
            ],
            [
                'is_typing' => $typing,
                'updated_at' => now(),
            ]
        );
    }
}
