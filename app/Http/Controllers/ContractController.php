<?php

namespace App\Http\Controllers;

use App\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{

    public function index($userId)
    {
        $contracts = Contract::where('user_id','=', $userId)->get();
        return response()->json($contracts);
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'contract' => 'required'
        ]);
        
        $data = $this->takeFile($request->contract);
        
        $contract = new Contract([
            'name' => $request->input('name'),
            'contract' => $data[0],
            'contract_path' => $data[1],
            'sub_folder' => $data[2],
            'user_id' => $request->input('user_id')
        ]);
        
        $contract->save();

        return response()->json($contract);

    }

    public function show(Contract $contract)
    {
        //
    }

    public function update(Request $request, Contract $contract)
    {
        $this->validate($request, [

        ]);

        $contract->update([

        ]);
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return dzejson('Contract deleted','201');
    }
    public function downloadContract($id, $filename) {
        $contract = Contract::findOrFail($id);

        return response()->file(public_path().'/nda'.'/'.$contract->sub_folder.'/'.$contract->contract);

    }
}
