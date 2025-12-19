<?php
namespace App\Services;

class ZebraPrinterService
{
    // Generate ZPL for a nutrition/pharmacy request label — using Code128
    public static function generateLabelZpl(array $data): string
    {
        // $data expected: ['patient_name','medical_no','room','bed','request_id']
        $patient = addslashes($data['patient_name'] ?? '');
        $mrn = addslashes($data['medical_no'] ?? '');
        $room = addslashes($data['room'] ?? '');
        $bed = addslashes($data['bed'] ?? '');
        $requestId = addslashes($data['request_id'] ?? '');

        // Example ZPL: adjust positions and sizes as needed for your label
        $zpl = "^XA\n";
        $zpl .= "^CF0,30\n"; // font
        $zpl .= "^FO20,20^FDPatient:^FS\n";
        $zpl .= "^FO140,20^FD{$patient}^FS\n";
        $zpl .= "^FO20,60^FDMRN:^FS\n";
        $zpl .= "^FO140,60^FD{$mrn}^FS\n";
        $zpl .= "^FO20,100^FDRoom:^FS\n";
        $zpl .= "^FO140,100^FD{$room} ^FS\n";
        $zpl .= "^FO20,140^FDBed:^FS\n";
        $zpl .= "^FO140,140^FD{$bed}^FS\n";
        // Barcode Code128
        $zpl .= "^FO20,190^BY2\n";               // module width
        $zpl .= "^BCN,80,Y,N,N\n";              // Code128, height 80, print interpretation line Y
        $zpl .= "^FD{$requestId}^FS\n";         // data inside barcode
        $zpl .= "^XZ";

        return $zpl;
    }

    // Send ZPL to networked Zebra printer (IP and port)
    public static function sendToPrinter(string $printerIp, int $port, string $zpl, int $timeout = 5): bool
    {
        $socket = @fsockopen($printerIp, $port, $errno, $errstr, $timeout);
        if (!$socket) {
            return false;
        }
        fwrite($socket, $zpl);
        fclose($socket);
        return true;
    }
}