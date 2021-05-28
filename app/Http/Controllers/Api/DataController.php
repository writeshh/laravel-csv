<?php

namespace App\Http\Controllers\Api;

use App\Models\Data;
use League\Csv\Reader;
use League\Csv\Writer;
use League\Csv\Statement;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use League\Csv\ColumnConsistency;
use App\Http\Requests\DataRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DataController extends Controller
{
    use ResponseAPI;

    private $dataPath;

    public function __construct()
    {
        $this->dataPath = storage_path() . '/app/data.csv';
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $records = Data::getAll();

            return $this->success(Response::$statusTexts['200'], $records);
        } catch (\Throwable $th) {
            throw $th;
            return $this->error(Response::$statusTexts['500']);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $records = Data::getOne($id - 1); //subtracting since index starts from 0 

            return $this->success(Response::$statusTexts['200'], $records);
        } catch (\Throwable $th) {
            throw $th;
            return $this->error(Response::$statusTexts['500']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric',
                'full_name' => 'required|string',
                'address' => 'required|string',
                'mobile' => 'required|numeric',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return $this->success(Response::$statusTexts['400'], $validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $writer = Data::create($request->all());

            return $this->success(Response::$statusTexts['200'], $writer);
        } catch (\Throwable $th) {
            throw $th;
            return $this->error(Response::$statusTexts['500']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric',
                'full_name' => 'required|string',
                'address' => 'required|string',
                'mobile' => 'required|numeric',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return $this->success(Response::$statusTexts['400'], $validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $writer = Data::update($request->except('_method'), $id);

            return $this->success(Response::$statusTexts['200'], $writer);
        } catch (\Throwable $th) {
            throw $th;
            return $this->error(Response::$statusTexts['500']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $writer = Data::delete($id);

            return $this->success(Response::$statusTexts['200'], $writer);
        } catch (\Throwable $th) {
            throw $th;
            return $this->error(Response::$statusTexts['500']);
        }
    }
}
