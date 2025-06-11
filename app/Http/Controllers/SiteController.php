<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfilePasswordRequest;
use App\Models\Product;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Mail as MailModel;
use App\Mail\MailContactAdmin;
use App\Mail\MailContactUser;
use App\Mail\MailCheckoutAdmin;
use App\Mail\MailCheckoutUser;
use Intervention\Image\ImageManagerStatic;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Reader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class SiteController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::where('site_id', 1)
            ->limit(18)
            ->where('publish_id', 1)
            ->get();

        return view('index', compact('products'));
    }

    public function list(Request $request)
    {
        $freeword = $request->input('freeword') ?? "";
        $category_id = $request->input('category_id') ?? null;
        $sub_category_id = $request->input('sub_category_id') ?? null;

        $request->session()->put('front_cond', $request->query());

        $products = Product::when($freeword, function ($query) use ($freeword) {
                return $query->where('name', 'like', '%'.$freeword.'%');
            })
            ->when($category_id, function ($query) use ($category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($sub_category_id, function ($query) use ($sub_category_id) {
                return $query->where('sub_category_id', $sub_category_id);
            })
            ->where('site_id', 1)
            ->where('publish_id', 1)
            ->orderByDesc('updated_at')
            ->paginate(20);

        $category = null;
        if ($category_id) {
            $category = Category::find($category_id);
        }
        $sub_category = null;
        if ($sub_category_id) {
            $sub_category = SubCategory::find($sub_category_id);
            // $category = Category::find($sub_category->category_id);
        }
        return view('list', compact('products', 'freeword', 'category', 'sub_category'));
    }

    public function detail($id)
    {
        $product = Product::where('site_id', 1)->where('publish_id', 1)->where('id', $id)->first();

        if (!$product) {
            return redirect('/');
        }

        return view('detail', compact('product'));
    }

    public function put_in(Request $request)
    {
        $input = $request->all();
        $id = $request->input("id");
        $product = Product::where('site_id', 1)->where('id', $id)->first();
        if (!$product) {
            return redirect('/');
        }

        $cart = $request->session()->get('cart') ?? [];
        $id = -1;
        foreach ($cart as $key => $value) {
            if ($value['product_id'] == $input['id'] && $value['nameplate'] == $input['nameplate']) {
                $id = $key;
                break;
            }
        }
        if ($id == -1) {
            if (is_numeric($input['quantity'])) {
                $cart[] = ['product_id' => $input['id'], 'nameplate' => $input['nameplate'], 'quantity' => $input['quantity']];
            } else {
                $cart[] = ['product_id' => $input['id'], 'nameplate' => $input['nameplate'], 'quantity' => 1];
            }
        } else {
            if (is_numeric($input['quantity'])) {
                $cart[$id]['quantity'] += $input['quantity'];
            }
        }

        $request->session()->put('cart', $cart);

        if (($input['mode'] ?? '') == "checkout") {
            return redirect('/checkout');
        }
        return redirect('/cart');
    }

    public function cart(Request $request)
    {
        $cart = $request->session()->get('cart') ?? [];
        $products = [];

        foreach ($cart as $value) {
            $products[$value['product_id']] = Product::find($value['product_id']);
        }

        return view('cart', compact('cart', 'products'));
    }

    public function cart_quantity(Request $request)
    {
        $input = $request->all();
        $cart = $request->session()->get('cart');

        foreach ($input['quantity'] as $id => $q) {
            $cart[$id]['quantity'] = (is_numeric($q) && $q > 0) ? $q : 1;
        }
        foreach ($input['remove'] as $id => $remove) {
            if ($remove == "1") {
                unset($cart[$id]);
            }
        }

        $request->session()->put('cart', $cart);

        if (($input['mode'] ?? '') == "checkout") {
            return redirect('/checkout');
        }
        return redirect('/cart');
    }

    public function checkout(Request $request)
    {
        $cart = $request->session()->get('cart') ?? [];
        if (!$cart) {
            return redirect('/cart');
        }

        $member_id = Auth::user()->id;
        $member = Member::find($member_id);

        $products = [];
        $free = 1;
        $card_disabled = 0;
        foreach ($cart as $id => $value) {
            $product = Product::find($value['product_id']);
            if ($product->free_delivery == 0) {
                $free = 0;
            }
            if ($product->card_disabled == 1) {
                $card_disabled = 1;
            }
            $products[$value['product_id']] = $product;
        }

        return view('checkout', compact('cart', 'products', 'member', 'free', 'card_disabled'));
    }

    public function purchase(PurchaseRequest $request)
    {
        \Log::info($request->all());

        $cart = $request->session()->get('cart') ?? [];
        if (!$cart) {
            return view('error', ['message' => "エラーが発生しました。"]);
        }

        $input = $request->all();
        $input['member_id'] = Auth::user()->id;
        $input['status_id'] = 1;
        $ret = Order::create($input);

        $products = [];

        foreach ($cart as $value) {
            $product = Product::find($value['product_id']);

            $arr = [
                'order_id' => $ret->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $value['quantity'],
                'nameplate' => $value['nameplate'],
            ];

            $detail = OrderDetail::create($arr);

            $product->quantity = $value['quantity'];
            $product->nameplate = $value['nameplate'];

            $products[] = $product->toArray();
        }

        if ($request->has("zeus_xid")) {
            // $url = "https://secure2-sandbox.cardservice.co.jp/cgi-bin/secure/api.cgi"; //testing
            $url = "https://linkpt.cardservice.co.jp/cgi-bin/secure/api.cgi";  //prod

            $xid = $request->input("zeus_xid") ?? '';

            if ($xid) {
                $xml = <<< EOM
<?xml version="1.0" encoding="utf-8"?>
<request service="secure_link_3d" action="authentication">
  <xid>$xid</xid>
  <PaRes>Y</PaRes>
</request>
EOM;
                \Log::info($xml);

                $context = [
                    'http' => [
                        'method'  => 'POST',
                        'header'  => 'Content-Type: application/xml',
                        'content' => $xml
                    ]
                ];
                
                $res = file_get_contents($url, false, stream_context_create($context));

                $obj = simplexml_load_string($res);
                $arr = json_decode(json_encode($obj), true);

                \Log::info($arr);

                $xml = <<< EOM
<?xml version="1.0" encoding="utf-8"?>
<request service="secure_link_3d" action="payment">
  <xid>$xid</xid>
  <print_am>yes</print_am>
  <print_addition_value>yes</print_addition_value>
</request>
EOM;
                \Log::info($xml);

                $context = [
                    'http' => [
                        'method'  => 'POST',
                        'header'  => 'Content-Type: application/xml',
                        'content' => $xml
                    ]
                ];

                $res = file_get_contents($url, false, stream_context_create($context));

                $obj = simplexml_load_string($res);
                $arr = json_decode(json_encode($obj), true);

                \Log::info($arr);
            }
        }

        $mailadmin = new MailCheckoutAdmin($input, $products);
        $body = $mailadmin->render();

        // $url = "https://api.chatwork.com/v2/rooms/"."554267"."/messages";
        $url = "https://api.chatwork.com/v2/rooms/"."255035601"."/messages";

        $ch = curl_init(); // はじめ

        // $headers = array("X-ChatWorkToken: "."abcbabdfcf46a30e523a837c265f3cfb");
        $headers = array("X-ChatWorkToken: "."34da921185ed5db79dff76276228fd2f");

        $adminurl = "https://manage.flower-araki.jp/orders/show/".$ret->id;
        $owner_body = <<<EOM
{$body}

下記URLより確認をお願いします。
{$adminurl}

※ログイン画面が表示される場合は、ログイン後再度上記URLを開いてください。 
EOM;
        $post_data = array('body'=> $owner_body);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        Mail::to(config('mail.from.address'))->send($mailadmin);

        $mailobj = new MailCheckoutUser($input, $products);
        $body = $mailobj->render();
        Mail::to($input['email'])->send($mailobj);

        MailModel::create(['order_id' => $ret->id, 'subject' => $mailobj->subject, 'body' => $body]);

        $request->session()->put('cart', []);
        return redirect('/complete');
    }

    public function purchase_check(PurchaseRequest $request)
    {
        return response()->json(['status' => 'success'], 200);
    }

    public function complete(Request $request)
    {
        return view('complete');
    }

    public function mypage()
    {
        return view('mypage');
    }

    public function term()
    {
        return view('term');
    }

    public function cancel_policy()
    {
        return view('cancel_policy');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function guide()
    {
        return view('guide');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contact_confirm(ContactRequest $request)
    {
        return view('contact_confirm', [
            'input' => $request->all(),
        ]);
    }

    public function contact_store(ContactRequest $request)
    {
        $input = $request->all();
        if ($request->has('back')) {
            return redirect()->route('contact')->withInput($input);
        }

        Mail::to(config('mail.from.address'))->send(new MailContactAdmin(json_decode(json_encode($input))));
        // Mail::to("eil@tikar.jp")->send(new MailContactAdmin(json_decode(json_encode($input))));
        Mail::to($input['email'])->send(new MailContactUser(json_decode(json_encode($input))));

        return redirect()->route('contact_complete')->with([
            'message' => 'お問い合わせを送信しました。',
        ]);
    }

    public function contact_complete()
    {
        return view('contact_complete');
    }

    public function profile()
    {
        $member_id = Auth::user()->id;
        $member = Member::find($member_id);

        return view('profile', compact('member'));
    }

    public function profile_edit()
    {
        $member_id = Auth::user()->id;
        $member = Member::find($member_id);

        return view('profile_edit', compact('member'));
    }

    public function profile_update(ProfileRequest $request)
    {
        $input = $request->all();

        $member_id = Auth::user()->id;
        $member = Member::find($member_id);

        $member->fill($input);
        $member->save();

        return redirect()->route('profile')->with([
            'message' => '会員情報を変更しました。',
        ]);
    }

    public function profile_password()
    {
        $member_id = Auth::user()->id;
        $member = Member::find($member_id);

        return view('profile_password', compact('member'));
    }

    public function profile_password_update(ProfilePasswordRequest $request)
    {
        $input = $request->all();

        $member_id = Auth::user()->id;
        $member = Member::find($member_id);

        $member->password = \Hash::make($input['password']);
        $member->save();

        return redirect()->route('profile')->with([
            'message' => 'パスワードを変更しました。',
        ]);
    }

    public function history()
    {
        $member_id = Auth::user()->id;
        $orders = Order::where('member_id', $member_id)->orderByDesc("created_at")->get();

        return view('history', compact('orders'));
    }

    public function history_detail($order_id)
    {
        $member_id = Auth::user()->id;
        $order = Order::where('member_id', $member_id)->where("id", $order_id)->first();
        if (!$order) {
            return redirect('/');
        }
        $order_details = OrderDetail::where("order_id", $order_id)->get();

        return view('history_detail', compact('order', 'order_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function estimate($order_id)
    {
        $member_id = Auth::user()->id;
        $order = Order::where('member_id', $member_id)->where("id", $order_id)->first();
        if (!$order) {
            return redirect('/');
        }
        $path = $this->make_estimate($order_id);

        $filename = '見積書_'.date('Ymd').'.pdf';
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        readfile($path);

        exit;
    }

    public function reorder(Request $request, $order_id)
    {
        $member_id = Auth::user()->id;
        $order = Order::where('member_id', $member_id)->where("id", $order_id)->first();
        if (!$order) {
            return redirect('/');
        }
        $order_details = OrderDetail::where("order_id", $order_id)->get();

        $cart = $request->session()->get('cart') ?? [];
        $warn = null;
        foreach ($order_details as $detail) {
            if ($detail->product != null) {
                $id = -1;
                foreach ($cart as $key => $value) {
                    if ($value['product_id'] == $detail->product_id && $value['nameplate'] == $detail->nameplate) {
                        $id = $key;
                        break;
                    }
                }
                if ($id == -1) {
                    $cart[] = ['product_id' => $detail->product_id, 'nameplate' => $detail->nameplate, 'quantity' => $detail->quantity];
                } else {
                    $cart[$id]['quantity'] += $detail->quantity;
                }
            } else {
                $warn = "現在扱っていない商品はカートに入りませんでした。";
            }
        }

        $request->session()->put('cart', $cart);
        return redirect('/cart')->with([
            'warning' => $warn,
        ]);
    }

    public function image(Request $request) {
        $path = $request->input("p");

        $image = ImageManagerStatic::make('/home/flx20121018/flower-araki.jp/public_html/data/images/'.$path);
        return $image->resize(480, null, function ($constraint) {
            $constraint->aspectRatio();
        })->response('jpg');
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zeus_enroll(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];
        $amount = $input['amount'];

        $memberid = Auth::user()->id;
        $member = Member::find($memberid);

        if ($token && $member) {
            $email = $member->email;
            $date = date("YmdHis");

            $xml = <<< EOM
<?xml version="1.0" encoding="utf-8"?>
<request service="secure_link_3d" action="enroll">
  <authentication>
    <clientip>2012018157</clientip>
    <key>ae1c5bb21c59fa5aca5c037f90e90e68e5a4a032</key>
  </authentication>
  <token_key>$token</token_key>
  <payment>
    <amount>$amount</amount>
    <count>01</count>
  </payment>
  <user>
    <email language="japanese">$email</email>
  </user>
  <uniq_key>
    <sendpoint>member-$memberid-$date</sendpoint>
  </uniq_key>
  <use_3ds2_flag>1</use_3ds2_flag>
</request>
EOM;

            // $url = "https://secure2-sandbox.cardservice.co.jp/cgi-bin/secure/api.cgi"; //testing
            $url = "https://linkpt.cardservice.co.jp/cgi-bin/secure/api.cgi";  //prod

            $context = [
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/xml',
                    'content' => $xml
                ]
            ];
            
            $res = file_get_contents($url, false, stream_context_create($context));

            $obj = simplexml_load_string($res);
            $arr = json_decode(json_encode($obj), true);
            \Log::info($arr);

            if ($arr['result']['status'] == "success" || $arr['result']['status'] == "outside") {
                return response()->json(['status' => $arr['result']['status'], 'data' => ['xid' => $arr['xid'], 'url' => $arr['iframeUrl'] ?? '']], 200);
            } else {
                return response()->json(['status' => 'error', 'data' => ['code' => $arr['result']['code']]], 400);
            }
        }

        return response()->json(['status' => 'error'], 400);
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zeus_term(Request $request)
    {
        \Log::info($request->all());
    }

    private function make_estimate($order_id)
    {
        $order = Order::find($order_id);
        $order_details = OrderDetail::where("order_id", $order_id)->get();

        $categories = Category::where("site_id", 1)->orderBy('rank')->pluck('category', 'id');

        $template = "/home/flx20121018/flower-araki.jp/public_html/manage/fl-araki-manage/resources/excel/estimate.xlsx";
        $reader = new Reader();
        $book = $reader->load($template);

        $sheet = $book->getSheetByName('見積書');

        $pref = config('const.pref');

        $zip = $order->zip;
        if (strlen($zip) == 7) {
            $zip = substr($zip, 0, 3)."-".substr($zip, 3);
        }

        $count = "/home/flx20121018/flower-araki.jp/public_html/manage/fl-araki-manage/storage/app/estimate.txt";
        $count++;
        file_put_contents(storage_path("app/estimate.txt"), $count);

        $sheet->setCellValue('P1', "No.".sprintf("%07d", $count));
        $sheet->setCellValue('C2', "〒".$zip);
        $sheet->setCellValue('C3', ($pref[$order->pref_id] ?? '').$order->city);
        $sheet->setCellValue('C4', $order->address);

        $name = $order->name;
        if ($order->contact_name) {
            $name .= " ".$order->contact_name;
        }
        $name .= " 様";
        $sheet->setCellValue('C6', $name);
        $sheet->setCellValue('P3', date("Y年n月j日 発行"));

        $ship_address = "";
        $zip = $order->ship_zip;
        if (strlen($zip) == 7) {
            $zip = substr($zip, 0, 3)."-".substr($zip, 3);
        }

        if ($zip) {
            $ship_address = "〒".$zip;
        }
        $ship_address .= " ".($pref[$order->ship_pref_id] ?? '').$order->ship_city;
        $ship_address .= $order->ship_address;

        $name = $order->ship_name;
        $name .= " 様";

        $sheet->setCellValue('B11', $ship_address);
        $sheet->setCellValue('B12', $name);

        $row = 15;
        foreach ($order_details as $key => $detail) {
            $sheet->setCellValue('A'.$row, date("Y/n/j", strtotime($order->created_at)));
            if ($order->delivery_date) {
                $sheet->setCellValue('D'.$row, date("Y/n/j", strtotime($order->delivery_date)));
            }
            $sheet->setCellValue('E'.$row, sprintf("%04d", $order->id).sprintf("%03d", $detail->id));
            $sheet->setCellValue('F'.$row, $detail->name);
            $sheet->setCellValue('J'.$row, $detail->quantity);
            $sheet->setCellValue('K'.$row, $detail->price);
            $sheet->setCellValue('L'.$row++, $detail->quantity * $detail->price);

            if ($detail->nameplate) {
                $nameplate = $detail->nameplate;
                $rows = explode("\r\n", $nameplate);

                $pre = "[名札] ";
                foreach ($rows as $text) {
                    $sheet->setCellValue('F'.$row++, $pre.$text);
                    $pre = "　";
                }
            }
        }
        $sheet->setCellValue('F'.$row, "送料");
        $sheet->setCellValue('I'.$row, 1);
        $sheet->setCellValue('J'.$row, $order->fee);
        $sheet->setCellValue('K'.$row++, $order->fee);

        if ($order->in_fee) {
            $sheet->setCellValue('F'.$row, "手数料");
            $sheet->setCellValue('I'.$row, 1);
            $sheet->setCellValue('J'.$row, $order->in_fee);
            $sheet->setCellValue('K'.$row++, $order->in_fee);
        }
        $row++;
        $sheet->setCellValue('F'.$row++, "【合計】");
        $sheet->setCellValue('F'.$row, "注文合計");
        $sheet->setCellValue('K'.$row++, $order->total);
        $sheet->setCellValue('F'.$row, "内、消費税 (10%)");
        $sheet->setCellValue('K'.$row++, $order->total - ($order->total / 1.1));
        $sheet->setCellValue('F'.$row, "請求金額");
        $sheet->setCellValue('K'.$row++, $order->total);

        $writer = IOFactory::createWriter($book, 'Xlsx');
        // $writer->save('php://output');

        $str = date("YmdHis").rand(100, 999);
        $path = sys_get_temp_dir()."/".$str.".xlsx";
        $writer->save($path);

        $pdf_path = storage_path('app/pdf');
        $cmd = '/home/flx20121018/lib/libreoffice/instdir/program/soffice --headless --convert-to pdf --outdir '.$pdf_path.' '.$path;
        exec($cmd);

        return $pdf_path."/".$str.".pdf";
    }

}
