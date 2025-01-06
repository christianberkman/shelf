<select name="section_id" id="sectionId" class="form-select">

    <?php foreach(sectionModel()->orderBy('name')->findAll() as $section): ?>
        <option value="<?=$section->section_id; ?>" <?=(($selected ?? null) === $section->section_id ? 'selected' : ''); ?>><?=$section->name; ?></option>
    <?php endforeach; ?>
</select>