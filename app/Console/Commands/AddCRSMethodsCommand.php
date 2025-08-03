<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddCRSMethodsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:CRS-methods {model : The model name} {--preset=basic : Preset methods to add (basic, search, status, pagination)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add multiple predefined methods to existing Contract, Repository, and Service classes';

    /**
     * Predefined method sets
     */
    protected $presets = [
        'basic' => [
            [
                'name' => 'findByField',
                'params' => ['string $field', 'mixed $value'],
                'return' => 'Collection of records matching field value',
                'repositoryLogic' => 'return {{MODEL}}::where($field, $value)->get();'
            ],
            [
                'name' => 'exists',
                'params' => ['int $id'],
                'return' => 'bool',
                'repositoryLogic' => 'return {{MODEL}}::where(\'id\', $id)->exists();'
            ],
            [
                'name' => 'count',
                'params' => [],
                'return' => 'int',
                'repositoryLogic' => 'return {{MODEL}}::count();'
            ]
        ],
        'search' => [
            [
                'name' => 'search',
                'params' => ['string $keyword'],
                'return' => 'Collection of search results',
                'repositoryLogic' => '// TODO: Implement search logic based on your model fields\n        // return {{MODEL}}::where(\'name\', \'like\', "%$keyword%")->get();'
            ],
            [
                'name' => 'findByKeyword',
                'params' => ['string $keyword', 'array $fields'],
                'return' => 'Collection of results',
                'repositoryLogic' => '$query = {{MODEL}}::query();\n        foreach ($fields as $field) {\n            $query->orWhere($field, \'like\', "%$keyword%");\n        }\n        return $query->get();'
            ]
        ],
        'status' => [
            [
                'name' => 'findByStatus',
                'params' => ['string $status'],
                'return' => 'Collection of records with specified status',
                'repositoryLogic' => 'return {{MODEL}}::where(\'status\', $status)->get();'
            ],
            [
                'name' => 'activate',
                'params' => ['int $id'],
                'return' => 'bool',
                'repositoryLogic' => 'return {{MODEL}}::where(\'id\', $id)->update([\'status\' => \'active\']);'
            ],
            [
                'name' => 'deactivate',
                'params' => ['int $id'],
                'return' => 'bool',
                'repositoryLogic' => 'return {{MODEL}}::where(\'id\', $id)->update([\'status\' => \'inactive\']);'
            ]
        ],
        'pagination' => [
            [
                'name' => 'getPaginated',
                'params' => ['int $perPage', 'array $filters'],
                'return' => 'Paginated results',
                'repositoryLogic' => '$query = {{MODEL}}::query();\n        \n        // Apply filters\n        foreach ($filters as $field => $value) {\n            if (!empty($value)) {\n                $query->where($field, \'like\', "%$value%");\n            }\n        }\n        \n        return $query->paginate($perPage);'
            ],
            [
                'name' => 'getWithLimit',
                'params' => ['int $limit', 'int $offset'],
                'return' => 'Collection with limit and offset',
                'repositoryLogic' => 'return {{MODEL}}::skip($offset)->take($limit)->get();'
            ]
        ]
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = Str::studly($this->argument('model'));
        $preset = $this->option('preset');

        if (!isset($this->presets[$preset])) {
            $this->error("Preset '{$preset}' not found. Available presets: " . implode(', ', array_keys($this->presets)));
            return 1;
        }

        // Check if CRS files exist
        if (!$this->crsFilesExist($modelName)) {
            $this->error("CRS files for {$modelName} do not exist. Please run 'php artisan make:CRS {$modelName}' first.");
            return 1;
        }

        $methods = $this->presets[$preset];
        $this->info("Adding {$preset} methods to {$modelName} CRS classes...");

        foreach ($methods as $method) {
            $this->addMethodToCRS($modelName, $method);
        }

        $this->info("Successfully added " . count($methods) . " methods from '{$preset}' preset to {$modelName} CRS classes!");
    }

    /**
     * Check if CRS files exist
     */
    protected function crsFilesExist($modelName)
    {
        $contractPath = app_path("Contracts/{$modelName}Contract.php");
        $repositoryPath = app_path("Repositories/{$modelName}Repository.php");
        $servicePath = app_path("Services/{$modelName}Service.php");

        return file_exists($contractPath) && file_exists($repositoryPath) && file_exists($servicePath);
    }

    /**
     * Add method to all CRS files
     */
    protected function addMethodToCRS($modelName, $method)
    {
        $methodName = $method['name'];
        $params = $method['params'];
        $returnDoc = $method['return'];
        $repositoryLogic = str_replace('{{MODEL}}', $modelName, $method['repositoryLogic']);

        // Add to Contract
        $this->addMethodToContract($modelName, $methodName, $params);

        // Add to Repository
        $this->addMethodToRepository($modelName, $methodName, $params, $returnDoc, $repositoryLogic);

        // Add to Service
        $this->addMethodToService($modelName, $methodName, $params, $returnDoc);

        $this->line("Added method: {$methodName}");
    }

    /**
     * Add method to Contract interface
     */
    protected function addMethodToContract($modelName, $methodName, $params)
    {
        $contractPath = app_path("Contracts/{$modelName}Contract.php");
        $content = file_get_contents($contractPath);

        // Build method signature
        $paramSignature = $this->buildParameterSignature($params);

        // Build method declaration
        $methodDeclaration = "    public function {$methodName}({$paramSignature});";

        // Find the last closing brace and add method before it
        $lastBracePos = strrpos($content, '}');
        $newContent = substr($content, 0, $lastBracePos) . $methodDeclaration . "\n" . substr($content, $lastBracePos);

        file_put_contents($contractPath, $newContent);
    }

    /**
     * Add method to Repository class
     */
    protected function addMethodToRepository($modelName, $methodName, $params, $returnDoc, $repositoryLogic)
    {
        $repositoryPath = app_path("Repositories/{$modelName}Repository.php");
        $content = file_get_contents($repositoryPath);

        // Build method signature
        $paramSignature = $this->buildParameterSignature($params);

        // Build method implementation
        $methodImplementation = "
    /**
     * {$returnDoc}
     */
    public function {$methodName}({$paramSignature})
    {
        {$repositoryLogic}
    }";

        // Find the last closing brace and add method before it
        $lastBracePos = strrpos($content, '}');
        $newContent = substr($content, 0, $lastBracePos) . $methodImplementation . "\n" . substr($content, $lastBracePos);

        file_put_contents($repositoryPath, $newContent);
    }

    /**
     * Add method to Service class
     */
    protected function addMethodToService($modelName, $methodName, $params, $returnDoc)
    {
        $servicePath = app_path("Services/{$modelName}Service.php");
        $content = file_get_contents($servicePath);

        // Build method signature
        $paramSignature = $this->buildParameterSignature($params);
        $lowerModelName = Str::camel($modelName);

        // Build method implementation
        $methodImplementation = "
    /**
     * {$returnDoc}
     */
    public function {$methodName}({$paramSignature})
    {
        return \$this->{$lowerModelName}Repository->{$methodName}(" . $this->buildMethodCall($params) . ");
    }";

        // Find the last closing brace and add method before it
        $lastBracePos = strrpos($content, '}');
        $newContent = substr($content, 0, $lastBracePos) . $methodImplementation . "\n" . substr($content, $lastBracePos);

        file_put_contents($servicePath, $newContent);
    }

    /**
     * Build parameter signature for method
     */
    protected function buildParameterSignature($params)
    {
        if (empty($params)) {
            return '';
        }

        return implode(', ', $params);
    }

    /**
     * Build method call parameters
     */
    protected function buildMethodCall($params)
    {
        if (empty($params)) {
            return '';
        }

        $callParams = [];
        foreach ($params as $param) {
            // Extract variable name from typed parameters
            if (strpos($param, ' ') !== false) {
                $parts = explode(' ', $param);
                $varName = end($parts);
            } else {
                $varName = '$' . ltrim($param, '$');
            }
            $callParams[] = $varName;
        }

        return implode(', ', $callParams);
    }
}
