<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    public function createTicket($validatedData)
    {
        $referenceNumber = Ticket::generateReferenceNumber();

        $ticket = Ticket::create([
            'reference_number' => 'TKT-'.$referenceNumber,
            'customer_name' => $validatedData['customer_name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'problem_description' => $validatedData['problem_description'],
            'status' => 'pending',
            'is_opened' => false,
        ]);

        return $ticket;
    }

    public function getTicketByReferenceNumber(string $referenceNumber)
    {
        $referenceNumber = trim($referenceNumber);

        return Ticket::with(['replies.user' => function ($q) {
            $q->select('id', 'name', 'email');
        }])->where('reference_number', $referenceNumber)->first();
    }

    public function replyTicket($ticket, $validatedData)
    {
        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validatedData['message'],
        ]);

        $ticket->update([
            'status' => 'replied',
            'is_opened' => true,
        ]);

        return $reply;
    }

    public function customerReplyTicket($ticket, string $message)
    {
        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => null,
            'message' => $message,
        ]);

        $ticket->update([
            'status' => 'pending',
            'is_opened' => false,
        ]);

        return $reply;
    }
}


