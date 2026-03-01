<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Contact;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    /**
     * お問い合わせフォーム入力ページ
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        return view('contact.index', [
            'categories' => $categories,
            'contact'    => $request->all()
        ]);
    }

    /**
     * お問い合わせフォーム確認ページ
     */
    public function confirm(ContactRequest $request)
    {
        $contact = $request->validated();
        $request->session()->put('contact_input', $contact);

        $category = Category::find($contact['contact_type']);

        return view('contact.confirm', compact('contact', 'category'));
    }

    /**
     * サンクスページ
     */
    public function thanks(Request $request)
    {
        $data = $request->all();

        Contact::create([
            'category_id' => $data['contact_type'],
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'gender'      => $data['gender'],
            'email'       => $data['email'],
            'tel'         => $data['tel1'] . $data['tel2'] . $data['tel3'],
            'address'     => $data['address'],
            'building'    => $data['building'] ?? null,
            'detail'      => $data['detail'],
        ]);

        //リダイレクト
        return redirect('/thanks');
    }
}