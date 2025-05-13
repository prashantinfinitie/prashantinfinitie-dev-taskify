<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Workspace;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\ScheduledEmail;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DynamicTemplateMail;

class EmailSendController extends Controller
{
    protected $workspace;
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->workspace = Workspace::find(getWorkspaceId());
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }
    public function create(Request $request)
    {
        try {
            $templates = EmailTemplate::all();
            return view('email.send', compact('templates'));
        } catch (Exception $e) {
            Log::error('Error loading email send page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load email send page.');
        }
    }

    public function getTemplateData($id)
    {
        $isApi = request()->get('isApi', false);
        try {
            $template = EmailTemplate::findOrFail($id);
            $defaultPlaceholders = ['CURRENT_YEAR', 'COMPANY_TITLE', 'COMPANY_LOGO', 'SUBJECT'];

            preg_match_all('/{(\w+)}/', $template->body, $matches);
            $placeholders = array_diff($matches[1], $defaultPlaceholders);

            $templateData = formatEmailTemplate($template);
            $templateData['placeholders'] = array_values($placeholders);

            if ($isApi) {
                return formatApiResponse(
                    false,
                    'Template data retrieved successfully!',
                    [
                        'data' => $templateData
                    ]
                );
            } else {
                return response()->json([
                    'subject' => $template->subject,
                    'body' => $template->body,
                    'placeholders' => array_values($placeholders) // Ensure we return an array
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Template not found'
            ], 404);
        }
    }

    public function preview(Request $request)
    {
        $isApi = request()->get('isApi', false) || $request->expectsJson();
        if ($request->has('is_encoded') && $request->is_encoded == '1') {
            $decodedContent = base64_decode($request->content);
            $request->merge(['body' => $decodedContent]);
        }
        try {
            $subject = $request->subject ?? 'No Subject';
            $body = $request->body;
            $placeholders = $request->placeholders ?? [];

            // Replace placeholders
            foreach ($placeholders as $key => $value) {
                $body = str_replace("{{$key}}", $value, $body);
                $subject = str_replace("{{$key}}", $value, $subject);
            }
            // Process attachments
            $attachmentPreview = '';
            if ($request->hasFile('attachments')) {
                $files = $request->file('attachments');
                $attachmentPreview .= "<hr><div><strong>Attachments:</strong><ul>";
                foreach ($files as $file) {
                    $attachmentPreview .= "<li>{$file->getClientOriginalName()}</li>";
                }
                $attachmentPreview .= "</ul></div>";
            }

            $body = preg_replace('/background-color:\s*[^;]+;?/i', '', $body);
            //remove background color
            $html = "

        <div>{$body}</div>
        {$attachmentPreview}
        ";

            if ($isApi) {
                return formatApiResponse(
                    false,
                    'preview generated successfully!',
                    [
                        'data' => $html
                    ]
                );
            } else {
                return response()->json(['preview' => $html]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate preview'], 500);
        }
    }


    public function store(Request $request)
    {
        // $isApi = request()->get('isApi', false);
        try {
            $general_settings = get_settings('general_settings');
            $maxFileSizeBytes = config('media-library.max_file_size');
            $maxFileSizeKb = (int) ($maxFileSizeBytes / 1024);

            // Determine if this is a template email or custom email
            $isTemplateEmail = $request->filled('email_template_id');

            // Common validation rules
            $rules = [
                'emails' => 'required|array|min:1',
                'emails.*' => 'email',
                'attachments' => 'nullable|array',
                'attachments.*' => "file|max:$maxFileSizeKb",
                'scheduled_at' => 'nullable|date|after:now',
            ];

            // Add template-specific or custom-specific validation
            if ($isTemplateEmail) {
                $rules = array_merge($rules, [
                    'email_template_id' => 'required|exists:email_templates,id',
                    'placeholders' => 'required|array',
                ]);
            } else {
                $rules = array_merge($rules, [
                    'subject' => 'required|string|max:255',
                    'body' => 'required|string',
                ]);
            }

            $data = $request->validate($rules);

            // Validate file extensions (BLOCK zip, exe, bat, etc.)
            $blockedExtensions = ['zip', 'exe', 'bat', 'cmd', 'scr', 'com', 'pif', 'jar', 'js', 'php', 'html', 'htm', 'vbs', 'wsf', 'wsh', 'cmd', 'cpl', 'reg', 'dll'];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if (in_array($file->getClientOriginalExtension(), $blockedExtensions)) {
                        return response()->json([
                            'error' => true,
                            'message' => 'Attachments with .zip, .exe and similar file types are not allowed for security reasons.',
                        ]);
                    }
                }
            }

            // Prepare email data for template emails
            if ($isTemplateEmail) {
                $template = EmailTemplate::findOrFail($data['email_template_id']);
                $subject = $template->subject;
                $body = $template->body;

                // Add default placeholders
                $data['placeholders'] = array_merge($data['placeholders'], [
                    'CURRENT_YEAR' => now()->year,
                    'COMPANY_TITLE' => $general_settings['company_title'] ?? 'Company Title',
                    'COMPANY_LOGO' => '<img src="' . asset("/storage/" . (get_settings('general_settings')['full_logo'] ?? 'logos/default_full_logo.png')) . '" width="200px" alt="Company Logo">',

                    'SUBJECT' => $subject,
                ]);

                // Replace placeholders in body
                foreach ($data['placeholders'] as $key => $value) {
                    $body = str_replace(['{' . $key . '}', '{{' . $key . '}}'], $value, $body);
                }
            } else {
                // For custom emails, use provided subject and body
                $subject = $data['subject'];
                $body = $data['body'];
            }

            // Determine if scheduled
            $isScheduled = !empty($data['scheduled_at']);
            $status = $isScheduled ? 'pending' : 'sent';
            $scheduledAtUtc = $isScheduled
                ? Carbon::parse($data['scheduled_at'], config('app.timezone', 'UTC'))->setTimezone('UTC')
                : null;

            $createdEmails = [];
            // Loop through each recipient and send/schedule the email
            foreach ($data['emails'] as $recipient) {
                // Store email record
                $email = ScheduledEmail::create([
                    'user_id' => auth()->id(),
                    'email_template_id' => $isTemplateEmail ? $data['email_template_id'] : null,
                    'workspace_id' => getWorkspaceId(),
                    'to_email' => $recipient,
                    'subject' => $subject,
                    'body' => $body,
                    'placeholders' => $isTemplateEmail ? $data['placeholders'] : null,
                    'scheduled_at' => $scheduledAtUtc,
                    'status' => $status,
                ]);

                // Handle attachments
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $email->addMedia($file)
                            ->sanitizingFileName(function ($fileName) {
                                $baseName = pathinfo($fileName, PATHINFO_FILENAME);
                                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                                $uniqueId = time() . '_' . mt_rand(1000, 9999);
                                return strtolower(str_replace(['#', '/', '\\', ' '], '-', $baseName)) . "-{$uniqueId}.{$extension}";
                            })
                            ->toMediaCollection('email-media');
                    }
                }

                if (!$isScheduled) {
                    try {
                        Mail::to($email->to_email)->send(new DynamicTemplateMail($email));
                        $email->update(['status' => 'sent']);
                    } catch (\Throwable $th) {
                        $email->update(['status' => 'failed']);
                        Log::error('Email sending failed for ' . $recipient . ': ' . $th->getMessage());
                    }
                }

                $createdEmails[] = formatEmailSend($email); // Add formatted email
            }
            $message = $isScheduled ? 'Emails scheduled successfully!' : 'Emails sent successfully.';
            // if ($isApi) {
            //     return formatApiResponse(false, $message, []);
            // } else {
            return response()->json([
                    'error' => false,
                    'message' => $message
                ]);
            // }
        } catch (Exception $e) {
            Log::error('Failed to send or schedule emails: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'An unexpected error occurred while sending/scheduling the emails.',
                'details' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }


    public function history()
    {
        try {
            $emails = isAdminOrHasAllDataAccess() ? $this->workspace->scheduledEmails() : $this->user->scheduledEmails();
            return view('email.history', compact('emails'));
        } catch (Exception $e) {
            dd($e);
            Log::error('Error loading email history: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load email history.');
        }
    }

    public function destroy($id)
    {
        $email = ScheduledEmail::findOrFail($id);

        $response = DeletionService::delete(ScheduledEmail::class, $email->id, 'Scheduled Email');

        return $response;
    }

    public function destroy_multiple(Request $request)
    {
        $validatedData = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:scheduled_emails,id',


        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];

        foreach ($ids as $id) {
            $email = ScheduledEmail::findOrFail($id);
            $deletedIds[] = $id;
            DeletionService::delete(ScheduledEmail::class, $email->id, 'Scheduled Email');
        }

        return response()->json([
            'error' => false,
            'message' => 'Scheduled Email(s) deleted successfully.',
            'id' => $deletedIds,
        ]);
    }

    public function historyList(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'DESC');
        $limit = (int) $request->input('limit', 10);

        $user = auth()->user();

        $query = isAdminOrHasAllDataAccess() ?  $this->workspace->scheduledEmails() :  $this->user->scheduledEmails();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('to_email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $query->orderBy('scheduled_emails.' . $sort, $order);

        $paginated = $query->paginate($limit)
            ->through(function ($email) {
                $canDelete = isAdminOrHasAllDataAccess() || ($email->user_id == auth()->id());
                $status = $email->status == 'pending' ? '<span class="badge bg-warning">Pending</span>' : ($email->status == 'sent' ? '<span class="badge bg-success">Sent</span>' :
                        '<span class="badge bg-danger">Failed</span>');
                $actions = $canDelete ? '<button type="button"
                    class="btn delete"
                    data-id="' . $email->id . '"
                    data-type="emails/history"
                    title="' . get_label('delete', 'Delete') . '">
                    <i class="bx bx-trash text-danger mx-1"></i>
                </button>' : '-';

                return [
                    'id' => $email->id,
                    'to_email' => $email->to_email,
                    'subject' => ucwords($email->subject),
                    'status' => $status,
                    'scheduled_at' => format_date($email->scheduled_at, true),
                    'created_at' => format_date($email->created_at, true),
                    'updated_at' => format_date($email->updated_at, true),
                    'user_name' => formatUserHtml($email->user) ?? 'N/A',
                    'body' => $email->body,
                    'actions' => $actions,
                ];
            });

        return response()->json([
            'total' => $paginated->total(),
            'rows' => $paginated->items(),
        ]);
    }

    public function apihistoryList(Request $request, $id = '')
    {
        try {
            // Validate input parameters
            $request->validate([
                'search' => 'nullable|string|max:255',
                'sort' => 'in:id,to_email,subject,scheduled_at,created_at,updated_at',
                'order' => 'in:ASC,DESC',
                'limit' => 'integer|min:1|max:100',
                'offset' => 'integer|min:0',
            ]);

            // Retrieve input parameters with defaults
            $search = $request->input('search');
            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'DESC');
            $limit = (int) $request->input('limit', 10);
            $offset = (int) $request->input('offset', 0);

            // Determine query based on permissions
            $query = isAdminOrHasAllDataAccess() ? $this->workspace->scheduledEmails() : auth()->user()->scheduledEmails();

            if ($id) {
                // Find email by ID
                $email = $query->where('id', $id)->first();

                if (!$email) {
                    return formatApiResponse(
                        false,
                        'Email Not Found!',
                        ['total' => 0, 'data' => []],
                        404
                    );
                }

                return formatApiResponse(
                    false, // Adjust based on formatApiResponse definition
                    'Email Retrieved Successfully!',
                    ['total' => 1, 'data' => formatEmailSend($email)],
                    200
                );
            }

            // Apply search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('to_email', 'like', '%' . $search . '%')
                        ->orWhere('subject', 'like', '%' . $search . '%');
                });
            }

            // Apply sorting
            $query->orderBy('scheduled_emails.' . $sort, $order);

            // Get total count for pagination
            $total = $query->count();

            // Fetch emails with pagination
            $emails = $query->skip($offset)->take($limit)->get();

            if ($emails->isEmpty()) {
                return formatApiResponse(
                    false,
                    'No email history found',
                    ['total' => 0, 'data' => []],
                    200
                );
            }

            // Transform emails
            $data = $emails->map(function ($email) {
                return formatEmailSend($email);
            })->toArray();

            return formatApiResponse(
                false, // Adjust based on formatApiResponse definition
                'Email History Retrieved Successfully!',
                ['total' => $total, 'data' => $data],
                200
            );
        } catch (\Exception $e) {
            Log::error('Failed to fetch email history!', ['error' => $e->getMessage()]);
            return formatApiResponse(
                true,
                'Something went wrong.',
                [],
                500
            );
        }
    }
}
