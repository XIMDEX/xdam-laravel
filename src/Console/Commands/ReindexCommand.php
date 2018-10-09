<?php

namespace Dam\Console\Commands;

use Dam\Models\Resource;
use Dam\Core\GenerateThumbnail;
use Illuminate\Console\Command;
use Dam\Interfaces\Models\DamPersist;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dam:regenerate 
                            {model : Resource model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-index solr core from model';

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

        $this->message("Get models from $model");

        foreach ($model::cursor() as $resource)
        {
            $data = $resource->attrsToIndex();
            if (is_null($data)) {
                continue;
            }
            
            $this->message("Start to index id:{$data['id']}");
            $dam = $resource->getDamModel();
            $dam->save($data);
        }

    }

    protected function message($message, $type = 'info')
    {
        if ($this->option('verbose')) {
            $this->$type($message);
        }
    }
}
