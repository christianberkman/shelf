<?php

namespace App\Controllers;

use Exception;

class Sections extends BaseController
{
    protected function getSection(string $sectionId)
    {
        $section = sectionModel()->find($sectionId);

        if ($section === null) {
            throw new Exception("Section with ID {$sectionId} does not exist");
        }

        return $section;
    }

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
        $section = $this->getSection($sectionId);

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
        $section = $this->getSection($sectionId);

        $section->name = $this->request->getPost('name');
        $section->note = $this->request->getPost('note');

        if ($section->hasChanged()) {
            $update = sectionModel()->update($sectionId, $section);
            if ($update) {
                return redirect()->back()->with('alert', 'success');
            }

            return redirect()->back()->with('alert', 'error');
        }

        return redirect()->back();
    }
}
