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
use App\Mail\MailContactAdmin;
use App\Mail\MailContactUser;

class SiteController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::where('site_id', 1)->limit(18)->get();

        return view('index', compact('products'));
    }

    public function list(Request $request)
    {
        $freeword = $request->input('freeword') ?? "";
        $category_id = $request->input('category_id') ?? null;

        $request->session()->put('front_cond', $request->query());

        $products = Product::when($freeword, function ($query) use ($freeword) {
                return $query->where('name', 'like', '%'.$freeword.'%');
            })
            ->when($category_id, function ($query) use ($category_id) {
                return $query->where('category_id', $category_id);
            })
            ->where('site_id', 1)
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('list', compact('products', 'freeword', 'category_id'));
    }

    public function detail($id)
    {
        $product = Product::where('site_id', 1)->where('id', $id)->first();

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

        $cart = $request->session()->get('cart');

        if (!empty($cart[$input['id']])) {
            $cart[$input['id']] += $input['quantity'];
        } else {
            $cart[$input['id']] = $input['quantity'];
        }

        $request->session()->put('cart', $cart);

        return redirect('/cart');
    }

    public function cart(Request $request)
    {
        $cart = $request->session()->get('cart') ?? [];
        $products = [];

        foreach ($cart as $id => $value) {
            $products[$id] = Product::find($id);
        }

        return view('cart', compact('cart', 'products'));
    }

    public function cart_quantity(Request $request)
    {
        $input = $request->all();
        $cart = $request->session()->get('cart');

        foreach ($input['quantity'] as $id => $q) {
            $cart[$id] = $q;
        }
        foreach ($input['remove'] as $id => $remove) {
            if ($remove == "1") {
                unset($cart[$id]);
            }
        }

        $request->session()->put('cart', $cart);

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
        foreach ($cart as $id => $value) {
            $products[$id] = Product::find($id);
        }

        return view('checkout', compact('cart', 'products', 'member'));
    }

    public function purchase(PurchaseRequest $request)
    {
        $cart = $request->session()->get('cart') ?? [];
        if (!$cart) {
            return view('error', ['message' => "エラーが発生しました。"]);
        }

        $input = $request->all();
        $input['member_id'] = Auth::user()->id;
        $ret = Order::create($input);

        $products = [];

        foreach ($cart as $id => $quantity) {
            $product = Product::find($id);

            $arr = [
                'order_id' => $ret->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
            ];

            $detail = OrderDetail::create($arr);
        }

        $request->session()->put('cart', []);

        return redirect('/complete');
    }

    public function complete()
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

        // Mail::to(config('mail.from.address'))->send(new MailContactAdmin(json_decode(json_encode($input))));
        Mail::to("eil@tikar.jp")->send(new MailContactAdmin(json_decode(json_encode($input))));
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

    public function reorder(Request $request, $order_id)
    {
        $member_id = Auth::user()->id;
        $order = Order::where('member_id', $member_id)->where("id", $order_id)->first();
        if (!$order) {
            return redirect('/');
        }
        $order_details = OrderDetail::where("order_id", $order_id)->get();

        $cart = [];
        $warn = null;
        foreach ($order_details as $detail) {
            if ($detail->product != null) {
                $cart[$detail->product_id] = $detail->quantity;
            } else {
                $warn = "現在扱っていない商品はカートに入りませんでした。";
            }
        }

        $request->session()->put('cart', $cart);
        return redirect('/cart')->with([
            'warning' => $warn,
        ]);
    }
}
