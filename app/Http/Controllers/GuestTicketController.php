<?php

namespace App\Http\Controllers;

use App\Http\Requests\Guest\CreateTicketRequest;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketAcknowledgementMail;
use Illuminate\Support\Facades\Log;

class GuestTicketController extends Controller
{
  protected $ticketService;

  function __construct(TicketService $ticketService){
    $this->ticketService = $ticketService;
  } 

  public function store(CreateTicketRequest $request){

    $ticket = $this->ticketService->createTicket($request->validated());
    if(!$ticket) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create support ticket.',
        ], 500);
    }

    // Send acknowledgement email to customer
    try {
        Mail::to($ticket->email)->send(new TicketAcknowledgementMail($ticket));
    } catch (\Exception $e) {
        Log::error("Failed sending ticket acknowledgement email: " . $e->getMessage());
    }

    return response()->json([
        'success' => true,
        'message' => 'Support ticket created successfully!',
        'reference_number' => $ticket->reference_number,
        'ticket' => $ticket,
    ], 201);
    
  }

  public function getStatus(Request $request){
    $validated = $request->validate([
        'reference_number' => 'required|string'
    ]);

    $ticket = $this->ticketService->getTicketByReferenceNumber($validated['reference_number']);

    if(!$ticket) {
        return response()->json([
            'success' => false,
            'message' => 'Ticket not found.',
        ], 404);
    }

     return response()->json([
            'success' => true,
            'ticket' => [
                'reference_number' => $ticket->reference_number,
                'customer_name' => $ticket->customer_name,
                'email' => $ticket->email,
                'phone_number' => $ticket->phone_number,
                'problem_description' => $ticket->problem_description,
                'status' => ucfirst($ticket->status),
                'created_at' => $ticket->created_at->format('M d, Y H:i'),
                'replies' => $ticket->replies->map(function ($reply) {
                    return [
                        'id' => $reply->id,
                        'agent_name' => $reply->user ? $reply->user->name : 'Customer (You)',
                        'is_customer' => $reply->user_id === null,
                        'message' => $reply->message,
                        'created_at' => $reply->created_at->format('M d, Y H:i'),
                    ];
                }),
            ],
        ]);
  }

  public function reply(Request $request)
  {
      $validated = $request->validate([
          'reference_number' => 'required|string',
          'message' => 'required|string|min:3',
      ]);

      $ticket = $this->ticketService->getTicketByReferenceNumber($validated['reference_number']);

      if (!$ticket) {
          return response()->json([
              'success' => false,
              'message' => 'Ticket not found.',
          ], 404);
      }

      $reply = $this->ticketService->customerReplyTicket($ticket, $validated['message']);

      return response()->json([
          'success' => true,
          'message' => 'Reply posted successfully!',
          'reply' => [
              'id' => $reply->id,
              'agent_name' => 'Customer (You)',
              'is_customer' => true,
              'message' => $reply->message,
              'created_at' => $reply->created_at->format('M d, Y H:i'),
          ],
          'status' => 'Pending',
      ]);
  }
}
