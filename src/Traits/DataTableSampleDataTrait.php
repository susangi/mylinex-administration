<?php

namespace Administration\Traits;

use Illuminate\Http\Request as HttpRequest;


trait DataTableSampleDataTrait
{
    /**
     * This function return data array
     *
     * @return object
     */
    public function dataTableDataSet($orderBy = 0, $orderDir = 'asc', $start = 0, $length = 10, $search = '')
    {
        return new HttpRequest([
            'draw' => 1,
            'columns' => [
                [
                    'data' => 0,
                    'name' => '',
                    'searchable' => true,
                    'orderable' => true,
                    'search' => [
                        'value' => '',
                        'regex' => false
                    ]
                ],
                [
                    'data' => 0,
                    'name' => '',
                    'searchable' => true,
                    'orderable' => true,
                    'search' => [
                        'value' => '',
                        'regex' => false
                    ]
                ]
            ],
            'order' => [
                [
                    'column' => $orderBy,
                    'dir' => $orderDir
                ]
            ],
            'start' => $start,
            'length' => $length,
            'search' => [
                'value' => $search,
                'regex' => false
            ]
        ]);
    }


    /**
     * This function return data array
     *
     * @return string
     */
    public function dataTableRequestDataSet(
        $orderBy = 0,
        $orderDir = 'asc',
        $start = 0,
        $length = 10,
        $search = ''
    ) {
        return http_build_query([
            'draw' => 1,
            'columns' => [
                [
                    'data' => 0,
                    'name' => '',
                    'searchable' => true,
                    'orderable' => true,
                    'search' => [
                        'value' => '',
                        'regex' => false
                    ]
                ],
                [
                    'data' => 0,
                    'name' => '',
                    'searchable' => true,
                    'orderable' => true,
                    'search' => [
                        'value' => '',
                        'regex' => false
                    ]
                ]
            ],
            'order' => [
                [
                    'column' => $orderBy,
                    'dir' => $orderDir
                ]
            ],
            'start' => $start,
            'length' => $length,
            'search' => [
                'value' => $search,
                'regex' => false
            ],
        ]);
    }

    /**
     * This function return data array
     *
     * @return string
     */
    public function bulkNiNumbersDataTableRequestDataSet(
        $ni_request = '',
        $orderBy = 0,
        $orderDir = 'asc',
        $start = 0,
        $length = 10,
        $search = ''
    ) {
        return http_build_query([
            'draw' => 1,
            'columns' => [
                [
                    'data' => 0,
                    'name' => '',
                    'searchable' => true,
                    'orderable' => true,
                    'search' => [
                        'value' => '',
                        'regex' => false
                    ]
                ],
                [
                    'data' => 0,
                    'name' => '',
                    'searchable' => true,
                    'orderable' => true,
                    'search' => [
                        'value' => '',
                        'regex' => false
                    ]
                ]
            ],
            'order' => [
                [
                    'column' => $orderBy,
                    'dir' => $orderDir
                ]
            ],
            'start' => $start,
            'length' => $length,
            'search' => [
                'value' => $search,
                'regex' => false
            ],
            'ni_request' => $ni_request,
            'filter' => true
        ]);
    }
}
