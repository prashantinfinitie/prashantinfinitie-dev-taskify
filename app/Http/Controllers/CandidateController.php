<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Models\CandidateStatus;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CandidateController extends Controller
{
  public function index()
  {
    $candidates = Candidate::with('status')->get(); //eager load status
    $statuses = CandidateStatus::orderBy('order')->get();

    return view('candidate.index', compact('statuses', 'candidates'));
  }

  public function show($id)
  {

    $users = User::all();
    $candidates = Candidate::all();
    $candidate = Candidate::with('status', 'interviews.interviewer')->findOrFail($id);
    $statuses = CandidateStatus::orderBy('order')->get();
    return view('candidate.show', compact('candidate', 'statuses', 'users', 'candidates'));
  }

  public function getCandidate($id)
  {

    try {
      $isApi = request()->get('isApi', false);

      if (!is_numeric($id) || $id <= 0) {
        throw new \InvalidArgumentException('Candidate ID not found.');
      }

      $candidate = Candidate::with(['status', 'interviews.interviewer', 'media'])->findOrFail($id);

      $responseData = response()->json([
        'candidate' => [
          'id' => $candidate->id,
          'name' => $candidate->name,
          'email' => $candidate->email,
          'phone' => $candidate->phone,
          'position' => $candidate->position,
          'source' => $candidate->source,
          'status' => $candidate->status ? $candidate->status->name : '-',
          'created_at' => format_date($candidate->created_at),
          'avatar' => $candidate->getFirstMediaUrl('candidate-media') ?: asset('/photos/default-avatar.png'),
        ],
        'attachments' => $candidate->getMedia('candidate-media')->map(function ($media) {
          $isPublicDisk = $media->disk == 'public';
          $fileUrl = $isPublicDisk
            ? asset('storage/candidate-media/' . $media->file_name)
            : $media->getFullUrl();

          $fileExtension = pathinfo($fileUrl, PATHINFO_EXTENSION);
          $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
          $isImage = in_array(strtolower($fileExtension), $imageExtensions);

          return [
            'id' => $media->id,
            'name' => $media->file_name,
            'type' => $media->mime_type,
            'size' => round($media->size / 1024, 2) . ' KB',
            'created_at' => format_date($media->created_at),
            'url' => $fileUrl,
            'is_image' => $isImage,
          ];
        }),
        'interviews' => $candidate->interviews->map(function ($interview) {
          return [
            'id' => $interview->id,
            'candidate_name' => $interview->candidate->name,
            'interviewer' => $interview->interviewer->first_name . ' ' . $interview->interviewer->last_name,
            'round' => $interview->round,
            'scheduled_at' => $interview->scheduled_at,
            'status' => $interview->status,
            'location' => $interview->location,
            'mode' => $interview->mode,
            'created_at' => format_date($interview->created_at),
            'updated_at' => format_date($interview->updated_at),
          ];
        }),
      ]);

      if ($isApi) {
        return formatApiResponse(
          false,
          'Candidate details retrieved successfully!',
          [
            'data' => $responseData
          ]
        );
      } else {
        return $responseData;
      }
    } catch (\Exception $e) {

      $message = config('app.debug') ? $e->getMessage() : 'An error occurred';

      return $isApi ? formatApiResponse(true, $message, [], 500) : response()->json(['error' => true, 'message' => $message], 500);
    }
  }

  public function getInterviewDetails($id)
  {

    if (!is_numeric($id)) {
      return response()->json(
        [
          "error" => true,
          "message" => "Invalid candidate ID!"
        ],
        400

      );
    }

    $isApi = request()->get('isApi', false);

    try {

      $candidate = Candidate::with(['interviews.interviewer'])->findOrFail($id);
      $html = view('partials.interview-details', compact('candidate'))->render();


      // response for api
      if ($isApi) {
        return formatApiResponse(
          false,
          'Interview details retrieved successfully!',
          [
            'data' => [
              'candidate' => $candidate,
              'html' => $html
            ]
          ]
        );
      }
      return response()->json([
        'error' => false,
        'candidate' => $candidate,
        'html' => $html
      ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      $response = ['error' => true, 'message' => 'Candidate not found!'];
      return $isApi ? formatApiResponse(true, 'Candidate not found!', [], 404) : response()->json($response, 404);
    } catch (\Exception $e) {
      $response = ['error' => true, 'message' => 'An error occurred!', 500];
      return $isApi ? formatApiResponse(true, 'An error occurred!', [], 500) : response()->json($response, 500);
    }
  }

  public function uploadAttachment(Request $request, $id)
  {

    // Determine if the request is coming from api
    $isApi = $request->get('isApi', false);

    try {

      // Validate candidate id
      if (!is_numeric($id) || $id <= 0) {
        throw new \InvalidArgumentException('Invalid candidate ID!');
      }

      $maxFileSizeBytes = config('media-library.max_file_size');
      $maxFileSizeKb = (int) ($maxFileSizeBytes / 1024);

      $request->validate([
        'attachments.*' => "required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:$maxFileSizeKb",
      ]);

      $candidate = Candidate::findOrFail($id);
      $uploadedFiles = [];

      if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
          $mediaItem = $candidate->addMedia($file)
            ->sanitizingFileName(function ($fileName) {
              $sanitizedFileName = strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
              $uniqueId = time() . '_' . mt_rand(1000, 9999);
              $extension = pathinfo($sanitizedFileName, PATHINFO_EXTENSION);
              $baseName = pathinfo($sanitizedFileName, PATHINFO_FILENAME);
              return "{$baseName}-{$uniqueId}.{$extension}";
            })
            ->toMediaCollection('candidate-media');

          $uploadedFiles[] = $mediaItem;
        }
      }

      if ($isApi) {
        return formatApiResponse(
          false,
          'Files uploaded successfully!',
          [
            'data' => $uploadedFiles
          ]
        );
      }

      return response()->json([
        'error' => false,
        'message' => 'Files uploaded successfully!',
        'files' => $uploadedFiles
      ]);
    } catch (ModelNotFoundException $e) {

      Log::error('Candidate not found.', [
        'candidate_id' => $id,
        'exception' => $e->getMessage()
      ]);

      $response = ['error' => true, 'message' => 'Candidate not found'];
      return $isApi ? formatApiResponse(true, 'Candidate not found', [], 404) : response()->json($response, 404);
    } catch (ValidationException $e) {

      $errors = $e->validator->errors->all();
      $message = 'Validation failed:' . implode(',', $errors);

      Log::warning('Validation failed in uploadAttachments', [
        'candidate_id' => $id,
        'errors' => $errors
      ]);

      return $isApi ? formatApiResponse(true, $message, [], 422) : response()->json(['error' => true, 'message' => $message], 422);
    } catch (\Exception $e) {
      Log::error('An error occurred', [
        'candidate_id' => $id,
        'exception' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      return $isApi ? formatApiResponse(true, 'An error occurred', [], 500) : response()->json(['error' => true, 'message' => 'An error occurred'], 500);
    }
  }

  public function attachmentsList($candidateId)
  {
    $search = request('search');
    $sort = request('sort', 'id');
    $order = request('order', 'desc');
    $limit = request('limit', 10);
    $offset = request('offset', 0);

    $candidate = Candidate::findOrFail($candidateId);
    $mediaCollection = $candidate->getMedia('candidate-media');

    if ($search) {
      $mediaCollection = $mediaCollection->filter(function ($media) use ($search) {
        return str_contains(strtolower($media->name), strtolower($search)) ||
          str_contains(strtolower($media->mime_type), strtolower($search));
      });
    }

    $total = $mediaCollection->count();
    $mediaCollection = ($order === 'desc')
      ? $mediaCollection->sortByDesc($sort)
      : $mediaCollection->sortBy($sort);
    $mediaItems = $mediaCollection->slice($offset, $limit);
    $canDelete = isAdminOrHasAllDataAccess();

    $rows = $mediaItems->map(function ($media) use ($canDelete, $candidate) {
      $actions = '';

      $isPublicDisk = $media->disk == 'public' ? 1 : 0;
      $fileUrl = $isPublicDisk
        ? asset('storage/candidate-media/' . $media->file_name)
        : $media->getFullUrl();
      $fileExtension = pathinfo($fileUrl, PATHINFO_EXTENSION);
      $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
      $isImage = in_array(strtolower($fileExtension), $imageExtensions);

      if ($canDelete) {
        $actions .= '<button class="btn delete"
                data-id="' . $media->id . '"
                data-type="candidate/candidate-media"
                title="' . get_label('delete', 'Delete') . '">
                <i class="bx bx-trash text-danger mx-1"></i>
            </button>';
      }

      $actions .= '<button class="btn download"
            onclick="window.location.href=\'' . route('candidate.attachment.download', ['mediaId' => $media->id, 'candidateId' => $candidate->id]) . '\'"
            title="' . get_label('download', 'Download') . '">
            <i class="bx bx-download text-primary mx-1"></i>
        </button>';

      if (in_array($media->mime_type, [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png',
      ])) {
        $viewUrl = $isImage ? $fileUrl : route('candidate.attachment.view', ['mediaId' => $media->id, 'candidateId' => $candidate->id]);
        $viewAttributes = $isImage
          ? 'data-lightbox="candidate-media" data-title="' . $media->name . '"'
          : 'data-view-url="' . $viewUrl . '"';
        $viewClass = $isImage ? 'view-lightbox' : 'view-in-lightbox';
        $viewOnclick = $isImage ? '' : 'onclick="window.open(\'' . $viewUrl . '\', \'_blank\')"';

        $actions .= '<a href="' . $viewUrl . '" ' . $viewAttributes . ' class="btn ' . $viewClass . '"
                ' . $viewOnclick . '
                title="' . get_label('view', 'View') . '">
                <i class="bx bx-show text-info mx-1"></i>
            </a>';
      }

      return [
        'id' => $media->id,
        'name' => $media->name,
        'type' => $media->mime_type,
        'size' => round($media->size / 1024, 2) . ' KB',
        'created_at' => format_date($media->created_at),
        'actions' => $actions ?: '-',
      ];
    })->values();

    return response()->json([
      'total' => $total,
      'rows' => $rows,
    ]);
  }


  public function apiAttachmentsList(Request $request, $id = null)
  {
    try {
      // Validate query parameters
      $validated = $request->validate([
        'search' => 'nullable|string|max:255',
        'sort' => 'nullable|string|in:id,name,mime_type,size,created_at',
        'order' => 'nullable|string|in:ASC,DESC',
        'limit' => 'nullable|integer|min:1|max:100',
        'offset' => 'nullable|integer|min:0',
      ]);

      // Validate candidate ID
      if ($id === null || !is_numeric($id) || $id <= 0) {
        throw new \InvalidArgumentException('Invalid or missing candidate ID.');
      }

      // Extract parameters with defaults
      $search = $validated['search'] ?? '';
      $sort = $validated['sort'] ?? 'id';
      $order = $validated['order'] ?? 'DESC';
      $limit = $validated['limit'] ?? config('pagination.default_limit', 10);
      $offset = $validated['offset'] ?? 0;

      // Fetch candidate
      $candidate = Candidate::findOrFail($id);

      // Get media collection
      $mediaCollection = $candidate->getMedia('candidate-media');

      // Apply search filter (in-memory, as Media Library doesn't support direct query filtering)
      if ($search) {
        $mediaCollection = $mediaCollection->filter(function ($media) use ($search) {
          return str_contains(strtolower($media->name), strtolower($search)) ||
            str_contains(strtolower($media->mime_type), strtolower($search));
        });
      }

      // Get total count
      $total = $mediaCollection->count();

      // Apply sorting (in-memory)
      $mediaCollection = ($order === 'DESC')
        ? $mediaCollection->sortByDesc($sort)
        : $mediaCollection->sortBy($sort);

      // Apply pagination (in-memory)
      $mediaItems = $mediaCollection->slice($offset, $limit);

      // Check permissions
      $canDelete = isAdminOrHasAllDataAccess();

      // Format attachments
      $data = $mediaItems->map(function ($media) use ($canDelete, $candidate) {
        $isPublicDisk = $media->disk === 'public';
        $fileUrl = $isPublicDisk
          ? asset('storage/candidate-media/' . $media->file_name)
          : $media->getFullUrl();
        $fileExtension = pathinfo($fileUrl, PATHINFO_EXTENSION);
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $isImage = in_array(strtolower($fileExtension), $imageExtensions);

        $viewableMimeTypes = [
          'application/pdf',
          'application/msword',
          'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          'image/jpeg',
          'image/png',
        ];
        $viewUrl = in_array($media->mime_type, $viewableMimeTypes)
          ? ($isImage ? $fileUrl : route('candidate.attachment.view', ['mediaId' => $media->id, 'candidateId' => $candidate->id]))
          : null;

        return [
          'id' => $media->id,
          'name' => $media->name,
          'mime_type' => $media->mime_type,
          'size' => round($media->size / 1024, 2) . ' KB',
          'created_at' => format_date($media->created_at),
          'download_url' => route('candidate.attachment.download', ['mediaId' => $media->id, 'candidateId' => $candidate->id]),
          'view_url' => $viewUrl,
          'can_delete' => $canDelete,
        ];
      })->values();

      // Log success
      Log::info('Candidate attachments list fetched via API', [
        'candidate_id' => $id,
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
        'Attachments retrieved successfully.',
        [
          'total' => $total,
          'data' => $data->toArray(),
          'permissions' => [
            'can_delete' => $canDelete,
          ],
        ],
        200
      );
    } catch (ValidationException $e) {
      $errors = $e->validator->errors()->all();
      $message = 'Validation failed: ' . implode(', ', $errors);
      Log::warning('Validation failed in apiAttachmentsList', [
        'candidate_id' => $id,
        'errors' => $errors,
        'input' => $request->all(),
      ]);
      return formatApiResponse(true, $message, [], 422);
    } catch (ModelNotFoundException $e) {
      Log::error('Candidate not found in apiAttachmentsList', [
        'candidate_id' => $id,
        'exception' => $e->getMessage(),
      ]);
      return formatApiResponse(true, 'Candidate not found.', [], 404);
    } catch (\Exception $e) {
      Log::error('Error in apiAttachmentsList', [
        'candidate_id' => $id,
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


  public function downloadAttachment($candidateId, $mediaId)
  {
    $isApi = request()->get('isApi', false);
    try {

      // validate candidate and media ID
      if (!is_numeric($candidateId) || $candidateId <= 0 || !is_numeric($mediaId) || $mediaId <= 0) {
        throw new \InvalidArgumentException('Invalid candidate or media ID.');
      }


      $candidate = Candidate::findOrFail($candidateId);
    $media = $candidate->getMedia('candidate-media')->find($mediaId);

    if (!$media) {
      abort(404, 'Media not found');
    }

    return response()->download($media->getPath());
    } catch (ModelNotFoundException $e) {
      Log::error('Candidate not found in downloadAttachment', [
        'candidate_id' => $candidateId,
        'media_id' => $mediaId,
        'exception' => $e->getMessage()
      ]);

      return $isApi ? formatApiResponse(true, 'Candidate not found', [], 404) : response()->json(['error' => true, 'message' => 'Candidate not found'], 404);
    } catch (\Exception $e) {
      Log::error('Error in download attachments.', [
        'candidate_id' => $candidateId,
        'media_id' => $mediaId,
        'exception' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
      return $isApi ? formatApiResponse(true, 'An error occurred', [], 500) : response()->json(['error' => true, 'message' => 'An error occurred'], 500);
    }
  }

  public function viewAttachment($candidateId, $mediaId)
  {

    $isApi = request()->get('isApi', false);

    try {

      if (!is_numeric($candidateId) || $candidateId <= 0 || !is_numeric($mediaId) || $mediaId <= 0) {
        throw new \InvalidArgumentException('Invalid candidate or media ID.');
      }

      $candidate = Candidate::findOrFail($candidateId);
      $media = $candidate->getMedia('candidate-media')->find($mediaId);

      if (!$media) {
        abort(404, 'Media not found');
      }

      if (in_array($media->mime_type, [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png',
      ])) {
        return response()->file($media->getPath());
      } else {
        return response()->json([
          'error' => true,
          'message' => 'File type not supported for viewing'
        ]);
      }
    } catch (ModelNotFoundException $e) {
      Log::error('Candidate not found in downloadAttachment', [
        'candidate_id' => $candidateId,
        'media_id' => $mediaId,
        'exception' => $e->getMessage()
      ]);

      return $isApi ? formatApiResponse(true, 'Candidate not found', [], 404) : response()->json(['error' => true, 'message' => 'Candidate not found'], 404);
    } catch (\Exception $e) {
      Log::error('Error in download attachments.', [
        'candidate_id' => $candidateId,
        'media_id' => $mediaId,
        'exception' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
      return $isApi ? formatApiResponse(true, 'An error occurred', [], 500) : response()->json(['error' => true, 'message' => 'An error occurred'], 500);
    }
  }


  public function deleteAttachment($id)
  {
    $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($id);

    $response = DeletionService::delete(\Spatie\MediaLibrary\MediaCollections\Models\Media::class, $media->id, 'Attachment');

    return $response;
  }


  public function update_status(Request $request, $id)
  {

    $isApi = $request->get('isApi', false) || $request->expectsJson();

    $request->validate([
      'status_id' => 'required|exists:candidate_statuses,id'
    ]);

    $candidate = Candidate::findOrFail($id);
    $candidate->update(['status_id' => $request->status_id]);

    if ($isApi) {
      return formatApiResponse(
        false,
        'Candidate Status Updated Successfully!',
        []
      );
    }
    return response()->json([
      'error' => false,
      'message' => 'Candidate Status Updated Successfully!'
    ]);
  }

  public function store(Request $request)
  {

    $isApi = request()->get('isApi',false) || $request->expectsjson();

    $maxFileSizeBytes = config('media-library.max_file_size');
    $maxFileSizeKb = (int) ($maxFileSizeBytes / 1024);

    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email',
      'phone' => 'nullable|max:15',
      'position' => 'required|string|max:255',
      'source' => 'required|string|max:255',
      'status_id' => 'required|exists:candidate_statuses,id',
      'attachments.*' => "nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:$maxFileSizeKb",
    ]);

    // check if email alrady exists
    if (Candidate::where('email', $validatedData['email'])->exists()) {
      return response()->json([
        'error' => true,
        'message' => 'A candidate with this email alrady exists.'
      ]);
    }

    $candidate = Candidate::create($validatedData);

    // Handle file attachments
    if ($request->hasFile('attachments')) {
      foreach ($request->file('attachments') as $file) {
        // dd($file);
        $mediaItem = $candidate->addMedia($file)
          ->sanitizingFileName(function ($fileName) {
            $sanitizedFileName = strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
            $uniqueId = time() . '_' . mt_rand(1000, 9999);
            $extension = pathinfo($sanitizedFileName, PATHINFO_EXTENSION);
            $baseName = pathinfo($sanitizedFileName, PATHINFO_FILENAME);
            return "{$baseName}-{$uniqueId}.{$extension}";
          })
          ->toMediaCollection('candidate-media');
      }
    }

    if($isApi){
      return formatApiResponse(
        false,
        'Candidate Created Successfully!',
        [
          'data'=> formatCandidate($candidate)
        ]
        );

    } else {
      return response()->json([
        'error' => false,
        'message' => 'Candidate Created Successfully!',
        'candidate' => $candidate
      ]);
    }


  }

  public function update(Request $request, $id)
  {

    $isApi = request()->get('isApi', false) || $request->expectsjson();
    $maxFileSizeBytes = config('media-library.max_file_size');
    $maxFileSizeKb = (int) ($maxFileSizeBytes / 1024);

    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email',
      'phone' => 'nullable|max:15',
      'position' => 'required|string|max:255',
      'source' => 'required|string|max:255',
      'status_id' => 'required|exists:candidate_statuses,id',
      'attachments.*' => "nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:$maxFileSizeKb",
    ]);


    $candidate = Candidate::findOrFail($id);

    $candidate->update($validatedData);

    if ($request->hasFile('attachments')) {
      foreach ($request->file('attachments') as $file) {
        $candidate->addMedia($file)->toMediaCollection('attachments');
      }
    }


    if ($isApi) {
      return formatApiResponse(
        false,
        'Candidate Created Successfully!',
        [
          'data' => formatCandidate($candidate)
        ]
      );
    } else {
      return response()->json([
        'error' => false,
        'message' => 'Candidate Created Successfully!',
        'candidate' => $candidate
      ]);
    }
  }

  public function destroy($id)
  {

    $candidate = Candidate::findOrFail($id);

    $response = DeletionService::delete(Candidate::class, $candidate->id, 'Candidate');

    return $response;
  }

  public function destroy_multiple(Request $request)
  {

    $validatedData = $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'exists:candidates,id'
    ]);



    $ids = $validatedData['ids'];
    $deletedIds = [];

    foreach ($ids as $id) {
      $candidate = Candidate::findOrFail($id);
      $deletedIds[] = $id;

      DeletionService::delete(Candidate::class, $candidate->id, 'Candidate');
    }

    return response()->json([
      'error' => false,
      'message' => 'Candidate(s) Deleted Successfully!',
      'id' => $deletedIds,
    ]);
  }



  public function kanban_view(Request $request)
  {
    $statuses = (array) $request->input('statuses', []);
    $startDate = $request->input('candidate_date_between_from');
    $endDate = $request->input('candidate_date_between_to');

    $sortOptions = [
      'newest' => ['created_at', 'desc'],
      'oldest' => ['created_at', 'asc'],
      'recently-updated' => ['updated_at', 'desc'],
      'earliest-updated' => ['updated_at', 'asc'],
    ];
    [$sort, $order] = $sortOptions[$request->input('sort')] ?? ['id', 'desc'];

    $candidatesQuery = Candidate::with(['status']) // Add necessary relationships
      ->orderBy($sort, $order);

    if (!empty($statuses)) {
      $candidatesQuery->whereIn('status_id', $statuses);
    }

    if ($startDate && $endDate) {
      $candidatesQuery->whereBetween('created_at', [$startDate, $endDate]);
    }

    $candidates = $candidatesQuery->get();

    $statuses = CandidateStatus::orderBy('order')->get();

    return view('candidate.kanban', compact('candidates', 'statuses'));
  }

  public function list()
  {

    $search = request('search');
    $order = request('order', 'DESC');
    $limit = request('limit', 10);
    $offset = request('offset', 0);
    $sort = request()->input('sort', 'id');

    $order = 'desc';
    switch ($sort) {
      case 'newest':
        $sort = 'created_at';
        $order = 'desc';
        break;
      case 'oldest':
        $sort = 'created_at';
        $order = 'asc';
        break;
      case 'recently-updated':
        $sort = 'updated_at';
        $order = 'desc';
        break;
      case 'earliest-updated':
        $sort = 'updated_at';
        $order = 'asc';
        break;
      default:
        $sort = 'id';
        $order = 'desc';
        break;
    }


    $candidateStatus = request('candidate_status');
    // dd($candidateStatus);

    $query = Candidate::query();

    if ($search) {
      $query->where(function ($query) use ($search) {
        $query->whereHas('status', function ($q) use ($search) {
          $q->where('name', 'like', "%$search%");
        })
          ->orWhere('name', 'like', "%$search%")
          ->orWhere('position', 'like', "%$search%")
          ->orWhere('source', 'like', "%$search%");
      });
    }

    if ($candidateStatus) {
      $query->whereIn('status_id', $candidateStatus);
    }

    $total = $query->count();

    $canEdit = checkPermission('edit_candidate');
    $canDelete = checkPermission('delete_candidate');


    $candidates = $query->orderBy($sort, $order)
      ->skip($offset)
      ->take($limit)
      ->get()
      ->map(function ($candidate) use ($canDelete, $canEdit) {

        $actions = '';



        if ($canEdit) {
          $actions .= '<a href="javascript:void(0);" class="edit-candidate-btn"
                                        data-candidate=\'' . htmlspecialchars(json_encode($candidate), ENT_QUOTES, 'UTF-8') . '\'
                                        title="' . get_label('update', 'Update') . '">
                                        <i class="bx bx-edit mx-1"></i>
                                    </a>';
        }

        if ($canDelete) {
          $actions .= '<button type="button"
                                        class="btn delete"
                                        data-id="' . $candidate->id . '"
                                        data-type="candidate"
                                        title="' . get_label('delete', 'Delete') . '">
                                        <i class="bx bx-trash text-danger mx-1"></i>
                                    </button>';
        }


        // Generate interview preview button
        $interviewsCount = $candidate->interviews->count();
        $interviewsPreview = '';

        if ($interviewsCount > 0) {
          $interviewsPreview = '
        <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1 view-interviews-btn" data-bs-toggle="modal" data-bs-target="#interviewDetailsModal" data-id = "' . $candidate->id . '">
            <i class="bx bx-calendar-check me-1"></i>' . get_label('view', 'View') . '
        </button>
    ';
        } else {
          $interviewsPreview = '
        <span class="text-muted small">
            <i class="bx bx-info-circle me-1"></i>' . get_label('no_interviews', 'No interviews') . '
        </span>
    ';
        }


        return [
          'id' => $candidate->id,
        'name' => "<a href='" . route('candidate.show', ['id' => $candidate->id]) . "' >" . ucwords($candidate->name) . "</a>",
          'email' => ucwords($candidate->email),
          'phone' => $candidate->phone,
          'position' => ucwords($candidate->position),
          'status' => optional($candidate->status)->name ?? 'N/A',
        'source' => ucwords($candidate->source),
          'interviews' => $interviewsPreview,
          'created_at' => format_date($candidate->created_at),
          'updated_at' => format_date($candidate->updated_at),
          'actions' => $actions ?: '-'
        ];
      });

    return response()->json([
      'rows' => $candidates,
      'total' => $total
    ]);
  }


  // list function for api

  public function apiList(Request $request, $id = null)
  {
    try {
      // Validate query parameters
      $validated = $request->validate([
        'search' => 'nullable|string|max:255',
        'sort' => 'nullable|string|in:id,newest,oldest,recently-updated,earliest-updated',
        'limit' => 'nullable|integer|min:1|max:100',
        'offset' => 'nullable|integer|min:0',
        'candidate_status' => 'nullable|array',
        'candidate_status.*' => 'integer|exists:candidate_statuses,id',
      ]);

      // Validate ID if provided
      if ($id !== null && (!is_numeric($id) || $id <= 0)) {
        throw new \InvalidArgumentException('Invalid candidate ID.');
      }

      // Extract parameters with defaults
      $search = $validated['search'] ?? '';
      $sortInput = $validated['sort'] ?? 'id';
      $limit = $validated['limit'] ?? config('pagination.default_limit', 10);
      $offset = $validated['offset'] ?? 0;
      $candidateStatus = $validated['candidate_status'] ?? [];

      // Determine sort and order
      $sort = 'id';
      $order = 'desc';
      switch ($sortInput) {
        case 'newest':
          $sort = 'created_at';
          $order = 'desc';
          break;
        case 'oldest':
          $sort = 'created_at';
          $order = 'asc';
          break;
        case 'recently-updated':
          $sort = 'updated_at';
          $order = 'desc';
          break;
        case 'earliest-updated':
          $sort = 'updated_at';
          $order = 'asc';
          break;
        default:
          $sort = 'id';
          $order = 'desc';
          break;
      }

      // Build query with eager loading
      $query = Candidate::query()->with(['status', 'interviews']);

      // Fetch single candidate if ID is provided
      if ($id) {
        $candidate = $query->findOrFail($id);
        $data = formatCandidate($candidate);
        $data['can_edit'] = checkPermission('edit_candidate');
        $data['can_delete'] = checkPermission('delete_candidate');

        Log::info('Single candidate fetched via API', [
          'candidate_id' => $id,
          'user_id' => auth()->id() ?? 'guest',
        ]);

        return formatApiResponse(
          false,
          'Candidate retrieved successfully.',
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

      // Apply search functionality
      if ($search) {
        $query->where(function ($query) use ($search) {
          $query->whereHas('status', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
          })
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('position', 'like', '%' . $search . '%')
            ->orWhere('source', 'like', '%' . $search . '%');
        });
      }

      // Apply status filter
      if ($candidateStatus) {
        $query->whereIn('status_id', $candidateStatus);
      }

      // Get total count
      $total = $query->count();

      // Check permissions
      $canEdit = checkPermission('edit_candidate');
      $canDelete = checkPermission('delete_candidate');

      // Fetch candidates
      $candidates = $query->orderBy($sort, $order)
        ->skip($offset)
        ->take($limit)
        ->get()
        ->map(function ($candidate) use ($canEdit, $canDelete) {
          $data = formatCandidate($candidate);
          $data['can_edit'] = $canEdit;
          $data['can_delete'] = $canDelete;
          return $data;
        });

      // Log success
      Log::info('Candidate list fetched via API', [
        'search' => $search,
        'sort' => $sortInput,
        'limit' => $limit,
        'offset' => $offset,
        'total' => $total,
        'user_id' => auth()->id() ?? 'guest',
      ]);

      return formatApiResponse(
        false,
        'Candidates retrieved successfully.',
        [
          'total' => $total,
          'data' => $candidates->toArray(),
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
      Log::error('Candidate not found in apiList', [
        'candidate_id' => $id,
        'exception' => $e->getMessage(),
      ]);
      return formatApiResponse(true, 'Candidate not found.', [], 404);
    } catch (\Exception $e) {
      Log::error('Error in apiList', [
        'candidate_id' => $id,
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
