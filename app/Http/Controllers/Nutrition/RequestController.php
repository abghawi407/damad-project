<?php
namespace App\Http\Controllers\Nutrition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as PatientRequest;
use App\Models\Patient;
use App\Events\RequestCreated;
use App\Services\ZebraPrinterService;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $requests = PatientRequest::where('request_type', 'nutrition')->orderBy('created_at','desc')->paginate(25);
        return view('nutrition.requests.index', compact('requests'));
    }

    public function approve($id)
    {
        $req = PatientRequest::findOrFail($id);
        $req->status = 'approved';
        $req->approved_by = Auth::id();
        $req->approved_at = now();
        $req->save();

        // broadcast event to update dashboards
        broadcast(new RequestCreated($req->id, $req->request_type, $req->patient->name));

        return redirect()->back()->with('success', __('messages.request_approved'));
    }

    public function printLabel(Request $request, $id)
    {
        $req = PatientRequest::findOrFail($id);
        $patient = $req->patient;
        $zpl = ZebraPrinterService::generateLabelZpl([
            'patient_name' => $patient->name,
            'medical_no' => $patient->medical_record_no,
            'room' => $patient->room_no,
            'bed' => $patient->bed_no,
            'request_id' => $req->id,
        ]);

        $printerIp = config('app.printer_ip', env('PRINTER_IP'));
        $port = (int) env('PRINTER_PORT', 9100);

        $sent = ZebraPrinterService::sendToPrinter($printerIp, $port, $zpl);

        if ($sent) {
            $req->status = 'printed';
            $req->save();
            return redirect()->back()->with('success', __('messages.print_sent'));
        }

        return redirect()->back()->with('error', __('messages.print_failed'));
    }
}