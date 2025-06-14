<?php

use Knuckles\Scribe\Extracting\Strategies;

return [
    // The HTML <title> for the generated documentation. If this is empty, Scribe will infer it from config('app.name').
    'title' => 'Taskify API Documentation',

    // A short description of your API. Will be included in the docs webpage, Postman collection and OpenAPI spec.
    'description' => 'This is the API documentation for the taskify.',

    // The base URL displayed in the docs. If this is empty, Scribe will use the value of config('app.url') at generation time.
    // If you're using `laravel` type, you can set this to a dynamic string, like '{{ config("app.tenant_url") }}' to get a dynamic base URL.
    // 'base_url' => 'http://localhost:8000/',
    'base_url' => env('APP_URL'),

    'routes' => [
        [
            // Routes that match these conditions will be included in the docs
            'match' => [
                // Match only routes whose paths match this pattern (use * as a wildcard to match any characters). Example: 'users/*'.
                'prefixes' => ['api/*'],

                // Match only routes whose domains match this pattern (use * as a wildcard to match any characters). Example: 'api.*'.
                'domains' => ['*'],

                // [Dingo router only] Match only routes registered under this version. Wildcards are NOT supported.
                'versions' => ['v1'],
            ],

            // Include these routes even if they did not match the rules above.
            'include' => [
                // 'users.index', 'POST /new', '/auth/*'
            ],

            // Exclude these routes even if they matched the rules above.
            'exclude' => [
                // 'GET /health', 'admin.*'
            ],
        ],
    ],

    // The type of documentation output to generate.
    // - "static" will generate a static HTMl page in the /public/docs folder,
    // - "laravel" will generate the documentation as a Blade view, so you can add routing and authentication.
    // - "external_static" and "external_laravel" do the same as above, but generate a basic template,
    // passing the OpenAPI spec as a URL, allowing you to easily use the docs with an external generator
    'type' => 'static',

    // See https://scribe.knuckles.wtf/laravel/reference/config#theme for supported options
    'theme' => 'default',

    'static' => [
        // HTML documentation, assets and Postman collection will be generated to this folder.
        // Source Markdown will still be in resources/docs.
        'output_path' => 'public/docs',
    ],

    'laravel' => [
        // Whether to automatically create a docs endpoint for you to view your generated docs.
        // If this is false, you can still set up routing manually.
        'add_routes' => true,

        // URL path to use for the docs endpoint (if `add_routes` is true).
        // By default, `/docs` opens the HTML page, `/docs.postman` opens the Postman collection, and `/docs.openapi` the OpenAPI spec.
        'docs_url' => '/docs',

        // Directory within `public` in which to store CSS and JS assets.
        // By default, assets are stored in `public/vendor/scribe`.
        // If set, assets will be stored in `public/{{assets_directory}}`
        'assets_directory' => null,

        // Middleware to attach to the docs endpoint (if `add_routes` is true).
        'middleware' => [],
    ],

    'external' => [
        'html_attributes' => []
    ],

    'try_it_out' => [
        // Add a Try It Out button to your endpoints so consumers can test endpoints right from their browser.
        // Don't forget to enable CORS headers for your endpoints.
        'enabled' => true,

        // The base URL for the API tester to use (for example, you can set this to your staging URL).
        // Leave as null to use the current app URL when generating (config("app.url")).
        'base_url' => null,

        // [Laravel Sanctum] Fetch a CSRF token before each request, and add it as an X-XSRF-TOKEN header.
        'use_csrf' => true,

        // The URL to fetch the CSRF token from (if `use_csrf` is true).
        'csrf_url' => '/sanctum/csrf-cookie',
    ],

    // How is your API authenticated? This information will be used in the displayed docs, generated examples and response calls.
    'auth' => [
        // Set this to true if ANY endpoints in your API use authentication.
        'enabled' => true,

        // Set this to true if your API should be authenticated by default. If so, you must also set `enabled` (above) to true.
        // You can then use @unauthenticated or @authenticated on individual endpoints to change their status from the default.
        'default' => false,

        // Where is the auth value meant to be sent in a request?
        // Options: query, body, basic, bearer, header (for custom header)
        'in' => 'bearer',

        // The name of the auth parameter (eg token, key, apiKey) or header (eg Authorization, Api-Key).
        'name' => 'key',

        // The value of the parameter to be used by Scribe to authenticate response calls.
        // This will NOT be included in the generated documentation. If empty, Scribe will use a random value.
        'use_value' => env('SCRIBE_AUTH_KEY'),

        // Placeholder your users will see for the auth parameter in the example requests.
        // Set this to null if you want Scribe to use a random value as placeholder instead.
        'placeholder' => '{YOUR_AUTH_KEY}',

        // Any extra authentication-related info for your users. Markdown and HTML are supported.
        'extra_info' => '',
    ],

    // Text to place in the "Introduction" section, right after the `description`. Markdown and HTML are supported.
    'intro_text' => <<<INTRO
This documentation aims to provide all the information you need to work with our API.

<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>
INTRO,

    // Example requests for each endpoint will be shown in each of these languages.
    // Supported options are: bash, javascript, php, python
    // To add a language of your own, see https://scribe.knuckles.wtf/laravel/advanced/example-requests
    'example_languages' => [
        'bash',
        'javascript',
    ],

    // Generate a Postman collection (v2.1.0) in addition to HTML docs.
    // For 'static' docs, the collection will be generated to public/docs/collection.json.
    // For 'laravel' docs, it will be generated to storage/app/scribe/collection.json.
    // Setting `laravel.add_routes` to true (above) will also add a route for the collection.
    'postman' => [
        'enabled' => true,

        'overrides' => [
            // 'info.version' => '2.0.0',
        ],
    ],

    // Generate an OpenAPI spec (v3.0.1) in addition to docs webpage.
    // For 'static' docs, the collection will be generated to public/docs/openapi.yaml.
    // For 'laravel' docs, it will be generated to storage/app/scribe/openapi.yaml.
    // Setting `laravel.add_routes` to true (above) will also add a route for the spec.
    'openapi' => [
        'enabled' => true,

        'overrides' => [
            // 'info.version' => '2.0.0',
        ],
    ],

    'groups' => [
        // Endpoints which don't have a @group will be placed in this default group.
        'default' => 'Endpoints',

        // By default, Scribe will sort groups alphabetically, and endpoints in the order their routes are defined.
        // You can override this by listing the groups, subgroups and endpoints here in the order you want them.
        // See https://scribe.knuckles.wtf/blog/laravel-v4#easier-sorting and https://scribe.knuckles.wtf/laravel/reference/config#order for details
        'order' => [
            'Endpoints',
            'User Authentication' => [
                'POST /users/signup',
                'POST /users/login'
            ],
            'Profile Management' => [
                'GET /user',
                'POST /users/{id}/photo'
            ],
            'Dashboard Management',
            'Project Management' => [
                'POST /projects/store',
                'GET /projects/{id?}',
                'POST /projects/update',
                'PATCH /projects/{id}/favorite',
                'PATCH /projects/{id}/status',
                'PATCH /projects/{id}/priority',
                'DELETE /projects/destroy/{id}',
                'GET /projects/{id}/status-timelines',
                'GET /projects/{id}/mind-map'
            ],

            'Milestone Management' => [
                'POST /milestones/store',
                'GET /milestones/{id?}',
                'GET /milestones/get/{id?}',
                'POST /milestones/update',
                'DELETE /milestones/destroy/{id}',
            ],
            'Project Comments' => [
                'POST /projects/information/{id}/comments',
                'GET /projects/comments/get/{id}',
                'POST /projects/comments/update',
                'DELETE /projects/comments/destroy',
            ],
            'Project Media' => [
                'POST /projects/upload-media',
                'GET /projects/get-media/{id}',
                'DELETE /projects/delete-media/{id}',
            ],
            'Task Management' => [
                'POST /tasks/store',
                'GET /tasks/{id?}',
                'POST /tasks/update',
                'PATCH /tasks/{id}/status',
                'PATCH /tasks/{id}/priority',
                'DELETE /tasks/destroy/{id}',
                'GET /tasks/{id}/status-timelines'
            ],
            'Task Comments' => [
                'POST /tasks/information/{id}/comments',
                'GET /tasks/comments/get/{id}',
                'POST /tasks/comments/update',
                'DELETE /tasks/comments/destroy',
            ],
            'Task Media' => [
                'POST /tasks/upload-media',
                'GET /tasks/get-media/{id}',
                'DELETE /tasks/delete-media/{id}',
            ],
            'Income vs Expense' =>[
                'GET /reports/income-vs-expense-report-data'
            ],
            'Status Management' => [
                'POST /status/store',
                'GET /status/{id?}',
                'GET /statuses/{id?}',
                'POST /status/update',
                'DELETE /status/destroy/{id}'
            ],
            'Priority Management' => [
                'POST /priorities/store',
                'GET /priorities/{id?}',
                'GET /priorities/get/{id?}',
                'POST /priorities/update',
                'DELETE /priorities/destroy/{id}'
            ],
            'Tag Management',
            'User Management' => [
                'POST /users/store',
                'GET /users/{id?}',
                'POST /users/update',
                'DELETE /users/destroy/{id}'
            ],
            'Client Management'  => [
                'POST /clients/store',
                'GET /clients/{id?}',
                'POST /clients/update',
                'DELETE /clients/destroy/{id}'
            ],
            'Workspace Management' => [
                'POST /workspaces/store',
                'GET /workspaces/{id?}',
                'POST /workspaces/update',
                'DELETE /workspaces/destroy/{id}',
            ],
            'Meeting Management' => [
                'POST /meetings/store',
                'GET /meetings/{id?}',
                'POST /meetings/update',
                'DELETE /meetings/destroy/{id}',
            ],
            'Todo Management' => [
                'POST /todos/store',
                'GET /todos/{id?}',
                'POST /todos/update',
                'PATCH /todos/{id}/status',
                'PATCH /todos/{id}/priority',
                'DELETE /todos/destroy/{id}',
            ],
            'Note Management' => [
                'POST /notes/store',
                'GET /notes/{id?}',
                'POST /notes/update',
                'DELETE /notes/destroy/{id}',
            ],
            'Leave Request Management' => [
                'POST /leave-requests/store',
                'GET /leave-requests/{id?}',
                'POST /leave-requests/update',
                'DELETE /leave-requests/destroy/{id}',
            ],
            'Notification Management',
            'Activity Log Management',
            'Role/Permission Management' =>[
                'POST /roles/store',
                'GET /roles/{id?}',
                'POST /roles/update',
                'DELETE /roles/destroy/{id}',
                'GET /permissions-list',
            ],
            'Setting Management' =>[
                'POST /settings/update',
                'GET /settings/{variable}',
            ],
            'Tag Management' => [
                'POST /tags/store',
                'GET /tags/{id?}',
                'POST /tags/update',
                'DELETE /tags/destroy/{id}',
            ],
            'Expense Management' => [
                'POST /expenses/store',
                'GET /expenses/{id?}',
                'POST /expenses/update',
                'DELETE /expenses/destroy/{id}',
                'POST /expenses/expense-type/store',
                'GET /expenses/expense-type/list/{id?}',
                'POST /expenses/expense-type/update',
                'DELETE /expenses/expense-type/destroy/{id}',
            ],
            'Payment Management' => [
                'POST /payments/store',
                'GET /payments/{id?}',
                'POST /payments/update',
                'DELETE /payments/destroy/{id}'
            ],
            'Payment Method Management' => [
                'POST /payment-methods/store',
                'GET /payment-methods/{id?}',
                'POST /payment-methods/update',
                'DELETE /payment-methods/destroy/{id}'
            ],
            'Tax Management' => [
                'POST /taxes/store',
                'GET /taxes/{id?}',
                'POST /taxes/update',
                'DELETE /taxes/destroy/{id}'
            ],
            'Unit Management' => [
                'POST /units/store',
                'GET /units/{id?}',
                'POST /units/update',
                'DELETE /units/destroy/{id}'
            ],
            'Item Management' => [
                'POST /items/store',
                'GET /items/{id?}',
                'POST /items/update',
                'DELETE /items/destroy/{id}'
            ],
            'Estimate Invoice Management' => [
                'GET /estimates-invoices/{id?}',
                'POST /estimates-invoices/store',
                'POST /estimates-invoices/update',
                'DELETE /estimates-invoices/destroy/{id}',
                'GET /estimates-invoices/pdf/{id}'
            ],
            'Human Resource Management' => [
                // candidate
                'POST /candidate/store',
                'POST /candidate/update/{id}',
                'POST /candidate/update_status',
                'DELETE /candidate/destroy/{id}',
                'GET /candidate/list/{id?}',
                'GET /candidate/{id}/interviews',
                'POST /candidate/{id}/upload-attachment',
                'DELETE /candidate/candidate-media/destroy/{id}',
                'GET /candidate/{id}/attachments/list',
                'GET /candidate/{candidateId}/attachment/{mediaId}/download',
                'GET /candidate/{candidateId}/attachment/{mediaId}/view',
                'GET /candidate/{id}/quick-view',
                // candidate status
                'POST /candidate_status/store',
                'POST /candidate_status/update/{id}',
                'DELETE /candidate_status/destroy/{id}',
                'POST /candidate_status/reorder',
                'GET /candidate_status/list/{id?}',
                // candidate interview
                'POST /interviews/store',
                'POST /interviews/update/{id}',
                'DELETE /interviews/destroy/{id}',
                'GET /interviews/list/{id?}'
            ],
            'Email Management' => [
                // Email Templates
                'POST /email-templates/store',
                'POST /email-templates/update/{id}',
                'DELETE /email-templates/destroy/{id}',
                'GET /email-templates/list/{id?}',

                // Email Sending
                'POST /emails/preview',
                'POST /emails/store',
                'GET /emails/historyList/{id?}',
                'DELETE /emails/history/destroy/{id}',
                'GET /emails/template-data/{id}',
            ],
            'Leads Management' => [
                // Leads
                'POST /leads/store',
                'GET /leads/get/{id?}',
                'GET /leads/list',
                'POST /leads/update/{id}',
                'DELETE /leads/destroy/{id}',
                'POST /leads/stage-change',
                'POST /leads/{lead}/convert-to-client',

                // Lead Follow Ups
                'POST /leads/follow-up/store',
                'GET /leads/follow-up/get/{id}',
                'POST /leads/follow-up/update',
                'DELETE /leads/follow-up/destroy/{id}',

                // View Preference
                'PUT /save-leads-view-preference',
            ],
            'Leads Source Management' => [
                // Lead Sources
                'POST /lead-sources/store',
                'GET /lead-sources/get/{id?}',
                'GET /lead-sources/list',
                'POST /lead-sources/update',
                'DELETE /lead-sources/destroy/{id}'
            ],
            'Leads Stage Management' => [
                // Lead Stages
                'POST /lead-stages/store',
                'GET /lead-stages/get/{id?}',
                'GET /lead-stages/list',
                'POST /lead-stages/update',
                'DELETE /lead-stages/destroy/{id}',
                'POST /lead-stages/reorder',
            ],
            'Custom Field Management' => [
                // Custome Fields
                'POST /custom-fields',
                'PUT /custom-fields/{id}',
                'GET /custom-fields/list/{id?}',
                'DELETE /custom-fields/{id}',
            ],

            'Payslip Management' => [
                // Payslip
                'POST /payslips/store',
                'POST /payslips/update',
                'DELETE /payslips/destroy/{id}',
                'GET /payslips/list',
            ],
            'Contracts Management' => [
                // Contracts
                'POST /contracts/store',
                'POST /contracts/update',
                'GET /contracts/list',
                'GET /contracts/get/{id}',
                'GET /contracts/sign/{id}',
                'POST /contracts/create-sign',
                'DELETE /contracts/destroy/{id}',
                'DELETE /contracts/delete-sign/{id}',
            ],

            'Contract Types Management' => [
                // Contract Types
                'POST /contracts/store-contract-type',
                'POST /contracts/update-contract-type',
                'GET /contracts/contract-types-list',
                'GET /contracts/get-contract-type/{id}',
                'DELETE /contracts/delete-contract-type/{id}',
            ],
            'Deduction Management' => [
                // Deductions
                'POST /deductions/store',
                'POST /deductions/update',
                'GET /deductions/list',
                'GET /deductions/get/{id}',
                'DELETE /deductions/destroy/{id}',
            ],
            'Allowance Management' => [
                // Allowances
                'POST /allowances/store',
                'POST /allowances/update',
                'GET /allowances/list',
                'GET /allowances/get/{id}',
                'DELETE /allowances/destroy/{id}',
            ],

            'Time Tracker Management' => [
                // Time Trackers
                'POST /time-tracker/store',
                'POST /time-tracker/update',
                'GET /time-tracker/list',
                'DELETE /time-tracker/destroy/{id}',
            ],
            'Task List Management' => [
                // Task Lists
                'POST /task-lists/store',
                'POST /task-lists/update',
                'GET /task-lists/get/{id}',
                'DELETE /task-lists/destroy/{id}',
                'GET /task-lists/list',
            ],


        ]

    ],

    // Custom logo path. This will be used as the value of the src attribute for the <img> tag,
    // so make sure it points to an accessible URL or path. Set to false to not use a logo.
    // For example, if your logo is in public/img:
    // - 'logo' => '../img/logo.png' // for `static` type (output folder is public/docs)
    // - 'logo' => 'img/logo.png' // for `laravel` type
    'logo' => false,

    // Customize the "Last updated" value displayed in the docs by specifying tokens and formats.
    // Examples:
    // - {date:F j Y} => March 28, 2022
    // - {git:short} => Short hash of the last Git commit
    // Available tokens are `{date:<format>}` and `{git:<format>}`.
    // The format you pass to `date` will be passed to PHP's `date()` function.
    // The format you pass to `git` can be either "short" or "long".
    'last_updated' => 'Last updated: ' . now()->timezone(config('app.timezone'))->format('F j, Y H:i:s'),

    'examples' => [
        // Set this to any number (eg. 1234) to generate the same example values for parameters on each run,
        'faker_seed' => null,

        // With API resources and transformers, Scribe tries to generate example models to use in your API responses.
        // By default, Scribe will try the model's factory, and if that fails, try fetching the first from the database.
        // You can reorder or remove strategies here.
        'models_source' => ['factoryCreate', 'factoryMake', 'databaseFirst'],
    ],

    // The strategies Scribe will use to extract information about your routes at each stage.
    // If you create or install a custom strategy, add it here.
    'strategies' => [
        'metadata' => [
            Strategies\Metadata\GetFromDocBlocks::class,
            Strategies\Metadata\GetFromMetadataAttributes::class,
        ],
        'urlParameters' => [
            Strategies\UrlParameters\GetFromLaravelAPI::class,
            Strategies\UrlParameters\GetFromUrlParamAttribute::class,
            Strategies\UrlParameters\GetFromUrlParamTag::class,
        ],
        'queryParameters' => [
            Strategies\QueryParameters\GetFromFormRequest::class,
            Strategies\QueryParameters\GetFromInlineValidator::class,
            Strategies\QueryParameters\GetFromQueryParamAttribute::class,
            Strategies\QueryParameters\GetFromQueryParamTag::class,
        ],
        'headers' => [
            Strategies\Headers\GetFromHeaderAttribute::class,
            Strategies\Headers\GetFromHeaderTag::class,
            [
                'override',
                [
                    // 'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'workspace_id' => 1,
                ]
            ]
        ],
        'bodyParameters' => [
            Strategies\BodyParameters\GetFromFormRequest::class,
            Strategies\BodyParameters\GetFromInlineValidator::class,
            Strategies\BodyParameters\GetFromBodyParamAttribute::class,
            Strategies\BodyParameters\GetFromBodyParamTag::class,
        ],
        'responses' => [
            Strategies\Responses\UseResponseAttributes::class,
            Strategies\Responses\UseTransformerTags::class,
            Strategies\Responses\UseApiResourceTags::class,
            Strategies\Responses\UseResponseTag::class,
            Strategies\Responses\UseResponseFileTag::class,
            [
                Strategies\Responses\ResponseCalls::class,
                [
                    'only' => ['GET *'],
                    // Disable debug mode when generating response calls to avoid error stack traces in responses
                    'config' => [
                        'app.debug' => false,
                    ],
                ]
            ]
        ],
        'responseFields' => [
            Strategies\ResponseFields\GetFromResponseFieldAttribute::class,
            Strategies\ResponseFields\GetFromResponseFieldTag::class,
        ],
    ],

    // For response calls, API resource responses and transformer responses,
    // Scribe will try to start database transactions, so no changes are persisted to your database.
    // Tell Scribe which connections should be transacted here. If you only use one db connection, you can leave this as is.
    'database_connections_to_transact' => [config('database.default')],

    'fractal' => [
        // If you are using a custom serializer with league/fractal, you can specify it here.
        'serializer' => null,
    ],

    'routeMatcher' => \Knuckles\Scribe\Matching\RouteMatcher::class,
];
