<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Repository\StudentPromotionRepositoryInterface;
use Illuminate\Http\Request;

class PromotionController extends Controller
{

    protected $promotion;
    public function __construct(StudentPromotionRepositoryInterface $promotion)
    {
        $this->promotion = $promotion;
    }

    public function index()
    {
        return $this->promotion->index();
    }


    public function create()
    {
        return $this->promotion->create();
    }


    public function store(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|integer',
            'classroom_id' => 'required|integer',
            'section_id' => 'required|integer',
            'academic_year' => 'required',
            'grade_id_new' => 'required|integer',
            'classroom_id_new' => 'required|integer',
            'section_id_new' => 'required|integer',
            'academic_year_new' => 'required',
        ]);

        return $this->promotion->store($request);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy(Request $request)
    {
        return $this->promotion->destroy($request);

    }
}