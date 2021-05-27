<?php

namespace App\Http\Controllers\Api;

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
            $reader = Reader::createFromPath($this->dataPath, 'r');
            // $reader->setDelimiter(';');
            $reader->setHeaderOffset(0);
            $records = Statement::create()->process($reader);
            $records->getHeader(); //returns ['First Name', 'Last Name', 'E-mail']

            return $this->success(Response::$statusTexts['200'], $records);
        } catch (\Throwable $th) {
            // throw $th;
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

            $reader = Reader::createFromPath($this->dataPath, 'r');
            $records = Statement::create()->process($reader);
            
            $writer = Writer::createFromPath($this->dataPath);
            $writer->insertAll($records);
            $writer->insertOne($request->all()); //will trigger a CannotInsertRecord exception

            return $this->success(Response::$statusTexts['200'], $writer->getContent());
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

            $reader = Reader::createFromPath($this->dataPath, 'r');
            $records = Statement::create()->process($reader);

            $updatedRecords = [];
            foreach ($records as $key => $value) {
                if ($id == $key) {
                    $updatedRecords[] = array_values($request->except('_method'));
                } else {
                    $updatedRecords[] = $value;
                }
            }
            
            $writer = Writer::createFromPath($this->dataPath);
            $writer->insertAll($updatedRecords);

            return $this->success(Response::$statusTexts['200'], $writer->getContent());
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
            $reader = Reader::createFromPath($this->dataPath, 'r');
            $records = Statement::create()->process($reader);

            $updatedRecords = [];
            foreach ($records as $key => $value) {
                if ($id != $key) {
                    $updatedRecords[] = $value;
                }
            }
            
            $writer = Writer::createFromPath($this->dataPath, 'w+');
            $writer->insertAll($updatedRecords);

            return $this->success(Response::$statusTexts['200'], $writer->getContent());
        } catch (\Throwable $th) {
            throw $th;
            return $this->error(Response::$statusTexts['500']);
        }
    }
}
