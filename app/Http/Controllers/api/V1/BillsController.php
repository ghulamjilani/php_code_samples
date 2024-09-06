<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    BillStoreRequest
};
use Illuminate\Http\Request;
use App\Http\Responses\{
    Message,
    ResponseCode
};
use App\Models\Bills;
use Illuminate\Support\Facades\{
    Storage,
    DB,
};

class BillsController extends Controller
{
    public function index()
    {
        try
        {
            $bill = (auth()->user()->role_id == 1) ? Bills::with('Organization')->get(): Bills::where('user_id', auth()->id())->with('Organization')->get();
            if(count($bill) == 0)
                return makeResponse(ResponseCode::SUCCESS, Message::NO_BILL_YET, [], ResponseCode::SUCCESS);
            else
                return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $bill, ResponseCode::SUCCESS);
        }
        catch (\Exception $e) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
        }
    }

    public function show($id)
    {
        try
        {
            $bill = Bills::where('user_id', auth()->id())->with('Organization')->find($id);
            if(!$bill)
                return makeResponse(ResponseCode::FAIL, Message::NO_BILL, [], ResponseCode::FAIL);
            else
                return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $bill, ResponseCode::SUCCESS);
        }
        catch (\Exception $e) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
        }
    }

    public function store(BillStoreRequest $request){
        if(auth()->user()->role_id == 1) {
            try {
                $path = "";
                if(!$request->id)
                {
                    if ($request->hasFile('invoice_attach')) {
                        $file = $request->file('invoice_attach');
                        $filename = time() . '_' . $file->getClientOriginalName();
                
                        // Save the file to the 'public' disk
                        $path = $file->storeAs('bills/uploads/'.auth()->id() , $filename, 'public');
                    }
                    else
                        return makeResponse(ResponseCode::FAIL, Message::FILE_NOT_FOUND, [], ResponseCode::FAIL);

                    DB::beginTransaction();
                    $bill = Bills::create([
                        'user_id'           => $request->user_id,
                        'invoice_no'        => $request->invoice_no,
                        'invoice_attach'    => $path,
                        'amount'            => $request->amount,
                        'billing_date'      => $request->billing_date,
                        'description'       => $request->description,
                        'status'            => $request->status,
                    ]);
                    if($bill){
                        DB::commit();
                        return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $bill, true);
                    }else
                        return makeResponse(ResponseCode::FAIL, ResponseCode::getMessage(ResponseCode::FAIL), [], ResponseCode::FAIL);
                }
                else {
                    $filePath = Bills::find($request->id);
                    $bill = 0;
                    if ($request->hasFile('invoice_attach')) {
                        $file = $request->file('invoice_attach');
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('bills/uploads/'.auth()->id(), $filename, 'public');

                        if (!Storage::disk('public')->delete($filePath['invoice_attach']))
                            return makeResponse(ResponseCode::FAIL, Message::UNABLE_TO_UPLOAD_FILE, [], ResponseCode::FAIL);
                    }
                    else
                        $path = $filePath['invoice_attach'];

                    DB::beginTransaction();
                    $bill = Bills::where('id', $request->id)->update([
                        'user_id'           => $request->user_id,
                        'invoice_no'        => $request->invoice_no,
                        'invoice_attach'    => $path,
                        'amount'            => $request->amount,
                        'billing_date'      => $request->billing_date,
                        'description'       => $request->description,
                        'status'            => $request->status,
                    ]);
                    if($bill){
                        DB::commit();
                        return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $bill, true);
                    }else
                        return makeResponse(ResponseCode::FAIL, ResponseCode::getMessage(ResponseCode::FAIL), [], ResponseCode::FAIL);
                }
            } 
            catch (\Throwable $th) {
                return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $th->getMessage());
            }
        }else
            return makeResponse(ResponseCode::UNAUTHORIZED, ResponseCode::getMessage(ResponseCode::UNAUTHORIZED), [], ResponseCode::UNAUTHORIZED);
    }

    public function destroy($id)
    {
        if(auth()->user()->role_id == 1) {
            try
            {
                $bill = Bills::find($id);
                if(!$bill)
                    return makeResponse(ResponseCode::FAIL, Message::NO_BILL, [], ResponseCode::FAIL);
                else{
                    Storage::disk('public')->delete($bill['invoice_attach']);
                    $bill->delete();
                    return makeResponse(ResponseCode::SUCCESS, Message::RECORD_DELETED, $bill, ResponseCode::SUCCESS);
                }
            }
            catch (\Exception $e) {
                return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
            }
        }
        else
            return makeResponse(ResponseCode::UNAUTHORIZED, ResponseCode::getMessage(ResponseCode::UNAUTHORIZED), [], ResponseCode::UNAUTHORIZED);
    }
}
