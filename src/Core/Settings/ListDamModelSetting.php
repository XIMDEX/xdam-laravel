<?php

namespace Dam\Core\Settings;

class ListDamModelSetting extends Settings
{
    protected $id = 'id';
    protected $title = 'title';
    protected $hash = 'hash';
    protected $size = 'size';
    protected $type = 'type';
    protected $image = 'image';
    protected $context = 'context';

    protected $nullable = [
        'size'
    ];

    public function __construct(
        string $id,
        string $title,
        string $hash,
        string $type,
        string $image,
        string $context,
        ?string $size = null
    ) {
        $this->setId($id);
        $this->setTitle($title);
        $this->setHash($hash);
        $this->setSize($size);
        $this->setType($type);
        $this->setImage($image);
        $this->setContext($context);
    }

    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }
    public function Id(): string
    {
        return $this->id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }
    public function Title(): string
    {
        return $this->title;
    }

    public function setHash(string $hash)
    {
        $this->hash = $hash;
        return $this;
    }
    public function Hash(): string
    {
        return $this->hash;
    }

    public function setSize(?string $size)
    {
        $this->size = $size;
        return $this;
    }
    public function Size(): ?string
    {
        return $this->size;
    }

    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }
    public function Type(): string
    {
        return $this->type;
    }

    public function setImage(string $image)
    {
        $this->image = $image;
        return $this;
    }
    public function Image(): string
    {
        return $this->image;
    }

    public function setContext(string $context)
    {
        $this->context = $context;
        return $this;
    }
    public function Context(): string
    {
        return $this->context;
    }
}