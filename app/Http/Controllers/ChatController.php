<?php

namespace App\Http\Controllers;
use App\Events\ChatMessageSent;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
public function getMessagesByMentorAndStartup(Request $req, $mentor_id, $startup_id)
{
    // Validate the request parameters
    $validator = Validator::make([
        'mentor_id' => $mentor_id,
        'startup_id' => $startup_id,
    ], [
        'mentor_id' => 'required|numeric|exists:mentors,id',
        'startup_id' => 'required|numeric|exists:startups,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid mentor or startup ID',
            'errors' => $validator->errors(),
        ], 400);
    }

    // Fetch the messages between the mentor and startup
    $messages = ChatMessage::where(function ($query) use ($mentor_id, $startup_id) {
            $query->where('mentor_id', $mentor_id)
                  ->where('startup_id', $startup_id);
        })
        ->orWhere(function ($query) use ($mentor_id, $startup_id) {
            $query->where('mentor_id', null) // Messages sent by startup
                  ->where('startup_id', $startup_id);
        })
        ->orWhere(function ($query) use ($mentor_id, $startup_id) {
            $query->where('mentor_id', $mentor_id) // Messages sent by mentor
                  ->where('startup_id', null);
        })
        ->orderBy('created_at', 'asc')
        ->get();

    // Return messages with structured data
    return response()->json([
        'status' => 'success',
        'messages' => $messages->map(function ($message) {
            return [
                'sender_type' => $message->sender_type,
                'mentor_id' => $message->mentor_id,
                'startup_id' => $message->startup_id,
                'message' => $message->message,
                'time' => $message->created_at->format('H:i'),
            ];
        }),
    ], 200);
}



// Send a new chat message
public function sendMessage(Request $req)
{
    // Validate the request parameters
    $validatedData = $req->validate([
        'sender_type' => 'required|in:mentor,startup',
        'mentor_id' => 'sometimes|numeric|exists:mentors,id',
        'startup_id' => 'sometimes|numeric|exists:startups,id',
        'message' => 'required|string',
    ]);

    // Create a new chat message
    $chatMessage = ChatMessage::create([
        'startup_id' => $validatedData['startup_id'], // Always set this
        'mentor_id' => $validatedData['mentor_id'],   // Always set this
        'sender_type' => $validatedData['sender_type'], // Set sender type
        'message' => $validatedData['message'],
    ]);

    // Return the created message
    return response()->json([
        'status' => 'success',
        'message' => [
            'sender_type' => $chatMessage->sender_type,
            'mentor_id' => $chatMessage->mentor_id,
            'startup_id' => $chatMessage->startup_id,
            'message' => $chatMessage->message,
            'time' => $chatMessage->created_at->format('H:i'),
        ],
    ], 201);
}






    // Update an existing message
    public function updateMessage(Request $req, $id)
    {
        $message = ChatMessage::find($id);
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }

        // Validate the request data
        $validatedData = $req->validate([
            'mentor_id' => 'required|numeric|exists:mentors,id',
            'startup_id' => 'required|numeric|exists:startups,id',
            'message' => 'required|string',
        ]);

        // Update the message
        $message->update($validatedData);

        return response()->json(['message' => 'Updated successfully'], 200);
    }

    // Delete a chat message
    public function deleteMessage($id)
    {
        $message = ChatMessage::find($id);
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }

        $message->delete();

        return response()->json(['message' => 'Deleted successfully'], 204);
    }

}