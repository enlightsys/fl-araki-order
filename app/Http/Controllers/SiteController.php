<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\ContactRequest;
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
        $products = Product::limit(18)->get();

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
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('list', compact('products', 'freeword', 'category_id'));
    }

    public function detail($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect('/');
        }

        return view('detail', compact('product'));
    }

    public function put_in(Request $request)
    {
        $input = $request->all();
        $product = Product::find($input['id']);
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
        $input = $request->all();
        $ret = Order::create($input);

        $cart = $request->session()->get('cart') ?? [];
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

}
