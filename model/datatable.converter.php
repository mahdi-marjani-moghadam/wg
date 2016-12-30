<?php
class convertDatatableIO
{
    public $input;
    public $columns;

    public function __construct()
    {

    }

    public function convertOutput($list,$columns,$others,$internal)
    {
        $i=-1;

        $export['recordsFiltered']=$list['paging'];
        $export['recordsTotal']=$list['paging'];
        $export['data']=array();

        foreach($list['list'] as $key=>$val)
        {
            $i++;
            foreach ($columns as $key2 =>$val2 )
            {

                $thisFields=$val2['db'];
                $dt=$val2['dt'];

               // if(isset($val[$thisFields]))
                //{
                    $tempFields[$thisFields]=$val[$thisFields];
                    $local[$thisFields]=$val[$thisFields];
                    $export['data'][$i][$dt]=$val[$thisFields];
                //}

            }


            foreach ($others as $k=>$jj )
            {
                $export['data']["$i"]["$k"] =  $jj['formatter']($val,$internal);
            }

        }
        return $export;
    }

    public function convertInput ()
    {

        $input=$this->input;
        $columns=$this->columns;

        foreach ($columns as $key =>$val )
        {

            if(isset($input['columns'][$key]))
            {
                $fieldName=$val['db'];
                $searchFields['showFields'][$key]=$fieldName;
                if($input['columns'][$key]['search']['value']!='')
                {
                    $searchFields['filter'][$fieldName]=$input['columns'][$key]['search']['value'];
                }
            }

        }
        foreach ($input['order'] as $key =>$val )
        {
            $columnIdx = $val['column'];
            $requestColumn =$input['columns'][$columnIdx];
            $column = $columns[ $columnIdx ];
            //print_r($column);
            if ( $requestColumn['order'] == 'true' )
            {
                $dir = $val['dir'] === 'asc' ?
                    'ASC' :
                    'DESC';
                $searchFields['order'][$column['db']]=$dir;

            }

        }


        if (isset($input['start']) && $input['length'] != -1 ) {
            $searchFields['limit']['start']=$input['start'];
            $searchFields['limit']['length']=$input['length'];

            //$limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }

        // print_r($searchFields);

        return $searchFields;
    }



    static function limit ( $request, $columns )
    {
        $limit = '';

        if ( isset($request['start']) && $request['length'] != -1 ) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }

        return $limit;
    }

}
?>
