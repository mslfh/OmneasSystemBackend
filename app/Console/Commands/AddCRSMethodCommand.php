<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddCRSMethodCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:CRS-method {model : The model name} {method : The method name} {--params= : Method parameters (comma separated)} {--return= : Return type documentation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a method to existing Contract, Repository, and Service classes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = Str::studly($this->argument('model'));
        $methodName = $this->argument('method');
        $params = $this->option('params') ? explode(',', $this->option('params')) : [];
        $returnDoc = $this->option('return') ?? 'mixed';

        // Clean up parameters
        $params = array_map('trim', $params);

        $this->info("Adding method '{$methodName}' to {$modelName} CRS classes...");

        // Check if CRS files exist
        if (!$this->crsFilesExist($modelName)) {
            $this->error("CRS files for {$modelName} do not exist. Please run 'php artisan make:CRS {$modelName}' first.");
            return 1;
        }

        // Add method to Contract
        $this->addMethodToContract($modelName, $methodName, $params, $returnDoc);

        // Add method to Repository
        $this->addMethodToRepository($modelName, $methodName, $params, $returnDoc);

        // Add method to Service
        $this->addMethodToService($modelName, $methodName, $params, $returnDoc);

        $this->info("Method '{$methodName}' added successfully to {$modelName} CRS classes!");
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
     * Add method to Contract interface
     */
    protected function addMethodToContract($modelName, $methodName, $params, $returnDoc)
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
        $this->info("Added method to Contract: {$modelName}Contract");
    }

    /**
     * Add method to Repository class
     */
    protected function addMethodToRepository($modelName, $methodName, $params, $returnDoc)
    {
        $repositoryPath = app_path("Repositories/{$modelName}Repository.php");
        $content = file_get_contents($repositoryPath);

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
        // TODO: Implement {$methodName} logic
        // Example: return {$modelName}::where('field', \$value)->get();
        throw new \\Exception('Method {$methodName} not implemented yet');
    }";

        // Find the last closing brace and add method before it
        $lastBracePos = strrpos($content, '}');
        $newContent = substr($content, 0, $lastBracePos) . $methodImplementation . "\n" . substr($content, $lastBracePos);

        file_put_contents($repositoryPath, $newContent);
        $this->info("Added method to Repository: {$modelName}Repository");
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
        $this->info("Added method to Service: {$modelName}Service");
    }

    /**
     * Build parameter signature for method
     */
    protected function buildParameterSignature($params)
    {
        if (empty($params)) {
            return '';
        }

        $signature = [];
        foreach ($params as $param) {
            // Handle typed parameters (e.g., "string $name", "array $data", "int $id")
            if (strpos($param, ' ') !== false) {
                $signature[] = $param;
            } else {
                // Default to no type hint
                $signature[] = '$' . ltrim($param, '$');
            }
        }

        return implode(', ', $signature);
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
