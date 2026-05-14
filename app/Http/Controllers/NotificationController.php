<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;


class NotificationController extends Controller
{
    public function index(){
        
        $notifications = Notification::where('user_id', Auth::id())
                        ->latest()
                        ->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id){
        $notifications = Notification::where('user_id', Auth::id())
                        ->where('id', $id)
                        ->firstOrFail();

        $notifications->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllasRead(){
        $notifications = Notification::where('user_id', Auth::id())
                        ->where('is_read', false)
                        ->update(['is_read' => true]);
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function destroy($id){
        $notifications = Notification::where('user_id', Auth::id())
                        ->where('id', $id)
                        ->firstOrFail();

        $notifications->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

    public function broadcastCreate()
    {
        return view('admin.notifications.broadcast');
    }

    public function broadcastStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'send_whatsapp' => 'nullable|boolean',
        ]);

        $users = \App\Models\User::all();
        $sendWhatsapp = $request->has('send_whatsapp');

        foreach ($users as $user) {
            // Create in-app notification
            Notification::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => 'broadcast',
                'is_read' => false,
            ]);

            // Send WhatsApp if requested and user has phone number
            if ($sendWhatsapp && $user->phone) {
                try {
                    $waMessage = "📢 *" . strtoupper($request->title) . "*\n\n" .
                                 "Halo Kak *{$user->name}*,\n\n" .
                                 $request->message . "\n\n" .
                                 "Terima kasih!";
                    \App\Services\FonnteService::send($user->phone, $waMessage);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("WA Broadcast Failed for {$user->phone}: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.notifications.broadcast')->with('success', 'Broadcast notification sent successfully to all users.');
    }
}
