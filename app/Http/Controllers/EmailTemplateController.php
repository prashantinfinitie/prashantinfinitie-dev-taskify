<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Services\DeletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

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


    public function apiList(Request $request, $id = '')
    {
        try {
            $search = $request->input('search');
            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'DESC');
            $limit = $request->input('limit', 10);
            $offset = $request->input('offset', 0);

            if ($id) {
                $email_template = EmailTemplate::find($id);

                if (!$email_template) {
                    return formatApiResponse(
                        false,
                        'Email Template Not Found',
                        [
                            'total ' => 0,
                            'data' => [],
                        ],
                        404
                    );
                } else {
                    return formatApiResponse(
                        false,
                        'Email Template retrived successfully!',
                        [
                            'total' => 1,
                            'data' => [formatEmailTemplate($email_template)]
                        ]
                    );
                }
            } else {

                $query = EmailTemplate::query();

                if ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('subject', 'like', '%' . $search . '%')
                            ->orWhere('body', 'like', '%' . $search . '%');
                    });
                }

                $total = $query->count(); // Get total count before applying offset and limit

                $templates = $query->orderBy($sort, $order)
                    ->skip($offset)
                    ->take($limit)
                    ->get();

                if ($templates->isEmpty()) {
                    return formatApiResponse(
                        false,
                        'No email templates found',
                        [
                            'total' => 0,
                            'data' => []
                        ]
                    );
                }

                $data = $templates->map(function ($template) {
                    return formatEmailTemplate($template);
                });

                return formatApiResponse(
                    false,
                    'Email Templates Retrieved Successfully!',
                    [
                        'total' => $total,
                        'data' => $data
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch email templates!', ['error' => $e->getMessage()]);
            return formatApiResponse(true, 'Something went wrong.', [], 500);
        }
    }
}
