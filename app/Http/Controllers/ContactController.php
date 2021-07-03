<?php

namespace App\Http\Controllers;
use App\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Displays view contact form
     */
    public function create()
    {
        return view('pages.contact');
    }

    /**
     * Send contact data in email
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'msg' => 'required'
        ]);

        Mail::send(
            'emails.contact-message',array(
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'msg' => $request->get('msg')
 	        ),
            function ($mail) use ($request) {
                $mail->from($request->email, $request->name);
                $mail->subject($request->get('msg'));
                $mail->to(['mehrlicht@gmail.com']);
            }
        );

        return redirect()->back()->with('flash_message', 'Muito obrigado pela sua mensagem.');
    }
}
