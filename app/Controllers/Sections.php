<?php

namespace App\Controllers;

use Exception;
use Throwable;

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
     * GET /sections/new
     */
    public function new()
    {
        $data = [
            'crumbs' => [
                ['Sections', '/sections'],
            ],
            'current' => 'Add section',
        ];

        return view('sections/new', $data);
    }

    /**
     * POST /sections/new
     */
    public function insert()
    {
        $section             = new \App\Entities\SectionEntity();
        $section->section_id = $this->request->getPost('section_id');
        $section->name       = $this->request->getPost('name');
        $section->note       = $this->request->getPost('note');

        $sectionModel = model('SectionModel');

        // Check if section with name or id already exists
        $check = $sectionModel
            ->where('name', $section->name)
            ->orWhere('section_id', $section->section_id)
            ->findAll();

        if (count($check) !== 0) {
            return redirect()
                ->back()
                ->with('alert', 'duplicate')
                ->withInput();
        }

        try {
            $sectionId = $sectionModel->insert($section);

            if ($sectionId === false) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('alert', 'error');
            }
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', 'error')
                ->with('error', $e->getMessage());
        }

        return redirect()->to("/sections/{$sectionId}")->with('alert', 'insert-success');
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
