<?php

namespace Dam\Core;

use Dam\Core\Settings\DamSettings;
use Illuminate\Support\Facades\Auth;

class Dam
{
    private $url;
    private $token;
    private $endpoints = ['resources' => []];
    private $settings;


    private $form = [];

    public function __construct($url, $token = null)
    {
        $this->url = $url;
        $user = Auth::user();

        if (!is_null($user)) {
            $apiTokenField = config('xdam.user_token_filed');
            $this->token = !is_null($token) ? $token : $user->$apiTokenField;
        }
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

    public function setSettings(DamSettings $settings)
    {
        $this->settings = $settings;
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
        return view('xdam::dam', ['settings' => $this->toArray()])->render();
    }

    public function toArray()
    {
        $schema = [
            'token' => 'token',
            'base_url' => 'url',
            'endpoints' => 'endpoints',
            'settings' => 'settings',
            'form' => 'form'
        ];
        $dam = [];

        foreach ($schema as $key => $value) {
            if (!is_null($this->{$value})) {
                $value = $this->{$value};

                if ($value instanceof DamSettings) {
                    $value = $value->toArray();
                }

                $dam[$key] = $value;
            }
        }
        return $dam;
    }
}