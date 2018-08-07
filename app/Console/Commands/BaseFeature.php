<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BaseFeature extends Command
{
    /**
     * @var string
     */
    protected $signature = 'base:feature {feature}';

    /**
     * @var string
     */
    protected $description = 'Scaffold out files for a new feature';

    /**
     * Scaffold files.
     */
    public function handle()
    {
        $this->controller();
        $this->contract();
    }

    public function controller()
    {
        $this->initializeStub('controller');
        $this->setFeature($this->argument('feature'));
        $this->replaceDescription();
        $this->replaceContract();
        $this->replaceControllerName();
        $this->replaceVariables();
        $this->replaceView();

        Storage::disk('base')->put('App\Http\Controllers\/'.$this->feature.'Controller.php', $this->stub);
    }

    public function contract()
    {
        $this->initializeStub('contract');
        $this->setFeature($this->argument('feature'));
        $this->replaceContractName();
        $this->stub = str_replace('getDummys', 'get'.ucfirst(strtolower($this->feature)).'s', $this->stub);
        $this->stub = str_replace('dummys', strtolower($this->feature).'s', $this->stub);

        Storage::disk('base')->put('Contracts\Repositories\/'.$this->feature.'RepositoryContract.php', $this->stub);
    }

    public function setFeature($feature)
    {
        $this->feature = $feature;
    }

    public function initializeStub($type)
    {
        $this->stub = Storage::disk('base')->get('stubs/'.$type.'.stub');
    }

    public function replaceDescription()
    {
        $this->stub = str_replace('Dummy Template', $this->feature.' Template', $this->stub);
    }

    public function replaceControllerName()
    {
        $this->stub = str_replace('DummyController', $this->feature.'Controller', $this->stub);
    }

    public function replaceContract()
    {
        $this->stub = str_replace('DummyRepositoryContract', $this->feature.'RepositoryContract', $this->stub);
    }

    public function replaceVariables()
    {
        $this->stub = str_replace(
            ['$dummy', '$this->dummy', '$dummys', '$this->getDummys'],
            ['$'.strtolower($this->feature), '$this->'.strtolower($this->feature), '$'.strtolower($this->feature).'s', '$this->get'.ucfirst(strtolower($this->feature)).'s'],
            $this->stub
        );
    }

    public function replaceView()
    {
        $this->stub = str_replace('DummyView', strtolower($this->feature), $this->stub);
    }

    public function replaceContractName()
    {
        $this->stub = str_replace('DummyRepositoryContract', $this->feature.'RepositoryContract', $this->stub);
    }
}
