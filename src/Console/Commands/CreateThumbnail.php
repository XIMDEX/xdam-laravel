<?php

namespace Dam\Console\Commands;


use Dam\Core\GenerateThumbnail;
use Dam\Interfaces\Models\DamPersist;
use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class CreateThumbnail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbnail:create 
                            {model : Resource model} 
                            {resourceId : The ID of the resource}
                            {storage : Path to save thumbnail} 
                            {--thumbnail=* : Dimensions of the thumbnail (name:width:height). Example: medium:100:200}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create thumbnails from resource';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command
     */
    public function handle()
    {
        $model = $this->argument('model');

        $resourceId = $this->argument('resourceId');
        $storage = $this->argument('storage');
        $thumbnails = $this->getThumbnails();
        $res = $model::find($resourceId);

        if (!($res instanceof DamPersist)) {
            throw new InvalidArgumentException("Model $model not implements " . DamPersist::class);
        }

        $storage = $this->processString($res, $storage);
        $pathFile = $res->getAbsolutePath() . $res->getFileName();
        $fileName = "{$res->getName()}.{$res->getThumbExtension()}";
        GenerateThumbnail::create($fileName, $pathFile, $res->getType(), $res->getMimeType(), $storage, $thumbnails);
    }


    private function processString($resource, $value)
    {
        return preg_replace_callback("/{[^{}]+}/", function ($matches) use ($resource) {
            $field = str_replace(["{", "}"], "", $matches[0]);
            return $resource->$field;
        }, $value);
    }

    private function getThumbnails()
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
}