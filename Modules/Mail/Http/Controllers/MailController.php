<?php

namespace Modules\Mail\Http\Controllers;

use App\User;
use Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\NewMessageEvent;
use Modules\Mail\Models\Letter;
use Illuminate\Routing\Controller;
use Modules\Mail\Models\LetterUser;
use App\Notifications\NewMessageNotification;

class MailController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $boxes = Letter::BOXES;
        $inbox = Letter::BOX_INBOX;
        $outbox = Letter::BOX_OUTBOX;
        $drafts = Letter::BOX_DRAFTS;
        $box = isset($request->box)  ? $request->box : Letter::BOX_INBOX;
        $letters = auth()->user()->getBox($box);
        // dd(auth()->user()->letters, $letters, $box);

        $notification = $request->user()->notifications()->get()->map(function($n) {
            $n->markAsRead();
        });;

        return view('mail::index', compact('letters', 'boxes', 'box', 'inbox', 'outbox', 'drafts'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create(Request $request)
    {
        $users = User::where('id', '!=', auth()->user()->getKey())->get();
        $outbox = Letter::BOX_OUTBOX;
        $drafts = Letter::BOX_DRAFTS;
        $letter = $request->letter_id ? Letter::findOrFail($request->letter_id) : null;
        // dd($letter->attachments->first()->getFile());
        return view('mail::create', compact('users', 'outbox', 'drafts', 'letter'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $request->validate([
        'to' => 'required',
        'content' => 'required',
        'title' => 'required',
        ]);
        
        $mail_content = htmlentities(htmlspecialchars($request->content));
        
        $letter = Letter::create([
        'user_id' => auth()->user()->getKey(),
        'content' => $mail_content,
        'title' => $request->title,
        'box' => $request->box,
        ]);
        if($request->attachments_names && $request->attachments_files){
            $letter->attach($request->attachments_names, $request->file('attachments_files'));
        }
        if($request->box == Letter::BOX_OUTBOX){
            for($index = 0; $index < count($request->to); $index++) {
                LetterUser::create([
                'user_id' => $request->to[$index],
                'letter_id' => $letter->id,
                ]);
            }
        }
        if ($letter) {
            $letter->attach();
        }
        $msg = ($request->box == Letter::BOX_OUTBOX) ? 'تم ارسال الرسالة' : 'تم حفظ الرسالة';

        broadcast(new NewMessageEvent($letter));
        $users = User::whereIn('id', $request->to)->get();
        Notification::send($users, new NewMessageNotification($letter));

        return redirect()->route('mail.index', ['box' => $request->box])->with('success', $msg);
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $letter = Letter::findOrFail($id);
        $letterUser = LetterUser::where('user_id', auth()->user()->getKey())->where('letter_id', $letter->id)->get()->first();
        $inbox = Letter::BOX_INBOX;
        $outbox = Letter::BOX_OUTBOX;
        $drafts = Letter::BOX_DRAFTS;
        $box = $letterUser->box;
        
        if($letterUser->seen !== 1){
            $letterUser->update(['seen' => 1]);
        }
        $boxes = Letter::BOXES;
        $box = $letterUser->box;
        // dd(\App\Attachment::all());
        return view('mail::show', compact('letter', 'boxes', 'box', 'inbox', 'outbox', 'drafts'));
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        return view('mail::edit');
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        $letter = Letter::findOrFail($id);
        
        $request->validate([
        'content' => 'required',
        ]);
        
        if($request->type == 'reply') {
            $letter_reply = Letter::create([
            'content' => $request->content,
            'letter_id' => $id,
            'user_id' => auth()->user()->getKey(),
            'title' => $letter->title,
            ]);
            
            LetterUser::create([
            'user_id' => $request->to,
            'letter_id' => $letter_reply->id
            ]);
        }else {
            
        }
        
        return redirect()->route('mail.show', $letter->id)->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {
        $letter = Letter::findOrFail($id);
        $letterUser = LetterUser::where('user_id', auth()->user()->getKey())->where('letter_id', $letter->id)->get()->first();
        $box = $letterUser->box;
        $letter->delete();
        $msg = 'تم حذف الرسالة بنجاح';
        return redirect()->route('mail.index', ['box' => $box])->with('success', $msg);
    }
    
    public function send() {
        $letters = Letter::where('user_id', auth()->user()->getKey())->get();
        // dd($letters);
        return view('mail::send', compact('letters'));
    }
}