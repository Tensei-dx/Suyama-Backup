<?php
/*
 * <System Name> iBMS
 * <Program Name> LogController.php
 *
 * <Create>
 * <Update> 2018.12.18 TP Harvey   Method Functions
 * <Update> 2018.12.21 TP Harvey   Add Functions
 * <Update> 2019.07.09 TP Ivin     Checking of Hierarchy, Adding of return comments and try catch functions
 * <Update> 2020.05.22 TP Uddin    Modify URL and Methodname according to the URL list
 */

namespace App\Http\Controllers;

use App\Models\AppLogs;
use App\Models\AuditLogs;
use App\Models\Logs;
use App\Models\Notification;
use App\Models\ProcessedData;
use App\Models\SaveLog;
use App\Models\UserNotification;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;             //Excel Generator
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * <Class name> LogController
 *
 * <Overview> Class that handles and manages record logs
 *
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class LogController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getAllLog                 (1.0) Retrieve all logs from database
    // getSystemLogs             (2.0) Retrieve System Logs from database
    // getAuditLogs              (3.0) Retrieve AuditTrail Logs from database
    // createSystemLogs          (4.0) Save System Logs to database
    // createAuditLogs           (5.0) Save Audit Logs to database
    // getUserLogs               (6.0) Retrieve specific log types based on user
    // downloadLog               (7.0) Audit for downloading logs sheet
    // generateReport            (8.0) Generate PDF and Excel Report
    // generateSystemLogsFile    (9.0) Generate System Logs (CSV and PDF File)
    // generateAuditLogsFile     (10.0) Generate Audit Logs (CSV and PDF File)
    // deleteOldLogs             (11.0) Delete old record

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> getAllLog<br>
     * <Function> Retrieve All Logs from database<br>
     *            URL: http://localhost/getAllLog<br>
     *            METHOD: GET
     * @param Request $request
     * @return object $this->createGetResponse($request, (new Logs())
     *                ->newQuery())
     */
    public function getAllLog(Request $request)
    {
        return $this->createGetResponse($request, (new SaveLog())->newQuery());
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> getSystemLogs<br>
     * <Function> Retrieve System Logs from database<br>
     *            URL: http://localhost/getSystemLogs<br>
     *            METHOD: POST
     * @param Request $request
     * @return object $this->createGetResponse($request,$systemLogs)
     * @throws Throwable When an exception occurs in this process
     */
    public function getSystemLogs(Request $request)
    {
        try {
            ini_set('memory_limit', '256M');
            $systemLogs = SaveLog::orderBy('LOGS_ID', 'DESC')
                ->with('user')
                ->whereDate('CREATED_AT', '>=', $request->START_DATE)
                ->whereDate('CREATED_AT', '<=', $request->END_DATE)
                ->limit(20000);
            return $this->createGetResponse($request, $systemLogs);
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> getAuditLogs<br>
     * <Function> Retrieve AuditTrail Logs from database<br>
     *            URL: http://localhost/getAuditLogs<br>
     *            METHOD: POST
     * @param Request $request
     * @return object $this->createGetResponse($request,$auditLogs)
     * @throws Throwable When an exception occurs in this process
     */
    public function getAuditLogs(Request $request)
    {
        try {
            $auditLogs = AuditLogs::orderBy('AUDIT_LOGS_ID', 'DESC')
                ->whereDate('CREATED_AT', '>=', $request->START_DATE)
                ->whereDate('CREATED_AT', '<=', $request->END_DATE)
                ->limit(20000);
            return $this->createGetResponse($request, $auditLogs);
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $InstructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $InstructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> createSystemLogs<br>
     * <Function> Save to System Logs database<br>
     *            URL: http://localhost/createSystemLogs<br>
     *            METHOD: POST
     * @param Request $request
     */
    public function createSystemLogs(Request $request)
    {
        // Insert to new logs
        $uri = $request->getUri();
        $th = $request->ERROR_MESSAGE;
        $this->processError($uri, $th);
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> createAuditLogs<br>
     * <Function> Save to Audit Logs database<br>
     *            URL: http://localhost/createAuditLogs<br>
     *            METHOD: POST
     * @param Request $request
     */
    public function createAuditLogs(Request $request)
    {
        $ip = $request->ip() ? $request->ip() : "-";
        $module = $request->module ? $request->module : '-';
        $function = $request->function ? $request->function : '-';
        $username = auth()->user()->USERNAME;
        $instruction = $request->INSTRUCTION ? $request->INSTRUCTION : '-';
        if (gettype($instruction) == 'array') {
            foreach ($instruction as $instruct) {
                if ($instruct !== null) {
                    $this->auditLogs(
                        $ip,
                        $username,
                        $module,
                        $function . $instruct
                    );
                }
            }
        } else {
            $this->auditLogs(
                $ip,
                $username,
                $module,
                $function . $instruction
            );
        }
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> getUserLogs<br>
     * <Function> Retrieve specific log types based on user<br>
     *            URL: http://localhost/getUserLogs<br>
     *            METHOD: POST
     * @param Request $request
     * @return object $this->createGetResponse($request,$limitedLogs)
     * @throws Throwable When an exception occurs in this process
     */
    public function getUserLogs(Request $request)
    {
        try {
            $limitedLogs = "";
            $user = auth()->user()->USER_TYPE;
            if ($user == 2) {
                $limitedLogs = SaveLog::orderBy('LOGS_ID', 'DESC')
                    ->with('user')
                    ->whereNotIn('TYPE', [0, 3])
                    ->whereDate('CREATED_AT', '>=', $request->START_DATE)
                    ->whereDate('CREATED_AT', '<=', $request->END_DATE)
                    ->limit(20000);
                return $this->createGetResponse($request, $limitedLogs);
            } else {
                $limitedLogs = SaveLog::orderBy('LOGS_ID', 'DESC')
                    ->with('user')
                    ->whereNotIn('TYPE', [0, 1, 2, 3])
                    ->whereDate('CREATED_AT', '>=', $request->START_DATE)
                    ->whereDate('CREATED_AT', '<=', $request->END_DATE)
                    ->limit(20000);
                return $this->createGetResponse($request, $limitedLogs);
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> downloadLog<br>
     * <Function> Audit for downloading logs sheet<br>
     *            URL: http://localhost/downloadLog<br>
     *            METHOD: POST
     * @param Request $request
     */
    public function downloadLog(Request $request)
    {
        $module = "Logs";
        $ip = $request->ip() ? $request->ip() : '-';
        $username = auth()->user()->USERNAME ? auth()->user()->USERNAME : '-';
        $instruction = $username . ' has downloaded ' . $request->log . ' in '
            . $request->filetype . ' format.';
        $this->auditLogs($ip, $username, $module, $instruction);
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> generateReport<br>
     * <Function> Generate PDF and Excel Report<br>
     *            URL: http://localhost/generateReport<br>
     *            METHOD: POST
     */
    public function generateReport()
    {
        $logTypes = ['SystemLogs', 'AuditLogs'];
        $fileTypes = ['pdf', 'csv'];
        $thresholdDate  = date(strtotime("-90 days"));                     //-90 Days
        //******************************
        //Remove old PDF and CSV files for SystemLogs and AuditLogs
        //******************************
        try {
            foreach ($logTypes as $log) {                                           //Loop for  SystemLogs and AuditLogs file type
                foreach ($fileTypes as $file) {                                    //Loop for  CSV and PDF file type
                    $path = 'public/report_logs/' . $log . '/' . $file . '/';
                    $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
                    foreach ($files as $name => $file) {
                        if ($file->getFileName() == '.' || $file->getFileName() == '..') { //Skip . and .. file name
                            continue;
                        }
                        $fileName = pathinfo($file, PATHINFO_FILENAME);              //Get File name
                        $fileDate = date(strtotime($fileName));                     //Get File date
                        if ($thresholdDate > $fileDate) {                             //Check if the file is older than 90 days
                            unlink($file->getPathName());                           //Delete File
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = 'generateReport';
            $this->processError($uri, $e);
        }

        //******************************
        //Generate System & Audit Logs File
        //******************************
        $this->generateSystemLogsFile();
        $this->generateAuditLogsFile();
        //******************************
        //Generate System Logs Zip for PDF and CSV file
        //******************************
        $zipFileNames = [
            'pdf' => 'Report_System_Logs_PDF',
            'csv' => 'Report_System_Logs_CSV'
        ];
        foreach ($zipFileNames as $fileType => $fileName) {
            $zip_file = './public/report_logs/' . $fileName . '.zip';
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $path = 'public/report_logs/SystemLogs/' . $fileType . '/';
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $fileName = $file->getFileName();
                    $zip->addFile($filePath, $fileName);
                }
            }
            $zip->close();
        }
        //******************************
        //Generate Audit Logs Zip for PDF and CSV file
        //******************************
        $zipFileNames = [
            'pdf' => 'Report_Audit_Logs_PDF',
            'csv' => 'Report_Audit_Logs_CSV'
        ];
        foreach ($zipFileNames as $fileType => $fileName) {
            $zip_file = './public/report_logs/' . $fileName . '.zip';
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $path = 'public/report_logs/AuditLogs/' . $fileType . '/';
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $fileName = $file->getFileName();
                    $zip->addFile($filePath, $fileName);
                }
            }
            $zip->close();
        }
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> generateSystemLogsFile<br>
     * <Function> Generate System Logs (CSV and PDF File)<br>
     *            URL: http://localhost/generateSystemLogsFile<br>
     *            METHOD: POST
     */
    public function generateSystemLogsFile()
    {
        $logType = "SystemLogs";
        //******************************
        //Generate CSV Report
        //******************************

        //******************************
        //Retrieve Logs from Database
        //******************************
        $yesterdayDate = date('Y-m-d', strtotime('-1 days'));
        $yesterdayDateFileName = date('Ymd', strtotime('-1 days'));
        $systemLogs = SaveLog::orderBy('LOGS_ID', 'DESC')
            ->whereNotIn('TYPE', [0, 3])
            ->whereDate('CREATED_AT', $yesterdayDate)
            ->get();
        $startingValue = 4;

        //******************************
        //Initialize Spreadsheet
        //******************************
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'System Logs');
        $sheet->setCellValue('G1', 'Date:');
        $sheet->setCellValue('H1', $yesterdayDate);

        //******************************
        //Initialize Headers
        //******************************
        $borderStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' =>
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'argb' => '00000000'
                    ],
                ],
            ],
        ];

        //Set Cell Color
        $sheet->getStyle('B3:L3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FFFF00');

        $sheet->setCellValue('B3', "ID");
        $sheet->setCellValue('C3', "EVENTS");
        $sheet->setCellValue('D3', "LOG_LEVEL");
        $sheet->setCellValue('E3', "ERROR_TYPE");
        $sheet->setCellValue('F3', "USER_INFORMATION");
        $sheet->setCellValue('G3', "REQUEST_TARGET");
        $sheet->setCellValue('H3', "PROCESSING_OBJECT");
        $sheet->setCellValue('I3', "PROCESSING_RESULTS");
        $sheet->setCellValue('J3', "ERROR_CODE");
        $sheet->setCellValue('K3', "PROCESSING_DETAILS");
        $sheet->setCellValue('L3', "CREATED_AT");

        $sheet->getStyle('B3')->applyFromArray($borderStyle);
        $sheet->getStyle('C3')->applyFromArray($borderStyle);
        $sheet->getStyle('D3')->applyFromArray($borderStyle);
        $sheet->getStyle('E3')->applyFromArray($borderStyle);
        $sheet->getStyle('F3')->applyFromArray($borderStyle);
        $sheet->getStyle('G3')->applyFromArray($borderStyle);
        $sheet->getStyle('H3')->applyFromArray($borderStyle);
        $sheet->getStyle('I3')->applyFromArray($borderStyle);
        $sheet->getStyle('J3')->applyFromArray($borderStyle);
        $sheet->getStyle('K3')->applyFromArray($borderStyle);
        $sheet->getStyle('L3')->applyFromArray($borderStyle);

        //******************************
        //Input data to cell
        //******************************
        foreach ($systemLogs as $log) {
            $sheet->setCellValue('B' . $startingValue, $log['LOGS_ID']);
            $sheet->setCellValue('C' . $startingValue, $log['EVENTS']);
            $sheet->setCellValue('D' . $startingValue, $log['LOG_LEVEL']);
            $sheet->setCellValue('E' . $startingValue, $log['ERROR_TYPE']);
            $sheet->setCellValue('F' . $startingValue, $log['USER_INFORMATION']);
            $sheet->setCellValue('G' . $startingValue, $log['REQUEST_TARGET']);
            $sheet->setCellValue('H' . $startingValue, $log['PROCESSING_OBJECT']);
            $sheet->setCellValue('I' . $startingValue, $log['PROCESSING_RESULTS']);
            $sheet->setCellValue('J' . $startingValue, $log['ERROR_CODE']);
            $sheet->setCellValue('K' . $startingValue, $log['PROCESSING_DETAILS']);
            $sheet->setCellValue('L' . $startingValue, $log['CREATED_AT']);
            $startingValue += 1;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('./public/report_logs/' . $logType . '/csv/' . $yesterdayDateFileName . '.csv');
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        //******************************
        //Generate PDF Report
        //******************************
        $pdf = Pdf::loadView('logs/pdf_report_template.index', [
            'systemLogs' => $systemLogs,
            'currentDate' => $yesterdayDate
        ]);
        $pdf->setPaper('a4', 'landscape');
        $pdf->save('./public/report_logs/' . $logType . '/pdf/'
            . $yesterdayDateFileName . '.pdf');
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> generateAuditLogsFile<br>
     * <Function> Generate Audit Logs (CSV and PDF File)<br>
     *            URL: http://localhost/generateAuditLogsFile<br>
     *            METHOD: POST
     */
    public function generateAuditLogsFile()
    {
        $logType = "AuditLogs";
        //******************************
        //Generate CSV Report
        //******************************

        //******************************
        //Retrieve Logs from Database
        //******************************
        $yesterdayDate = date('Y-m-d', strtotime('-1 days'));
        $yesterdayDateFileName = date('Ymd', strtotime('-1 days'));
        $auditLogs = AuditLogs::orderBy('AUDIT_LOGS_ID', 'DESC')
            ->whereDate('CREATED_AT', $yesterdayDate)->get();
        $startingValue = 4;

        //******************************
        //Initialize Spreadsheet
        //******************************
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'AuditLogs');
        $sheet->setCellValue('G1', 'Date:');
        $sheet->setCellValue('H1', $yesterdayDate);

        //******************************
        //Initialize Headers
        //******************************
        $borderStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' =>
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'argb' => '00000000'
                    ],
                ],
            ],
        ];

        //Set Cell Color
        $sheet->getStyle('B3:G3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FFFF00');

        $sheet->setCellValue('B3', "AUDIT_LOGS_ID");
        $sheet->setCellValue('C3', "IP");
        $sheet->setCellValue('D3', "HOST");
        $sheet->setCellValue('E3', "MODULE");
        $sheet->setCellValue('F3', "INSTRUCTION");
        $sheet->setCellValue('G3', "CREATED_AT");

        $sheet->getStyle('B3')->applyFromArray($borderStyle);
        $sheet->getStyle('C3')->applyFromArray($borderStyle);
        $sheet->getStyle('D3')->applyFromArray($borderStyle);
        $sheet->getStyle('E3')->applyFromArray($borderStyle);
        $sheet->getStyle('F3')->applyFromArray($borderStyle);
        $sheet->getStyle('G3')->applyFromArray($borderStyle);

        //******************************
        //Input data to cell
        //******************************
        foreach ($auditLogs as $log) {
            $sheet->setCellValue('B' . $startingValue, $log['AUDIT_LOGS_ID']);
            $sheet->setCellValue('C' . $startingValue, $log['IP']);
            $sheet->setCellValue('D' . $startingValue, $log['HOST']);
            $sheet->setCellValue('E' . $startingValue, $log['MODULE']);
            $sheet->setCellValue('F' . $startingValue, $log['INSTRUCTION']);
            $sheet->setCellValue('G' . $startingValue, $log['CREATED_AT']);
            $startingValue += 1;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('./public/report_logs/' . $logType . '/csv/'
            . $yesterdayDateFileName . '.csv');
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        //******************************
        //Generate PDF Report
        //******************************
        $pdf = Pdf::loadView('logs/pdf_report_template.index', [
            'auditLogs' => $auditLogs,
            'currentDate' => $yesterdayDate
        ]);
        $pdf->setPaper('a4', 'landscape');
        $pdf->save('./public/report_logs/' . $logType . '/pdf/'
            . $yesterdayDateFileName . '.pdf');
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> deleteOldLogs<br>
     * <Function>  Delete Old Logs<br>
     *             URL: http://localhost/deleteOldLogs<br>
     *             METHOD: POST
     */
    public function deleteOldLogs()
    {
        // get date from 3 months ago
        $now = date('Y-m-d H:i:s', strtotime("-3 Months"));
        // Delete Processed Data records
        ProcessedData::where('CREATED_AT', '<', $now)->delete();
        // Delete Log records
        Logs::where('CREATED_AT', '<', $now)->delete();
        // Delete Audit Log records
        AuditLogs::where('CREATED_AT', '<', $now)->delete();
        // Delete Notification
        Notification::where('CREATED_AT', '<', $now)->delete();
        $notifIds = Notification::select('NOTIFICATION_ID')->get();
        // Store NOTIFICATION IDs inside an array
        $notifArr = [];
        foreach ($notifIds as $value) {
            array_push($notifArr, $value->NOTIFICATION_ID);
        }
        // Delete all user notifications where notification id does not exist
        UserNotification::whereNotIn('NOTIFICATION_ID', $notifIds)
            ->delete();
    }

    public function getAppLogs(Request $request)
    {
        try {
            $appLogs = AppLogs::orderBy('APPLOGS_ID', 'DESC')
                ->with('user')
                ->whereDate('CREATED_AT', '>=', $request->START_DATE)
                ->whereDate('CREATED_AT', '<=', $request->END_DATE)
                ->limit(20000)
                ->get();
            return $appLogs;
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $InstructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $InstructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }
}
