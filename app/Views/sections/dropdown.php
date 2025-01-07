<select name="section_id" id="sectionId" class="form-select" <?=($required ?? true) ? 'required' : ''; ?>>
    <option value="">Select...</option>
    <?php foreach(sectionModel()->orderBy('name')->findAll() as $section): ?>
        <option value="<?=$section->section_id; ?>" <?=(($selected ?? null) === $section->section_id ? 'selected' : ''); ?>><?=$section->name; ?></option>
    <?php endforeach; ?>
</select>