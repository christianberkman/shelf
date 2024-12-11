<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class BookEntity extends Entity
{
    protected $datamap              = [];
    protected $dates                = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts                = [];
    protected ?SeriesEntity $series = null;
    protected array $authors        = [];
    protected ?string $authorString = null;

    /**
     * ========================================
     * Set functions
     * ========================================
     *
     * @param mixed $value
     */
    public function setTitle($value): self
    {
        // Move article to end of title
        $this->attributes['title'] = formatAsTitle($value);

        // Update search string
        $this->attributes['search_string'] = $this->getSearchString();

        return $this;
    }

    public function setSubtitle($value): self
    {
        $this->attributes['subtitle'] = formatAsTitle($value);

        $this->attributes['search_string'] = $this->getSearchString();

        return $this;
    }

    public function setSeriesId($value): self
    {
        $this->attributes['series_id'] = (int) $value;

        $this->attributes['search_string'] = $this->getSearchString();

        return $this;
    }

    /**
     * ========================================
     * Get functions
     * ========================================
     */

    /**
     * Get the books's series entity
     */
    public function getSeries(): ?SeriesEntity
    {
        if (null !== $this->series) {
            return $this->series;
        }

        if (! array_key_exists('series_id', $this->attributes) || $this->attributes['series_id'] === null) {
            return null;
        }

        $this->series = (model('SeriesModel'))->find($this->attributes['series_id']);

        return $this->series;
    }

    /**
     * Return [series.series_title]
     */
    public function getSeriesTitle(): ?string
    {
        $this->getSeries();

        return $this->series->series_title ?? null;
    }

    /**
     * Get the book's search string
     */
    protected function getSearchString(): string
    {
        return implode(' ', [
            $this->attributes['title'],
            $this->attributes['subtitle'] ?? null,
            $this->attributes['part'] ?? null,
            $this->getSeriesTitle(),
        ]);
    }

    /**
     * Get the book's author entities
     *
     * @return list<AuthorEntity>
     */
    public function getAuthorEntities(): array
    {
        if (count($this->authors) !== 0) {
            return $this->authors;
        }

        // Look up author ids
        $authorIds = (model('BooksAuthorsModel'))->where('book_id', $this->attributes['book_id'])->findColumn('author_id');
        if (count($authorIds) === 0) {
            return [];
        }

        // Author entities
        $this->authors = (model('AuthorModel'))->whereIn('author_Id', $authorIds)->findALl();

        return $this->authors;
    }

    /**
     * Return a compiled title including subtitle, series, part
     */
    public function getDisplayTitle(): string
    {
        $displayTitle = $this->attributes['title'];
        if (! empty($this->attributes['subtitle'])) {
            $displayTitle .= "; {$this->attributes['subtitle']}";
        }
        if (! empty($this->getSeriesTitle())) {
            $displayTitle .= " ({$this->getSeriesTitle()})";
        }
        if (! empty($this->attributes['part'])) {
            $displayTitle .= " part {$this->attributes['part']}";
        }

        return $displayTitle;
    }

    /**
     * Get the author string
     */
    public function getAuthors(): ?string
    {
        $this->getAuthorEntities();

        if (count($this->authors) === 0) {
            return null;
        }

        if (! empty($this->authorString)) {
            return $this->authorString;
        }

        $authorNames = [];

        foreach ($this->authors as $author) {
            $authorNames[] = $author->name;
        }

        sort($authorNames);
        $this->authorString = implode(', ', $authorNames);

        return $this->authorString;
    }

    /**
     * ========================================
     * Other functions
     * ========================================
     * */
}
