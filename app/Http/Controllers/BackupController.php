<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Collection;
use ZanySoft\Zip\Zip;
use App\Backup;
class BackupController extends Controller
{
    public function index(Request $request){
        $backup = new Backup();
        $backup->user_id = 1;
        $models = ['User', 'Attachment'];
        $backup->setModels($models);
        
        // $json = Collection::make([
        //     'name' => 'Test',
        //     'description' => 'Another awesome laravel package',
        //     'license' => 'MIT'
        // ])->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        $destinationPath = public_path() . DIRECTORY_SEPARATOR . "dashboard" . DIRECTORY_SEPARATOR . "backups_files";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath,0777,true);
        }
        $file = $destinationPath . DIRECTORY_SEPARATOR . 'backup-' . date('Ymdhis') .rand() . '.zip';
        // $zip = Zip::open('C:\laragon\www\recruitment\public\dashboard\backups_files\backup-20200909045843272533436.zip');
        // $zip->add(public_path() . DIRECTORY_SEPARATOR . 'attachments_files' . DIRECTORY_SEPARATOR . 'Bo0sHnRDKk2Q0I6D20200907044722.jpg');
        // $zip->setPassword('slartibartfast');
        // $zip->close();
        // $zip->extract('C:\laragon\www\recruitment\public\dashboard\backups_files');
        $vouchers = "Modules\Accounting\Models\Voucher"::all();
        $accounts = "Modules\Accounting\Models\Account"::all();
        dd(safesAccount()->backupData());
        $zip = Zip::create($file);
        foreach ($vouchers->filter(function($v){ return !is_null($v->auth()->id); }) as $voucher) {
            foreach ($voucher->attachments as $attachment) {
                if($attachment->fileExists()){
                    dd($voucher->backupData());
                    $zip->add($attachment->getFilePath());
                }
            }
        }
        $vouchers = "Modules\Accounting\Models\Voucher"::exports($vouchers);
        
        dd(json_encode($vouchers), $zip);
        
        $from_date = $request->has('from_date') ? $request->from_date : date('Y-m-d');
        if(!$request->has('from_date')){
            $first = Backup::first();
            if($first){
                $from_date = $first->created_at->format('Y-m-d');
            }
        }
        
        $to_date = $request->has('to_date') ? $request->to_date : date('Y-m-d');
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $backups = Backup::whereBetween('created_at', [$from_date_time, $to_date_time])->orderBy('created_at', 'DESC');
        return view('backups.index', compact('backups', 'from_date', 'to_date'));
    }
    
    public function backup(Request $request){
        $request->validate([
        'tables' => 'required|array',
        ]);
        $tables = $request->tables;
        /*
        $txt = "Logs \n";
        
        $response = new StreamedResponse();
        $response->setCallBack(function () use($txt) {
        echo $txt;
        });
        $response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Content-Type', 'application/json')
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'logs.txt');
        $response->headers->set('Content-Disposition', $disposition);
        
        return $response;
        */
        
        $data = json_encode(['Element 1','Element 2','Element 3','Element 4','Element 5']);
        $file = 'backup-' . date('Ymdhis') .rand() . '.json';
        $destinationPath = public_path() . "/backups_files//";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath,0777,true);
        }
        
        \File::put($destinationPath.$file,$data);
        return response()->download($destinationPath.$file);
        return back()->withError('حدث خطأ ما لم يتم انشاء النسخة.');
    }
    
    public function store(Request $request){
        return back()->withSuccess('تم انشاء النسخة الاحتياطية بنجاح.');
    }
    
    public function destroy(Backup $backup){
        $deleted = $backup->delete();
        if($deleted){
            return back()->withSuccess('تم حذف النسخة الاحتياطية بنجاح.');
        }
        return back()->withError('لم يتم حذف النسخة الاحتياطية');
    }
}