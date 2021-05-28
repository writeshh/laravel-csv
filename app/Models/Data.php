<?php

namespace App\Models;

use League\Csv\Reader;
use League\Csv\Writer;
use League\Csv\Statement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Data
{

    public static function getOne()
    {
        $reader = Reader::createFromPath(storage_path() . '/app/data.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = Statement::create()->process($reader);

        return $records;
    }

    public static function getAll()
    {
        $reader = Reader::createFromPath(storage_path() . '/app/data.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = Statement::create()->process($reader);

        return $records;
    }


    public static function create($data)
    {
        $reader = Reader::createFromPath(storage_path() . '/app/data.csv', 'r');
        $records = Statement::create()->process($reader);
        
        $writer = Writer::createFromPath(storage_path() . '/app/data.csv');
        $writer->insertAll($records);
        $writer->insertOne($data);
        
        return $writer;
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  array  $data
     * @param  int  $index
     */
    public static function update($data, $index)
    {
        $reader = Reader::createFromPath(storage_path() . '/app/data.csv', 'r');
        $records = Statement::create()->process($reader);

        $updatedRecords = [];
        foreach ($records as $key => $value) {
            if ($index == $key) {
                $updatedRecords[] = array_values($data);
            } else {
                $updatedRecords[] = $value;
            }
        }
        
        $writer = Writer::createFromPath(storage_path() . '/app/data.csv');
        $writer->insertAll($updatedRecords);
        return $writer;
    }

    public static function delete($index)
    {
        $reader = Reader::createFromPath(storage_path() . '/app/data.csv', 'r');
        $records = Statement::create()->process($reader);

        $updatedRecords = [];
        foreach ($records as $key => $value) {
            if ($index != $key) {
                $updatedRecords[] = $value;
            }
        }
        
        $writer = Writer::createFromPath(storage_path() . '/app/data.csv', 'w+');
        $writer->insertAll($updatedRecords);
        return $writer;
    }
}
