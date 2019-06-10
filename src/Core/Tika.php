<?php

namespace Dam\Core;

use Vaites\ApacheTika\Client;
use Xfind\Core\Utils\DateHelpers;
use Ximdex\Core\FileSystem\MimeTypes;
use Vaites\ApacheTika\Metadata\ImageMetadata;
use Vaites\ApacheTika\Metadata\DocumentMetadata;

class Tika
{
    public const APP = 'app';
    public const SERVER = 'server';

    protected $tika = null;
    protected $file;
    protected $metadatData = [];

    public function __construct($mode = null, $file = '')
    {
        $this->mode($mode);
        $this->setFile($file);
    }


    public function setFile(string $path)
    {
        $this->file = $path;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function mode($mode = null)
    {
        $default = $mode ?? config('xdam.tika.mode');
        $tikaMode = config("xdam.tika.{$default}");
        $this->tika = null;
        if (method_exists($this, $default)) {
            $this->tika = $this->$default($tikaMode);
        }
        return $this->tika;
    }

    public function getMetadata()
    {
        $metadata = $this->tika->getMetadata($this->file);
        $type = $this->type($metadata->meta->{'Content-Type'});
        $result = [];

        if (method_exists($this, $type)) {
            $result = $this->$type($metadata);
        }

        return $result;
    }

    /************************************************PROTECTED METHODS*************************************************/

    protected function app(string $path)
    {
        return Client::make($path);
    }

    protected function server($configs, int $port = null)
    {
        if (is_array($configs)) {
            $host = $configs['host'];
            $port = $configs['port'];
        } elseif (is_string($configs)) {
            $host = $configs;
        }

        return Client::make($host, $port);
    }

    public function type($data)
    {
        $mimeTypes = new MimeTypes();
        $type = $mimeTypes->getGroup($data);
        return $type;
    }

    protected function text(DocumentMetadata $metadata)
    {
        $created_at = DateHelpers::parse($metadata->created);
        $updated_at = DateHelpers::parse($metadata->updated);
        return [
            'language' => $metadata->language ?? null,
            'description' => $metadata->description ?? null,
            'tags' => $metadata->keywords ?? null,
            'author' => $metadata->author ?? null,
            'generator' => $metadata->generator ?? null,
            'pages' => $metadata->pages ?? 0,
            'words' => $metadata->words ?? null,
            'encoding' => $metadata->meta->{'Content-encoding'} ?? null,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }

    protected function image(ImageMetadata $metadata)
    {
        return [
            'color' => $metadata->meta->{'Chroma ColorSpaceType'} ?? null,
            'tranparency' => $metadata->meta->{'Transparency Alpha'} ?? null,
            'num_channels' => $metadata->meta->{'Chroma NumChannels'} ?? null,
            'orientation' => $metadata->meta->{'Dimension ImageOrientation'} ?? null,
        ];
    }

    protected function application(DocumentMetadata $metadata)
    {
        return array_merge(
            $this->text($metadata),
            [
                'protected' => $metadata->meta->protected ?? null
            ]
        );
    }

    /**************************************************MAGIC MEYHODS***************************************************/
}
