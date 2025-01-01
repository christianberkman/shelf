<?php

namespace App\Controllers;

use Exception;

class Sections extends BaseController
{
    /**
     * GET /sections
     */
    public function index()
    {
        $sectionModel = model('SectionModel');
        $sections     = $sectionModel->findAll();

        $data = [
            'sections' => $sections,
            'current'  => 'Sections',
        ];

        return view('sections/index', $data);
    }

    /**
     * GET /sections/$sectionId
     */
    public function view(string $sectionId)
    {
        $sectionModel = model('SectionModel');
        $section      = $sectionModel->find($sectionId);
        if ($section === null) {
            throw new Exception('Section does not exist');
        }

        $data = [
            'section' => $section,
            'crumbs'  => [
                ['Sections', '/sections'],
            ],
            'current' => $section->name,
        ];

        return view('sections/view', $data);
    }

    /**
     * POST /sections/$sectionId
     */
    public function update(string $sectionId)
    {
        $sectionModel = model('SectionModel');
        $section      = $sectionModel->find($sectionId);
        if ($section === null) {
            throw new Exception('Section does not exist');
        }

        $section->name = $this->request->getPost('name');
        $section->note = $this->request->getPost('note');

        if ($section->hasChanged()) {
            $update = $sectionModel->update($sectionId, $section);
            if ($update) {
                return redirect()->back()->with('alert', 'success');
            }

            return redirect()->back()->with('alert', 'error');
        }

        return redirect()->back();
    }
}
