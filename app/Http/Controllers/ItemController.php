<?php

namespace App\Http\Controllers;

use App\Model\Item;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ItemController extends Controller
{
    const host = '作成したHerokuアプリのURL';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 変数を用意
        $url = '/api/items/';
        $method = 'GET';

        // Client(接続する為のクラス)を生成
        $client = new Client();
        // 接続失敗時はnullを返すようにする
        try {
            // URLにアクセスした結果を変数$responseに代入
            $response = $client->request($method, self::host . $url);
            // $responseのBodyを取得
            $body = $response->getBody();
            $items = json_decode($body, false);
        } catch (\Exception $e) {
            $items = null;
        }


        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 変数を用意
        $url = '/api/items';
        $method = 'POST';

        // 送信するデータとヘッダーを用意
        $item = [
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'seller'      => $request->seller,
            'email'       => $request->email,
            'image_url'   => $request->image_url,
        ];
        $options = [
            'json' => $item,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        // 接続
        $client = new Client(['http_errors' => false]);
        try {
            $response = $client->request($method, self::host . $url, $options);
            $body = $response->getBody();
            $json = json_decode($body, false);
            // 'errors'が設定されていたらエラーを設定して前画面へ戻る
            if (isset($json->errors)) {
                return back()->withErrors($json->errors);
            };
        } catch (\Exception $e) {
            return back();
        }

        return redirect('/items');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 変数を用意

        // 変数を用意
        $url = '/api/items/' . $id;
        $method = 'GET';

        // Client(接続する為のクラス)を生成
        $client = new Client();
        // 接続失敗時はnullを返すようにする
        try {
            // URLにアクセスした結果を変数$responseに代入
            $response = $client->request($method, self::host . $url);
            // $responseのBodyを取得
            $item = $response->getBody();
            $item = json_decode($item, false);
        } catch (\Exception $e) {
            $item = null;
        }
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 変数を用意
        $url = '/api/items/' . $id;
        $method = 'GET';

        // Client(接続する為のクラス)を生成
        $client = new Client();
        // 接続失敗時はnullを返すようにする
        try {
            // URLにアクセスした結果を変数$responseに代入
            $response = $client->request($method, self::host . $url);
            // $responseのBodyを取得
            $item = $response->getBody();
            $item = json_decode($item, false);
        } catch (\Exception $e) {
            return back();
        }

        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 変数を用意
        $url = '/api/items/' . $id;
        $method = 'PUT';

        // 送信するデータとヘッダーを用意
        $item = [
            'name'          => $request->name,
            'description'   => $request->description,
            'price'         => $request->price,
            'seller'        => $request->seller,
            'email'         => $request->email,
            'image_url'     => $request->image_url,
        ];
        $options = [
            'json' => $item,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        // 接続
        $client = new Client(['http_errors' => false]);
        try {
            // URLにアクセスした結果を変数$responseに代入
            $response = $client->request($method, self::host . $url, $options);
            $body = $response->getBody();
            $json = json_decode($body, false);
            if (isset($json->errors)) {
                return back()->withErrors($json->errors);
            };
        } catch (\Exception $e) {
            return back();
        }

        return redirect('/items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 変数を用意
        $url = '/api/items/' . $id;
        $method = 'DELETE';

        // 接続
        $client = new Client();
        try {
            $client->request($method, self::host . $url);
        } catch (\Exception $e) {
            return back();
        }

        return redirect('/items');
    }
}
