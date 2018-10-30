<?php

namespace Dam\Core;

use Illuminate\Support\Facades\Auth;

class Dam
{
    private $url;
    private $token;
    private $form;
    private $profile;
    private $endpoints = ['resources' => []];
    private $models = [
        'item' => [
            'id' => 'resource_id',
            'title' => 'name',
            'hash' => 'id',
            'size' => '',
            'type' => 'type',
            'image' => 'preview',
            'context' => 'context'
        ],
        'requests' => [
            'get' => 'hash',
            'delete' => 'id',
            'put' => 'id'
        ]
    ];

    public function __construct($url, $token = null)
    {
        $apiTokenField = config('xdam.user_token_filed');
        $this->url = $url;
        $this->token = !is_null($token) ? $token : Auth::user()->$apiTokenField;
    }

    public function setForm(array $form)
    {
        $this->form = $form;
        return $this;
    }

    public function setRequestModel(?string $get = null, ?string $delete = null, ?string $put = null)
    {
        $params = [
            'get' => $get,
            'delete' => $delete,
            'put' => $put,
        ];
        
        foreach ($params as $key => $param) {
            if (!is_null($param)) {
                $this->models['requests'][$key] = $param;
            }
        }

        return $this;
    }

    public function setItemsModel(
        ?string $id = null,
        ?string $title = null,
        ?string $hash = null,
        ?string $size = null,
        ?string $type = null,
        ?string $image = null
    ) {
        $params = [
            'id' => $id,
            'title' => $title,
            'hash' => $hash,
            'size' => $size,
            'type' => $type,
            'image' => $image
        ];
        
        foreach ($params as $key => $param) {
            if (!is_null($param)) {
                $this->models['item'][$key] = $param;
            }
        }

        return $this;
    }

    public function addEndpoints(string $endpoint, ...$routes)
    {
        [$list, $get, $post, $delete] = array_replace([null, null, null, null], $routes);

        if (is_null($get) && is_null($post) && is_null($delete)) {
            $get = $post = $delete = $list;
        } elseif (is_null($post) && is_null($delete)) {
            $post = $delete = $get;
        } elseif (is_null($delete)) {
            $delete = $post;
        }

        $this->endpoints[$endpoint] = compact('list', 'get', 'post', 'delete');
        return $this;
    }


    public function __toString()
    {
        $dam = [
            'dam_url' => $this->url,
            'dam_token' => $this->token,
            'dam_form' => $this->form,
            'dam_endpoints' => $this->endpoints,
            'dam_models' => $this->models
        ];

        return view('xdam::dam', $dam)->render();
    }
}
