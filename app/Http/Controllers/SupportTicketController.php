<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Ticket berhasil dikirim.');
    }

    public function respond(Request $request, SupportTicket $ticket)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'response' => 'required|string',
        ]);

        $ticket->update([
            'admin_id' => Auth::id(),
            'response' => $request->response,
            'status' => 'closed',
        ]);

        return back()->with('success', 'Ticket telah ditandai selesai.');
    }
}
