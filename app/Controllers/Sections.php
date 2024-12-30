<?php

namespace App\Controllers;

use Exception;

class Sections extends BaseController
{
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
}
