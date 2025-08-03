<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCRSCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:CRS {name : The name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Contract, Repository, and Service for a model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $modelName = Str::studly($name);

        $this->info("Creating CRS for {$modelName}...");

        // Create Contract
        $this->createContract($modelName);
        
        // Create Repository
        $this->createRepository($modelName);
        
        // Create Service
        $this->createService($modelName);

        // Add binding to AppServiceProvider
        $this->addBindingToProvider($modelName);

        $this->info("CRS files created successfully for {$modelName}!");
        $this->info("Repository binding has been added to AppServiceProvider.");
    }

    /**
     * Create Contract interface
     */
    protected function createContract($modelName)
    {
        $contractName = $modelName . 'Contract';
        $path = app_path("Contracts/{$contractName}.php");

        if (file_exists($path)) {
            $this->error("Contract {$contractName} already exists!");
            return;
        }

        $stub = $this->getContractStub($modelName);
        
        file_put_contents($path, $stub);
        $this->info("Created Contract: {$contractName}");
    }

    /**
     * Create Repository class
     */
    protected function createRepository($modelName)
    {
        $repositoryName = $modelName . 'Repository';
        $path = app_path("Repositories/{$repositoryName}.php");

        if (file_exists($path)) {
            $this->error("Repository {$repositoryName} already exists!");
            return;
        }

        $stub = $this->getRepositoryStub($modelName);
        
        file_put_contents($path, $stub);
        $this->info("Created Repository: {$repositoryName}");
    }

    /**
     * Create Service class
     */
    protected function createService($modelName)
    {
        $serviceName = $modelName . 'Service';
        $path = app_path("Services/{$serviceName}.php");

        if (file_exists($path)) {
            $this->error("Service {$serviceName} already exists!");
            return;
        }

        $stub = $this->getServiceStub($modelName);
        
        file_put_contents($path, $stub);
        $this->info("Created Service: {$serviceName}");
    }

    /**
     * Add binding to AppServiceProvider
     */
    protected function addBindingToProvider($modelName)
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');
        
        if (!file_exists($providerPath)) {
            $this->error('AppServiceProvider not found!');
            return;
        }

        $content = file_get_contents($providerPath);
        
        // Check if binding already exists
        $contractClass = $modelName . 'Contract';
        $repositoryClass = $modelName . 'Repository';
        
        if (strpos($content, $contractClass) !== false) {
            $this->warn("Binding for {$contractClass} already exists in AppServiceProvider.");
            return;
        }

        // Add use statements
        $useContract = "use App\\Contracts\\{$contractClass};";
        $useRepository = "use App\\Repositories\\{$repositoryClass};";
        
        // Find the position to add use statements (after existing use statements)
        $lastUsePos = strrpos($content, 'use App\\');
        if ($lastUsePos !== false) {
            $endOfLine = strpos($content, "\n", $lastUsePos);
            $content = substr_replace($content, "\n{$useContract}\n{$useRepository}", $endOfLine, 0);
        }

        // Add binding in register method
        $binding = "        \$this->app->bind({$contractClass}::class, {$repositoryClass}::class);";
        
        // Find the position to add binding (before the closing brace of register method)
        $registerPos = strpos($content, 'public function register(): void');
        if ($registerPos !== false) {
            $bindingPos = strpos($content, '}', strpos($content, '$this->app->bind(VoucherContract::class, VoucherRepository::class);'));
            if ($bindingPos !== false) {
                $content = substr_replace($content, "\n{$binding}\n\n    ", $bindingPos, 0);
            }
        }

        file_put_contents($providerPath, $content);
        $this->info("Added binding to AppServiceProvider.");
    }

    /**
     * Get Contract stub content
     */
    protected function getContractStub($modelName)
    {
        return "<?php

namespace App\Contracts;

interface {$modelName}Contract
{
    public function getAll();
    public function findById(\$id);
    public function create(array \$data);
    public function update(\$id, array \$data);
    public function delete(\$id);
}
";
    }

    /**
     * Get Repository stub content
     */
    protected function getRepositoryStub($modelName)
    {
        $lowerModelName = Str::camel($modelName);
        
        return "<?php

namespace App\Repositories;

use App\Models\\{$modelName};
use App\Contracts\\{$modelName}Contract;

class {$modelName}Repository implements {$modelName}Contract
{
    /**
     * Get all {$lowerModelName}s
     */
    public function getAll()
    {
        return {$modelName}::all();
    }

    /**
     * Find {$lowerModelName} by ID
     */
    public function findById(\$id)
    {
        return {$modelName}::find(\$id);
    }

    /**
     * Create new {$lowerModelName}
     */
    public function create(array \$data)
    {
        return {$modelName}::create(\$data);
    }

    /**
     * Update {$lowerModelName}
     */
    public function update(\$id, array \$data)
    {
        \${$lowerModelName} = {$modelName}::find(\$id);
        if (\${$lowerModelName}) {
            \${$lowerModelName}->update(\$data);
            return \${$lowerModelName};
        }
        return null;
    }

    /**
     * Delete {$lowerModelName}
     */
    public function delete(\$id)
    {
        \${$lowerModelName} = {$modelName}::find(\$id);
        if (\${$lowerModelName}) {
            return \${$lowerModelName}->delete();
        }
        return false;
    }
}
";
    }

    /**
     * Get Service stub content
     */
    protected function getServiceStub($modelName)
    {
        $lowerModelName = Str::camel($modelName);
        
        return "<?php

namespace App\Services;

use App\Contracts\\{$modelName}Contract;

class {$modelName}Service
{
    protected \${$lowerModelName}Repository;

    public function __construct({$modelName}Contract \${$lowerModelName}Repository)
    {
        \$this->{$lowerModelName}Repository = \${$lowerModelName}Repository;
    }

    /**
     * Get all {$lowerModelName}s
     */
    public function getAll{$modelName}s()
    {
        return \$this->{$lowerModelName}Repository->getAll();
    }

    /**
     * Get {$lowerModelName} by ID
     */
    public function get{$modelName}ById(\$id)
    {
        return \$this->{$lowerModelName}Repository->findById(\$id);
    }

    /**
     * Create new {$lowerModelName}
     */
    public function create{$modelName}(array \$data)
    {
        return \$this->{$lowerModelName}Repository->create(\$data);
    }

    /**
     * Update {$lowerModelName}
     */
    public function update{$modelName}(\$id, array \$data)
    {
        return \$this->{$lowerModelName}Repository->update(\$id, \$data);
    }

    /**
     * Delete {$lowerModelName}
     */
    public function delete{$modelName}(\$id)
    {
        return \$this->{$lowerModelName}Repository->delete(\$id);
    }
}
";
    }
}
