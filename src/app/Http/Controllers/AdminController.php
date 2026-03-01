<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Contact;
use App\Models\Category;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * 管理画面
     */
    public function index(Request $request)
    {
        $contacts = Contact::with('category')
                ->KeywordSearch($request->keyword)
                ->GenderSearch($request->gender)
                ->CategorySearch($request->category_id)
                ->DateSearch($request->date)
                ->paginate(7)
                ->appends($request->all());

        $categories = Category::all();
        return view('admin.index', compact('contacts', 'categories'));
    }

    /**
     * 検索リセット
     */
    public function reset()
    {
        return redirect()->route('admin.index');
    }

    /**
     * お問い合わせフォーム削除
     */
    public function delete($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->back()->with('success', 'お問い合わせを削除しました。');
    }

    /**
     * エクスポート（CSV形式で出力）
     */
    public function export(Request $request)
    {
         $contacts = Contact::with('category')
        ->KeywordSearch($request->keyword)
        ->GenderSearch($request->gender)
        ->CategorySearch($request->category_id)
        ->DateSearch($request->date)
        ->get();

        $filename = 'contacts_export_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($contacts) {
            $handle = fopen('php://output', 'w');
            // ヘッダー行(モーダルと同じ)
            fputcsv($handle, [
                'お名前',
                '性別',
                'メールアドレス',
                '電話番号',
                '住所',
                '建物名',
                'お問い合わせの種類',
                'お問い合わせ内容'
            ]);
            
           foreach ($contacts as $contact) {

            $gender = match ($contact->gender) {
                1 => '男性',
                2 => '女性',
                3 => 'その他',
                default => ''
            };

            fputcsv($handle, [
                $contact->last_name . ' ' . $contact->first_name,
                $gender,
                $contact->email,
                $contact->tel,
                $contact->address,
                $contact->building, 
                optional($contact->category)->content,
                $contact->detail,
            ]);
        }


            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
