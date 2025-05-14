<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of all email templates.
     */
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('email-templates.index', compact('templates'));
    }

    /**
     * Extract dynamic placeholders from email body.
     * Excludes system-wide constants like {COMPANY_LOGO}, {SUBJECT}, etc.
     */
    private function extractPlaceholders($body)
    {
        preg_match_all('/\{(.*?)\}/', $body, $matches);
        $placeholders = array_unique($matches[0]);

        $exclude = ['{COMPANY_LOGO}', '{SUBJECT}', '{CURRENT_YEAR}', '{COMPANY_TITLE}'];

        return array_values(array_filter($placeholders, function ($ph) use ($exclude) {
            return !in_array($ph, $exclude);
        }));
    }

    /**
     * Store a new email template.
     */
    public function store(Request $request)
    {
        $isApi = $request->get('isApi', false);
        if ($request->has('is_encoded') && $request->is_encoded == '1') {
            $decodedContent = base64_decode($request->content);
            $request->merge(['body' => $decodedContent]);
        }
        try {

            $rule = $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'body' => 'nullable'
            ]);
            $rule['workspace_id'] = getWorkspaceId();
            $rule['placeholders'] = $this->extractPlaceholders($rule['body']);

            $email_templates = EmailTemplate::create($rule);

            if ($isApi) {
                return formatApiResponse(
                    false,
                    'Email Template Created Successfully!',
                    [
                        'data' => formatEmailTemplate($email_templates)
                    ]
                );
            } else {
                return response()->json([
                    'error' => false,
                    'message' => 'Email Template Created Successfully!',
                    'email_templates' => $email_templates
                ]);
            }
        } catch (\Exception $e) {
            return formatApiResponse(
                true,
                'Failed to create email template',
                [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ],
                500);
        }
    }

    /**
     * Update an existing email template.
     */
    public function update(Request $request, $id)
    {
        $isApi = $request->get('isApi', false);
        if ($request->has('is_encoded') && $request->is_encoded == '1') {
            $decodedContent = base64_decode($request->content);
            $request->merge(['body' => $decodedContent]);
        }
        try {

            $rule = $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'body' => 'required'
            ]);

            $rule['placeholders'] = $this->extractPlaceholders($rule['body']);

            $email_templates = EmailTemplate::findOrFail($id);
            $email_templates->update($rule);

            if ($isApi) {
                return formatApiResponse(
                    false,
                    'Email Template Created Successfully!',
                    [
                        'data' => formatEmailTemplate($email_templates)
                    ]
                );
            } else {
                return response()->json([
                    'error' => false,
                    'message' => 'Email Template Updated Successfully!',
                    'email_templates' => $email_templates
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update email template', ['error' => $e->getMessage()]);
            return Response::json(['error' => true, 'message' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Delete a specific email template.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $template = EmailTemplate::findOrFail($id);
            $response = DeletionService::delete(EmailTemplate::class, $template->id, 'Email Template');

            // Check if the request expects a JSON response (API)
            if ($request->expectsJson()) {
                return $response;
            }
        } catch (\Exception $e) {
            Log::error('Failed to delete email template', ['error' => $e->getMessage()]);
            return Response::json(['error' => true, 'message' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Delete multiple selected email templates.
     */
    public function destroy_multiple(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:email_templates,id',
            ]);

            $deletedIds = [];

            foreach ($validatedData['ids'] as $id) {
                $template = EmailTemplate::findOrFail($id);
                DeletionService::delete(EmailTemplate::class, $template->id, 'Email Template');
                $deletedIds[] = $id;
            }

            return response()->json([
                'error' => false,
                'message' => 'Email Template(s) deleted successfully.',
                'id' => $deletedIds
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete multiple email templates', ['error' => $e->getMessage()]);
            return Response::json(['error' => true, 'message' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Return paginated, searchable list of email templates.
     */
    public function list()
    {
        try {
            $search = request('search');
            $sort = request('sort', 'id');
            $order = request('order', 'DESC');
            $limit = request('limit', 10);
            $offset = request('offset', 0);

            $query = EmailTemplate::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('subject', 'like', "%$search%")
                        ->orWhere('body', 'like', "%$search%");
                });
            }

            $total = $query->count();
            $canEdit = (isAdminOrHasAllDataAccess() || auth()->user()->can('manage_email_template'));
            $canDelete = (isAdminOrHasAllDataAccess() || auth()->user()->can('delete_email_template'));

            $templates = $query->orderBy($sort, $order)
                ->skip($offset)
                ->take($limit)
                ->get()
                ->map(function ($template) use ($canEdit, $canDelete) {
                    $actions = '';

                    if ($canEdit) {
                        $actions .= '<a href="javascript:void(0);" class="edit-template-btn"
                            data-template=\'' . htmlspecialchars(json_encode($template), ENT_QUOTES, 'UTF-8') . '\'
                            title="' . get_label('update', 'Update') . '">
                            <i class="bx bx-edit mx-1"></i>
                        </a>';
                    }

                    if ($canDelete) {
                        $actions .= '<button type="button"
                            class="btn delete"
                            data-id="' . $template->id . '"
                            data-type="email-templates"
                            title="' . get_label('delete', 'Delete') . '">
                            <i class="bx bx-trash text-danger mx-1"></i>
                        </button>';
                    }

                    return [
                        'id' => $template->id,
                        'name' => $template->name,
                        'subject' => $template->subject,
                        'placeholders' => count($template->placeholders ?? []) > 0
                            ? '<button class="btn btn-sm btn-outline-primary view-placeholders-btn"
                                data-placeholders=\'' . e(json_encode($template->placeholders)) . '\'>
                                View Placeholders
                            </button>'
                            : '',
                        'created_at' => format_date($template->created_at),
                        'updated_at' => format_date($template->updated_at),
                        'actions' => $actions ?: '-',
                    ];
                });

            return response()->json([
                'rows' => $templates,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch email templates list', ['error' => $e->getMessage()]);
            return Response::json(['error' => true, 'message' => 'Something went wrong.'], 500);
        }
    }


    public function apiList(Request $request, $id = null)
    {
        try {
            // Validate query parameters
            $validated = $request->validate([
                'search' => 'nullable|string|max:255',
                'sort' => 'nullable|string|in:id,name,subject,created_at,updated_at',
                'order' => 'nullable|string|in:ASC,DESC',
                'limit' => 'nullable|integer|min:1|max:100',
                'offset' => 'nullable|integer|min:0',
            ]);

            // Validate ID if provided
            if ($id !== null && (!is_numeric($id) || $id <= 0)) {
                throw new \InvalidArgumentException('Invalid email template ID.');
            }

            // Extract parameters with defaults
            $search = $validated['search'] ?? '';
            $sort = $validated['sort'] ?? 'id';
            $order = $validated['order'] ?? 'DESC';
            $limit = $validated['limit'] ?? config('pagination.default_limit', 10);
            $offset = $validated['offset'] ?? 0;

            // Build query
            $query = EmailTemplate::query();

            // Fetch single template if ID is provided
            if ($id) {
                $template = $query->findOrFail($id);
                $data = formatEmailTemplate($template);
                $data['can_edit'] = checkPermission('edit_email_template');
                $data['can_delete'] = checkPermission('delete_email_template');

                Log::info('Single email template fetched via API', [
                    'template_id' => $id,
                    'user_id' => auth()->id() ?? 'guest',
                ]);

                return formatApiResponse(
                    false,
                    'Email template retrieved successfully.',
                    [
                        'total' => 1,
                        'data' => [$data],
                        'permissions' => [
                            'can_edit' => $data['can_edit'],
                            'can_delete' => $data['can_delete'],
                        ],
                    ],
                    200
                );
            }

            // Apply search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . addslashes($search) . '%')
                        ->orWhere('subject', 'like', '%' . addslashes($search) . '%')
                        ->orWhere('body', 'like', '%' . addslashes($search) . '%');
                });
            }

            // Get total count
            $total = $query->count();

            // Check permissions
            $canEdit = checkPermission('edit_email_template');
            $canDelete = checkPermission('delete_email_template');

            // Fetch templates
            $templates = $query->orderBy($sort, $order)
                ->skip($offset)
                ->take($limit)
                ->get()
                ->map(function ($template) use ($canEdit, $canDelete) {
                    $data = formatEmailTemplate($template);
                    $data['can_edit'] = $canEdit;
                    $data['can_delete'] = $canDelete;
                    return $data;
                });

            // Log success
            Log::info('Email template list fetched via API', [
                'search' => $search,
                'sort' => $sort,
                'order' => $order,
                'limit' => $limit,
                'offset' => $offset,
                'total' => $total,
                'user_id' => auth()->id() ?? 'guest',
            ]);

            return formatApiResponse(
                false,
                'Email templates retrieved successfully.',
                [
                    'total' => $total,
                    'data' => $templates->toArray(),
                    'permissions' => [
                        'can_edit' => $canEdit,
                        'can_delete' => $canDelete,
                    ],
                ],
                200
            );
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $message = 'Validation failed: ' . implode(', ', $errors);
            Log::warning('Validation failed in apiList', [
                'errors' => $errors,
                'input' => $request->all(),
            ]);
            return formatApiResponse(true, $message, [], 422);
        } catch (ModelNotFoundException $e) {
            Log::error('Email template not found in apiList', [
                'template_id' => $id,
                'exception' => $e->getMessage(),
            ]);
            return formatApiResponse(true, 'Email template not found.', [], 404);
        } catch (\Exception $e) {
            Log::error('Error in apiList', [
                'template_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return formatApiResponse(
                true,
                config('app.debug') ? $e->getMessage() : 'An error occurred.',
                [],
                500
            );
        }
    }
}
