<?php

namespace Dam\Console\Commands;

use Dam\Models\Resource;
use Dam\Core\GenerateThumbnail;
use Illuminate\Console\Command;
use Dam\Interfaces\Models\DamPersist;
use Solarium\Exception\HttpException;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dam:regenerate 
                            {model : Resource model}
                            {--id=* : Id of resource}
                            {--from= : first resource to index}
                            {--to= : Last resource to index}';

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
        $modelName = $this->argument('model');

        $query = $modelName::query();

        if ($ids = $this->option('id')) {
            $query->whereIn('id', $ids);
        }

        if ($from = $this->option('from')) {
            $query->where('id', '>=' ,$from);
        }
        
        if ($to = $this->option('to')) {
            $query->where('id', '<=' ,$to);
        }

        $this->message("Get models from {$modelName}");

        foreach ($query->cursor() as $resource)
        {
            $data = $resource->attrsToIndex();
            if (is_null($data)) {
                continue;
            }
            
            $this->message("Start to index id:{$data['id']}");
            $this->save($resource, $data);
        }

    }

    private function save($resource, $data, $retry = 0)
    {
        try {
            $dam = $resource->getDamModel();
            $dam->save($data);
        } catch (HttpException $ex) {
            $message = "Failed to save in solr with message: {$ex->getMessage()}";
            if ($retry < 10) {
                $message .= ", retry in 5 seconds";
                \Log::error($message);
                $this->message($message, 'line');
                sleep(2);
                $this->save($resource, $data, ++$retry);
            } else {
                $message .= ", max retry reached";
                \Log::error($message);
                $this->message($message, 'error');
            }
        }catch (\ErrorException $ex) {
             $message = "Failed to index id:{$data['id']} with message: {$ex->getMessage()}";
             \Log::error($message);
             $this->message($message, 'error');
        }
    }

    protected function message($message, $type = 'info')
    {
        if ($this->option('verbose')) {
            $this->$type($message);
        }
    }
}
