<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LeadForm;
use App\Models\LeadStage;
use App\Models\LeadSource;
use Illuminate\Http\Request;

class LeadFormController extends Controller
{

    public function index()
    {
        // $forms = LeadForm::with(['user', 'leadSource', 'leadStage'])
        //     ->where('workspace_id', auth()->user()->workspace_id)
        //     ->paginate(10);

        $forms = LeadForm::all();

        // dd($forms);
        return view('lead_form.index', compact('forms'));
    }

    public function create(){
        $sources = LeadSource::where('workspace_id',auth()->user()->workspace_id)->get();
        $stages = LeadStage::where('workspace_id', auth()->user()->workspace_id)->get();
        $users =User::all();

        // dd($sources,$stages, $users);

        return view('lead_form.create', compact('sources', 'stages', 'users'));
    }

    public function list()
    {

        $search = request()->input('search');
        $limit = request()->input('limit', 10);
        $offset = request()->input('offset', 0);
        $sort = request()->input('sort', 'id');
        $order = request()->input('order', 'DESC');


        $query = LeadForm::query();



        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        $total = $query->count();

        $canEdit = isAdminOrHasAllDataAccess();
        $canDelete = isAdminOrHasAllDataAccess();

        $leadForms = $query->orderBy($sort, $order)
            ->take($limit)
            ->skip($offset)
            ->get()
            ->map(function ($leadForm) use ($canDelete, $canEdit) {
                $actions = '';



                if ($canEdit) {
                    $actions .= '<a href="javascript:void(0);" class="edit-candidate-btn"
                                        data-candidate=\'' . htmlspecialchars(json_encode($leadForm), ENT_QUOTES, 'UTF-8') . '\'
                                        title="' . get_label('update', 'Update') . '">
                                        <i class="bx bx-edit mx-1"></i>
                                    </a>';
                }

                if ($canDelete) {
                    $actions .= '<button type="button"
                                        class="btn delete"
                                        data-id="' . $leadForm->id . '"
                                        data-type="leadForm"
                                        title="' . get_label('delete', 'Delete') . '">
                                        <i class="bx bx-trash text-danger mx-1"></i>
                                    </button>';
                }

                return [
                    'id' => $leadForm->id,
                    'title' => $leadForm->title,
                    'description' => $leadForm->description,
                    'source' => $leadForm->leadSource->name ?? '-',
                    'stage' => $leadForm->leadStage->name ?? '-',
                    'assigned_to' => ($leadForm->user->first_name . "" . $leadForm->user->last_name)  ?? '-',
                    'created_at' => $leadForm->created_at->format('Y-m-d'),
                    'actions' => $actions
                ];
            });



        // dd($leadForms);
        return response()->json([
            'rows' => $leadForms,
            'total' => $total
        ]);
    }
}
