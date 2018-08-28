<?php

namespace Dam\Core;

use Illuminate\Support\Facades\Auth;

class Dam
{

    private $url;
    private $token;
    private $form;

    public function __construct($url, $token = null)
    {
        $this->url = $url;
        $this->token = !is_null($token) ? $token : Auth::user()->api_token;
    }

    public function setForm(array $form)
    {   
        $this->form = $form;
        return $this;
    }

    public function __toString()
    {
        $dam = [
            'dam_url' => $this->url,
            'dam_token' => $this->token,
            'dam_form' => $this->form
        ];

        return view('xdam::dam', $dam)->render();
    }

}
