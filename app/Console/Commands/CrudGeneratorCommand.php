<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\Generators\GenerateRecipes;
use App\Traits\Generators\ModelFieldsArguments;

class CrudGeneratorCommand extends Command
{
    use GenerateRecipes, ModelFieldsArguments;

    protected $signature = 'crud:generate';

    protected $description = 'CRUD generator for eloquent models.';

    public $recipes = [
        \App\Generators\BladeFilesRecipe::class,
        \App\Generators\ControllerRecipe::class,
        \App\Generators\MigrationRecipe::class,
        \App\Generators\ModelRecipe::class,
        \App\Generators\RequestRecipe::class,
        \App\Generators\ResourceRecipe::class,
        \App\Generators\RoutesRecipe::class,
        \App\Generators\VueFilesRecipe::class,
    ];

    public function handle()
    {
        $this->makeRecipes($this->recipes);
        $this->info("\nCRUD for {$this->model()} created successfully.");
    }
}
