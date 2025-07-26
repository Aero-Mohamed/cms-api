# API Documentation with Laravel Scribe

This guide explains how to use Laravel Scribe to generate API documentation for the CMS-API project.

## Overview

Laravel Scribe is a tool that automatically generates API documentation for your Laravel applications. It extracts information from your routes, controllers, requests, and models to create comprehensive documentation.

## Installation

To install Laravel Scribe, run the following command:

```bash
composer require knuckleswtf/scribe
```

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="Knuckles\Scribe\ScribeServiceProvider" --tag=scribe-config
```

## Configuration

The configuration file is located at `config/scribe.php`. Here are some important configuration options:

### Basic Configuration

```php
// config/scribe.php

return [
    // The type of documentation output to generate.
    // Options: "static" or "laravel"
    'type' => 'static',
    
    // The output path for the generated documentation.
    // For 'static' type, this is the directory where the documentation will be generated.
    // For 'laravel' type, this is the route prefix where the documentation will be served.
    'output' => [
        'laravel' => [
            'routes' => [
                'path' => 'docs',
                'middleware' => ['web'],
            ],
        ],
        'static' => [
            'output_path' => 'public/docs',
        ],
    ],
    
    // The base URL to be displayed in the docs.
    'base_url' => env('APP_URL', 'http://localhost'),
    
    // The title of the documentation.
    'title' => 'CMS API Documentation',
];
```

### Authentication Configuration

Since our API uses token-based authentication, we need to configure Scribe to include authentication information:

```php
// config/scribe.php

'auth' => [
    'enabled' => true,
    'default' => 'bearer',
    'in' => 'header',
    'name' => 'Authorization',
    'use_value' => 'Bearer {token}',
    'placeholder' => '{token}',
    'extra_info' => 'You can retrieve your token by logging in through the /api/auth/login endpoint.',
],
```

## Generating Documentation

To generate the API documentation, run:

```bash
php artisan scribe:generate
```

This will generate the documentation based on the configuration and annotations in your controllers.

## Viewing Documentation

### Static Documentation

If you've configured Scribe to generate static documentation, you can access it at:

```
http://your-app-url/docs/index.html
```

### Laravel Documentation

If you've configured Scribe to generate Laravel documentation, you can access it at:

```
http://your-app-url/docs
```

## Annotating Controllers

Controllers should be annotated with PHPDoc comments to provide information for the documentation. Here's an example of how controllers are annotated in this project:

```php
/**
 * @group Entity Content Management
 *
 * APIs for managing entity records
 */
class GenericEntityController extends Controller
{
    /**
     * Create Entity Record
     * 
     * Creates a new record for the specified entity type.
     * The validation rules are dynamically generated based on the entity's attributes.
     *
     * @urlParam entitySlug string required The slug of the entity to create a record for. Example: article
     * 
     * @bodyParam title string required The title of the article. Example: My First Article
     * @bodyParam content string required The content of the article. Example: This is the content of my first article
     * @bodyParam published boolean Whether the article is published. Example: true
     * @bodyParam publish_date date The date when the article was published. Example: 2025-07-26
     * @bodyParam author_id integer The ID of the author. Example: 1
     * 
     * @response 201 {
     *   "message": "Record created successfully",
     *   "data": {
     *     "record_id": 1,
     *     "entity_id": 1,
     *     "entity_slug": "article"
     *   }
     * }
     * 
     * @authenticated
     */
    public function create(Request $request, string $entitySlug): JsonResponse
    {
        // Method implementation
    }
}
```

## Common Annotations

Here are some common annotations used in Scribe:

- `@group`: Groups endpoints together in the documentation
- `@urlParam`: Documents URL parameters
- `@queryParam`: Documents query parameters
- `@bodyParam`: Documents request body parameters
- `@response`: Documents response examples
- `@authenticated`: Indicates that the endpoint requires authentication

## Maintaining Documentation

When you make changes to your API, you should:

1. Update the annotations in your controllers
2. Regenerate the documentation using `php artisan scribe:generate`
3. Commit the updated documentation to version control

## Troubleshooting

If you encounter issues with the generated documentation:

1. Check that your annotations follow the correct format
2. Ensure that your routes are properly defined
3. Run `php artisan scribe:generate --verbose` for more detailed output
4. Check the Scribe documentation at https://scribe.knuckles.wtf/laravel for more information

## Additional Resources

- [Official Scribe Documentation](https://scribe.knuckles.wtf/laravel)
- [Laravel Documentation](https://laravel.com/docs)
