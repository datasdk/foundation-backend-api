<?php

namespace Modules\Chat\Services;

use Modules\Chat\Models\User;
use Modules\Chat\Models\Conversation;
use Musonza\Chat\Facades\ChatFacade as Chat;
use Illuminate\Support\Facades\Log;
use Modules\Chat\Events\ChatMessageNotification;
use Modules\Media\Services\MediaLibraryService;
use Modules\Chat\Events\MessageSent;


class ChatService
{

    public function create(string $name,$title = "New Chat", $description = null): ?Conversation
    {

        
        try {

            
            return Conversation::firstOrCreate([
                'name'          => $name,
                'title'         => $title,
                'description'   => $description,
            ]);


        } catch (\Exception $e) {

            Log::error("ChatService createChat error: " . $e->getMessage());

            return null;

        }


    }


    public function edit(array $data, $chatId, $authUserId): ?Conversation
    {

        try {


            $conversation = Conversation::findByNameOrId($chatId);

            if (!$conversation) return null;

            $authUser = User::find($authUserId);


            // Join/leave
            if (isset($data['join']) && $authUser) {

                if ($data['join'] && !$conversation->hasParticipant($authUser)) {

                    $conversation->addParticipants([$authUser]);

                } elseif (!$data['join']) {

                    $conversation->removeParticipant([$authUser]);

                }

            }



            // Invites
            $invites = $this->getInvitedUsers($data, $conversation);

            if (!empty($invites)) {

                Chat::conversation($conversation)->addParticipants($invites);

            }

            return $conversation;



        } catch (\Exception $e) {

            Log::error("ChatService editChat error: " . $e->getMessage());

            return null;

        }


    }


    public function addUser(Conversation $conversation, int $userId): bool
    {


        $user = User::find($userId);

        if ($user && !$conversation->hasParticipant($user)) {

            $conversation->addParticipants([$user]);

            return true;

        }

        return false;

    }

    
    public function addUsers(Conversation $conversation, array $userIds)
    {
     
        $usersToAdd = [];

        foreach ($userIds as $userId) {

            $usersToAdd[]= $this->addUser($conversation,$userId);

        }

        return $usersToAdd;

    }


    public function removeUser(Conversation $conversation, int $userId): bool
    {

        $user = User::find($userId);

        if ($user && $conversation->hasParticipant($user)) {

            $conversation->removeParticipant([$user]);

            return true;

        }

        return false;

    }


    public function delete(Conversation $conversation): bool
    {

        try {

            $conversation->participants()->delete();

            $conversation->delete();

            return true;

        } catch (\Exception $e) {

            Log::error("ChatService deleteChat error: " . $e->getMessage());

            return false;

        }

    }




    private function getAuthUser(array $data): ?User
    {

        return isset($data['authUserId']) ? User::find($data['authUserId']) : null;

    }


    private function shouldJoin(array $data, Conversation $conversation, User $user): bool
    {

        return !empty($data['join']) && !$conversation->hasParticipant($user);

    }


    private function getInvitedUsers(array $data, Conversation $conversation): array
    {

        if (empty($data['invite'])) return [];

        return array_values(array_filter(array_map(function ($userId) use ($conversation) {
            $user = User::find($userId);
            return ($user && !$conversation->hasParticipant($user)) ? $user : null;
        }, $data['invite'])));

    }


    private function addParticipantsToConversation(Conversation $conversation, array $users): void
    {

        if (!empty($users)) {
            $conversation->addParticipants($users)->makePrivate(true);
        }

    }

}
