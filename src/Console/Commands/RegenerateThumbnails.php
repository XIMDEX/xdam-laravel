<?php

namespace Dam\Console\Commands;

use Dam\Models\Resource;
use Dam\Core\GenerateThumbnail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RegenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbnail:regenerate 
                            {model : Resource model}
                            {--id=* : Id of resource}
                            {--from= : first resource to index}
                            {--to= : Last resource to index}
                            {--thumbnail=* : Dimensions of the thumbnail (name:width:height). Example: medium:100:200}
                            {--force : Force thumbnails regeneration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate thumbnails for element';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command
     */
    public function handle()
    {
        $modelName = $this->argument('model');
        $thumbnails = $this->getThumbnailSize();

        $force = $this->option('force');

        $query = $modelName::query();

        if ($ids = $this->option('id')) {
            $query->whereIn('id', $ids);
        }

        if ($from = $this->option('from')) {
            $query->where('id', '>=', $from);
        }
        
        if ($to = $this->option('to')) {
            $query->where('id', '<=', $to);
        }

        $this->message("Get models from {$modelName}");

        foreach ($query->cursor() as $resource) {
            $this->message("Start to regenerate id:{$resource->id}");
            if (method_exists($resource, 'getThumbnail')) {
                $thumbs = [];
                foreach ($thumbnails as $target => $size) {
                    $route = $resource->getThumbnail($target);
                    if (! File::exists($route) || $force) {
                        $this->message("Start to regenerate Thumbnail size:{$target} for id:{$resource->id}");
                        $pathFile = $resource->getAbsolutePath() . $resource->getFileName();
                        $fileName = "{$resource->getName()}.{$resource->getThumbExtension()}";
                        GenerateThumbnail::create(
                            $fileName,
                            $pathFile,
                            $resource->getType(),
                            $resource->getMimeType(),
                            $resource->getAbsolutePath(),
                            [$target => $size]
                        );
                    } else {
                        $this->message("Tumbnail size:{$target} for id:{$resource->id} already exists");
                    }
                }
            }
        }
    }

    protected function message($message, $type = 'info')
    {
        if ($this->option('verbose')) {
            $this->$type($message);
        }
    }

    private function getThumbnailSize()
    {
        $result = [];
        $thumbnails = $this->option('thumbnail');
        if (is_array($thumbnails) && count($thumbnails) > 0) {
            foreach ($thumbnails as $thumbnail) {
                $value = explode(":", $thumbnail);
                if (count($value) == 3 && ctype_digit($value[1]) && ctype_digit($value[2])) {
                    $result[$value[0]] = [$value[1], $value[2]];
                }
            }
        }
        return count($result) > 0 ? $result : GenerateThumbnail::$thumbnails;
    }

    private function processString($resource, $value)
    {
        return preg_replace_callback("/{[^{}]+}/", function ($matches) use ($resource) {
            $field = str_replace(["{", "}"], "", $matches[0]);
            return $resource->$field;
        }, $value);
    }
}
