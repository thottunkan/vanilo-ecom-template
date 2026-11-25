<label for="languages">Choose Related products:</label>
<select class="form-control" id="languages" name="related_products[]" multiple size="5">

    @foreach ($related_products as $rp)
        <option value="{{ $rp->id }}">{{ $rp->name }}</option>
    @endforeach
</select>
