<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Rubix\ML\Persistable;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Serializers\RBX;

class MachineLearningService
{
    public function __construct(private readonly RBX $modelSerializer)
    {
    }

    public function loadModel(string $name): Persistable
    {
        $persister = new Filesystem($this->getModelFilePath($name));
        return $persister->load()->deserializeWith($this->modelSerializer);
    }

    public function saveModel(string $name, Persistable $model): void
    {
        $persister = new Filesystem($this->getModelFilePath($name));
        $this->modelSerializer->serialize($model)->saveTo($persister);
    }

    protected function getModelFilePath(string $name): string
    {
        return Storage::path($name);
    }
}
