<?php
namespace Modules\Chat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatRole extends Model
{
    // Use HasFactory for factory support (useful for testing and seeding)
    use HasFactory;

    // Define fillable attributes for mass assignment
    protected $fillable = ['conversation_id', 'user_id', 'role'];

    /**
     * Define the relationship to the Conversation model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Define the relationship to the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a user is an admin in the given conversation.
     *
     * @param  int  $conversationId
     * @param  int  $userId
     * @return bool
     */
    public static function isAdmin($conversationId, $userId)
    {
        // Check if the user is an admin in the specified conversation
        return self::where('conversation_id', $conversationId)
                   ->where('user_id', $userId)
                   ->where('role', 'admin')
                   ->exists();
    }

    /**
     * Set a user as the admin of the conversation.
     *
     * @param  int  $conversationId
     * @param  int  $userId
     * @return void
     */
    public static function setAdmin($conversationId, $userId)
    {
        // Remove the existing admin, if any
        self::where('conversation_id', $conversationId)
            ->where('role', 'admin')
            ->update(['role' => 'member']);

        // Set the new admin role
        self::updateOrCreate(
            ['conversation_id' => $conversationId, 'user_id' => $userId],
            ['role' => 'admin']
        );
    }

    /**
     * Change the admin of the conversation to a new user.
     *
     * @param  int  $conversationId
     * @param  int  $newAdminId
     * @return void
     */
    public static function changeAdmin($conversationId, $newAdminId)
    {
        // Call the setAdmin method to change the admin
        self::setAdmin($conversationId, $newAdminId);
    }

    /**
     * Remove a member from the conversation (admin only).
     *
     * @param  int  $conversationId
     * @param  int  $memberId
     * @return void
     */
    public static function removeMember($conversationId, $memberId)
    {
        // Delete the member from the specified conversation
        self::where('conversation_id', $conversationId)
            ->where('user_id', $memberId)
            ->delete();
    }

    /**
     * Close the chat (delete the conversation) - admin only.
     *
     * @param  int  $conversationId
     * @return void
     */
    public static function closeChat($conversationId)
    {
        // Find the conversation by its ID
        $conversation = Conversation::find($conversationId);
        
        // If the conversation exists, delete it
        if ($conversation) {
            $conversation->delete();
        }
    }
}
