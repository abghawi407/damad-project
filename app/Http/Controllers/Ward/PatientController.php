<?php
namespace App\Http\Controllers\Ward;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::orderBy('created_at', 'desc')->paginate(25);
        return view('ward.patients.index', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'medical_record_no' => 'required|string',
            'nationality' => 'nullable|string',
            'room_no' => 'nullable|string',
            'bed_no' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'specialty' => 'nullable|string',
            'has_companion' => 'nullable|boolean',
            'admission_date' => 'nullable|date',
        ]);

        $data['created_by'] = Auth::id();
        $patient = Patient::create($data);

        return redirect()->back()->with('success', __('messages.patient_created'));
    }

    public function update(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);

        $data = $request->validate([
            'name' => 'required|string',
            'medical_record_no' => 'required|string',
            'nationality' => 'nullable|string',
            'room_no' => 'nullable|string',
            'bed_no' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'specialty' => 'nullable|string',
            'has_companion' => 'nullable|boolean',
            'admission_date' => 'nullable|date',
        ]);

        $patient->update($data);

        return redirect()->back()->with('success', __('messages.patient_updated'));
    }
}