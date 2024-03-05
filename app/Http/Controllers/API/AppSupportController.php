<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppSupport;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class AppSupportController extends Controller
{
    public function get_support_tickets(Request $request)
    {
        $tickets = AppSupport::where("memberid", "=", $request->id)->get();
        return $tickets;
    }

    public function create_support_ticket(Request $request)
    {
        try {
            // Validate incoming request data
            $validatedData = $request->validate([
                'memberid' => 'required|string',
                'name' => 'required|string',
                'email' => 'required|email',
                'concern' => 'required|string',
                'image' => 'required|file|mimes:jpeg,png,gif', // Example of allowed MIME types
            ]);

            $file = $request->file('image');
            $filename = 'lr_app/support_ticket/' . $file->getClientOriginalName();
            // Assuming 's3' is your disk name
            Storage::disk('s3')->put($filename, file_get_contents($file), 'public');
            // You can also specify additional options, such as visibility (public or private)
            // Storage::disk('s3')->put('fh-app-uploads/' . $file->getClientOriginalName(), file_get_contents($file), 'public');

            // Create a new support ticket
            $supportTicket = AppSupport::create([
                'memberid' => $validatedData['memberid'],
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'concern' => $validatedData['concern'],
                'image' => 'https://filipinohomes123.s3.ap-southeast-1.amazonaws.com/' . $filename,
                'status' => 'Unresolved',
                'responses' => '[]',
            ]);

            // Return the newly created support ticket
            return response()->json([
                'message' => 'Support ticket created successfully',
                'data' => $supportTicket,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store_message_agent(Request $request)
    {
        $request->validate([
            'ID' => 'required',
            'message' => 'required',
        ]);

        // Get the current date and time
        $date = now();

        // Generate a random message ID of 10 characters
        $randomMessageID = Str::random(10);

        // Prepare the message data in an associative array
        $messageData = [
            "messageid" => $randomMessageID,
            "role" => "AGENT",
            "message" => $request->message,
            "setStatusTo" => "InProgress",
            "date" => $date,
        ];

        // Find the AppSupport data based on the ID and update status and responses
        $thisticket = AppSupport::findOrFail($request->ID);

        // Decode the existing JSON string in the 'responses' field, or initialize an empty array if it's null
        $existingResponses = json_decode($thisticket->responses, true) ?? [];

        // Append the new message data to the existing array of responses
        $existingResponses[] = $messageData;

        // Encode the combined array back to JSON format
        $updatedResponses = json_encode($existingResponses);

        // Update the AppSupport record with the new status and updated responses
        $thisticket->update([
            "status" => "InProgress",
            "responses" => $updatedResponses
        ]);

        return response()->json(['message' => 'Message stored successfully'], 200);
    }
}
